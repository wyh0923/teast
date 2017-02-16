<?php

/**
 * Created by PhpStorm.
 * User: qirupeng
 * Date: 2016/8/1
 * Time: 14:45
 * 派生自CI控制器类
 */
class ECQ_Controller extends CI_Controller
{
    /**
     * 导航菜单
     * @var array
     */
    public $nav = array();
    /***
     * 页面title
     * @var string
     */
    public $title = '';
    /***
     * 会员信息
     * @var array
     */
    public $userinfo = array();
    /***
     * 默认头像
     * @var string
     */
    public $default_icon = '';

    public function __construct()
    {
        parent::__construct();
        $this->check_login();
        $this->init_nav();
    }

    /**
     * 初始化导航菜单
     */
    public function init_nav()
    {
        //获取登录角色ID
        $role_id = $this->session->userdata('UserRole');//用户角色
        $this->load->model('Menu_model');
        $this->menus = $this->Menu_model->get_menu($role_id);//所有菜单列表
        $this->nav['nav'] = get_nav($this->menus);//顶部导航
        $this->nav['nav_id'] = current_nav_id($this->nav['nav'], $this->router->class . '/');
        $this->check_role($this->nav['nav_id']);
        $this->nav['left_nav'] = get_left_nav($this->menus, $this->nav['nav_id']);//左侧导航
        $this->nav['left_nav_id'] = current_nav_id($this->nav['left_nav'], $this->router->class . '/' . $this->router->method);

        $this->title = current_title($this->nav['nav'], $this->router->class . '/') . '-' . current_title($this->nav['left_nav'], $this->router->class . '/' . $this->router->method);
        //登录后的用户信息
        $this->load->model('User_model');
        $this->userinfo = $this->User_model->get_userinfo($this->session->userdata('UserID'));
        if (!$this->userinfo || $this->userinfo['IsLocked']) {
            $this->session->unset_userdata('Account');
            $this->session->unset_userdata('UserID');
            $this->session->unset_userdata('UserName');
            delete_cookie('username');
            delete_cookie('password');
            redirect(base_url());
        }
        //头像设置
        switch ($this->userinfo['UserRole']) {
            case 1:
                $this->default_icon = 'adminicon.jpg';
                break;
            case 2:
                $this->default_icon = 'teachericon.jpg';
                break;
            case 3:
                $this->default_icon = 'studenticon.jpg';
                break;
            default:
                $this->default_icon = 'studenticon.jpg';

        }

    }

    /***
     * 检测用户是否登录，未登录跳转到登录页
     */
    public function check_login()
    {
        //非登录页
        if (!in_array($this->router->class, array('Login')) && $this->session->userdata('UserID') == NULL) {
            redirect(base_url());
        }
    }

    /***
     * 用户权限检测
     * @param $nav_id
     */
    private function check_role($nav_id)
    {
        if (!$nav_id) {
            redirect(base_url());
        }

    }

}