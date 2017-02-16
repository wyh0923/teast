<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/9/9
 * Time: 11:27
 */

class Teacount_model extends CI_Model
{
    /**
     * 学习、考试任务
     */
    public function get_task_num($where)
    {
        $this->db->select('TaskCode')
            ->from('task')
            ->where($where);
        $this->db->group_by('TaskCode');
        
        $result = $this->db->get()->result_array();
        return $result;
    }

    /**
     * 管理班级
     */
    public function get_class_num($where)
    {
        $this->db->select('count(1) as count')
            ->from('class')
            ->where($where);

        $result = $this->db->get()->result_array();
        return isset($result[0]['count']) ? $result[0]['count'] : 0;
    }

    /**
     * 创建课程
     */
    public function get_course_num($where)
    {
        $this->db->select('count(1) as count')
            ->from('package')
            ->where($where);

        $result = $this->db->get()->result_array();
        return isset($result[0]['count']) ? $result[0]['count'] : 0;
    }
    
    /**
     * 创建试卷
     */
    public function get_paper_num($where)
    {
        $this->db->select('count(1) as count')
            ->from('exam')
            ->where($where);

        $result = $this->db->get()->result_array();
        return isset($result[0]['count']) ? $result[0]['count'] : 0;
    }

    /**
     * 创建项目
     */
    public function get_item_num($where)
    {
        $this->db->select('count(1) as count')
            ->from('question')
            ->where($where);

        $result = $this->db->get()->result_array();
        return isset($result[0]['count']) ? $result[0]['count'] : 0;
    }


    /**
     * 班级能力统计
     */
    public function class_sys_score($uid, $account, $classid="")
    {
        //自己所有的方案
        $res2 = $this->db->select('ArchitectureName')->from('architecture')->where(array('ArchitectureParent'=>0, 'Author'=>$account))->get()->result_array();
        if(!empty($res2))
        {
            foreach ($res2 as $k=>$v) {
                $plan[$k]['ArchitectureName'] = $v['ArchitectureName'];
                $plan[$k]['Score'] = '0';
            }

            foreach ($plan as $kp=>$p) {
                $oplan[$p['ArchitectureName']] = $p;
            }
        }

        if($classid == "")
        {
            $sql = <<<END
                SELECT p_architecture.ArchitectureName, SUM(p.TaskScore) as Score
                FROM (
                    SELECT p_task.TaskScore,p_task.PackageID,p_architecture_package.ArchitectureID,p_architecture.ArchitectureName,p_architecture.ArchitectureParent FROM p_task 
                    JOIN p_architecture_package ON p_task.PackageID= p_architecture_package.PackageID
                    JOIN p_architecture ON p_architecture.ArchitectureID=p_architecture_package.ArchitectureID
                    WHERE p_task.TeacherID=$uid AND p_task.PackageID IS NOT NULL AND  p_architecture_package.ArchitectureID IS NOT NULL
                )p 
                JOIN p_architecture ON p_architecture.ArchitectureID=p.ArchitectureParent
                GROUP BY p_architecture.ArchitectureID            
END;
            $task = $this->db->query($sql)->result_array();
            if(!empty($task))
            {
                foreach ($task as $kt=>$t) {
                    $otask[$t['ArchitectureName']] = $t;
                }
            }

        }
        else {

            $sql = <<<END
            SELECT p_architecture.ArchitectureName, SUM(p.TaskScore) as Score
                FROM (
                    SELECT p_task.TaskScore,p_task.PackageID,p_architecture_package.ArchitectureID,p_architecture.ArchitectureName,p_architecture.ArchitectureParent FROM p_task 
                    JOIN p_architecture_package ON p_task.PackageID= p_architecture_package.PackageID
                    JOIN p_architecture ON p_architecture.ArchitectureID=p_architecture_package.ArchitectureID
                    WHERE p_task.TeacherID=$uid AND p_task.PackageID IS NOT NULL AND  p_architecture_package.ArchitectureID IS NOT NULL AND p_task.ClassID= $classid
                )p 
                JOIN p_architecture ON p_architecture.ArchitectureID=p.ArchitectureParent
                GROUP BY p_architecture.ArchitectureID
END;
            $task = $this->db->query($sql)->result_array();
            if(!empty($task))
            {
                foreach ($task as $kt=>$t) {
                    $otask[$t['ArchitectureName']] = $t;
                }
            }
        }
        if(!empty($oplan)&&!empty($otask))
        {
            $merge = array_merge($oplan, $otask);
        }
        elseif (empty($oplan)&&!empty($otask))
        {
            $merge = $otask;
        }
        else
        {
            $merge = array(array('ArchitectureName'=>'', 'Score'=>0));
        }

        if(!empty($oplan)&& empty($otask))
        {
            $merge = $plan;
        }

        return $merge;

    }

    /**
     * 教师下的班级
     */
    public function get_class_by_teacher($teacherCode,$page,$size=10,$search=''){
        $offset = $size*($page-1);
        $this->db->where('TeacherID',$teacherCode)->from('p_class');
        if( !empty($search) ){
            $this->db->like('ClassName',$search);
        }
        $count = $this->db->count_all_results();
        $this->db->select('ClassID,ClassName')
            ->where('TeacherID',$teacherCode);
        if( !empty($search) ){
            $this->db->like('ClassName',$search);
        }
        $query = $this->db->from('p_class')
            ->limit($size,$offset)
            ->get()->result_array();
        return array("rows"=>$query,"count"=>$count);
    }

    public function get_all_send_task_ajax($UserCode,$ArchitectureCode='',$TaskSourceType,$packagediff='',$offset='',$num='',$search='',$type=''){
        $this->load->library('ResPacket');

        $resPacket = new ResPacket();
        //$where = array('p_task.TeacherCode'=>$UserCode,'TaskSourceType'=>$TaskSourceType,'p_task.PackageCode!='=>NULL);
        $where = array('p_task.TeacherID'=>$UserCode,'TaskSourceType'=>$TaskSourceType);
        try {
            $this->db->select('p_task.* , ROUND(SUM(TaskProcess)/(COUNT(TaskProcess)),0) as per ,p_ts2.undown,COUNT(TaskProcess) as allstu');
            $this->db->where($where);

            $this->db->from('p_task');
            $this->db->join('(SELECT COUNT(TaskProcess) as undown,p_task.TaskID  from p_task where TaskProcess=100 and TaskSourceType=1 GROUP BY TaskID ) as p_ts2 ','p_ts2.TaskID =p_task.TaskID','left');

            $res = $this->db->group_by('p_task.TaskID')->limit($num,$offset)->get()->result_array();

            $resPacket->status = 1;
            $resPacket->msg = $res;

        } catch (Exception $e) {
            $resPacket->status = 0;
            $resPacket->msg = $e->getMessage ();;
        }
        return $resPacket;
    }


    public function get_all_send_task_num($UserCode,$ArchitectureCode='',$TaskSourceType,$packagediff='',$offset='',$num='',$search='',$type=''){
        $this->load->library('ResPacket');

        $resPacket = new ResPacket();
        $where = array('p_task.TeacherID'=>$UserCode,'TaskSourceType'=>$TaskSourceType,'p_task.PackageID!='=>NULL);
        if($packagediff!=''){
            $where['p_package.PackageDiff'] = $packagediff;
        }
        if($type!=''){
            $where['p_package.PackageSectionType'] = $type;
        }
        try {
            $this->db->select('p_task.*,p_package.*');
            $this->db->where($where);
            if( $ArchitectureCode != '' )
            {
                $this->db->where_in('p_architecture_package.ArchitectureID',$ArchitectureCode);
            }
            $this->db->from('p_task');
            $this->db->join('p_architecture_package','p_task.PackageID = p_architecture_package.PackageID','right');
            $this->db->join('p_package','p_package.PackageID = p_task.PackageID');
            if($search!=''){
                $where = '(p_task.TaskID like "%'.$search.'%" or p_task.TaskName like "%'.$search.'%" or p_package.PackageName like "%'.$search.'%" or p_task.TaskStartTime like "%'.$search.'%")';
                $this->db->where($where);
            }
            $res = $this->db->group_by('p_task.TaskID')->order_by('p_architecture_package.Index','asc')->get()->result_array();
            $res = count($res);

            $resPacket->status = 1;
            $resPacket->msg = $res;
        } catch (Exception $e) {
            $resPacket->status = 0;
            $resPacket->msg = $e->getMessage ();;
        }
        return $resPacket;
    }

    /**
     *  统计个人发布课程 下发数量 top5
     *  @param $examcode
     */
    public function topcount_package($usercode)
    {
        $this->load->library('ResPacket');
        $resPacket = new ResPacket();
        try{

            $this->db->select("COUNT(p_task.PackageID) as countnum,p_package.PackageName");
            $this->db->from('p_package');
            $this->db->join('p_task','p_package.PackageID = p_task.PackageID','left');
            $this->db->where(array('p_package.PackageAuthor'=>$usercode,'p_package.PackageParent'=>0));
            $this->db->group_by('p_package.PackageID');
            $this->db->order_by('countnum','DESC');
            $this->db->limit(5);

            $res = $this->get_my_result_array($this->db);
            //var_dump($this->db->last_query());die;

        }catch(Exception $exp){
            $resPacket->status = 0;
            $resPacket->msg = $exp->getMessage();
        }
        return $res;
    }

    //该方法替代   $this->db->get()->result_array()；防止 $this->db->get()为false时报错。
    public function get_my_result_array($thisdb)
    {
        $res = $thisdb->get();

        if ($res===false) {
            return array() ;
            ;
        }else{

            if($thisdb->affected_rows()>0){
                return $res->result_array() ;
            }else{
                return array() ;
            }
        }
    }


    /**
     *  统计个人发布试卷  下发数量 top5
     *  @param $examcode
     */
    public function topcount_exam($usercode)
    {
        $this->load->library('ResPacket');
        $resPacket = new ResPacket();
        try{

            $this->db->select("COUNT(p_task.ExamID) as countnum,p_exam.ExamName");
            $this->db->from('p_exam');
            $this->db->join('p_task','p_exam.ExamID = p_task.ExamID','left');
            $this->db->where(array('p_exam.TeacherID'=>$usercode));
            $this->db->group_by('p_exam.ExamID');
            $this->db->order_by('countnum','DESC');
            $this->db->limit(5);

            $res = $this->get_my_result_array($this->db);

            $resPacket->status = 1;
            $resPacket->msg = $res;
        }catch(Exception $exp){
            $resPacket->status = 0;
            $resPacket->msg = $exp->getMessage();
        }
        return $resPacket;
    }

    /**
     * 班级前10
     */
    public function get_classten($tid, $num, $ttype)
    {
        $ids = $this->db->select('ClassID, sum(TaskScore) as Score')
            ->from('task')
            ->where(array('TeacherID'=>$tid))
            ->group_by('ClassID')->order_by('Score', 'DESC')->limit(10)
            ->get()->result_array();

        if(!empty($ids))
        {
            $new_ids = array();
            foreach ($ids as $ki=>$i) {
                $new_ids[] = $i['ClassID'];
            }

            if($ttype==1)//日
            {
                //echo $tid.'--'.$num.'--'.$ttype;die;
                for($i=$num-1;$i>=0;$i--)
                {
                    $arr[] = $i;//array(0,1,2)
                    $n = date('Y-m-d', strtotime('-'.$i.' day'));
                    $day[] = $n;//array('2016-09-07', '2016-09-08', '2016-09-09')
                    $timearr[$n] = 0;//array('2016-09-07'=>0, '2016-09-08'=>0, '2016-09-09'=>0)
                }

                $start = strtotime(date('Y-m-d 00:00:00', strtotime(min($day))));
                $end = strtotime(date('Y-m-d 23:59:59', strtotime(max($day))));

                $res = $this->db->select('ClassID, TaskScore, TaskStartTime')
                    ->from('task')
                    ->where_in('ClassID', $new_ids)
                    ->where('TaskStartTime between '.$start.' and '.$end)
                    ->get()->result_array();
                //echo $this->db->last_query();


                $new_res = array();
                foreach ($new_ids as $kn=>$n) {
                    foreach ($res as $kr=>$r) {
                        if($n == $r['ClassID'])
                        {
                            $nres['score'] = $r['TaskScore'];
                            $nres['time'] = date('Y-m-d', $r['TaskStartTime']);

                            //$new_res[$n][] = $nres;
                            $new_res[$n][$nres['time']][] = $nres['score'];
                        }
                    }
                }

                foreach ($new_res as $kn => $n) {
                    foreach ($n as $kv => $v) {
                        $new_res[$kn][$kv] = array_sum($v);
                    }
                }
                $data = array();
                foreach ($new_res as $knr => $nr) {
                    $data[$knr]['data'] = array_merge($timearr, $new_res[$knr]);
                }

                //p($data);die;
                $series = array();
                foreach ($data as $kd => $d) {
                    $name = $this->get_class_name($kd);
                    $serie['name'] = $name;
                    $serie['data'] = array_combine(array_reverse($arr), $d['data']);

                    $series[] = $serie;
                }

                if(empty($data))
                {
                    $c = count($day);
                    for($i=0;$i<$c;$i++)
                    {
                        $data[] = '';
                    }
                    $series = array(array('name'=>'暂无数据', 'data'=>$data));
                }

                $json = array(
                    'categories' => $day,
                    'series' => $series
                );

            }
            else {//月
                $first_day_of_month = date('Y-m',time()) . '-01 00:00:01';
                $t = strtotime($first_day_of_month);

                for($i=$num-1;$i>=0;$i--)
                {
                    $arr[] = $i;//array(0,1,2)
                    $n = date('Y-m', strtotime('-'.$i.' month',$t));
                    $month[] = $n;//array('2016-07', '2016-08', '2016-09')
                    $timearr[$n] = 0;//array('2016-07'=>0, '2016-08'=>0, '2016-09'=>0)

                }

                $stime = min($month);
                $etime = max($month);

                $res = $this->db->select('ClassID, sum(TaskScore) as Score ,from_unixtime(TaskStartTime,\'%Y-%m\') as stime')
                    ->from('task')
                    ->where_in('ClassID', $new_ids)
                    ->where('from_unixtime(TaskStartTime,\'%Y-%m\') between "' . $stime . '" and "' . $etime . '"')
                    ->group_by('ClassID,stime')
                    ->get()->result_array();

                $new_res = array();
                foreach ($new_ids as $kn=>$n) {
                    foreach ($res as $kr=>$r) {
                        if($n == $r['ClassID'])
                        {
                            $new_res[$n][$r['stime']][] = $r['Score'];
                        }
                    }
                }

                foreach ($new_res as $kn => $n) {
                    foreach ($n as $kv => $v) {
                        $new_res[$kn][$kv] = array_sum($v);
                    }
                }
                $data = array();
                foreach ($new_res as $knr => $nr) {
                    $data[$knr]['data'] = array_merge($timearr, $new_res[$knr]);
                }

                $series = array();
                foreach ($data as $kd => $d) {
                    $name = $this->get_class_name($kd);
                    $serie['name'] = $name;
                    $serie['data'] = array_combine(array_reverse($arr), $d['data']);

                    $series[] = $serie;
                }

                if(empty($data))
                {
                    $c = count($month);
                    for($i=0;$i<$c;$i++)
                    {
                        $data[] = '';
                    }
                    $series = array(array('name'=>'', 'data'=>$data));
                }

                $json = array(
                    'categories' => $month,
                    'series' => $series
                );

            }
        } else {
            $json = array(
                'categories' => array(),
                'series' => array('name'=>'暂无数据', 'data'=>'')
            );
        }

        return json_encode($json);
    }

    /***
     * 获取班级名称
     * @param $classid
     * @return mixed
     */
    public function get_class_name($classid)
    {
        $this->db->select('ClassName');
        $this->db->from('class');
        $this->db->where(array('ClassID' => $classid));
        $this->db->limit(1);
        $result = $this->db->get()->result_array();
        return isset($result[0]['ClassName']) ? $result[0]['ClassName'] : '';

    }

    /**
     * 学生前10
     */
    public function get_studentten($tid,$num,$ttype)
    {
        if($ttype==1)//日
        {
            for($i=$num-1;$i>=0;$i--)
            {
                $arr[] = $i;//array(0,1,2)
                $n = date('Y-m-d', strtotime('-'.$i.' day'));
                $day[] = $n;//array('2016-09-07', '2016-09-08', '2016-09-09')
                $timearr[$n] = 0;//array('2016-09-07'=>0, '2016-09-08'=>0, '2016-09-09'=>0)
            }
            $start = strtotime(date('Y-m-d 00:00:00', strtotime(min($day))));
            $end = strtotime(date('Y-m-d 23:59:59', strtotime(max($day))));

            //班级id
            $ids = $this->db->select('StudentID, sum(TaskScore) as Score')
                ->from('task')
                ->where(array('TeacherID'=>$tid))
                ->where('TaskStartTime between '.$start.' and '.$end)
                ->group_by('StudentID')->order_by('Score', 'DESC')->limit(10)
                ->get()->result_array();

            if(!empty($ids)) {
                $new_ids = array();
                foreach ($ids as $ki => $i) {
                    $new_ids[] = $i['StudentID'];
                }

                $res = $this->db->select('StudentID, TaskScore, TaskStartTime')
                    ->from('task')
                    ->where_in('StudentID', $new_ids)
                    ->where('TaskStartTime between ' . $start . ' and ' . $end)
                    ->get()->result_array();

                $new_res = array();
                foreach ($new_ids as $kn => $n) {
                    foreach ($res as $kr => $r) {
                        if ($n == $r['StudentID']) {
                            $nres['score'] = $r['TaskScore'];
                            $nres['time'] = date('Y-m-d', $r['TaskStartTime']);

                            //$new_res[$n][] = $nres;
                            $new_res[$n][$nres['time']][] = $nres['score'];
                        }
                    }
                }

                foreach ($new_res as $kn => $n) {
                    foreach ($n as $kv => $v) {
                        $new_res[$kn][$kv] = array_sum($v);
                    }
                }
                $data = array();
                foreach ($new_res as $knr => $nr) {
                    $data[$knr]['data'] = array_merge($timearr, $new_res[$knr]);
                }

                //p($data);die;
                $series = array();
                foreach ($data as $kd => $d) {
                    $name = $this->get_student_name($kd);
                    $serie['name'] = $name;
                    $serie['data'] = array_combine(array_reverse($arr), $d['data']);

                    $series[] = $serie;
                }

                if (empty($data)) {
                    $c = count($day);
                    for ($i = 0; $i < $c; $i++) {
                        $data[] = '';
                    }
                    $series = array(array('name' => '暂无数据', 'data' => $data));
                }


                $json = array(
                    'categories' => $day,
                    'series' => $series
                );
            } else {
                $c = count($day);
                for ($i = 0; $i < $c; $i++) {
                    $data[] = '';
                }
                $series = array(array('name' => '暂无数据', 'data' => $data));

                $json = array(
                    'categories' => $day,
                    'series' => $series
                );
            }

        }
        else {//月
            $first_day_of_month = date('Y-m',time()) . '-01 00:00:01';
            $t = strtotime($first_day_of_month);

            for($i=$num-1;$i>=0;$i--)
            {
                $arr[] = $i;//array(0,1,2)
                $n = date('Y-m', strtotime('-'.$i.' month',$t));
                $month[] = $n;//array('2016-07', '2016-08', '2016-09')
                $timearr[$n] = 0;//array('2016-07'=>0, '2016-08'=>0, '2016-09'=>0)
            }
            $stime = min($month);
            $etime = max($month);

            $ids = $this->db->select('StudentID, sum(TaskScore) as Score')
                ->from('task')
                ->where(array('TeacherID'=>$tid))
                ->where('from_unixtime(TaskStartTime,\'%Y-%m\') between "' . $stime . '" and "' . $etime . '"')
                ->group_by('StudentID')->order_by('Score', 'DESC')->limit(10)
                ->get()->result_array();

            if(!empty($ids)) {
                $new_ids = array();
                foreach ($ids as $ki => $i) {
                    $new_ids[] = $i['StudentID'];
                }

                $res = $this->db->select('StudentID, sum(TaskScore) as Score ,from_unixtime(TaskStartTime,\'%Y-%m\') as stime')
                    ->from('task')
                    ->where_in('StudentID', $new_ids)
                    ->where('from_unixtime(TaskStartTime,\'%Y-%m\') between "' . $stime . '" and "' . $etime . '"')
                    ->group_by('StudentID,stime')
                    ->get()->result_array();

                $new_res = array();
                foreach ($new_ids as $kn => $n) {
                    foreach ($res as $kr => $r) {
                        if ($n == $r['StudentID']) {
                            $new_res[$n][$r['stime']][] = $r['Score'];
                        }
                    }
                }

                foreach ($new_res as $kn => $n) {
                    foreach ($n as $kv => $v) {
                        $new_res[$kn][$kv] = array_sum($v);
                    }
                }
                $data = array();
                foreach ($new_res as $knr => $nr) {
                    $data[$knr]['data'] = array_merge($timearr, $new_res[$knr]);
                }

                $series = array();
                foreach ($data as $kd => $d) {
                    $name = $this->get_student_name($kd);
                    $serie['name'] = $name;
                    $serie['data'] = array_combine(array_reverse($arr), $d['data']);

                    $series[] = $serie;
                }
                //p($new_ids);die;
//                p($series);die;

                if (empty($data)) {
                    $c = count($month);
                    for ($i = 0; $i < $c; $i++) {
                        $data[] = '';
                    }
                    $series = array(array('name' => '暂无数据', 'data' => $data));
                }


                $json = array(
                    'categories' => $month,
                    'series' => $series
                );
            } else {
                $c = count($month);
                for ($i = 0; $i < $c; $i++) {
                    $data[] = '';
                }
                $series = array(array('name' => '暂无数据', 'data' => $data));

                $json = array(
                    'categories' => $month,
                    'series' => $series
                );
            }

        }

        //p($json);die();
        return json_encode($json);


    }

    /***
     * 获取用户信息
     * @param $user_code
     * @return mixed
     */
    public function get_student_name($user_code)
    {
        $this->db->select('UserName');
        $this->db->from('user');
        $this->db->where(array('UserID' => $user_code));
        $this->db->limit(1);
        $result = $this->db->get()->result_array();
        return isset($result[0]['UserName']) ? $result[0]['UserName']: [];
    }
    
    
    
    
    
    
    
    
    
    
    


}