<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: WKF
 * Date: 16-7-25
 * Time: 11:03
 */

/**
 * Class Data_Exchange
 * @package Core
 */
class Data_exchange
{
    private $URL;

    private $_header;

    private $_message = array();

    private $iv = "Ecq@12!Byad`^#.1"; /* 必须16位哦 */

    private $aes_key = 'ecq@13!Back`^#.1';

    private $api_config = array(

        // 启动/开始/挂起/恢复/重启/暂停场景、场景快照操作
        'scene_operate' => array(
            'method' => 'GET',
            'sign_type' => 1,
            'format' => 'json',
            'server' => '/api/v1.0/scene_operate/'
        ),

        //添加节点
        'add_node' => array(
            'method' => 'POST',
            'sign_type' => 1,
            'format' => 'json',
            'server' => '/api/v1.0/node'
        ),
        //构建场景
        'create_scenarios' => array(
            'method' => 'POST',
            'sign_type' => 1,
            'format' => 'json',
            'server' => '/api/v1.0/scene_xml'
        ),
        //修改场景
        'modify_scenarios' => array(
            'method' => 'PUT',
            'sign_type' => 1,
            'format' => 'json',
            'server' => '/api/v1.0/scene_xml'
        ),
        //删除场景
        'delete_scenarios' => array(
            'method' => 'DELETE',
            'sign_type' => 1,
            'format' => 'json',
            'server' => '/api/v1.0/scene'
        ),
        //获取主节点信息
        'get_node' => array(
            'method' => 'GET',
            'sign_type' => 1,
            'format' => 'json',
            'server' => '/api/v1.0/node/0?host_type=3'
        ),
        //获取靶机信息
        'get_sub_node' => array(
            'method' => 'GET',
            'sign_type' => 1,
            'format' => 'json',
            'server' => '/api/v1.0/node/0'
        ),
        //配置IP信息
        'modify_ip' => array(
            'method' => 'PUT',
            'sign_type' => 1,
            'format' => 'json',
            'server' => '/api/v1.0/node/'
        ),
        //获取靶机配置IP进度
        'task_progress' => array(
            'method' => 'GET',
            'sign_type' => 1,
            'format' => 'json',
            'server' => '/api/v1.0/task_progress/'
        ),
        //备份数据
        'backup' => array(
            'method' => 'POST',
            'sign_type' => 0,
            'format' => 'json',
            'server' => '/backup_mysql/'
        ),
        //恢复出厂设置
        'recover' => array(
            'method' => 'POST',
            'sign_type' => 0,
            'format' => 'json',
            'server' => '/restore_mysql/'
        ),
        //恢复出厂设置php程序
        'restore_php' => array(
            'method' => 'POST',
            'sign_type' => 0,
            'format' => 'json',
            'server' => '/restore_php_file'
        ),
        //虚拟机管理
        'manage_vm' => array(
            'method' => 'POST',
            'sign_type' => 1,
            'format' => 'json',
            'server' => '/api/v1.0/vm_ins/'
        ),
        //升级日志
        'upgrade_log' => array(
            'method' => 'GET',
            'sign_type' => 0,
            'format' => 'json',
            'server' => '/version_control/'
        ),
        //系统升级
        'upgrade' => array(
            'method' => 'POST',
            'sign_type' => 0,
            'format' => 'json',
            'server' => '/override_php_file/'
        ),
        //课件实验升级
        'course_upgrade' => array(
            'method' => 'POST',
            'sign_type' => 0,
            'format' => 'json',
            'server' => '/import_xml/'
        ),
        //课件实验升级进度查询
        'upgrade_progress' => array(
            'method' => 'GET',
            'sign_type' => 1,
            'format' => 'json',
            'server' => '/import_schedule'
        ),
        //cpu使用率
        'cpu_use' => array(
            'method' => 'GET',
            'sign_type' => 1,
            'format' => 'json',
            'server' => '/api/v1.0/host_info/cpu_use/'
        ),
        //重启、关机节点
        'node_operate' => array(
            'method' => 'POST',
            'sign_type' => 1,
            'format' => 'json',
            'server' => '/api/v1.0/host_info/'
        ),
        //删除节点
        'delete_node' => array(
            'method' => 'DELETE',
            'sign_type' => 1,
            'format' => 'json',
            'server' => '/api/v1.0/node/'
        ),
        //节点资源统计
        'host_resource' => array(
            'method' => 'GET',
            'sign_type' => 1,
            'format' => 'json',
            'server' => '/api/v1.0/host_resource'
        ),
        //节点资源统计
        'vm_list' => array(
            'method' => 'GET',
            'sign_type' => 1,
            'format' => 'json',
            'server' => '/api/v1.0/vm_ins/0'
        ),
        //获取视频地址
        'get_video_url' => array(
            'method' => 'GET',
            'sign_type' => 1,
            'format' => 'json',
            'server' => '/api/v1.0/function_node'
        ),
        //获取ctf地址
        'get_ctf_url' => array(
            'method' => 'GET',
            'sign_type' => 1,
            'format' => 'json',
            'server' => '/api/v1.0/function_node'
        ),
        //创建场景
        'create_scene' => array(
            'method' => 'POST',
            'sign_type' => 1,
            'format' => 'json',
            'server' => '/api/v1.0/scene/'
        ),
        //删除场景
        'del_scene' => array(
            'method' => 'DELETE',
            'sign_type' => 1,
            'format' => 'json',
            'server' => '/api/v1.0/scene_operate/'
        ),
        //查看场景下发进度
        'get_scene_progress' => array(
            'method' => 'GET',
            'sign_type' => 1,
            'format' => 'json',
            'server' => '/api/v1.0/task_progress/'
        ),
        //判断场景是否存在
        'judge_scene' => array(
            'method' => 'GET',
            'sign_type' => 1,
            'format' => 'json',
            'server' => '/api/v1.0/host_check_oper/scene_ins/0/'
        ),
        //进入场景
        'enter_scene' => array(
            'method' => 'GET',
            'sign_type' => 1,
            'format' => 'json',
            'server' => '/api/v1.0/scene_manipulator/'
        ),
        //检查场景是否存在
        'check_scene' => array(
            'method' => 'GET',
            'sign_type' => 1,
            'format' => 'json',
            'server' => '/api/v1.0/host_check_oper/scene_ins/'
        ),
        //删除场景计划任务
        'del_task_scene' => array(
            'method' => 'DELETE',
            'sign_type' => 1,
            'format' => 'json',
            'server' => '/api/v1.0/scheduled_tasks/'
        ),
        //下发场景计划任务
        'create_task_scene' => array(
            'method' => 'POST',
            'sign_type' => 1,
            'format' => 'json',
            'server' => '/api/v1.0/scheduled_tasks'
        ),
        //获取场景列表
        'scene_list' => array(
            'method' => 'GET',
            'sign_type' => 1,
            'format' => 'json',
            'server' => '/api/v1.0/node_info/scene_tpl/0'
        ),
        //获取场景信息
        'scene_info' => array(
            'method' => 'GET',
            'sign_type' => 1,
            'format' => 'json',
            'server' => '/api/v1.0/node_info/scene_tpl/0'
        ),
        //删除场景模板
        'del_scene_tpl' => array(
            'method' => 'DELETE',
            'sign_type' => 1,
            'format' => 'json',
            'server' => '/api/v1.0/scene/'
        ),
        //获取虚拟机模板
        'get_vm_list' => array(
            'method' => 'GET',
            'sign_type' => 1,
            'format' => 'json',
            'server' => '/api/v1.0/vm_tpl/0'
        ),
        //获取操作系统类别
        'os_type' => array(
            'method' => 'GET',
            'sign_type' => 1,
            'format' => 'json',
            'server' => '/api/v1.0/os_type/0'
        ),
        //获取场景名是否重复
        'check_scene_name' => array(
            'method' => 'GET',
            'sign_type' => 1,
            'format' => 'json',
            'server' => '/api/v1.0/node_info/scene_tpl/0'
        ),
        //获取虚拟机模板信息
        'get_vm_info' => array(
            'method' => 'GET',
            'sign_type' => 1,
            'format' => 'json',
            'server' => '/api/v1.0/vm_tpl/'
        ),
        //删除虚拟机模板
        'del_vm' => array(
            'method' => 'DELETE',
            'sign_type' => 1,
            'format' => 'json',
            'server' => '/api/v1.0/vm_tpl/'
        ),
        //获取虚拟机上传目录
        'mount_path' => array(
            'method' => 'GET',
            'sign_type' => 1,
            'format' => 'json',
            'server' => '/api/v1.0/node/0?host_type=2'
        ),
        //创建虚拟机
        'create_vm' => array(
            'method' => 'POST',
            'sign_type' => 1,
            'format' => 'json',
            'server' => '/api/v1.0/vm_tpl/'
        ),
        //更新虚拟机
        'update_vm' => array(
            'method' => 'PUT',
            'sign_type' => 1,
            'format' => 'json',
            'server' => '/api/v1.0/vm_tpl/'
        ),
        //获取工具上传目录
        'get_tool_db' => array(
            'method' => 'GET',
            'sign_type' => 1,
            'format' => 'json',
            'server' => '/api/v1.0/function_node?server_type=6'
        ),
        //获取视频上传目录
        'video_path' => array(
            'method' => 'GET',
            'sign_type' => 1,
            'format' => 'json',
            'server' => '/api/v1.0/function_node?server_type=7'
        ),
        

    );


    /**
     * 初始化
     *
     * @param $header_name Array  报文格式数据
     * @param $header_name['api_name'] String  api地址命名
     * @param $header_name['message']  Array  消息参数，具体根据中间件要求的参数组成
     * @param $header_name['port']    int 端口如升级端口为5000
     */
    public function __construct($header_name)
    {
        $this->_header = $this->api_config[$header_name['api_name']];
        $this->_message = $header_name['message'];
        if(is_array($this->_message)) {
            $this->_message['reqTime'] = time();
        }

        $CI =& get_instance();
        $CI->load->model("System_model");
        $_tmp = $CI->System_model->get_system_info();

        $this->URL = 'http://' . $_tmp['DCenterIP'] . ':' . (isset($header_name['port']) ? $header_name['port'] : $_tmp['DCenterPort']);

        $CI->load->helper("curl");


    }


    private function aes_encode($sourcestr, $key)
    {

        return base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key, $sourcestr, MCRYPT_MODE_CBC, $this->iv));
    }

    private function aes_decode($crypttext, $key)
    {

        return rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key, base64_decode($crypttext), MCRYPT_MODE_CBC, $this->iv), "\0");
    }


    /**
     * 向后端API服务器发起请求
     * 顺序为：
     * 1、消息内容打包成API要求格式
     * 2、发起请求，获取反馈
     * 3、将反馈解包成易读格式
     * @param $url_param Array  Url 地址参数
     * 例如：http://api.ecq3.com/$param_a/$param_b
     * Array($param_a,$param_b)
     * @return ArrayObject
     */
    public function request($url_param = array())
    {
        try {
            $starttime = time();

            $post_data = $this->message_pack_core($this->_message);

            switch ($this->_header['method']) {

                case 'POST':
                    $post_result = $this->send_request_post($post_data, $url_param);
                    break;
                case 'GET':
                    $post_result = $this->send_request_get($post_data, $url_param);
                    break;
                case 'PUT':
                    $post_result = $this->send_request_put($post_data, $url_param);
                    break;
                case 'DELETE':
                    $post_result = $this->send_request_delete($post_data, $url_param);
                    break;
                default:
                    return FALSE;
                    break;

            }
            //对加密的解密处理
            if ($this->_header['sign_type']){
                $post_result = $this->aes_decode($post_result, $this->aes_key);
            }

            $result = json_decode(trim($post_result), TRUE);


        } catch (\ErrorException $e) {
            //var_dump($e);
            return FALSE;
        }

        if (!is_array($result)) {
            //接口请求失败 日志记录
            $CI =& get_instance();
            $data['CreateTime'] = date("Y-m-d H:i:s",time());
            $data['url'] = $this->URL . $this->_header['server'] . join('/', $url_param);
            $data['method'] = $this->_header['method'];
            $data['post_data'] = $post_data;
            $data['message'] = '请求接口失败:'.$result;
            $CI->load->library('Log_user');
            $CI->log_user->add_api_log($data);
        }
        return $result;
    }

    /**
     * 打包消息体
     * 1、将数组格式的消息转换成String格式
     * 2、加密消息体
     * 3、组织成API要求格式
     * @param $message String,Array  Api私有参数
     */
    public function message_pack_core($message)
    {
        $data = is_array($message) ? http_build_query($message) : $message;

        return $data;
    }

    /**
     * 发送请求post 方式
     * @param $post_data Array
     * @param $url_param Array
     *
     */
    public function send_request_post($post_data, $url_param = array())
    {
        $result = request_by_curl($this->URL . $this->_header['server'] . join('/', $url_param), $post_data, 'POST');
        return $result;
    }

    /**
     * 发送请求 get 方式
     * @param $post_data Array
     * @param $url_param Array
     *
     */
    public function send_request_get($post_data, $url_param)
    {

        $result = @file_get_contents($this->URL . $this->_header['server'] . join('/', $url_param) . '?' . $post_data);
        return $result;
    }

    /*
     * 发送请求 put 方式
     * @param $post_data Array
     * @param $url_param Array
     */
    public function send_request_put($post_data, $url_param)
    {

        $result = request_by_curl($this->URL . $this->_header['server'] . join('/', $url_param), $post_data, 'PUT');

        return $result;
    }

    /*
     * 发送请求 delete 方式
     * @param $post_data Array
     * @param $url_param Array
     */
    public function send_request_delete($post_data, $url_param)
    {
        $result = request_by_curl($this->URL . $this->_header['server'] . join('/', $url_param), $post_data, 'DELETE');

        return $result;
    }
} 