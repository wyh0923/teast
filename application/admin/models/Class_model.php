<?php

/**
 * Created by PhpStorm.
 * User: qirupeng
 * Date: 2016/8/5
 * Time: 10:29
 */
class Class_model extends CI_Model
{
    /***
     * 获取班级
     * @param $where
     * @return mixed
     */
    public function get_all_classes($where)
    {
        $this->db->select('p_class.ClassName, p_class.ClassID, p_class.CreateTime, ' .
            'COUNT(DISTINCT p_class_user.UserID) AS StudentNum, IFNULL(p_task.TaskNum,0) AS TaskNum, ' .
            'IFNULL(p_task.TaskScore, 0) as TaskScore');
        $this->db->from('p_class');
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

    /***
     * 获取班级总数
     * @param $where
     * @return int
     */
    public function get_count($where)
    {
        $this->db->select('count(1) as count');
        $this->db->from('class');
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
     * 删除班级
     * @param $where
     * @return mixed
     */
    public function del_classes($data)
    {
        //此处应考虑事务以后再加
        $this->db->where_in('ClassID', $data);
        $this->db->delete( 'class');
        if ($this->db->affected_rows() > 0) {
            //有可能班级里没有用户
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
     * 删除某个班的某个用户
     */
    public function del_class_user($classcode, $usercode)
    {
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

    /***
     * 更新班级
     * @param $where
     * @param $data
     * @return mixed
     */
    public function update_class($where, $data)
    {
        $this->db->update( 'class',$data,$where);
        if ($this->db->affected_rows() > 0) {
            $tmp['code'] = '0000';
            $tmp['msg'] = '更新成功';
            $tmp['data'] = array();
        } else {
            $tmp['code'] = 'error';
            $tmp['msg'] = '未更新内容';
            $tmp['data'] = array();
        }
        return $tmp;

    }


}