<?php
/**
 * Created by PhpStorm.
 * User: liuqi
 * Date: 2016/8/22
 * Time: 9:14
 */


/**
 * Class TeacherInfo
 * 教学统计中心
 */
class Teacount extends ECQ_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('Interface_output');
        $this->load->model('User_model');
        $this->load->model('Teacount_model');
        $this->load->library('Data_validate');
        $this->uid = $this->session->userdata('UserID');
        $this->author = $this->session->userdata('Account');
        $this->name = $this->session->userdata('UserName');

        $this->load->library('Paginationajax');
    }

    /**
     * 教学统计
     */
    public function personalstatistic()
    {
        //学习任务
        $data['study_num'] = count($this->Teacount_model->get_task_num(array('TeacherID'=>$this->uid, 'TaskSourceType'=>1)));

        //考试任务
        $data['exam_num'] = count($this->Teacount_model->get_task_num(array('TeacherID'=>$this->uid, 'TaskSourceType'=>2)));

        //班级管理
        $data['class_num'] = $this->Teacount_model->get_class_num(array('TeacherID'=>$this->uid));

        //创建课程
        $data['course_num'] = $this->Teacount_model->get_course_num(array('PackageParent'=>0, 'PackageAuthor'=>$this->name));

        //创建试卷
        $data['paper_num'] = $this->Teacount_model->get_paper_num(array('TeacherID'=>$this->uid));

        //创建项目
        $data['item_num'] = $this->Teacount_model->get_item_num(array('QuestionAuthor'=>$this->name));
        //$this->Teacount_model->test();

        $this->load->view('teacher/personal_count', $data);
    }

    /**
     *班级
     */
    public function class_by_teacher(){
        $page			= (int)$this->input->get_post("p");
        $size			= (int)$this->input->get_post("s");
        $search			= $this->input->get_post("se");
        $teacherCode	= $this->uid;
        if( empty($page) || !is_int($page) ){
            $page = 1;
        }
        if( empty($size) || !is_int($size) ){
            $size = 10;
        }
        if( empty($search) || $search == NULL ){
            $search = "";
        }

        $result = $this->Teacount_model->get_class_by_teacher($teacherCode,$page,$size,$search);
        
        $obj = new stdClass();
        $obj->Page	= $page;
        $obj->Size	= $size;
        $obj->Count	= $result['count'];
        $obj->PageCount = ceil($obj->Count/$size);
        $obj->Result = $result['rows'];
        $obj->Search = $search;
        echo json_encode($obj);
    }

    /**
     * 方案积分
     */
    public function class_course_sum_rose()
    {
        $classid = $this->input->post("code");

        $result = $this->Teacount_model->class_sys_score($this->uid, $this->author, $classid);

        $data['sonnamelist'] = $result;
        
        $this->load->view("teacher/ajaxpagrosecharttable", $data);
    }

    /**
     * 统计前10
     */
    public function class_sum_topten()
    {
        $timeType		= (int)$this->input->get_post("t");// 按日1/ 月2
        $cateType		= (int)$this->input->get_post("su");// 学生1/ 班级2
        $num			= (int)$this->input->get_post("num");// 月3--24/ 天7-30

        if( $cateType==1 ){
            //学生前10

            //$json_string = $this->Teacount_model->studentTopTen($this->uid,$num,$timeType);
            $json_string = $this->Teacount_model->get_studentten($this->uid,$num,$timeType);
        } else {

            //班级前10
            //$json_string = $this->Teacount_model->classTopTen($this->uid,$num,$timeType);
            $json_string = $this->Teacount_model->get_classten($this->uid,$num,$timeType);
        }
        echo $json_string;
    }

    /**
     * ajax 分页  学习任务进度统计
     */
    public function studytaskpageajax()
    {
        $this->load->library('ResPacket');

        //分页
        $UserCode = $this->uid;
        $total=$this->Teacount_model->get_all_send_task_num($UserCode,'',1,'','','','','')->msg;

        try {
            //设置config
            //$title = isset($infos['UserName']) ? 'searchcnt='.$infos['UserName'] : '';
            $config['base_url'] = site_url('Teacount/studytaskpageajax?'.'');
            $config['total_rows'] = $total;
            $config['enable_query_strings'] = FALSE;
            $config['use_page_numbers'] = TRUE;
            $config['page_query_string'] = TRUE;
            //  $config[anchor_class] = 'class="ajax_fpage"';
            //设置每页显示条数
            $config['per_page'] = 3;
            $offset = $this->input->get('per_page') != '' ? $this->input->get('per_page') : 1;
            $offset = ($offset - 1) * $config['per_page'];
            //装载分页类

            $this->paginationajax->initialize($config);
            $data['linksa'] = $this->paginationajax->create_links();

            $num = $config['per_page'];
            //$result = $this->UserModel->get_all_usersname($offset,$num,$where,$infos);
            $result = $this->Teacount_model->get_all_send_task_ajax($UserCode,'',1,'',$offset,$config['per_page'],'','')->msg;
            $data['taskinfo']=$result;

            $this->load->view("teacher/studytaskpagination", $data);
        } catch (Exception $e) {
        }


    }

    /**
     * ajax 分页  考试任务进度统计
     */
    public function ajaxpagExamTask()
    {
        $UserCode = $this->uid;
        // $total= count($this->TaskModel->get_all_send_examtask($UserCode,'',2,'','','','','')->msg);
        $total=$this->Teacount_model->get_all_send_task_num($UserCode,'',2,'','','','','')->msg;
        try {
            $config['base_url'] = site_url('Teacount/ajaxpagExamTask?'.'');
            $config['total_rows'] = $total;
            $config['enable_query_strings'] = FALSE;
            $config['use_page_numbers'] = TRUE;
            $config['page_query_string'] = TRUE;
            $config['per_page'] = 3;
            $offset = $this->input->get('per_page') != '' ? $this->input->get('per_page') : 1;
            $offset = ($offset - 1) * $config['per_page'];
            //装载分页类

            $this->paginationajax->initialize($config);
            $data['linksa'] = $this->paginationajax->create_links();

            $num = $config['per_page'];
            //$result = $this->UserModel->get_all_usersname($offset,$num,$where,$infos);
            $result = $this->Teacount_model->get_all_send_task_ajax($UserCode,'',2,'',$offset,$config['per_page'],'','')->msg;
            $data['examtaskinfo']=$result;

            // var_dump($result);die;

            $this->load->view("teacher/ajaxpagExamTask", $data);
        } catch (Exception $e) {
        }


    }

    /**
     * ajax 分页 课程
     */
    public function ajaxpagbookstatistics()
    {
        try {
            $res = $this->Teacount_model->topcount_package($this->name);
            //$data['topPackage'] =  $res;
            $arr_y = array();
            $arr_x = array();
            foreach ($res as $val){
                array_push($arr_y,$val['PackageName']);
                array_push($arr_x,(int)$val['countnum']);
            }

            $datares = array('arry'=>$arr_y,'arrx'=>$arr_x);
            echo  json_encode($datares);
        } catch (Exception $e) {
        }
    }

    /**
     * ajax 分页 考试统计
     */
    public function ajaxpagexamstatistics()
    {
        try {

            $res=  $this->Teacount_model->topcount_exam($this->uid)->msg;
            $arr_y = array();
            $arr_x = array();
            foreach ($res as $val){
                array_push($arr_y,$val['ExamName']);
                array_push($arr_x,(int)$val['countnum']);
            }

            $datares = array('arry'=>$arr_y,'arrx'=>$arr_x);
            echo  json_encode($datares);
        } catch (Exception $e) {
        }
    }

    /**
     * 个人信息
     */
    public function personaldetails()
    {
        if ($data = $this->input->post(NULL, TRUE))
        {
            $tmp = array('code' => '0000', 'msg' => 'success!', 'data' => []);

            do {
                //数据检查
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
                if (!empty($data['UserPhone']) && !($this->data_validate->is_mobile($data['UserPhone']) == FALSE XOR $this->data_validate->is_tel($data['UserPhone']) == FALSE)) {
                    $tmp = array('code' => '0324', 'msg' => '电话格式有误!', 'data' => []);
                    break;
                }
                $info = array(
                    'UserName' => $data['UserName'],
                    'UserSex' => $data['UserSex'],
                    'UserEmail' => isset($data['UserEmail']) ? $data['UserEmail'] : '',
                    'UserPhone' => isset($data['UserPhone']) ? $data['UserPhone'] : '',
                );

                $result = $this->User_model->update_user($info, array('UserID' => $this->uid));
                if ($result['code'] != '0000') {
                    $tmp = array('code' => $result['code'], 'msg' => $result['msg'], 'data' => []);
                    break;
                }

            } while (FALSE);

            $this->interface_output->output_fomcat('js_Ajax', $tmp);
        }
        else
        {
            $output_data = array();

            $output_data['member'] = $this->User_model->get_info($this->uid);

            $this->load->view('teacher/userinfo', $output_data);
        }
    }

    /***
     * 用户头像上传
     */
    public function avatar()
    {
        $filename = $this->input->post("filename");//上传文件
        $filetype = strtolower(strrchr($filename, '.'));
        $filename = 'PIC_' . time() . $filetype;
        $config['file_name'] = $filename;
        $config['upload_path'] = getcwd() . '/resources/files/picture/';
        $config['allowed_types'] = 'gif|png|jpg|jpeg';
        $config['max_size'] = 1024;

        $this->load->library('upload', $config);
        if (!$this->upload->do_upload('file')) {
            $tmp = array('code' => '0388', 'msg' => '上传错误!', 'data' => []);
        } else {
            //必须有这个返回
            $tmp = array('code' => '0000', 'msg' => '上传成功!', 'data' => array('filename' => $config['upload_path'] . $filename));
            //更新用户信息
            $user_code = $this->session->userdata('UserID');//用户
            $this->load->model('User_model');
            $this->User_model->update_user(array('UserIcon' => $filename), array('UserID' => $user_code));
        }
        $this->interface_output->output_fomcat('js_Ajax', $tmp);
    }

    /**
     * 修改密码
     */
    public function modifypassword()
    {
        if ($data = $this->input->post(NULL, TRUE)) {
            $this->load->library('Interface_output');
            $tmp = array('code' => '0000', 'msg' => 'success!', 'data' => []);
            $this->load->library('Data_validate');//验证类引入
            do {
                //数据检查
                if (empty($data['oldpass'])) {
                    $tmp = array('code' => '0306', 'msg' => '原密码不能为空!', 'data' => []);
                    break;
                }
                if (empty($data['newpass'])) {
                    $tmp = array('code' => '0306', 'msg' => '新密码不能为空!', 'data' => []);
                    break;
                }
                //密码长度
                if (!$this->data_validate->is_password($data['newpass'], 6, 16)) {
                    $tmp = array('code' => '0341', 'msg' => '密码必须是6到16位的字符!', 'data' => []);
                    break;
                }
                $data['UserID'] = $this->session->userdata('UserID');
                $this->load->model('User_model');
                $info = array('UserID' => $data['UserID'], 'UserPass' => md5($data['oldpass']));

                $result = $this->User_model->check_user($info);

                if ($result['code'] == '0000') {
                    $tmp = array('code' => '0302', 'msg' => '用户原密码不正确!', 'data' => []);
                    break;
                }
                $result = $this->User_model->update_user(array('UserPass' => md5($data['newpass'])), array('UserID' => $data['UserID']));
                if ($result['code'] != '0000') {
                    $tmp = array('code' => $result['code'], 'msg' => $result['msg'], 'data' => []);
                    break;
                }

            } while (FALSE);

            $this->interface_output->output_fomcat('js_Ajax', $tmp);

        } else {
            $this->load->view('teacher/password');
        }
    }













}