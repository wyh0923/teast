<?php
/**
 * Created by PhpStorm.
 * User: liuqi
 * Date: 2016/8/22
 * Time: 18:35
 */

/**
 * 班级模型
 */
class Class_model extends CI_Model
{
    /**
     * 获取班级名称、时间、人数、任务数、总积分
     */
    public function get_class_infos($where)
    {
        $this->db->select('p_class.ClassName, p_class.ClassID, p_class.CreateTime, ' .
            'COUNT(DISTINCT p_class_user.UserID) AS StudentNum, IFNULL(p_task.TaskNum,0) AS TaskNum, ' .
            'IFNULL(p_task.TaskScore, 0) as TaskScore');
        $this->db->from('p_class');
        $this->db->where(array('p_class.TeacherID'=>$where['uid']));
        $this->db->join("p_class_user", "p_class.ClassID = p_class_user.ClassID", "left");
        //$this->db->join("p_user", "p_user.UserID = p_class_user.UserID", "left");
        $this->db->join("(select SUM(TaskScore) as TaskScore,ClassID, count(ClassID) AS TaskNum from p_task  GROUP BY ClassID)as p_task", "p_task.ClassID = p_class.ClassID", "left");
        if (isset($where['CreateTime']) && is_array($where['CreateTime']) && !empty($where['CreateTime']['starttime'])) {
            $this->db->where('p_class.CreateTime >=', strtotime($where['CreateTime']['starttime']));
            $this->db->where('p_class.CreateTime <=', strtotime($where['CreateTime']['endtime']));
        }
        if (!empty($where['search'])) {
            $this->db->like(array('p_class.ClassName' => $where['search']));
        }
        $this->db->group_by("p_class.ClassID");
        $this->db->group_by("p_task.ClassID");
        if (isset($where['sort'])) {
            $this->db->order_by($where['sort']['field'], $where['sort']['order']);
        }else{
            $this->db->order_by('p_class.ClassID', 'DESC');//默认排序
        }
        if (isset($where['limit'])) {
            $this->db->limit($where['limit']['limit'], $where['limit']['offset']);
        }
        $result = $this->db->get()->result_array();
        return $result;
    }

    /**
     * 获取所有学员
     */
    public function get_all_students($where)
    {
        $this->db->select('UserID,UserPhone,UserName,UserSex,IsLocked,UserDepartment,CreateTime');
        $this->db->from('user');
        $this->db->where('UserRole=', 3);//角色
        $this->db->where('IsDeleted', 0);
        if (isset($where['CreateTime']) && is_array($where['CreateTime']) && !empty($where['CreateTime']['starttime'])) {
            $this->db->where('CreateTime >=', $where['CreateTime']['starttime']);
            $this->db->where('CreateTime <=', $where['CreateTime']['endtime']);
        }
        if (!empty($where['search'])) {
            $this->db->group_start();
            $this->db->like(array('UserName' => $where['search']));
            $this->db->group_end();
        }
        if (isset($where['sort'])) {
            $this->db->order_by($where['sort']['field'], $where['sort']['order']);
        }
        if (isset($where['limit'])) {
            $this->db->limit($where['limit']['limit'], $where['limit']['offset']);
        }
        $result = $this->db->get()->result_array();
        return $result;
    }

    /**
     * 是否正在学习
     */
    public function is_study($where)
    {
        $this->db->select('*')->from('task')->where($where)->get()->result_array();
        $num = $this->db->affected_rows ();

        if($num > 0)
        {
            $tmp['code'] = '0000';
            $tmp['msg'] = '有未完成任务';
            $tmp['data'] = array();
        } else {
            $tmp['code'] = '0377';
            $tmp['msg'] = '';
            $tmp['data'] = array();
        }

        return $tmp;
    }

    public function is_studys($where)
    {
        $this->db->select('*')->from('task')
            ->where_in('StudentID', $where['sids'])
            ->where(array('TaskType !='=>2))
            ->get()->result_array();
        $num = $this->db->affected_rows ();

        if($num > 0)
        {
            $tmp['code'] = '0000';
            $tmp['msg'] = '有未完成任务';
            $tmp['data'] = array();
        } else {
            $tmp['code'] = '0377';
            $tmp['msg'] = '';
            $tmp['data'] = array();
        }

        return $tmp;
    }

    /***
     * 删除用户
     * @param $where
     * @return mixed
     */
    public function del_user($data)
    {
        $this->db->where_in('UserID', $data);
        $this->db->delete( 'user');
        if ($this->db->affected_rows() > 0) {
            $this->db->where_in('UserID', $data);
            $this->db->delete( 'class_user');

            $this->db->where_in('StudentID', $data);
            $this->db->delete( 'task');

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
     * 启用、禁用
     */
    public function is_lock($data)
    {
        $this->db->where('UserID', $data['stuid']);
        $this->db->update('user', array('isLocked'=>$data['is_lock']));

        if ($this->db->affected_rows() > 0)
        {
            $tmp['code'] = '0000';
            $tmp['msg'] = 'success';
            $tmp['data'] = array();
        } else {
            $tmp['code'] = '0356';
            $tmp['msg'] = '';
            $tmp['data'] = array();

        }
        return $tmp;

    }

    /***
     * 获取班级总数
     * @param $where
     * @return int
     */
    public function get_count($where)
    {
        $this->db->select('count(1) as count');
        $this->db->from('class');
        if(isset($where['uid']))
        {
            $this->db->where('p_class.TeacherID', $where['uid']);
        }
        if (isset($where['CreateTime']) && is_array($where['CreateTime']) && !empty($where['CreateTime']['starttime'])) {
            $this->db->where('CreateTime >=', strtotime($where['CreateTime']['starttime']));
            $this->db->where('CreateTime <=', strtotime($where['CreateTime']['endtime']));
        }
        if (isset($where['search'])) {
            $this->db->like(array('ClassName' => $where['search']));
        }
        $result = $this->db->get()->result_array();
        return isset($result[0]['count']) ? $result[0]['count'] : 0;
    }

    /**
     * 统计一个班的学员
     */
    public function count_class_students($where)
    {
        $this->db->select('count(1) as count');
        $this->db->from('p_class_user');
        $this->db->where(array('p_class_user.ClassID'=>$where['ClassID']));

        if (!empty($where['search'])) {
            $this->db->join('p_user', "p_user.UserID = p_user.UserID", "left");
            $this->db->like(array('p_user.UserName' => $where['search']));
        }

        $result = $this->db->get()->result_array();
        return isset($result[0]['count']) ? $result[0]['count'] : 0;
    }

    /**
     * 班级任务
     */
    public function class_task($cid)
    {
        $res = $this->db->select('ClassID')->from('task')
            ->where(array('ClassID' => $cid, 'TaskType !=' => 2))
            ->limit(1)->get()->result_array();
        if(!empty($res))
        {
            $tmp['code'] = '0000';
            $tmp['msg'] = 'success';
            $tmp['data'] = array();
        } else {
            $tmp['code'] = '0301';
            $tmp['msg'] = '';
            $tmp['data'] = array();

        }
        return $tmp;

    }

    /***
     * 删除班级
     * @param $where
     * @return mixed
     */
    public function del_classes($data)
    {
        $this->db->where_in('ClassID', $data);
        $this->db->delete( 'class');

        if ($this->db->affected_rows() > 0) {
            $this->db->where_in('ClassID', $data);
            $this->db->delete( 'task');

            $this->db->where_in('ClassID', $data);
            $this->db->delete( 'class_user');
            $tmp['code'] = '0000';
            $tmp['msg'] = 'success';
            $tmp['data'] = array();
        } else {
            $tmp['code'] = 'error';
            $tmp['msg'] = '删除失败';
            $tmp['data'] = array();

        }
        return $tmp;
    }

    /***
     * 更新班级
     * @param $where
     * @param $data
     * @return mixed
     */
    public function update_class($where, $data)
    {
        $res = $this->db->select('*')->from('class')
            ->where(array('ClassName'=>$data['ClassName'], 'ClassID != '=>$where['ClassID']))
            ->limit(1)->get()->result_array();

        if(empty($res))
        {
            $this->db->update( 'class',$data,$where);
            if ($this->db->affected_rows() > 0) {
                $tmp['code'] = '0000';
                $tmp['msg'] = '更新成功';
                $tmp['data'] = array();
            } else {
                $tmp['code'] = 'error';
                $tmp['msg'] = '';
                $tmp['data'] = array();
            }
        } else {
            $tmp['code'] = 'error';
            $tmp['msg'] = '';
            $tmp['data'] = array();
        }
        

        return $tmp;

    }

    /**
     * 查找班级是否已存在
     * @param $classname string()
     * @return ResPacket
     */
    public function select_classname($classname)
    {
        $this->db->select('*')->where('ClassName',$classname)->get('p_class');
        $num = $this->db->affected_rows();
        if($num >=1){
            $status = 1;
        }else{
            $status = 2;
        }

        return $status;
    }

    /***
     * 获取某个班级的所有学生
     * @param $where
     * @return mixed
     */
    public function get_student($where)
    {
        $this->db->select('c.ClassName,u.UserID,u.UserPhone,u.UserName,u.UserSex,u.IsLocked,u.UserDepartment,u.UserEmail,u.CreateTime');
        $this->db->from('class_user as cu');
        $this->db->join("user as u", "u.UserID = cu.UserID", "left");
        $this->db->join("class as c", "cu.ClassID = c.ClassID", "left");
        if (!empty($where['ClassID'])) {
            $this->db->where(array('cu.ClassID' => $where['ClassID']));
        }
        $this->db->where('u.IsDeleted', 0);

        if (!empty($where['search'])) {
            $this->db->like(array('u.UserName' => $where['search']));
        }

        $this->db->order_by('u.CreateTime','DESC');
        if (isset($where['limit'])) {
            $this->db->limit($where['limit']['limit'], $where['limit']['offset']);
        }
        $result = $this->db->get()->result_array();
        return $result;

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

    /***
     * 获取某个班总人数
     * @param $where
     * @return int
     */
    public function get_student_count($where)
    {
        $this->db->select('count(1) as count');
        $this->db->from('class_user as cu');
        $this->db->join("user as u", "u.UserID = cu.UserID", "left");
        if (!empty($where['ClassID'])) {
            $this->db->where(array('cu.ClassID' => $where['ClassID']));
        }
        $this->db->where('u.IsDeleted', 0);

        if (!empty($where['search'])) {
            $this->db->like(array('u.UserName' => $where['search']));
        }
        $result = $this->db->get()->result_array();
        return isset($result[0]['count']) ? $result[0]['count'] : 0;

    }


    /***
     * 检测班级是否存在
     * @param $where
     * @return mixed
     */
    public function check_class($where)
    {
        $this->db->select('ClassID');
        $this->db->from('class');
        $this->db->where($where);
        $result = $this->db->get()->result_array();
        if (count($result) > 0) {
            $tmp['code'] = '0000';
            $tmp['msg'] = 'success';
            $tmp['data'] = array();
        } else {
            $tmp['code'] = '0380';
            $tmp['msg'] = 'error';
            $tmp['data'] = array();
        }
        return $tmp;

    }

    /***
     * 添加班级
     * @param array $data
     */
    public function add_class($data)
    {
        $this->db->insert('class', $data);
        $num = $this->db->affected_rows();
        if ($num >= 1) {
            $tmp['code'] = '0000';
            $tmp['msg'] = 'success';
            $tmp['data'] = $this->db->insert_id();
        } else {
            $tmp['code'] = '0385';
            $tmp['msg'] = 'error';
            $tmp['data'] = '';
        }

        return $tmp;

    }

    /***
     * 批量插入某个班级的学生
     * @param $data array(array(UserCode,ClassCode)...)
     * @return mixed
     */
    public function add_class_student($data)
    {
        $this->db->insert_batch('class_user', $data);
        $num = $this->db->affected_rows();
        if ($num >= 1) {
            $tmp['code'] = '0000';
            $tmp['msg'] = 'success';
            $tmp['data'] = '';
        } else {
            $tmp['code'] = '0386';
            $tmp['msg'] = '班级学员添加失败';
            $tmp['data'] = '';
        }
        return $tmp;
    }

    /**
     * 获取某个老师创建的所有班级
     * author kyx
     */
    public function get_class($where)
    {
        $this->db->select('ClassName,ClassID');
        $this->db->from('class');
        $this->db->where('TeacherID', $where['TeacherID']);//角色
        if (!empty($where['search'])) {
            $this->db->like(array('ClassName' => $where['search']));
        }

        $this->db->order_by('CreateTime','DESC');

        if(isset($where['num']) && isset($where['offset'])) {
            $this->db->limit($where['num'], $where['offset']);
        }
        $result = $this->db->get()->result_array();
        return $result;
    }

    /**
     * 获取多个班级下的学员
     * author kyx
     */
    public function get_class_student($where)
    {
        $this->db->select('u.UserID as StudentID,ClassID');
        $this->db->from('class_user as cu');
        $this->db->join("user as u", "u.UserID = cu.UserID", "left");
        $this->db->where_in('ClassID',$where);
        $result = $this->db->get()->result_array();
        return $result;
    }

    /***
     * 获取班级可添加的学员
     * @return mixed
     */
    public function get_all_user_add($where)
    {
        $this->db->select('UserID,IFNULL(StuId,"") as StuId,UserPhone,UserName,UserSex,IsLocked,IFNULL(UserDepartment,"") as UserDepartment ,CreateTime,UserPoint');
        $this->db->from('user');
        $this->db->where('UserRole=', 3);//角色
        $this->db->where('IsDeleted', 0);
        $this->db->where('UserID NOT IN  (SELECT UserID FROM p_class_user WHERE ClassID = '.$where['classcode'].')',NULL,FALSE);
        if (!empty($where['search'])) {
            $this->db->group_start();
            $this->db->like(array('UserName' => $where['search']));
            $this->db->group_end();
        }
        if (isset($where['limit'])) {
            $this->db->limit($where['limit']['limit'], $where['limit']['offset']);
        }
        $result = $this->db->get()->result_array();
        if ($result) {
            foreach ($result as &$row) {
                //获取班级信息
                $res = $this->get_user_class($row['UserID']);
                if ($res) {
                    $classname = array();
                    foreach ($res as $value) {
                        array_push($classname, $value['ClassName']);
                    }
                    $row['class'] = implode(',', $classname);
                } else {
                    $row['class'] = '';
                }

            }
            unset($row);
        }
        return $result;
    }

    /***
     * 获取可添加学员数量
     * @param $where
     * @return int
     */
    public function get_count_add($where)
    {
        $this->db->select('count(1) as count');
        $this->db->from('user');
        $this->db->where('UserRole=', 3);//学员
        $this->db->where('IsDeleted', 0);
        $this->db->where('UserID NOT IN (SELECT UserID FROM p_class_user WHERE ClassID = '.$where['classcode'].')',NULL,FALSE);
        if (!empty($where['search'])) {
            $this->db->group_start();
            $this->db->like(array('UserName' => $where['search']));
            $this->db->group_end();
        }
        $result = $this->db->get()->result_array();
        return isset($result[0]['count']) ? $result[0]['count'] : 0;

    }

    /***
     *
     * @param $user_code
     * @return mixed
     */
    public function get_user_class($user_code)
    {
        $this->db->select('ClassName');
        $this->db->from('class');
        $this->db->join('class_user','class.ClassID=class_user.ClassID','left');
        $this->db->where(array('class_user.UserID' => $user_code));
        $result = $this->db->get()->result_array();
        return $result;

    }

    /***
     * 删除某个班的某个用户
     */
    public function del_class_user($classcode, $usercode)
    {
        $this->db->where(array('ClassID'=> $classcode, 'StudentID' => $usercode));
        $this->db->delete( 'task');

        $this->db->where(array('ClassID'=> $classcode, 'UserID' => $usercode));
        $this->db->delete( 'class_user');

        if ($this->db->affected_rows() > 0) {
            $tmp['code'] = '0000';
            $tmp['msg'] = '删除成功';
            $tmp['data'] = array();
        } else {
            $tmp['code'] = 'error';
            $tmp['msg'] = '删除失败';
            $tmp['data'] = array();
        }
        return $tmp;

    }












}