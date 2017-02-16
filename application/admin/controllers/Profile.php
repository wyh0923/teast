<?php

/**
 * Created by PhpStorm.
 * User: qirupeng
 * Date: 2016/8/3
 * Time: 14:46
 */

/***
 * Class Profile
 * 个人中心
 */
class Profile extends ECQ_Controller
{
    /**
     * 个人信息
     *
     */
    public function info()
    {
        $user_code = $this->session->userdata('UserID');//用户
        if ($data = $this->input->post(NULL, TRUE)) {
            $this->load->library('Interface_output');
            $tmp = array('code' => '0000', 'msg' => 'success!', 'data' => []);
            $this->load->library('Data_validate');//验证类引入
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
                    $tmp = array('code' => '0321', 'msg' => '姓名由2-12位的中文字母组成', 'data' => []);
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
                $info = array(
                    'UserName' => $data['UserName'],
                    'UserSex' => $data['UserSex'],
                    'UserEmail' => isset($data['UserEmail']) ? $data['UserEmail'] : '',
                    'UserPhone' => isset($data['UserPhone']) ? $data['UserPhone'] : '',
                );
                $this->load->model('User_model');

                $result = $this->User_model->update_user($info, array('UserID' => $user_code));
                if ($result['code'] != '0000') {
                    $tmp = array('code' => $result['code'], 'msg' => $result['msg'], 'data' => []);
                    break;
                }

            } while (FALSE);

            $this->interface_output->output_fomcat('js_Ajax', $tmp);
        } else {
            $output_data = array();
            $this->load->model('User_model');
            $output_data['member'] = $this->User_model->get_userinfo($user_code);

            $this->load->view('admin/profile_info', $output_data);

        }


    }

    /**
     * 修改密码
     *
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
                $result = $this->User_model->check_user(array('UserID' => $data['UserID'], 'UserPass' => md5($data['oldpass'])));

                if ($result['code'] != '0000') {
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
            $this->load->view('admin/profile_modify_password');
        }
    }

    /**
     * 系统日志
     *
     */
    public function systemlog()
    {

        $output_data = array();
        $parameter = $this->uri->uri_to_assoc(3);
        $uri_segment = (count($parameter) * 2) + 1;
        $per_page = $this->uri->segment($uri_segment) === NULL ? 1 : $this->uri->segment($uri_segment);
        $search = array_key_exists('search', $parameter) ? urldecode($parameter['search']) : '';//搜索字符串要转码
        //安全过滤
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

        $this->load->model("Log_model");
        $output_data['log_list'] = $this->Log_model->get_log($where);
        $output_data['search'] = $search;
        //分页

        $output_data['total_rows'] = $this->Log_model->get_count($where);//获取总记录数

        $output_data['page_url'] = site_url('Profile/systemlog') .  $pageurl.'/';
        $output_data['page_count'] = ceil($output_data['total_rows'] / $perpage);
        $output_data['page_pre'] = $page;

        //日志类型
        $this->load->library('Config_items');
        $output_data['log_type'] = Config_items::$log_type;

        $this->load->view('admin/profile_systemlog', $output_data);


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
        $this->load->library('Interface_output');
        $this->interface_output->output_fomcat('js_Ajax', $tmp);
    }

}