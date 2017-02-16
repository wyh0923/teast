<?php

/**
 * Created by PhpStorm.
 * User: qirupeng
 * Date: 2016/7/28
 * Time: 9:29
 */
class User_model extends CI_Model
{
    /***
     * 检测用户原密码是否正确
     * @param $where
     * @return mixed
     */
    public function check_user($where)
    {
        $this->db->select('UserID');
        $this->db->from('user');
        $this->db->where($where);
        $this->db->limit(1);
        $result = $this->db->get()->result_array();
        if (count($result) > 0) {
            $tmp['code'] = '0000';
            $tmp['msg'] = 'success';
            $tmp['data'] = $result[0]['UserID'];
        } else {
            $tmp['code'] = '0305';
            $tmp['msg'] = 'error';
            $tmp['data'] = [];
        }
        return $tmp;

    }

    /**
     * 获取对应角色的菜单
     * @param int $role_id 角色ID
     * @return array
     */
    public function verify_user($data)
    {
        $this->db->select('UserID');
        $this->db->from('user');
        $this->db->where($data);
        $result = $this->db->get()->result_array();
        if (count($result) > 0) {
            $tmp['code'] = '0000';
            $tmp['msg'] = 'success';
            $tmp['data'] = array();
        } else {
            $tmp['code'] = '0200';
            $tmp['msg'] = 'error';
            $tmp['data'] = array();
        }
        return $tmp;
    }

    /***
     * 修改信息
     * @param $data
     * @param $where
     * @return mixed
     */
    public function update_user($data, $where)
    {
        $this->db->update('user', $data, $where);
        if ($this->db->affected_rows() > 0) {
            $tmp['code'] = '0000';
            $tmp['msg'] = 'success';
            $tmp['data'] = array();
        } else {
            $tmp['code'] = '0308';
            $tmp['msg'] = '未更新内容';
            $tmp['data'] = array();
        }
        return $tmp;

    }

    /***
     * 获取教员
     * @param $where
     * @return mixed
     */
    public function get_all_teacher($where)
    {
        $this->db->select('UserID,UserPhone,UserName,UserSex,IsLocked,UserDepartment,CreateTime');
        $this->db->from('user');
        $this->db->where('UserRole=', 2);//角色
        $this->db->where('IsDeleted', 0);
        if (isset($where['CreateTime']) && is_array($where['CreateTime']) && !empty($where['CreateTime']['starttime'])) {
            $this->db->where('CreateTime >=', strtotime($where['CreateTime']['starttime']));
            $this->db->where('CreateTime <=', strtotime($where['CreateTime']['endtime']));
        }
        if (!empty($where['search'])) {
            $this->db->group_start();
            $this->db->like(array('UserName' => $where['search']));
            $this->db->group_end();
        }
        if (isset($where['sort'])) {
            $this->db->order_by($where['sort']['field'], $where['sort']['order']);
        }else{
            $this->db->order_by('UserID', 'DESC');//默认排序
        }
        if (isset($where['limit'])) {
            $this->db->limit($where['limit']['limit'], $where['limit']['offset']);
        }
        $result = $this->db->get()->result_array();
        return $result;

    }

    /***
     * 获取学员
     * @param $where
     * @return mixed
     */
    public function get_all_student($where)
    {
        $this->db->select('UserID,UserPhone,UserName,UserSex,IsLocked,UserDepartment,CreateTime');
        $this->db->from('user');
        $this->db->where('UserRole=', 3);//角色
        $this->db->where('IsDeleted', 0);
        if (isset($where['CreateTime']) && is_array($where['CreateTime']) && !empty($where['CreateTime']['starttime'])) {
            $this->db->where('CreateTime >=', strtotime($where['CreateTime']['starttime']));
            $this->db->where('CreateTime <=', strtotime($where['CreateTime']['endtime']));
        }
        if (!empty($where['search'])) {
            $this->db->group_start();
            $this->db->like(array('UserName' => $where['search']));
            $this->db->group_end();
        }
        if (isset($where['sort'])) {
            $this->db->order_by($where['sort']['field'], $where['sort']['order']);
        }else{
            $this->db->order_by('UserID', 'DESC');//默认排序
        }
        if (isset($where['limit'])) {
            $this->db->limit($where['limit']['limit'], $where['limit']['offset']);
        }
        $result = $this->db->get()->result_array();
        return $result;

    }

    /***
     * 添加用户，教员、学员
     * @param array $data
     * @param int $roleid 角色
     */
    public function add_user($data, $roleid)
    {
        $data['CreateTime'] = time();
        $data['UserRole'] = $roleid;
        $data['UserPass'] = md5($data['UserPass']);
        return $this->db->insert('user', $data);
    }

    /***
     * 批量插入用户
     * @param $data
     * @param $roleid
     * @return mixed
     */
    public function batch_add_user($data)
    {
        $this->db->insert_batch('user', $data);
        $num = $this->db->affected_rows();
        if ($num >= 1) {
            $tmp['code'] = '0000';
            $tmp['msg'] = 'success';
            $tmp['data'] = '';
        } else {
            $tmp['code'] = '0386';
            $tmp['msg'] = '用户添加失败';
            $tmp['data'] = '';
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
        //此处有个问题若删除的是老师，是否更新老师所在的班
        $this->db->where_in('UserID', $data);
        $this->db->delete( 'class_user');
        $this->db->where_in('UserID', $data);
        $this->db->delete( 'user');
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

    /***
     * 锁定用户
     * @param $where
     * @return mixed
     */
    public function lock_user($where, $type)
    {
        $data = array(
            "IsLocked" => $type == 0 ? 0 : 1
        );
        $this->db->update('user', $data, $where);
        if ($this->db->affected_rows() > 0) {
            $tmp['code'] = '0000';
            $tmp['msg'] = 'success';
            $tmp['data'] = array();
        } else {
            $tmp['code'] = '0319';
            $tmp['msg'] = '操作失败';
            $tmp['data'] = array();

        }
        return $tmp;
    }

    /***
     * 获取符合查询条件的记录总数
     * @param $where
     * @param int $roleid 角色
     * @return int
     */
    public function get_count($where, $roleid)
    {
        $this->db->select('count(1) as count');
        $this->db->from('user');
        $this->db->where('UserRole=', $roleid);//角色
        $this->db->where('IsDeleted', 0);
        if (isset($where['CreateTime']) && is_array($where['CreateTime']) && !empty($where['CreateTime']['starttime'])) {
            $this->db->where('CreateTime >=', strtotime($where['CreateTime']['starttime']));
            $this->db->where('CreateTime <=', strtotime($where['CreateTime']['endtime']));
        }
        if (!empty($where['search'])) {
            $this->db->group_start();
            $this->db->like(array('UserName' => $where['search']));
            $this->db->group_end();
        }
        $result = $this->db->get()->result_array();
        return isset($result[0]['count']) ? $result[0]['count'] : 0;

    }

    /***
     * 获取用户信息
     * @param $user_code
     * @return mixed
     */
    public function get_userinfo($user_code)
    {
        $this->db->select('UserID,UserAccount,UserRole,UserEmail,UserPhone,UserName,UserSex,IsLocked,UserDepartment,UserIcon,UserPoint,StuId');
        $this->db->from('user');
        $this->db->where(array('UserID' => $user_code));
        $this->db->limit(1);
        $result = $this->db->get()->result_array();
        return isset($result[0]) ? $result[0]: [];
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
     * 获取所有学员
     * @param $where
     * @return mixed
     */
    public function get_all_user($where)
    {
        $this->db->select('UserID,IFNULL(StuId,"") as StuId,UserPhone,UserName,UserSex,IsLocked,IFNULL(UserDepartment,"") as UserDepartment ,CreateTime,IFNULL(UserPoint,0) as UserPoint');
        $this->db->from('user');
        $this->db->where('UserRole=', 3);//角色
        $this->db->where('IsDeleted', 0);
        if (isset($where['CreateTime']) && is_array($where['CreateTime']) && !empty($where['CreateTime']['starttime'])) {
            $this->db->where('CreateTime >=', strtotime($where['CreateTime']['starttime']));
            $this->db->where('CreateTime <=', strtotime($where['CreateTime']['endtime']));
        }
        if (!empty($where['search'])) {
            $this->db->group_start();
            $this->db->like(array('UserName' => $where['search']));
            $this->db->group_end();
        }
        if (isset($where['sort'])) {
            $this->db->order_by($where['sort']['field'], $where['sort']['order']);
        }else{
            $this->db->order_by('UserID', 'DESC');
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
     * 获取班级可添加的学员
     * @return mixed
     */
    public function get_all_user_add($where)
    {
        $this->db->select('UserID,IFNULL(StuId,"") as StuId,UserPhone,UserName,UserSex,IsLocked,IFNULL(UserDepartment,"") as UserDepartment ,CreateTime,IFNULL(UserPoint,0) as UserPoint');
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
     * 会员登录
     * @param $username
     * @param $password
     * @return mixed
     */
    public function login($username, $password)
    {
        $this->db->select('UserID,UserAccount,UserEmail,UserPhone,UserName,UserRole,UserSex,IsLocked,UserPoint,UserIcon,UserTheme');
        $this->db->from('user');
        $this->db->where(array('UserAccount' => $username, 'UserPass'=> $password, 'IsDeleted' => 0));
        $this->db->limit(1);
        $result = $this->db->get()->result_array();
        if($result) {
            //日志
            $this->load->library('Log_user');
            $data = array(
                'UserID' => $result[0]['UserID'],
                'LogTaskName' => '登录',
                'LogContent' => '登录系统',
                'LogTypeID' => 4,
                'LogResult' => site_url() . "Login/index"
            );
            $this->log_user->add_log($data);
        }
        return isset($result[0]) ? $result[0]: 0;

    }
    /***
     * 获取创建ctf模板的用户
     * @param $where
     * @return mixed
     */
    public function get_all_ctf_author($where)
    {
        $this->db->select('UserID,UserName');
        $this->db->from('user as u');
        //$this->db->where('UserID IN (SELECT AuthorID FROM p_ctf)', NULL, FALSE);
        $this->db->where_in('UserRole', array(1,2));
        if (isset($where['sort'])) {
            $this->db->order_by($where['sort']['field'], $where['sort']['order']);
        }else{
            $this->db->order_by('UserID', 'DESC');//默认排序
        }
        if (isset($where['limit'])) {
            $this->db->limit($where['limit']['limit'], $where['limit']['offset']);
        }
        $result = $this->db->get()->result_array();
        return $result;

    }

    /***
     * 获取所有创建场景作者
     * @param $where
     * @return mixed
     */
    public function get_all_scene_author($where)
    {
        $this->db->select('UserID,UserName');
        $this->db->from('user');
        $this->db->where_in('UserRole', array(1,2));
        if (isset($where['sort'])) {
            $this->db->order_by($where['sort']['field'], $where['sort']['order']);
        }else{
            $this->db->order_by('UserID', 'DESC');//默认排序
        }
        if (isset($where['limit'])) {
            $this->db->limit($where['limit']['limit'], $where['limit']['offset']);
        }
        $result = $this->db->get()->result_array();
        return $result;

    }

    /***
     * 获取用户名
     * @param $user
     * @return array
     */
    public function get_account($user)
    {
        $this->db->select('UserID,UserAccount,UserName');
        $this->db->from('user');
        $this->db->where_in('UserID',$user);
        $result = $this->db->get()->result_array();
        $data = array();
        if (count($result) > 0) {
            foreach ($result as $row)
            {
                $data[$row['UserID']] = $row;
            }
        }
        return $data;
    }

    /***
     * 检查用户是否有创建体系
     * @param $account
     * @return bool
     */
    public function check_architecture($account)
    {
        $this->db->select("ArchitectureID");
        $this->db->from("architecture");
        $this->db->where('Author', $account);
        $result = $this->db->get()->result_array();
        if(count($result)>0){
            return TRUE;
        }else{
            return FALSE;
        }
    }
    /***
     * 检查用户是否有创建课程
     * @param $account
     * @return bool
     */
    public function check_package($account)
    {
        $this->db->select("PackageID");
        $this->db->from("package");
        $this->db->where_in('PackageAuthor', $account);
        $result = $this->db->get()->result_array();
        if(count($result)>0){
            return TRUE;
        }else{
            return FALSE;
        }
    }

    /***
     * 是否创建题目
     * @param $account
     * @return bool
     */
    public function check_question($account)
    {
        $this->db->select("QuestionID");
        $this->db->from("question");
        $this->db->where_in('QuestionAuthor', $account);
        $result = $this->db->get()->result_array();
        if(count($result)>0){
            return TRUE;
        }else{
            return FALSE;
        }
    }


}