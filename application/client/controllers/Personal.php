<?php
/**
 * Created by PhpStorm.
 * User: kyx
 * Date: 2016/8/3
 * Time: 10:30
 */

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 学生端个人统计控制器
 *
 */
class Personal extends ECQ_Controller{

    /**
     * 个人统计页
     */
    public function statistic(){
        $this->load->model( "Task_model" );

        $userID = $this->session->userdata('UserID');
        $data['study'] = count($this->Task_model->get_task(array('StudentID'=>$userID,'TaskSourceType'=>1,'TaskType'=>2)));
        $data['exam'] = count($this->Task_model->get_task(array('StudentID'=>$userID,'TaskSourceType'=>2,'TaskType'=>2)));
        $totalscore = $this->Task_model->get_total_score(array('StudentID'=>$userID))[0]['totalscore'];
        $data['total_score'] = $totalscore ? $totalscore : 0;
        $data['section'] = $this->Task_model->get_task_section(array('StudentID'=>$userID,'TaskSourceType'=>1,'Finished'=>2));
        //体系得分
        $data['book_score'] = $this->Task_model->get_arc_score();
        //print_r($data);
        $this->load->view('student/statistic',$data);
    }

    /**
     * 实时更新个人统计
     */
    public function get_personal_ajax(){
        $this->load->model( "Task_model" );
        $this->load->library('Interface_output');

        $userID = $this->session->userdata('UserID');
        $output_data['data']['study'] = count($this->Task_model->get_task(array('StudentID'=>$userID,'TaskSourceType'=>1,'TaskType'=>2)));
        $output_data['data']['exam'] = count($this->Task_model->get_task(array('StudentID'=>$userID,'TaskSourceType'=>2,'TaskType'=>2)));
        $totalscore = $this->Task_model->get_total_score(array('StudentID'=>$userID))[0]['totalscore'];
        $output_data['data']['total_score'] = $totalscore ? $totalscore : 0;
        $output_data['data']['section'] = $this->Task_model->get_task_section(array('StudentID'=>$userID,'TaskSourceType'=>1,'Finished'=>2));

        $output_data['code'] = '0000';
        $output_data['msg'] = '统计';
        $this->interface_output->output_fomcat('js_Ajax', $output_data);
    }

    /**
     * 日志页
     */
    public function log(){
        $this->load->model( "Log_model" );
        $this->load->library ('Filter');

        $search = $this->input->get('search');
        $page = $this->input->get('per_page');
        $search = $search ? urldecode($search) : '';//搜索字符串要转码
        //安全过滤
        $search = $this->security->xss_clean($search);

        $UserID = $this->session->userdata('UserID');

        $page = max(intval($page), 1);
        $num = 10;//每页记录数
        $offset = ($page - 1) * $num;
        //分页
        $this->load->helper('util');
        $data['result'] = $this->Log_model->get_log(array('UserID'=>$UserID,'search'=>$search,'num'=>$num,'offset'=>$offset));
        //总数
        $result = $this->Log_model->get_log(array('UserID'=>$UserID,'search'=>$search));
        $data['total'] = count($result);

        //搜索
        $data['search'] = $search;
        //分页
        $per_page =  $this->filter->generateBaseUrl("per_page");
        $data['page_url'] = $per_page.'per_page=';;
        $data['page_count'] = ceil($data['total']/10);
        $data['page_pre'] = $page;

        //日志类型
        $this->load->library('Config_items');
        $data['log_type'] = Config_items::$log_type;
        
        $this->load->view('student/log',$data);
    }

    /**
     * 个人信息页
     */
    public function information(){
        $this->load->model( "User_model" );
        $UserID = $this->session->userdata('UserID');
        $data['data'] = $this->User_model->get_user(array('UserID'=>$UserID));

        $this->load->view('student/information',$data);
    }

    /**
     * 修改个人信息
     */
    public function updateinfor(){
        $this->load->model( "User_model" );
        $this->load->library('Data_validate');//验证类引入
        $this->load->library('Interface_output');

        $data = $this->input->post();
        $output_data['data'] = array();
        do {
            if($data['UserName'] == ''){
                $output_data['code'] = '0401';
                $output_data['msg'] = '姓名不能为空';
                break;
            }
            if(!$this->data_validate->is_name($data['UserName'])){
                $output_data['code'] = '0402';
                $output_data['msg'] = '姓名由2-12位的中文字母组成';
                break;
            }
            if (!($data['UserSex'] == '男' || $data['UserSex'] == '女')){
                $output_data['code'] = '0403';
                $output_data['msg'] = '性别选择有误';
                break;
            }

            if(isset($data['UserEmail']) && $data['UserEmail'] != '' && !$this->data_validate->is_email($data['UserEmail'])){

                $output_data['code'] = '0404';
                $output_data['msg'] = '邮箱格式不正确!';
                break;
            }
            if (isset($data['UserPhone']) && $data['UserPhone'] != '' && ! ($this->data_validate->is_mobile($data['UserPhone']) == FALSE XOR $this->data_validate->is_tel($data['UserPhone']) == FALSE)) {
                $output_data['code'] = '0405';
                $output_data['msg'] = '手机或电话号码格式不正确!';
                break;
            }
            $where['UserID'] = $this->session->userdata('UserID');
            $output_data = $this->User_model->edit_user($where,$data);

        } while (FALSE);

        $this->interface_output->output_fomcat('js_Ajax', $output_data);
    }

    /**
     * 修改密码页
     */
    public function changepassword(){
        $this->load->view('student/change_password');
    }

    /**
     * 修改密码
     */
    public function updatepassword(){
        $this->load->model( "User_model" );
        $this->load->library('Data_validate');//验证类引入
        $this->load->library('Interface_output');

        $data = $this->input->post();
        $output_data['data'] = array();

        do {
            if($data['nowpass'] == ''){
                $output_data['code'] = '0410';
                $output_data['msg'] = '当前密码不能为空';
                break;
            }
            if(isset($data['newpassone']) && $data['newpassone'] ==''){
                $output_data['code'] = '0412';
                $output_data['msg'] = '新密码不能为空';
                break;
            }
            if($data['nowpass'] == $data['newpassone']){
                $output_data['code'] = '0413';
                $output_data['msg'] = '新密码与当前密码不能相同!';
                break;
            }
            if(!$this->data_validate->is_password($data['newpassone'], 6, 16)){
                $output_data['code'] = '0414';
                $output_data['msg'] = '新密码必须是6到15位的字符!';
                break;
            }
            if($data['newpasstwo'] == ''){
                $output_data['code'] = '0415';
                $output_data['msg'] = '确认密码不能为空';
                break;
            }
            if($data['newpassone'] != $data['newpasstwo']){
                $output_data['code'] = '0416';
                $output_data['msg'] = '两次输入的密码不一致!请重新输入!';
                break;
            }
            //输入密码与数据库密码匹配
            $where['UserID'] = $this->session->userdata('UserID');
            $result = $this->User_model->get_user($where);
            if($result[0]['UserPass'] != md5($data['nowpass'])){
                $output_data['code'] = '0411';
                $output_data['msg'] = '当前密码输入错误,请重新输入!';
                break;
            }

            $output_data = $this->User_model->edit_user($where,array('UserPass'=>md5($data['newpassone'])));

        } while (FALSE);

        $this->interface_output->output_fomcat('js_Ajax', $output_data);

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
        $config['allowed_types'] = 'png|jpg|gif|jpeg';
        $config['max_size'] = 1024;

        $this->load->library('upload', $config);
        if (!$this->upload->do_upload('file')) {
            $tmp = array('code' => '0388', 'msg' => '上传失败', 'data' => []);
        } else {
            //必须有这个返回
            $tmp = array('code' => '0000', 'msg' => '上传成功!', 'data' => array('filename' => $config['upload_path'].$filename));
            //更新用户信息
            $UserID = $this->session->userdata('UserID');//用户
            $this->load->model('User_model');
            $this->User_model->edit_user(array('UserID' => $UserID), array('UserIcon' => $filename));
        }
        $this->load->library('Interface_output');
        $this->interface_output->output_fomcat('js_Ajax', $tmp);
    }
    
}
