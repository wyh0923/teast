<?php

/**
 * Created by PhpStorm.
 * User: kyx
 * Date: 2016/8/11
 * Time: 14:30
 */
class Task_model extends CI_Model{

    /**
     * 获取任务
     * User:kyx
     * @param array taskid 任务id
     * @return array
     */
    public function get_task($where){
        $this->db->select('TaskID,TaskCode,TaskScore,StudentID,TaskName,TaskStartTime,TaskEndTime,TaskFinishedTime,TaskType,TeaEnd');
        $this->db->from('task');
        $this->db->where($where);
        $this->db->order_by('TaskID','desc');
        $result = $this->db->get()->result_array();
        return $result;
    }

    /**
     * 获取任务下学生获得的积分
     * User:kyx
     * @param array taskid 任务id
     * @return array
     */
    public function get_task_score($codeArr){
        $this->db->select('TaskID,TaskCode,TaskScore,StudentID,TaskType,TeaEnd');
        $this->db->from('task');
        $this->db->where_in('TaskCode',$codeArr);
        $this->db->order_by('TaskID','desc');
        $result = $this->db->get()->result_array();
        return $result;
    }
    /**
     * 更改任务表
     * User:kyx
     * @param array taskid 任务id
     * @return true or false
     */
    public function edit_task($where,$data){
        $this->db->update('task',$data,$where);
        if($this->db->affected_rows()>0){
            return TRUE;
        }else{
            return FALSE;
        }
    }
    /**
     * 删除任务表
     * User:kyx
     * @param array taskid 任务id
     * @return true or false
     */
    public function del_task($where){
        $this->db->where_in('TaskCode', $where);
        $this->db->delete( 'task');

        if($this->db->affected_rows()>0){
            return TRUE;
        }else{
            return FALSE;
        }
    }

    /**
     * 删除学习任务下发小节实例表表
     * User:kyx
     * @param array taskid 任务id
     * @return true or false
     */
    public function del_section_instance($where){
        $this->db->where_in('TaskCode', $where);
        $this->db->delete( 'section_instance');

        if($this->db->affected_rows()>0){
            return TRUE;
        }else{
            return FALSE;
        }
    }

    /**
     * 删除学习任务下发随堂练习实例表表
     * User:kyx
     * @param array taskid 任务id
     * @return true or false
     */
    public function del_practice_instance($where){
        $this->db->where_in('TaskCode', $where);
        $this->db->delete( 'practice_instance');

        if($this->db->affected_rows()>0){
            return TRUE;
        }else{
            return FALSE;
        }
    }
    /**
     * 删除考试任务下发题目实例表表
     * User:kyx
     * @param array taskid 任务id
     * @return true or false
     */
    public function del_question_instance($where){
        $this->db->where_in('TaskCode', $where);
        $this->db->delete( 'question_instance');

        if($this->db->affected_rows()>0){
            return TRUE;
        }else{
            return FALSE;
        }
    }

    /**
     * 更改考试任务下发题目实例表表
     * User:kyx
     * @param array taskid 任务id
     * @return true or false
     */
    public function edit_question_instance($where,$data){
        $flag = $this->db->update('question_instance',$data,$where);
        if($flag){
            return TRUE;
        }else{
            return FALSE;
        }
    }
    /**
     * 更改任务积分
     * User:kyx
     * @param array taskid 任务id
     * @return true or false
     */
    public function edit_task_score($where,$data){
        $this->db->where($where);
        $this->db->set('TaskScore',"TaskScore + ".$data['TaskScore'],FALSE);
        $this->db->update('task');
        if($this->db->affected_rows()>0){
            return TRUE;
        }else{
            return FALSE;
        }
    }

    /**
     * 更改任务进度
     * User:kyx
     * @param array taskid 任务id
     * @return true or false
     */
    public function edit_task_process($where){
        $this->db->select('count(*) as  countNum,(select count(*) from  p_section_instance where  Finished = 2 AND TaskID = '.$where['TaskID'].') / (select count(*) from  p_section_instance where TaskID = '.$where['TaskID'].')  as TaskProcess');
        $this->db->from('section_instance');
        $this->db->where($where);
        $process = $this->db->get()->result_array();
        $data['TaskProcess'] = round($process[0]['TaskProcess'], 2)*100;
        $data['TaskType'] = 1;
        if($data['TaskProcess'] == 100){
            $data['TaskType'] = 2;
            $data['TaskFinishedTime'] = time();
        }
        $this->db->update('task',$data,$where);
        if($this->db->affected_rows()>0){
            return TRUE;
        }else{
            return FALSE;
        }
    }


    /**
     * 添加任务表
     * User:kyx
     * @param array taskid 任务id
     * @return true or false
     */
    public function insert_task($data){
        $this->db->insert('task',$data);
        if($this->db->affected_rows()>0){
            return $this->db->insert_id();
        }else{
            return FALSE;
        }
    }

    /**
     * 统计学员学习和考试总得分
     * User:kyx
     * @return array
     */
    public function get_total_score($where){
        $this->db->select('sum(TaskScore) as totalscore');
        $this->db->from('task');
        $this->db->where($where);
        $result = $this->db->get()->result_array();
        return $result;
    }
    /*
     * 获取所有体系
     * */
    public function get_arch(){
        $this->db->select('ArchitectureID,ArchitectureName,ArchitectureParent');
        $this->db->from('architecture');
        $this->db->	order_by('ArchitectureID','asc');
        $result = $this->db->get()->result_array();
        return $result;
    }
    /**
     * 获取下发任务的课程得分
     * User:kyx
     * @return array
     */
    public function get_book_score(){
        $userID = $this->session->userdata( 'UserID' );
        $this->db->select ('a2.ArchitectureName as pname,a2.ArchitectureID as pid,a.ArchitectureName,ap.ArchitectureID, sum(tt.TaskScore) as tscore');
        $this->db->from ( 'task as tt' );
        $this->db->join ( 'architecture_package as ap', 'tt.PackageID = ap.PackageID','left' );
        $this->db->join ( 'architecture as a', 'a.ArchitectureID = ap.ArchitectureID','left' );
        $this->db->join ( 'architecture as a2', 'a2.ArchitectureID = a.ArchitectureParent','left' );

        $where = array('tt.StudentID' => $userID, 'tt.TaskSourceType' => 1);
        $this->db->where ( $where );
        $this->db->group_by('ap.ArchitectureID');
        $result= $this->db->get ()->result_array();
        return $result;
    }
    /**
     * 个人能力雷达
     * User:kyx
     * @return array
     */
    public function get_arc_score(){
        //知识体系
        $architecture = $this->get_arch();
        $parc_arr = array();
        //课程得分
        $book_score = $this->get_book_score();
        $score_arc = array_column($book_score,'pid');
        if(count($book_score) > 0 ) {
            foreach ($architecture as $k => $parc) {
                if ($parc['ArchitectureParent'] == 0 && in_array($parc['ArchitectureID'], $score_arc)) {
                    $parc_arr[$parc['ArchitectureID']]['name'] = $parc['ArchitectureName'];
                    $arr = array();
                    foreach ($architecture as $kk => $arc) {
                        if ($parc['ArchitectureID'] == $arc['ArchitectureParent']) {
                            $arr[$kk]['ArchitectureID'] = $arc['ArchitectureID'];
                            $arr[$kk]['ArchitectureName'] = $arc['ArchitectureName'];
                            //分数
                            foreach ($book_score as $book) {
                                if ($book['ArchitectureID'] == $arc['ArchitectureID']) {
                                    $arr[$kk]['score'] = $book['tscore'];
                                    $arr[$kk]['ArchitectureScore'] = $book['tscore'];
                                }
                            }

                            if (!isset($arr[$kk]['score'])) {
                                $arr[$kk]['score'] = 0;
                            }
                            if (!isset($arr[$kk]['ArchitectureScore'])) {
                                $arr[$kk]['ArchitectureScore'] = 0;
                            }
                        }
                    }
                    $parc_arr[$parc['ArchitectureID']]['list'] = $arr;
                }

            }
        }
        
        return $parc_arr;
    }

    /**
     * 获取学员完成课时总数
     * User:kyx
     * *@param array StudentID 学生id
     * @return total 完成课时总数
     */
    public function get_task_section($where){
        $this->db->select ('count(SectionID) as total');
        $this->db->from ( 'task as t' );
        $this->db->join ( 'p_section_instance as si', 't.TaskID = si.TaskID','left' );
        $this->db->where ( $where );
        $result= $this->db->get ()->result_array();
        return $result[0]['total'];
    }
    /**
     * 获取该老师所下发的学习列表
     * User:kyx
     * @param array $where 条件 $search 搜索 $offset $num 分页
     * @return array
     */
    public function study_list($where,$search='',$num='',$offset='',$sort=''){
        $this->db->select('t.TaskID,TaskCode,t.PackageID,u.UserName,t.TaskName,TaskTargetType,t.CreateTime,TaskType,TaskStartTime,TaskEndTime,TaskEndTime-UNIX_TIMESTAMP(now()) as Etime,sum(t.TaskType)/COUNT(t.TaskType) AS TaskTypeJudge, ROUND(SUM(TaskProcess)/(COUNT(TaskProcess)),0) as Progress,t.TaskType,p.PackageName,p.PackageDiff,PackageImg');
        $this->db->from('task as t');
        $this->db->join('package as p','t.PackageID = p.PackageID','right');
        $this->db->join('user as u','u.UserID = t.TeacherID','left');
        $this->db->join('user as stu','stu.UserID = t.StudentID','right'); //统计进度时 已删除的学生 不统计
        $this->db->where($where);
        if($search != ''){
            $this->db->like("t.TaskName",$search);
        }
        $this->db->group_by('t.TaskCode');
        if (is_array($sort)) {
            $this->db->order_by($sort[0],$sort[1]);
        }else{
            $this->db->order_by('t.TaskID', 'desc');
        }
        if($offset >= 0 && $num != '') {
            $this->db->limit($num, $offset);
        }
        $result = $this->db->get()->result_array();
        return $result;
    }

    /**
     * 学习任务统计 学员学习情况
     * User:kyx
     * @param array TaskCode  任务code  TeacherID 老师ID  $offset $num 分页
     * @return array
     */
    public function study_student_list($where,$num='',$offset='',$sort=''){
        $this->db->select('t.TaskID,t.TaskType,u.UserName,si.SectionID,sum(si.Finished - 1)as finished,count(si.Finished) - sum(si.Finished - 1)as underway,COUNT(si.Finished)as allsection,t.TaskProcess,t.TaskScore');
        $this->db->from('task as t');
        $this->db->join('section_instance as si','si.TaskID = t.TaskID','left');
        $this->db->join('user as u','u.UserID = t.StudentID','right');
        $this->db->where($where);
        if($offset >= 0 && $num != '') {
            $this->db->limit($num, $offset);
        }
        $this->db->group_by('t.StudentID');

        if (is_array($sort)) {
            $this->db->order_by($sort[0],$sort[1]);
        }else{
            $this->db->order_by('u.UserID', 'desc');
        }

        $result = $this->db->get()->result_array();
        return $result;
    }
    /**
     * 获取所有老师所创建的考试列表
     * User:kyx
     * @param array $where 条件 $search 搜索 $offset $num 分页
     * @return array
     */
    public function teacher_exam_list($where,$num='',$offset='',$sort=''){
        $this->db->select('ExamID,ExamName,UserName,ExamType,ExamDiff,e.CreateTime');
        $this->db->from('exam as e');
        $this->db->join('user as u','u.UserID = e.TeacherID','left');

        if($where['search'] != ''){
            $this->db->group_start();
            $this->db->like("ExamName",$where['search']);
            $this->db->or_like('u.UserName',$where['search']);
            $this->db->group_end();
        }

        if($where['diff'] != ''){
            $this->db->where('ExamDiff',$where['diff']);
        }
        if(is_array($where['examtype'])){

            $this->db->group_start(); //不能去除
            $this->db->where_in("ExamType",$where['examtype']);
            foreach ($where['examtype'] as $val){
                $this->db->or_where('ExamType&'.$val,$val."",false);
            }
            $this->db->group_end(); //不能去除
        }
        
        if (is_array($sort)) {
            $this->db->order_by($sort[0],$sort[1]);
        }else{
            $this->db->order_by('ExamID', 'desc');
        }
        if($offset >= 0 && $num != '') {
            $this->db->limit($num, $offset);
        }
        $result = $this->db->get()->result_array();
        return $result;
    }
    /**
     * 获取该老师所下发的考试列表
     * User:kyx
     * @param array $where 条件 $search 搜索 $offset $num 分页
     * @return array
     */
    public function exam_task($where,$search='',$diff='',$num='',$offset='',$sort=''){
        $this->db->select('t.TaskID,TaskCode,TaskDesc,SceneTaskID,t.ExamID,u.UserName,t.TaskName,TaskStartTime,TaskEndTime,TaskEndTime-UNIX_TIMESTAMP(now()) as Etime,TaskTargetType,t.CreateTime,sum(t.TaskType)/COUNT(t.TaskType) AS TaskTypeJudge, CAST(sum(t.TaskType) / COUNT(t.TaskType) / 2 * 100 AS SIGNED) AS  Progress,,t.TaskType,e.ExamDiff');
        $this->db->from('task as t');
        $this->db->join('exam as e','t.ExamID = e.ExamID','right');
        $this->db->join('user as u','u.UserID = t.TeacherID','left');
        $this->db->join('user as stu','stu.UserID = t.StudentID','right'); //统计进度时 已删除的学生 不统计
        $this->db->where($where);
        if($diff != ''){
            $this->db->where("e.ExamDiff",$diff);
        }
        if($search != ''){
            $this->db->like("t.TaskName",$search);
        }

        $this->db->group_by('t.TaskCode');
        if (is_array($sort)) {
            $this->db->order_by($sort[0],$sort[1]);
        }else{
            $this->db->order_by('t.TaskCode', 'desc');
        }
        if($offset >= 0 && $num != '') {
            $this->db->limit($num, $offset);
        }
        $result = $this->db->get()->result_array();
        return $result;
    }
    /**
     * 考试任务统计 学员学习情况
     * User:kyx
     * @param array TaskCode  任务code  TeacherID 老师ID  $offset $num 分页
     * @return array
     */
    public function exam_student_list($where,$num='',$offset=''){
        $this->db->select('t.TaskID,t.TaskCode,t.TaskType,u.UserID,u.UserName,t.TaskProcess,t.TaskScore,t.TaskFinishedTime,t.TaskEndTime');
        $this->db->from('task as t');
        $this->db->join('user as u','u.UserID = t.StudentID','right');
        $this->db->where($where);
        if($offset >= 0 && $num != '') {
            $this->db->limit($num, $offset);
        }

        $this->db->order_by('t.TaskScore', 'desc');

        $result = $this->db->get()->result_array();
        return $result;
    }
    /**
     * 查看是否有该时间段 带场景题的考试任务
     * User:kyx
     * @param array QuestionLinkType  题目实验类型  TaskType 任务是否结束  TaskSourceType 任务类型
     *  @param  $start 任务开始时间  $end任务结束时间
     * @return array
     */
    public function get_scene_task_time($where,$start,$end){
        $this->db->select("t.TaskID,TaskName,case WHEN TaskStartTime >= '$start' AND TaskEndTime <= '$end' THEN 1 WHEN TaskStartTime <= '$start' AND TaskEndTime >= '$start' THEN 2 WHEN TaskStartTime <= '$start' AND TaskEndTime >= '$end' THEN 3 WHEN TaskStartTime <= '$start' AND TaskEndTime >= '$end' THEN 4 ELSE  6 end  as startEnd");
        $this->db->from('task as t');
        $this->db->join('exam_question as eq','eq.ExamID = t.ExamID','left');
        $this->db->join('question as qi','qi.QuestionID = eq.QuestionID','left');
        $this->db->where($where);

        $this->db->group_by('t.TaskID');
        $this->db->order_by('startEnd', 'asc');
        $result = $this->db->get()->result_array();
        return $result;
    }
    /**
     * 场景计划任务
     * @return array
     */
    public function create_task_scene($data){
        $this->load->library('Data_exchange', array('api_name' => 'create_task_scene', 'message' => $data), 'get_main');
        $res = $this->get_main->request();
        //var_dump($res);
        if ($res && isset($res['RespHead']['ErrorCode']) && $res["RespHead"]["ErrorCode"] === 0) {
            $tmp['code'] = '0000';
            $tmp['msg'] = 'success';
            $tmp['data'] = $res['RespBody']['Result'];
        } else if(isset($res['RespHead']['ErrorCode'])){
            //返回的错误编码
            $tmp['code'] = $res["RespHead"]["ErrorCode"];
            $tmp['msg'] = $res["RespHead"]["Message"];
            $tmp['data'] = $res['RespBody']['Result'];
        } else {
            $tmp['code'] = '0201';
            $tmp['msg'] = '请检查网络';
            $tmp['data'] = array();
        }
        return $tmp;
    }

    /*
     * 删除已下发学习任务时删除学员相应的积分
     * */
    public function del_user_study_score($info){
        foreach ($info as $val){
            $this->db->where('UserID',$val['StudentID']);
            $this->db->set('UserPoint',"UserPoint - ".$val['TaskScore'],FALSE);
            $this->db->update('user');
        }
        return true;
    }

}