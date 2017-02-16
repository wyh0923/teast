<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: qirupeng
 * Date: 2016/8/15
 * Time: 17:03
 */

/***
 * 登录
 * Class Login
 */
class Login extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('User_model');
        $this->load->helper('cookie');
    }

    /***
     * 登录页
     */
    public function index()
    {
        //如果已经登录就跳转到各自主页
        if ($this->session->userdata('UserID') != NULL && $this->session->userdata('UserRole') != NULL){
            $usertype = $this->session->userdata('UserRole');
            if ($usertype == '1') {
                redirect(base_url().'admin.php/System/info');
            } else if ($usertype == '2') {
                redirect(base_url().'index.php/Education/edubook');
            } else {
                redirect(base_url().'index.php/Study/listunderway');
            }
        }
        //是否自动登录
        $username = get_cookie('username');
        if (!empty($username)) {
            $password = get_cookie('password');
            $res = $this->User_model->login($username, $password);
            if (empty($res)) { //如果结果集为空则登录失败
                $this->load->view('public/login');
            } else {
                $this->session->set_userdata(array(
                    'Account' => $res['UserAccount'],
                    'UserID' => $res['UserID'],
                    'UserName' => $res['UserName'],
                    'UserRole' => $res['UserRole'],
                    'UserPoint' => $res['UserPoint'],
                    'UserIcon' => $res['UserIcon'],
                    'UserTheme' => $res['UserTheme'],
                    'IsLocked' => $res['IsLocked'],
                ));
                //更新最后登录时间
                $this->User_model->update_user(array('LastLoginTime' => time()), array('UserID' => $res['UserID']));
                //判断登录者身份
                $usertype = $res['UserRole'];
                if ($usertype == '1') {
                    redirect(base_url().'admin.php/System/info');
                } else if ($usertype == '2') {
                    redirect(base_url().'index.php/Education/edubook');
                } else {
                    redirect(base_url().'index.php/Study/listunderway');
                }
            }
        } else {
            $this->load->view('public/login');
        }

    }

    /***
     * 登录提交
     */
    public function login()
    {

        $output_data = array();
        $username = $this->security->xss_clean(htmlspecialchars($this->input->post('username')));
        $password = $this->security->xss_clean(htmlspecialchars($this->input->post('password')));
        $is_auto = $this->security->xss_clean(htmlspecialchars($this->input->post('AutoLogin')));
        $usertype = FALSE;
        do {
            //验证
            if (empty ($username) OR empty ($password)) {
                $output_data ['msg'] = "用户名密码不能为空";
                break;
            }
            //登录
            $res = $this->User_model->login($username, md5($password));
            if (!$res) {
                $output_data['msg'] = '登录账号或登录密码不正确！';
                break;
            }
            if ($res['IsLocked'] == '1') {
                $output_data['msg'] = '您已经被限制登录，请联系管理员！';
                break;
            }
            $this->session->set_userdata(array(
                'Account' => $res['UserAccount'],
                'UserID' => $res['UserID'],
                'UserName' => $res['UserName'],
                'UserRole' => $res['UserRole'],
                'UserPoint' => $res['UserPoint'],
                'UserIcon' => $res['UserIcon'],
                'UserTheme' => $res['UserTheme'],
                'IsLocked' => $res['IsLocked'],
            ));
            //更新最后登录时间
            $this->User_model->update_user(array('LastLoginTime' => time()), array('UserID' => $res['UserID']));
            //是否自动登录
            if ($is_auto == '1') {
                set_cookie('username', $username, 3600);
                set_cookie('password', md5($password), 3600);
            }
            // 判断用户类型
            $usertype = $res['UserRole'];
        } while (FALSE);


        if ($usertype) {
            if ($usertype == '1') {
                redirect(base_url().'admin.php/System/info');
            } else if ($usertype == '2') {
                redirect(base_url().'index.php/Education/edubook');
            } else {
                redirect(base_url().'index.php/Study/listunderway');
            }
        } else {
            $this->load->view('public/login', $output_data);
        }


    }

    /***
     * 登出
     */
    public function logout()
    {
        //登出日志
        $this->load->library('Log_user');
        $data = array(
            'UserID' => $this->session->userdata('UserID'),
            'LogTaskName' => '登出',
            'LogContent' => '登出系统',
            'LogTypeID' => 5,
            'LogResult' => $_SERVER['HTTP_REFERER']
        );
        $this->log_user->add_log($data);

        $this->session->unset_userdata ( 'Account' );
        $this->session->unset_userdata ( 'UserID' );
        $this->session->unset_userdata ( 'UserName' );
        delete_cookie('username');
        delete_cookie('password');

        redirect(base_url());
    }

}