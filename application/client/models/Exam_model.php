<?php

/**
 * Created by PhpStorm.
 * User: kyx
 * Date: 2016/8/11
 * Time: 10:00
 */
class Exam_model extends CI_Model{

    /**
     * 获取考试列表
     * User:kyx
     * @param array $where 条件 $search 搜索 $offset $num 分页
     * @return array
     */
    public function exam_list($where,$search='',$offset='',$num='',$sort=''){
        $this->db->select('t.TaskID,t.TaskCode,t.ExamID,SceneTaskID,t.TaskName,e.ExamType,u.UserName,TaskEndTime,TaskStartTime,TaskScore,TaskStartTime-UNIX_TIMESTAMP(now()) as Stime,TaskEndTime-UNIX_TIMESTAMP(now()) as Etime,(TaskEndTime-TaskStartTime) as TaskTime,t.TaskFinishedTime');
        $this->db->from('task as t');
        $this->db->join('exam as e','t.ExamID = e.ExamID');
        $this->db->join('user as u','u.UserID = t.TeacherID');
        $this->db->where($where);
        if($search != ''){
            $this->db->like("t.TaskName",$search);
        }
        if($offset >= 0 && $num != '') {
            $this->db->limit($num, $offset);
        }
        //排序
        if (is_array($sort)) {
            $this->db->order_by($sort[0],$sort[1]);
        }else{
            $this->db->order_by("t.TaskID","DESC");
        }
        $result = $this->db->get()->result_array();
        return $result;
    }

    /**
     * 获取考试试题
     * User:kyx
     * @param array TaskId 任务ID
     * @return array
     */
    public function get_question_instance_info($where){
        $this->db->select('qi.ID,t.TaskID,u.UserName,t.TaskScore,t.SceneTaskID,t.TaskCode,QuestionID,TaskName,TaskStartTime,TaskEndTime,QuestionAnswer,Answer,QuestionScore,ResourceUrl,ResourceName,QuestionDesc,QuestionType,QuestionChoose,QuestionLink,QuestionLinkType,CtfServerID,CtfServerPort,CtfUrl,CtfUrlDesc,CtfName,CtfResources,SceneInstanceUUID,TaskUUID');
        $this->db->from('question_instance as qi');
        $this->db->join('task as t','t.TaskId = qi.TaskId','left');
        $this->db->join('user as u','u.UserId = t.StudentId','left');
        $this->db->join('ctf as c','c.CtfID = qi.QuestionLink','left');
        $this->db->where($where);
        $result = $this->db->get()->result_array();
        return $result;
    }

    /**
     * 更改题目下发实例表
     * User:kyx
     * @param array TaskId 任务ID
     * @return array
     */
    public function edit_question_instance($where,$data){
        return $this->db->update('question_instance',$data,$where);
    }

    /**
     * 更改题目下发实例表
     * User:kyx
     * @param array 条件
     * @return array
     */
    public function get_question_instance($where){
        $this->db->select('ID,TaskID,SceneInstanceUUID,TaskUUID');
        $this->db->from('question_instance');
        $this->db->where($where);
        $result = $this->db->get()->result_array();
        return $result;
    }

    /**
     * 删除场景计划任务
     * User:kyx
     * @param array SceneTaskID 计划任务ID
     * @return array
     */
    public function del_task_scene($sceneTaskID){
        $this->load->library('Data_exchange', array('api_name' => 'del_task_scene', 'message' => array()), 'get_main');
        $res = $this->get_main->request(array($sceneTaskID));
        if ($res && isset($res['RespHead']['ErrorCode']) && $res["RespHead"]["ErrorCode"] === 0) {
            $tmp['code'] = '0000';
            $tmp['msg'] = '删除场景计划任务';
            $tmp['data'] = $res['RespBody']['Result'];
        } else {
            $tmp['code'] = '0201';
            $tmp['msg'] = '请检查网络';
            $tmp['data'] = array();
        }
        return $tmp;
    }

    /**
     * 查看试卷
     * User:kyx
     * @param array ExamID 试卷ID
     * @return array
     */
    public function get_exam_question($where){
        $this->db->select('ExamName,q.QuestionID,q.QuestionType,ResourceUrl,ResourceName,eq.Score,q.QuestionDesc,q.QuestionChoose,QuestionAnswer,q.QuestionLink,q.QuestionLinkType,CtfServerID,CtfServerPort,CtfUrl,CtfUrlDesc,CtfName,CtfResources');
        $this->db->from('exam as e');
        $this->db->join('exam_question as eq','e.ExamId = eq.ExamId','left');
        $this->db->join('question as q','eq.QuestionId = q.QuestionId','left');
        $this->db->join('ctf as c','c.CtfID = q.QuestionLink','left');
        $this->db->where($where);
        $result = $this->db->get()->result_array();
        return $result;
    }

    /**
     * 获取试卷下的题目
     * User:kyx
     * @param array ExamID 试卷ID
     * @return array
     */
    public function get_exam_question_infos($where){
        $this->db->select('eq.Score as QuestionScore,q.QuestionID,QuestionDesc,QuestionType,QuestionChoose,QuestionPriv,QuestionAnswer,QuestionLink,QuestionLinkType,QuestionDiff,UpdateTime,QuestionAuthor,ResourceUrl,ResourceName,QuestionScene');
        $this->db->from('exam_question as eq');
        $this->db->join('question as q','eq.QuestionId = q.QuestionId','left');
        $this->db->where($where);
        $result = $this->db->get()->result_array();
        return $result;
    }

    //下发考试 添加下发考试实例表
    public function insert_question_instance($data){
        foreach ($data['data'] as $val){
            $question = $val;
            $question['TaskID'] = $data['TaskID'];
            $question['TaskCode'] = $data['TaskCode'];
            $this->db->insert('question_instance',$question);
            if($this->db->affected_rows() <= 0){
                return FALSE;
            }
        }
        return TRUE;
    }

    /**
     * 获取试卷
     */
    public function get_exams($where)
    {
        $this->db->select('*')->from('exam')->where(array('TeacherID' => $where['UserID']));

        if (!empty($where['search'])) {
            $this->db->group_start();
            $this->db->like(array('ExamName' => $where['search']));
            $this->db->group_end();
        }

        if (isset($where['limit'])) {
            $this->db->limit($where['limit']['limit'], $where['limit']['offset']);
        }
        if (isset($where['CreateTime']) && is_array($where['CreateTime']) && !empty($where['CreateTime']['starttime'])) {
            $this->db->where('CreateTime >=', $where['CreateTime']['starttime']);
            $this->db->where('CreateTime <=', $where['CreateTime']['endtime']);
        }

        if (isset($where['sort'])) {
            $this->db->order_by($where['sort']['field'], $where['sort']['order']);
        }else{
            $this->db->order_by('ExamID', 'DESC');//默认排序
        }
        return $this->db->get()->result_array();
    }

    /***
     * 获取记录总数
     */
    public function get_count_exams($where)
    {
        $this->db->select('count(1) as count');
        $this->db->from('exam')->where(array('TeacherID' => $where['UserID']));

        if (!empty($where['search'])) {
            $this->db->group_start();
            $this->db->like(array('ExamName' => $where['search']));
            $this->db->group_end();
        }

        if (isset($where['CreateTime']) && is_array($where['CreateTime']) && !empty($where['CreateTime']['starttime'])) {
            $this->db->where('CreateTime >=', $where['CreateTime']['starttime']);
            $this->db->where('CreateTime <=', $where['CreateTime']['endtime']);
        }

        $result = $this->db->get()->result_array();
        return isset($result[0]['count']) ? $result[0]['count'] : 0;

    }

    /**
     * 试卷任务是否完成
     */
    public function is_finish($eid)
    {
        $this->db->select('ExamID');
        $this->db->from('task');
        $this->db->where(array('ExamID'=>$eid));
        $this->db->limit(1);

        $res = $this->db->get()->result_array();
        
        if(!empty($res))
        {
            $tmp['code'] = '0000';
            $tmp['msg'] = '';
            $tmp['data'] = '';
        }else {
            $tmp['code'] = '0377';
            $tmp['msg'] = '';
            $tmp['data'] = '';
        }

        return $tmp;
    }

    /***
     * 删除试卷
     */
    public function del_exam($data)
    {
        $this->db->where('ExamID', $data);
        $this->db->delete('exam');
        if ($this->db->affected_rows() > 0) {
            $this->db->where('ExamID', $data);
            $this->db->delete('exam_question');
            $tmp['code'] = '0000';
            $tmp['msg'] = 'success';
            $tmp['data'] = array();
        } else {
            $tmp['code'] = '0318';
            $tmp['msg'] = '删除失败';
            $tmp['data'] = array();

        }
        return $tmp;
    }
    
    /**
     * 新增试卷
     */
    public function add_exam($data)
    {
        $this->db->select('ExamName');
        $this->db->from('exam');
        $this->db->where('ExamName', $data['ExamName']);
        $this->db->limit(1);

        $res = $this->db->get()->result_array();
        if(!empty($res))
        {
            $tmp['code'] = '0386';
            $tmp['msg'] = '';
            $tmp['data'] = '';
        }else{
            $sum = array();
            foreach ($data['data'] as $d) {
                $val = explode('@@@@', $d);
                $sum[] = $val['2'];
                $sum[] = $val['3'];
            }
            $info = array(
                'CreateTime' => time(),
                'ExamDiff' => $data['ExamDiff'],
                'ExamName' => $data['ExamName'],
                'TeacherID' => $data['TeacherID'],
                'ExamType' => array_sum(array_unique($sum)),
            );

            $this->db->insert('exam', $info);
            
            $eid = $this->db->insert_id();

            $num = $this->db->affected_rows();
            if ($num >= 1) {
                $tmp['code'] = '0000';
                $tmp['msg'] = 'success';
                $tmp['data'] = $eid;
            } else {
                $tmp['code'] = '0386';
                $tmp['msg'] = '';
                $tmp['data'] = '';
            }
        }

        return $tmp;
    }

    /**
     * 试卷与题目关联
     */
    public function add_exam_question($data, $eid)
    {
        foreach ($data as $k => $q)
        {
            $qarr = explode('@@@@', $q);
            $eq = array('ExamID'=>$eid, 'QuestionID'=>$qarr[0], 'Score'=>$qarr[1]);

            $this->db->insert('exam_question',$eq);
        }

        if ($this->db->affected_rows() > 0) {
            $tmp['code'] = '0000';
            $tmp['msg'] = 'success';
            $tmp['data'] = array();
        } else {
            $tmp['code'] = '0318';
            $tmp['msg'] = '删除失败';
            $tmp['data'] = array();
        }
        return $tmp;
    }
    
    /**
     * 编辑试卷详情
     */
    public function edit_exam($eid)
    {
        $this->db->select('p_exam_question.Score, p_question.*')
            ->from('exam')
            ->join('p_exam_question', 'p_exam.ExamID=p_exam_question.ExamID')
            ->join('p_question', 'p_exam_question.QuestionID=p_question.QuestionID')
            ->where(array('p_exam.ExamID' => $eid));

        $res = $this->db->get()->result_array();
        return $res;

    }

    /**
     * 修改试卷
     */
    public function mod_exam($data, $eid)
    {
        $this->db->select('ExamName');
        $this->db->from('exam');
        $this->db->where(array('ExamName'=> $data['ExamName'], 'ExamID != '=>$eid));
        $this->db->limit(1);
        $res = $this->db->get()->result_array();
        if(!empty($res))
        {
            $tmp['code'] = '0386';
            $tmp['msg'] = '';
            $tmp['data'] = '';
        }else{
            $this->db->where('ExamID', $eid)->update('exam', $data);

            $num = $this->db->affected_rows();
            if ($num >= 0) {
                $tmp['code'] = '0000';
                $tmp['msg'] = 'success';
                $tmp['data'] = $eid;
            }
        }

        return $tmp;
    }
    
    /**
     * 试卷与题目
     */
    public function add_eq($data, $eid)
    {
        $this->db->where('ExamID', $eid)->delete('exam_question');

        foreach ($data as $k => $q)
        {
            $qarr = explode('@@@@', $q);
            $eq = array('ExamID'=>$eid, 'QuestionID'=>$qarr[0], 'Score'=>$qarr[1]);
            switch ($qarr['2'])
            {
                case 1:
                    $sum[] = 1;
                    break;
                case 2:
                    $sum[] = 2;
                    break;
                case 3:
                    $sum[] = 4;
                    break;
                case 4:
                    $sum[] = 8;
                    break;
                case 5:
                    $sum[] = 16;
                    break;
            }
            switch ($qarr['3'])
            {
                case 0:
                    $sum[] = 0;
                    break;
                case 2:
                    $sum[] = 32;
                    break;
            }

            $this->db->insert('exam_question',$eq);
        }
        $typenum = array_sum(array_unique($sum));

        $this->db->where('ExamID', $eid)->update('exam', array('ExamType'=>$typenum));

        if ($this->db->affected_rows() >= 0) {
            $tmp['code'] = '0000';
            $tmp['msg'] = 'success';
            $tmp['data'] = array();
        } else {
            $tmp['code'] = '0318';
            $tmp['msg'] = '';
            $tmp['data'] = array();
        }
        return $tmp;
    }

    /**
     * 获取所有题目
     */
    public function get_all_question($where)
    {
        $this->db->select('*')
            ->from('question');

        //20161115新增加 题目类型筛选
        if(!empty($where['QuestionType'])){
        	$this->db->where('QuestionType',$where['QuestionType']);
        }
        //20161115 end
        
        if (!empty($where['search'])) {
            $this->db->group_start();
            $this->db->like(array('QuestionDesc' => $where['search']));
            $this->db->group_end();
        }

        if (isset($where['limit'])) {
            $this->db->limit($where['limit']['limit'], $where['limit']['offset']);
        }

        $this->db->order_by('QuestionID', 'DESC');
        return $this->db->get()->result_array();
    }

    /**
     * 统计所有题目
     */
    public function get_all_count($where)
    {
        $this->db->select('count(1) as count')
            ->from('question');

        //20161115新增加 题目类型筛选
        if(!empty($where['QuestionType'])){
        	$this->db->where('QuestionType',$where['QuestionType']);
        }
        //20161115 end
        
        if (!empty($where['search'])) {
            $this->db->group_start();
            $this->db->like(array('QuestionDesc' => $where['search']));
            $this->db->group_end();
        }

        //echo $this->db->last_query();

        $result = $this->db->get()->result_array();
        return isset($result[0]['count']) ? $result[0]['count'] : 0;
    }

















}