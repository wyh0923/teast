<?php
/**
 * Created by PhpStorm.
 * User: liuqi
 * Date: 2016/8/22
 * Time: 17:18
 */

/**
 * 班级人员管理
 */
class Classstaff extends ECQ_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->uid = $this->session->userdata('UserID');
        $this->load->model('Class_model');
        $this->load->model('User_model');
        $this->load->library('Interface_output');

    }

    /**
     * 我的班级
     */
    public function myclass()
    {
        $output_data = array();
        //从url里获取参数
        $parameter = $this->uri->uri_to_assoc(3);
        $uri_segment = (count($parameter) * 2) + 1;
        $per_page = $this->uri->segment($uri_segment) === NULL ? 1 : $this->uri->segment($uri_segment);
        $search = array_key_exists('search', $parameter) ? urldecode($parameter['search']) : '';
        $time = array_key_exists('time', $parameter) ? $parameter['time'] : '';
        $sort = array_key_exists('sort', $parameter) ? $parameter['sort'] : '';

        //安全过滤
        $search = $this->security->xss_clean($search);

        $page = max(intval($per_page), 1);
        $perpage = 10;//每页记录数
        $offset = ($page - 1) * $perpage;

        $where = array(
            'uid' => $this->uid,
            'limit' => array('limit' => $perpage, 'offset' => $offset)
        );
        $pageurl = '';//页面url拼接

        if (!empty($search)) {
            $where['search'] = $search;//搜索字符串要转码
            $pageurl .= '/search/' . $search;
        }

        //按时间范围查询
        if (!empty($time)) {
            $time = str_replace('%20', ' ', $time);
            $where['CreateTime'] = array('starttime' => explode("_", $time)[0], 'endtime' => explode("_", $time)[1]);
            $pageurl .= '/time/' . $time;
        }
        //排序方式:CreateTime DESC
        if (!empty($sort)) {
            $where['sort'] = array('field' => explode("%20", $sort)[0], 'order' => explode("%20", $sort)[1]);
            $pageurl .= '/sort/' . $sort;
        }

        //p($where);
        $output_data['classes_list'] = $this->Class_model->get_class_infos($where);

        $output_data['search'] = $search;
        $output_data['time'] = isset($where['CreateTime']) ? $where['CreateTime'] : '';
        $output_data['sort'] = isset($where['sort']) ? $where['sort'] : '';

        //分页
        $this->load->helper('util');
        $output_data['total_rows'] = $this->Class_model->get_count($where);;//获取总记录数
        $output_data['pages'] = get_pages(site_url('Classstaff/myclass'), $output_data['total_rows']);

        $output_data['page_url'] = site_url('Classstaff/myclass') . $pageurl.'/';

        $output_data['page_count']=ceil($output_data['total_rows']/10);
        $output_data['page_pre']=$page;

        $this->load->view('teacher/myclass', $output_data);
    }

    /***
     * 班级详情
     */
    public function classdetail()
    {
        //$classid = intval($this->input->get('classid', TRUE));
        $parameter = $this->uri->uri_to_assoc(3);
        $uri_segment = (count($parameter) * 2) + 1;
        $per_page = $this->uri->segment($uri_segment) === NULL ? 1 : $this->uri->segment($uri_segment);
        $classid = array_key_exists('classid', $parameter) ? intval($parameter['classid']) : 0;

        $search = array_key_exists('search', $parameter) ? urldecode($parameter['search']) : '';
        //安全过滤
        $search = $this->security->xss_clean($search);
        $page = max(intval($per_page), 1);
        $perpage = 10;//每页记录数
        $offset = ($page - 1) * $perpage;

        $where = array(
            'ClassID' => $classid,
            'search' => $search,//搜索字符串要转码
            'limit' => array('limit' => $perpage, 'offset' => $offset)
        );

        $output_data['student_list'] = $this->Class_model->get_student($where);
        $output_data['search'] = $search;
        $output_data['classid'] = $classid;
        $output_data['classname'] = $this->Class_model->get_class_name($classid);

        //分页
        $this->load->helper('util');
        $output_data['total_rows'] = $this->Class_model->count_class_students($where);;//获取总记录数
        $output_data['pages'] = get_pages(site_url('Classstaff/classdetail/classid').'/'.$classid, $output_data['total_rows']);

        $output_data['page_url'] = site_url('Classstaff/editclass') . '/classid/' . $classid . '/' . (!empty($search) ? 'search/' . $search . '/' : '');

        $output_data['page_count']=ceil($output_data['total_rows']/10);
        $output_data['page_pre']=$page;

        $this->load->view('teacher/classdetail', $output_data);

    }

    /**
     * 班级任务
     */
    public function classtask()
    {
        $cid= $this->input->post('cid', TRUE);

        $tmp = $this->Class_model->class_task($cid);
        
        $this->interface_output->output_fomcat('js_Ajax', $tmp);
    }
    
    /***
     * 删除班级信息
     */
    public function del_classes()
    {
        do {
            $classcode = $this->input->post('classcode', TRUE);
            if (empty($classcode)) {
                $tmp = array('code' => '0316', 'msg' => '参数错误!', 'data' => []);
                break;
            }
            $data = json_decode($classcode);
            //是否单个删除
            if (is_int($data)) $data = array($data);

            $tmp = $this->Class_model->del_classes($data);

        } while (FALSE);

        $this->interface_output->output_fomcat('js_Ajax', $tmp);

    }

    /***
     * 删除某个班的某个用户
     */
    public function del_class_user()
    {
        $tmp = array('code' => '0000', 'msg' => 'success!', 'data' => []);
        do {
            $classcode = intval($this->input->post('classcode'));
            $usercode = intval($this->input->post('usercode'));
            if ($classcode == 0) {
                $tmp = array('code' => '0316', 'msg' => '班级信息错误!', 'data' => []);
                break;
            }
            if ($usercode == 0) {
                $tmp = array('code' => '0317', 'msg' => '学员信息错误!', 'data' => []);
                break;
            }

            $result = $this->Class_model->del_class_user($classcode, $usercode);
            if ($result['code'] != '0000') {
                $tmp['code'] = $result['code'];
                $tmp['msg'] = $result['msg'];
                $tmp['data'] = array();
                break;
            }

        } while (FALSE);

        $this->interface_output->output_fomcat('js_Ajax', $tmp);
    }

    /**
     * 新建班级
     */
    public function addclass()
    {

        if ($data = $this->input->post(NULL, TRUE)) {

            $tmp = array('code' => '0000', 'msg' => '录入学员成功!', 'data' => []);
            $this->load->library('Data_validate');//验证类引入
            do {
                //数据检查
                if (empty($data['classname'])) {
                    $tmp = array('code' => '0380', 'msg' => '班级名称不能为空!', 'data' => []);
                    break;
                }
                if (!$this->data_validate->length($data['classname'], 3, 3, 16)) {
                    $tmp = array('code' => '0381', 'msg' => '班级名称必须是3到16位的字符!', 'data' => []);
                    break;
                }
                if (empty($data['type'])) {
                    $tmp = array('code' => '03811', 'msg' => '添加类型错误!', 'data' => []);
                    break;
                }

                //名称是否重复检查
                $result = $this->Class_model->check_class(array('ClassName' => $data['classname']));
                if ($result['code'] == '0000') {
                    $tmp = array('code' => '0382', 'msg' => '班级名称已有帐号占用!', 'data' => []);
                    break;
                }
                //添加班级
                $result = $this->Class_model->add_class(array('ClassName' => $data['classname'],
                    'TeacherID' => $this->uid, 'CreateTime' => time()));
                if ($result['code'] != '0000') {
                    $tmp = array('code' => '0383', 'msg' => '班级创建失败!', 'data' => []);
                    break;
                }
                $class_id = $result['data'];
                //选择学员或导入学员都要求传ID过来统一处理
                if (!empty($data['infos']) && is_array($data['infos']) && $data['type'] == 1) {
                    $student = array();
                    foreach ($data['infos'] as $v) {
                        $student[] = array(
                            'UserID' => $v,
                            'ClassID' => $class_id
                        );
                    }
                    //p($student);die;
                    $res = $this->Class_model->add_class_student($student);
                    if ($res['code'] != '0000') {
                        $tmp = array('code' => $res['code'], 'msg' => $res['msg'], 'data' => []);
                        break;
                    }

                }

                $repeat_error = array(array('学号', '用户名', '密码', '姓名', '性别', '邮箱', '工作单位', '电话', '错误信息'));

                //导入学员
                if ($data['type'] == 2){
                    if (empty($data['filename'])) {
                        $tmp = array('code' => '0390', 'msg' => '请先上传文件!', 'data' => []);
                        break;
                    }
                    if (strpos($data['filename'], '../') !== FALSE) {
                        $tmp = array('code' => '0391', 'msg' => '文件名非法!', 'data' => []);
                        break;
                    }
                    if (empty($data['infos'])) {
                        $tmp = array('code' => '0392', 'msg' => '请先选择要导入的用户!', 'data' => []);
                        break;
                    }
                    $import_users = $data['infos'];
                    //是否有重复账号,学号也要检测
                    $counts = array_count_values($import_users);
                    $error_data = array();
                    foreach ($counts as $key => $item) {
                        if ($item > 1) $error_data[] = $key;
                    }
                    if (count($error_data) > 0) {
                        $tmp = array('code' => 'repeat', 'msg' => '存在相同的用户名', 'data' => implode(",", $error_data));
                        break;
                    }
                    //read csv
                    $field = array('StuId', 'UserAccount', 'UserPass', 'UserName', 'UserSex', 'UserEmail', 'UserDepartment', 'UserPhone');
                    $this->load->library('Get_csv');
                    $csv_data = $this->get_csv->set_file_path(getcwd() . '/resources/files/csv/' . $data['filename'])->get_array($field);

                    if (empty($csv_data)) {
                        $tmp = array('code' => '0394', 'msg' => '上传文件没有数据!', 'data' => []);
                        break;
                    }

                    $user_data = array();
                    $stu = array();//学号
                    $data_error = array(array('学号', '用户名', '密码', '姓名', '性别', '邮箱', '工作单位', '电话', '错误信息'));

                    foreach ($csv_data as $k => $row) {
                        $validate = $this->_validate_user($row);//验证用户

                        //是否为所选用户对于用户名已经存在的不做处理
                        if (in_array($row['UserAccount'], $import_users) && empty($validate)) {
                            $row['UserPass'] = md5($row['UserPass']);
                            $row['UserRole'] = 3;//角色
                            $row['CreateTime'] = time();
                            $user_data[] = $row;
                            if($row['StuId'] != '')
                            {$stu[] = $row['StuId'];}

                        }
                        //把出错的记录
                        if (!empty($validate) && $validate['code'] != '0315') {
                            $row['error'] = $validate['msg'];
                            unset($row['CreateTime']);
                            $data_error[] = $row;
                        }
                        //重复学员
                        if(!empty($validate) && $validate['code'] == '0315'){
                            $row['error'] = $validate['msg'];
                            unset($row['CreateTime']);
                            $repeat_error[] = $row;
                        }
                    }

                    //重复学号
                    $counts = array_count_values($stu);
                    $error_data = array();
                    foreach ($counts as $key => $item) {
                        if ($item > 1) $error_data[] = $key;
                    }
                    if (count($error_data) > 0) {
                        $tmp = array('code' => 'repeat', 'msg' => '存在相同的学号', 'data' => implode(",", $error_data));
                        break;
                    }

                    if (count($data_error) > 1) {
                        //生成CSV文件
                        $this->load->library('Get_csv');
                        $filename = 'error' . time() . '.csv';
                        $this->get_csv->set_file_path(getcwd() . '/resources/files/csv/' . $filename)->rewrite_csv($data_error);
                        $tmp = array('code' => 'error', 'msg' => '有数据信息出错!', 'data' => $filename);
                        break;
                    }
                    if (empty($user_data)){
                        $tmp = array('code' => '0557', 'msg' => '数据均已存在', 'data' => []);
                        break;
                    }

                    $result = $this->User_model->batch_add_user($user_data);
                    if ($result['code'] != '0000') {
                        $tmp = array('code' => $result['code'], 'msg' => $result['msg'], 'data' => []);
                        break;
                    }

                    //加入班级表
                    $student = array();
                    foreach ($user_data as $v) {
                        //获取用户UserID
                        $user = $this->User_model->check_user(array('UserAccount' => $v['UserAccount']));
                        
                        if ($user['code'] != '0000'){
                            $student[] = array(
                                'UserID' => $user['data'],
                                'ClassID' => $class_id
                            );
                        }

                    }
                    $res = $this->Class_model->add_class_student($student);
                    if ($res['code'] != '0000') {
                        $tmp = array('code' => $res['code'], 'msg' => $res['msg'], 'data' => []);
                        break;
                    }

                }

                //事务回滚
                if ($this->db->trans_status() === FALSE)
                {
                    $this->db->trans_rollback();
                }
                else
                {
                    $this->db->trans_commit();
                }

                if (count($repeat_error) > 1) {//导入成功
                    //生成CSV文件
                    $this->load->library('Get_csv');
                    $filename = 'error' . time() . '.csv';
                    $this->get_csv->set_file_path(getcwd() . '/resources/files/csv/' . $filename)->rewrite_csv($repeat_error);
                    $tmp = array('code' => 'import', 'msg' => '导入学员成功!', 'data' => array('file'=>$filename,
                        'success_count'=>count($student),'count'=>count($repeat_error)-1));
                    break;
                }


            } while (FALSE);

            $this->interface_output->output_fomcat('js_Ajax', $tmp);

        } else {
            $this->load->view('teacher/addclass');
        }
    }

    /**
     * 新建班级
     */
    public function stuimport()
    {
        if ($data = $this->input->post(NULL, TRUE)) {

            $tmp = array('code' => '0000', 'msg' => '录入学员成功!', 'data' => []);
            $this->load->library('Data_validate');//验证类引入
            do {
                //导入学员
                if ($data['type'] == 2){

                    $import_users = $data['infos'];

                    $field = array('StuId', 'UserAccount', 'UserPass', 'UserName', 'UserSex', 'UserEmail', 'UserDepartment', 'UserPhone');
                    $this->load->library('Get_csv');
                    $csv_data = $this->get_csv->set_file_path(getcwd() . '/resources/files/csv/' . $data['filename'])->get_array($field);

                    $user_data = array();
                    $stu = array();//学号
                    $data_error = array(array('学号', '用户名', '密码', '姓名', '性别', '邮箱', '工作单位', '电话', '错误信息'));
                    $repeat_error = array(array('学号', '用户名', '密码', '姓名', '性别', '邮箱', '工作单位', '电话', '错误信息'));

                    $importArr = array();
                    foreach ($csv_data as $k => $row){
                        if (in_array($row['UserAccount'], $import_users)){
                            $importArr[]  = $row;
                        }
                    }

                    foreach ($importArr as $k => $row) {

                        $validate = $this->_validate_user($row);//验证用户
                        //是否为所选用户对于用户名已经存在的不做处理
                        if (in_array($row['UserAccount'], $import_users) && empty($validate)) {
                            $row['UserPass'] = md5($row['UserPass']);
                            $row['UserRole'] = 3;//角色
                            $row['CreateTime'] = time();
                            $user_data[] = $row;
                            $stu[] = $row['StuId'];

                        }
                        //把出错的记录
                        if (!empty($validate) && $validate['code'] != '0315') {
                            $row['error'] = $validate['msg'];
                            unset($row['CreateTime']);
                            $data_error[] = $row;
                        }
                        //重复学员
                        if(!empty($validate) && $validate['code'] == '0315'){
                            $row['error'] = $validate['msg'];
                            unset($row['CreateTime']);
                            $repeat_error[] = $row;
                        }
                    }

                    //重复学号
                    $counts = array_count_values($stu);

                    $error_data = array();
                    foreach ($counts as $key => $item) {
                        if ($item > 1) $error_data[] = $key;
                    }

                    if (count($error_data) > 0) {
                        $tmp = array('code' => 'repeat', 'msg' => '存在相同的学号', 'data' => implode(",", $error_data));
                        break;
                    }

                    if (count($data_error) > 1) {
                        //生成CSV文件
                        $this->load->library('Get_csv');
                        $filename = 'error' . time() . '.csv';
                        $this->get_csv->set_file_path(getcwd() . '/resources/files/csv/' . $filename)->rewrite_csv($data_error);
                        $tmp = array('code' => 'error', 'msg' => '有数据信息出错!', 'data' => $filename);
                        break;
                    }
                    if (empty($user_data)){
                        $tmp = array('code' => '0557', 'msg' => '数据无效', 'data' => []);
                        break;
                    }

                    $result = $this->User_model->batch_add_user($user_data);
                    if ($result['code'] != '0000') {
                        $tmp = array('code' => $result['code'], 'msg' => $result['msg'], 'data' => []);
                        break;
                    }

                    //加入班级表
                    $student = array();
                    foreach ($user_data as $v) {
                        //获取用户UserID
                        $user = $this->User_model->check_user(array('UserAccount' => $v['UserAccount']));

                        if ($user['code'] != '0000'){
                            $student[] = array(
                                'UserID' => $user['data'],
                                'ClassID' => $data['cid']
                            );
                        }

                    }
                    $res = $this->Class_model->add_class_student($student);
                    if ($res['code'] != '0000') {
                        $tmp = array('code' => $res['code'], 'msg' => $res['msg'], 'data' => []);
                        break;
                    }

                }

                //事务回滚
                if ($this->db->trans_status() === FALSE)
                {
                    $this->db->trans_rollback();
                }
                else
                {
                    $this->db->trans_commit();
                }
                if (count($repeat_error) > 1) {//导入成功
                    //生成CSV文件
                    $this->load->library('Get_csv');
                    $filename = 'error' . time() . '.csv';
                    $this->get_csv->set_file_path(getcwd() . '/resources/files/csv/' . $filename)->rewrite_csv($repeat_error);
                    $tmp = array('code' => 'import', 'msg' => '导入学员成功!', 'data' => array('file'=>$filename,
                        'success_count'=>count($student),'count'=>count($repeat_error)-1));
                    break;
                }


            } while (FALSE);

            $this->interface_output->output_fomcat('js_Ajax', $tmp);

        }
    }




    /**
     * 编辑班级
     */
    public  function editclass()
    {
        //$classid = intval($this->input->get('classid', TRUE));
        $parameter = $this->uri->uri_to_assoc(3);
        $uri_segment = (count($parameter) * 2) + 1;
        $per_page = $this->uri->segment($uri_segment) === NULL ? 1 : $this->uri->segment($uri_segment);
        $classid = array_key_exists('classid', $parameter) ? intval($parameter['classid']) : 0;
        //没有classid时直接报错或跳转
        if (!$classid) {
            echo "<script>window.location.href='" . site_url('Classstaff/myclass') . "'</script>";
            exit ();
        }
        $search = array_key_exists('search', $parameter) ? urldecode($parameter['search']) : '';
        //安全过滤
        $search = $this->security->xss_clean($search);
        $page = max(intval($per_page), 1);
        $perpage = 10;//每页记录数
        $offset = ($page - 1) * $perpage;

        $where = array(
            'ClassID' => $classid,
            'search' => $search,//搜索字符串要转码
            'limit' => array('limit' => $perpage, 'offset' => $offset)
        );

        $output_data['student_list'] = $this->Class_model->get_student($where);
        $output_data['search'] = $search;
        $output_data['classid'] = $classid;
        $output_data['classname'] = $this->Class_model->get_class_name($classid);

        //分页
        $this->load->helper('util');
        $output_data['total_rows'] = $this->Class_model->count_class_students($where);;//获取总记录数
        $output_data['pages'] = get_pages(site_url('Classstaff/editclass'), $output_data['total_rows']);

        $page_count = ceil($output_data['total_rows']/10);

        $output_data['page_url'] = site_url('Classstaff/editclass') . '/classid/' . $classid . '/' . (!empty($search) ? 'search/' . $search . '/' : '');
        $output_data['page_count'] = $page_count;
        $output_data['page_pre']=$page;

        $this->load->view('teacher/editclass', $output_data);
    }

    /***
     * 编辑班级名称
     */
    public function edit_class_name()
    {
        $tmp = array('code' => '0000', 'msg' => 'success!', 'data' => []);
        do {
            $classcode = intval($this->input->post('classcode'));
            $classname = $this->input->post('classname', TRUE);
            $oldclassname = $this->input->post('OldClassName', TRUE);
            if ($classcode == 0) {
                $tmp['code'] = '0316';
                $tmp['msg'] = '参数错误!';
                $tmp['data'] = array();
                break;
            }
            if ($classname == $oldclassname) {
                break;//相同直接退出返回修改成功
            }
            $data = array('ClassName' => $classname);
            $where = array('ClassID' => $classcode);
            $this->load->model("Class_model");
            $result = $this->Class_model->update_class($where, $data);
            if ($result['code'] != '0000') {
                $tmp['code'] = $result['code'];
                $tmp['msg'] = $result['msg'];
                $tmp['data'] = array();
                break;
            }

        } while (FALSE);

        $this->interface_output->output_fomcat('js_Ajax', $tmp);

    }

    /**
     * 所有学员
     */
    public function allstudents()
    {
        //从url里获取参数,TODO数据安全过滤
        $parameter = $this->uri->uri_to_assoc(3);
        $uri_segment = (count($parameter) * 2) + 1;
        $per_page = $this->uri->segment($uri_segment) === NULL ? 1 : $this->uri->segment($uri_segment);
        $search = array_key_exists('search', $parameter) ? urldecode($parameter['search']) : '';
        $time = array_key_exists('time', $parameter) ? $parameter['time'] : '';
        $sort = array_key_exists('sort', $parameter) ? $parameter['sort'] : '';

        $search = $this->security->xss_clean($search);

        $page = max(intval($per_page), 1);
        $perpage = 10;//每页记录数
        $offset = ($page - 1) * $perpage;

        $where = array(
            'limit' => array('limit' => $perpage, 'offset' => $offset)
        );
        $pageurl = '';//页面url拼接
        if (!empty($search)) {
            $where['search'] = $search;//搜索字符串要转码
            $pageurl .= '/search/' . $search;
        }
        //按时间范围查询
        if (!empty($time)) {
            $time = str_replace('%20', ' ', $time);
            $where['CreateTime'] = array('starttime' => explode("_", $time)[0], 'endtime' => explode("_", $time)[1]);
            $pageurl .= '/time/' . $time;
        }
        //排序方式:CreateTime DESC
        if (!empty($sort)) {
            $where['sort'] = array('field' => explode("%20", $sort)[0], 'order' => explode("%20", $sort)[1]);
            $pageurl .= '/sort/' . $sort;
        }

        $data['students'] = $this->User_model->get_all_student($where);

        $data['search'] = $search;
        $data['time'] = isset($where['CreateTime']) ? $where['CreateTime'] : '';
        $data['sort'] = isset($where['sort']) ? $where['sort'] : '';

        //分页
        $this->load->helper('util');
        $data['total_rows'] = $this->User_model->get_count($where, 3);;//获取总记录数
        $data['pages'] = get_pages(site_url('Classstaff/allstudents'), $data['total_rows']);

        $data['page_url'] = site_url('Classstaff/allstudents') . $pageurl.'/';
        $data['page_count']=ceil($data['total_rows']/10);
        $data['page_pre']=$page;

        $this->load->view('teacher/allstudents', $data);
    }

    /**
     * 启用、禁用
     */
    public function en_disable()
    {
        do {
            $data = $this->input->post(NULL, TRUE);

            $result = $this->Class_model->is_lock($data);

        } while (FALSE);

        $this->interface_output->output_fomcat('js_Ajax', $result);
    }

    /***
     * 删除用户
     */
    public function deluser()
    {
        $tmp = array('code' => '0000', 'msg' => 'success!', 'data' => []);
        do {
            $usercode = $this->input->post('codes', TRUE);

            if (empty($usercode)) {
                $tmp = array('code' => '0316', 'msg' => '参数错误!', 'data' => []);
                break;
            }
            $user_data = json_decode($usercode);
            //是否单个删除
            if (is_int($user_data)) $user_data = array($user_data);
            $result = $this->Class_model->del_user($user_data);
            if ($result['code'] != '0000') {
                $tmp['code'] = $result['code'];
                $tmp['msg'] = $result['msg'];
                $tmp['data'] = array();
                break;
            }

        } while (FALSE);

        $this->interface_output->output_fomcat('js_Ajax', $tmp);
    }

    /**
     * 是否正在学习
     */
    public function isstudys()
    {
        do {
            $usercode = $this->input->post('codes', TRUE);

            if (empty($usercode)) {
                $tmp = array('code' => '0316', 'msg' => '参数错误!', 'data' => []);
                break;
            }
            $user_data = json_decode($usercode);
            //是否单个删除
            if (is_int($user_data)) $user_data = array($user_data);
            $tmp = $this->Class_model->is_studys(array('sids'=>$user_data,'TaskType !='=>2));
            
        } while (FALSE);

        $this->interface_output->output_fomcat('js_Ajax', $tmp);
       
    }
    
    public function isstudy()
    {
        do {
            $sid = $this->input->post('sid', TRUE);

            $tmp = $this->Class_model->is_study(array('StudentID'=>$sid,'TaskType !='=>2));

        } while (FALSE);

        $this->interface_output->output_fomcat('js_Ajax', $tmp);
    }

    /***
     * 获取用户详细信息
     */
    public function get_userinfo()
    {
        $tmp = array('code' => '0000', 'msg' => 'success!', 'data' => []);
        do {
            $usercode = intval($this->input->post('code', TRUE));
            if ($usercode == 0) {
                $tmp = array('code' => '0316', 'msg' => '参数错误!', 'data' => []);
                break;
            }
            $result = $this->User_model->get_userinfo($usercode);
            if (empty($result)) {
                $tmp = array('code' => '0316', 'msg' => '找不到用户信息!', 'data' => []);
                break;
            }
            $tmp['data'] = $result;
            //获取班级信息
            $res = $this->User_model->get_user_class($usercode);
            if ($res){
                $classname = array();
                foreach($res as $value){
                    array_push($classname,$value['ClassName']);
                }
                $tmp['data']['ClassName'] = implode(',',$classname);
            }else{
                $tmp['data']['ClassName'] = '无';
            }

        } while (FALSE);

        $this->interface_output->output_fomcat('js_Ajax', $tmp);

    }

    /***
     * 修改用户
     */
    public function edit_user()
    {
        $tmp = array('code' => '0000', 'msg' => 'success!', 'data' => []);
        do {
            $data = $this->input->post(NULL, TRUE);
            $this->load->library('Data_validate');//验证类引入
            //数据检查
            if (intval($data['UserID']) == 0) {
                $tmp = array('code' => '0320', 'msg' => '非法操作!', 'data' => []);
                break;
            }
            if (empty($data['UserName'])) {
                $tmp = array('code' => '0320', 'msg' => '姓名不能为空!', 'data' => []);
                break;
            }
            if (empty($data['UserSex'])) {
                $tmp = array('code' => '0320', 'msg' => '性别不能为空!', 'data' => []);
                break;
            }
            if (!$this->data_validate->is_name($data['UserName'])) {
                $tmp = array('code' => '0321', 'msg' => '姓名只能为中文,英文!', 'data' => []);
                break;
            }
            if (!in_array($data['UserSex'], array('男', '女'))) {
                $tmp = array('code' => '0322', 'msg' => '性别信息不正确!', 'data' => []);
                break;
            }
            //如果邮箱输入了，
            if (!empty($data['UserEmail']) && $this->data_validate->is_email($data['UserEmail']) == FALSE) {
                $tmp = array('code' => '0323', 'msg' => '邮箱格式不正确!', 'data' => []);
                break;
            }
            //如果电话输入了
            if (!empty($data['UserPhone']) && !$this->data_validate->is_mobile_or_tel($data['UserPhone'])) {
                $tmp = array('code' => '0324', 'msg' => '电话格式有误!', 'data' => []);
                break;
            }
            //密码长度
            if (!empty($data['Userpassword']) && !$this->data_validate->is_password($data['Userpassword'], 6, 16)) {
                $tmp = array('code' => '0341', 'msg' => '密码必须是6到16位的字符!', 'data' => []);
                break;
            }
            $info = array(
                'UserName' => $data['UserName'],
                'UserSex' => $data['UserSex'],
                'UserDepartment' => $data['UserDepartment'],
                'UserEmail' => isset($data['UserEmail']) ? $data['UserEmail'] : '',
                'UserPhone' => isset($data['UserPhone']) ? $data['UserPhone'] : '',
            );
            if (!empty($data['Userpassword'])) $info['UserPass'] = md5($data['Userpassword']);
            if (!empty($data['StuId'])) $info['StuId'] = $data['StuId'];

            $result = $this->User_model->update_user($info, array('UserID' => intval($data['UserID'])));
            if ($result['code'] != '0000') {
                $tmp = array('code' => $result['code'], 'msg' => $result['msg'], 'data' => []);
                break;
            }

        } while (FALSE);

        $this->interface_output->output_fomcat('js_Ajax', $tmp);

    }


    /**
     * 新建学员
     */
    public function addstudent()
    {
        if($data = $this->input->post(NULL, TRUE))
        {
            //加载接口输出类
            $this->load->library('Interface_output');
            $tmp = array('code'=> '0000', 'msg'=> 'success', 'data'=> []);
            //加载数据验证类
            $this->load->library('Data_validate');

            do{
                if(empty($data['UserName']))
                {
                    $tmp = array('code'=> '0370', 'msg'=> '姓名不能为空', 'data'=> []);
                    break;
                }

                if(empty($data['UserAccount']))
                {
                    $tmp = array('code'=> '0371', 'msg'=> '用户名不能为空', 'data'=> []);
                    break;
                }

                if(empty($data['UserPass']))
                {
                    $tmp = array('code'=> '0372', 'msg'=> '密码不能为空', 'data'=> []);
                    break;
                }

                if(empty($data['UserSex']))
                {
                    $tmp = array('code'=> '0373', 'msg'=> '性别不能为空', 'data'=> []);
                    break;
                }

                //如果学号输入了，
                if (!empty($data['StuId']) && $this->data_validate->is_num_word($data['StuId'],1,16) == FALSE) {
                    $tmp = array('code' => '0323', 'msg' => '学号由1-16位的字母数字组成!', 'data' => []);
                    break;
                }

                if(!$this->data_validate->is_name($data['UserName']))
                {
                    $tmp = array('code'=> '0374', 'msg'=> '姓名只能为中英文', 'data'=> []);
                    break;
                }

                if(!$this->data_validate->length($data['UserAccount'], 3, 3, 16))
                {
                    $tmp = array('code'=> '0375', 'msg'=> '用户名必须是3到16位的字符', 'data'=> []);
                    break;
                }

                if(!$this->data_validate->is_password($data['UserPass'], 6, 16))
                {
                    $tmp = array('code'=> '0376', 'msg'=> '密码必须是6到16位的字符', 'data'=> []);
                    break;
                }

                if(!empty($data['UserEmail']) && $this->data_validate->is_email($data['UserEmail']) == FALSE)
                {
                    $tmp = array('code'=> '0377', 'msg'=> '邮箱格式不正确', 'data'=> []);
                    break;
                }

                //如果电话输入了
                if (!empty($data['UserPhone']) && !$this->data_validate->is_mobile_or_tel($data['UserPhone'])) {
                    $tmp = array('code' => '0379', 'msg' => '电话格式有误!', 'data' => []);
                    break;
                }
                //账号是否重复检查
                $result = $this->User_model->check_user(array('UserAccount' => $data['UserAccount']));
                //p($result);die;
                if ($result['code'] != '0000') {
                    $tmp = array('code' => '0380', 'msg' => '用户名已经被占用!', 'data' => []);
                    break;
                }
                //p($data);die;
                $result = $this->User_model->add_user($data, 3);
                if (!$result) {
                    $tmp = array('code' => '0381', 'msg' => '用户创建失败!', 'data' => []);
                    break;
                }

            }while(FALSE);

            $this->interface_output->output_fomcat('js_Ajax', $tmp);
        }
        else
        {
            $this->load->view('teacher/addstudent');
        }
    }

    /***
     * 上传CSV文件
     */
    public function uploadcsv($type = 'student')
    {
        $key = $this->input->post("key");
        $key2 = $this->input->post("key2");
        //$filename = $this->input->post("fileName");//上传文件
        $filename = $key2 . $key .'.csv';
        $config['file_name'] = $filename;
        $config['upload_path'] = getcwd() . '/resources/files/csv/';
        $config['allowed_types'] = 'csv';
        $config['max_size'] = 0;


        $this->load->library('upload', $config);//非切片上传
        do{
            if (!$this->upload->do_upload('file')) {
                $tmp = array('code' => '0388', 'msg' => '文件上传失败！', 'data' => []);
                break;
            }
            //必须有这个返回
            $output_data = $type == 'student' ? $this->_resolve_csv($filename) : $this->_resolve_teacher_csv($filename);
            if (empty($output_data)){
                $tmp = array('code' => '0388', 'msg' => '解析错误，请下载指定模板文件导入!', 'data' => []);
                break;
            }
            $tmp = array('code' => '0000', 'msg' => '上传成功!', 'data' => array('filename' => $filename,
                'contents' => $output_data));

        }while(FALSE);

        //$res = $this->upload->uploadfile($_FILES['file'], array('filename' => $filename), $config['upload_path']);
        $this->interface_output->output_fomcat('js_Ajax', $tmp);

    }

    /***
     * 解析读出的CSV文件数据
     * @param $filename
     * @return array
     */
    private function _resolve_csv($filename)
    {
        //read csv
        $field = array('StuId', 'UserAccount', 'UserPass', 'UserName', 'UserSex', 'UserEmail', 'UserDepartment', 'UserPhone');
        $this->load->library('Get_csv');
        $data = $this->get_csv->set_file_path(getcwd() . '/resources/files/csv/' . $filename)->get_array($field);
        return $data;
    }

    /***
     * 解析读出的教员CSV文件数据
     * @param $filename
     * @return array
     */
    private function _resolve_teacher_csv($filename)
    {
        //read csv
        $field = array('UserAccount', 'UserPass', 'UserName', 'UserSex', 'UserEmail', 'UserDepartment', 'UserPhone');
        $this->load->library('Get_csv');
        $data = $this->get_csv->set_file_path(getcwd() . '/resources/files/csv/' . $filename)->get_array($field);
        return $data;
    }

    /***
     * 导入学员
     */
    public function import_student()
    {
        $filename = $this->input->post("filename", TRUE);
        $import_users = $this->input->post('UserAccount', TRUE);
        $tmp = array('code' => '0000', 'msg' => 'success!', 'data' => []);
        do {
            if (empty($filename)) {
                $tmp = array('code' => '0390', 'msg' => '请先上传文件!', 'data' => []);
                break;
            }
            if (strpos($filename, '../') !== FALSE) {
                $tmp = array('code' => '0391', 'msg' => '文件名非法!', 'data' => []);
                break;
            }
            if (empty($import_users)) {
                $tmp = array('code' => '0392', 'msg' => '请先选择要导入的用户!', 'data' => []);
                break;
            }
            //是否有重复账号
            $counts = array_count_values($import_users);
            $error_data = array();
            foreach ($counts as $key => $item) {
                if ($item > 1) $error_data[] = $key;
            }
            if (count($error_data) > 0) {
                $tmp = array('code' => '0393', 'msg' => '存在相同的用户名!', 'data' => $error_data);
                break;
            }
            //read csv
            $field = array('StuId','UserAccount', 'UserPass', 'UserName', 'UserSex', 'UserEmail', 'UserDepartment', 'UserPhone');
            $this->load->library('Get_csv');
            $data = $this->get_csv->set_file_path(getcwd() . '/resources/files/csv/' . $filename)->get_array($field);

            if (empty($data)) {
                $tmp = array('code' => '0394', 'msg' => '上传文件没有数据!', 'data' => []);
                break;
            }
            $user_data = array();//学员数组
            $data_error = array(array('学号','用户名','密码','姓名','性别','邮箱','工作单位','电话','错误信息'));
            foreach ($data as $k => $row) {
                $validate = $this->_validate_user($row);//验证用户
                //是否为所选用户
                if (in_array($row['UserAccount'], $import_users) && empty($validate)) {
                    $row['UserPass'] = md5($row['UserPass']);
                    $row['UserRole'] = 3;//角色
                    $row['CreateTime'] = time();
                    $user_data[] = $row;
                }
                //把出错的记录
                if (!empty($validate)){
                    $row['error'] = $validate['msg'];
                    $data_error[] = $row;
                }
            }
            if (count($data_error)>1){
                //生成CSV文件
                $this->load->library('Get_csv');
                $filename = 'error'.time().'.csv';
                $this->get_csv->set_file_path(getcwd() . '/resources/files/csv/' . $filename)->rewrite_csv($data_error);
                $tmp = array('code' => 'error', 'msg' => '有数据信息出错!', 'data' => $filename);
                break;
            }
            $result = $this->User_model->batch_add_user($user_data);
            if ($result['code'] != '0000'){
                $tmp = array('code' => $result['code'], 'msg' => $result['msg'], 'data' => []);
                break;
            }

        } while (FALSE);

        $this->interface_output->output_fomcat('js_Ajax', $tmp);


    }

    /***
     * 会员数据验证
     * @param $data
     * @return array
     */
    private function _validate_user($data)
    {
        $this->load->library('Data_validate');//验证类引入
        $tmp = array();
        do {
            if (empty($data['UserAccount'])) {
                $tmp = array('code' => '03091', 'msg' => '登录账户不能为空!', 'data' => []);
                break;
            }
            if (empty($data['UserName'])) {
                $tmp = array('code' => '0320', 'msg' => '姓名不能为空!', 'data' => []);
                break;
            }
            if (empty($data['UserPass'])) {
                $tmp = array('code' => '03092', 'msg' => '密码不能为空!', 'data' => []);
                break;
            }
            if (empty($data['UserSex'])) {
                $tmp = array('code' => '0320', 'msg' => '性别不能为空!', 'data' => []);
                break;
            }
            //如果学号输入了，
            if (!empty($data['StuId']) && !$this->data_validate->is_num_word($data['StuId'], 1, 16)) {
                $tmp = array('code' => '0323', 'msg' => '学号由1-16位的字母数字组成!', 'data' => []);
                break;
            }
            //用户名长度
            if (!$this->data_validate->is_username($data['UserAccount'], 6, 16, 'EN')) {
                $tmp = array('code' => '0342', 'msg' => '用户名由6-16位的字母数字下滑线组成!', 'data' => []);
                break;
            }
            if (!$this->data_validate->is_name($data['UserName'])) {
                $tmp = array('code' => '0321', 'msg' => '姓名由2-12位的中文字母组成', 'data' => []);
                break;
            }
            if (!in_array($data['UserSex'], array('男', '女'))) {
                $tmp = array('code' => '0322', 'msg' => '性别信息不正确!', 'data' => []);
                break;
            }
            //如果单位输入了
            if (!empty($data['UserDepartment']) && !$this->data_validate->is_department($data['UserDepartment'])) {
                $tmp = array('code' => '03242', 'msg' => '工作单位只能是中文字母!', 'data' => []);
                break;
            }
            //如果邮箱输入了，
            if (!empty($data['UserEmail']) && $this->data_validate->is_email($data['UserEmail']) == FALSE) {
                $tmp = array('code' => '0323', 'msg' => '邮箱格式不正确!', 'data' => []);
                break;
            }
            //如果电话输入了
            if (!empty($data['UserPhone']) && !$this->data_validate->is_mobile_or_tel($data['UserPhone'])) {
                $tmp = array('code' => '0324', 'msg' => '电话格式有误!', 'data' => []);
                break;
            }
            //账号是否已经存在
            $result = $this->User_model->check_user(array('UserAccount' => $data['UserAccount']));
            if ($result['code'] != '0000') {
                $tmp = array('code' => '0315', 'msg' => '该用户名已存在!', 'data' => []);
                break;
            }
        } while (FALSE);
        return $tmp;

    }

    /***
     * 所有学员
     */
    public function all_user()
    {
        $search = $this->input->post('keyword');
        $perpage = intval($this->input->post('percount'));//每页记录数
        $curpage = intval($this->input->post('page'));

        //安全过滤
        $search = $this->security->xss_clean($search);

        $page = max(intval($curpage), 1);
        $offset = ($page - 1) * $perpage;
        $where = array(
            'search' => $search,//搜索字符串要转码
            'limit' => array('limit' => $perpage, 'offset' => $offset)
        );

        $output_data['data'] = $this->User_model->get_all_user($where);
        $output_data['count'] = $this->User_model->get_count($where, 3);;//获取总记录数
        $output_data['pagecount'] = ceil($output_data['count']/$perpage);
        $output_data['currentpage'] = $page;

        echo json_encode($output_data);

    }

    /***
     * 解析csv文件学员导入文件
     */
    public function resolve_csv()
    {
        $filename = $this->input->get("filename", TRUE);
        $search = $this->input->post('keyword');
        $perpage = intval($this->input->post('percount'));//每页记录数
        $curpage = intval($this->input->post('page'));

        //安全过滤
        $search = $this->security->xss_clean($search);
        $page = max(intval($curpage), 1);
        $offset = ($page - 1) * $perpage;
        $tmp = array('code' => '0000', 'msg' => '解析成功!', 'data' => []);
        do{
            if (empty($filename))
            {
                $tmp = array('code' => '0388', 'msg' => '文件不能为空!', 'data' => []);
                break;
            }
            $field = array('StuId', 'UserAccount', 'UserPass', 'UserName', 'UserSex', 'UserEmail', 'UserDepartment', 'UserPhone');
            $this->load->library('Get_csv');
            $data = $this->get_csv->set_file_path(getcwd() . '/resources/files/csv/' . $filename)->get_array($field);
            if (empty($data)){
                $tmp = array('code' => '0388', 'msg' => '文件没有数据!', 'data' => []);
                break;
            }

            //如果是搜索
            if (!empty($search)){
                $search_data = [];
                foreach ($data as $k => $v) {
                    if (strpos($v['UserName'],$search) !== FALSE){
                        $search_data[] = $v;
                    }
                }
                $tmp['data'] = array_slice($search_data,$offset,$perpage);//分页
                $tmp['count'] = count($search_data);//获取总记录数
                $tmp['pagecount'] = ceil($tmp['count'] / $perpage);
                $tmp['currentpage'] = $page;
                break;
            }
            $tmp['data'] = array_slice($data,$offset,$perpage);
            $tmp['count'] = count($data);//获取总记录数
            $tmp['pagecount'] = ceil($tmp['count']/$perpage);
            $tmp['currentpage'] = $page;

        }while(FALSE);

        echo json_encode($tmp);

    }

    /***
     * 获取班级可添加的学员
     */
    public function all_user_add()
    {
        $search = $this->input->post('keyword');
        $classcode = intval($this->input->get('classcode'));
        $perpage = intval($this->input->post('percount'));//每页记录数
        $curpage = intval($this->input->post('page'));

        //安全过滤
        $search = $this->security->xss_clean($search);

        $page = max(intval($curpage), 1);
        $offset = ($page - 1) * $perpage;
        $where = array(
            'classcode' => $classcode,
            'search' => $search,//搜索字符串要转码
            'limit' => array('limit' => $perpage, 'offset' => $offset)
        );

        $output_data['data'] = $this->Class_model->get_all_user_add($where);
        $output_data['count'] = $this->Class_model->get_count_add($where, 3);;//获取总记录数
        $output_data['pagecount'] = ceil($output_data['count'] / $perpage);
        $output_data['currentpage'] = $page;
        echo json_encode($output_data);
    }

    /***
     * 先某个班添加用户
     */
    public function edit_class_user()
    {
        $tmp = array('code' => '0000', 'msg' => 'success!', 'data' => []);
        do {
            $classcode = intval($this->input->post('classcode'));
            $usercode = $this->input->post('usercode');
            if ($classcode == 0) {
                $tmp = array('code' => '0316', 'msg' => '班级信息错误!', 'data' => []);
                break;
            }
            if (empty($usercode) OR !is_array($usercode)) {
                $tmp = array('code' => '0317', 'msg' => '学员信息错误!', 'data' => []);
                break;
            }
            //选择学员或导入学员都要求传ID过来统一处理
            $student = array();
            foreach ($usercode as $v) {
                $student[] = array(
                    'UserID' => $v,
                    'ClassID' => $classcode
                );
            }

            $res = $this->Class_model->add_class_student($student);
            if ($res['code'] != '0000') {
                $tmp = array('code' => $res['code'], 'msg' => $res['msg'], 'data' => []);
                break;
            }

        } while (FALSE);

        $this->interface_output->output_fomcat('js_Ajax', $tmp);

    }











}