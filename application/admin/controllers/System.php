<?php
/**
 * Created by PhpStorm.
 * User: WKF
 * Date: 2016/7/21
 * Time: 17:03
 */

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 系统管理控制器
 *
 */
class System extends ECQ_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model("System_model");
        $this->load->library('Interface_output');
    }

    /**
     * 系统状态页
     *
     */
    public function info()
    {

        $output_data = array();

        $res = $this->System_model->get_sub_node();
        $output_data['sub_node'] = isset($res['host']) ? $res['host'] : [];
        $output_data['summary'] = $this->System_model->summary();
        //日志类型
        $this->load->library('Config_items');
        $output_data['log_type'] = Config_items::$log_type;

        $this->load->view('admin/system_info', $output_data);


    }

    /**
     * 系统设置页
     */
    public function config()
    {
        $output_data = array();

        $_tmp = $this->System_model->get_node();
        //防止没有数据时页面出现错误提示
        $emptyarr = array('id' => '', 'host_gateway' => '', 'host_netmask' => '', 'root_router_ip' => '');
        $output_data['main_node'] = !empty($_tmp['main_node']) ? $_tmp['main_node'] : $emptyarr;//主节点
        $output_data['sub_node'] = !empty($_tmp['sub_node']) ? $_tmp['sub_node'] : $emptyarr;//子节点
        $output_data['sysinfo_ip'] = $_SERVER['HTTP_HOST'];

        $res = $this->System_model->get_system_info();
        $output_data['NewCourseVersion'] = $res['NewCourseVersion'];

        $this->load->view('admin/system_config', $output_data);
    }

    /**
     * 服务器管理页
     */
    public function server()
    {
        $output_data = array();
        $host_ip = $this->input->get('Search', TRUE);
        $per_page = intval($this->input->get('per_page'));//当前页码
        $per_page = max($per_page, 1);
        $param = array('page' => $per_page, 'size' => 10);//默认一页显示10条记录
        //搜索节点
        if ($host_ip) {
            $param['host_ip'] = $host_ip;
            $param['like'] = 'host_ip';
        }
        $res = $this->System_model->get_sub_node($param);
        $output_data['sub_node'] = isset($res['host']) ? $res['host'] : [];
        $output_data['total_node'] = isset($res['total']) ? $res['total'] : 0;
        $output_data['search'] = empty($host_ip) ? '' : $host_ip;

        $this->load->view('admin/system_server', $output_data);

    }


    /**
     * 虚拟化管理页
     */
    public function virtual()
    {
        $output_data = array();
        $_tmp = $this->System_model->get_host_resource();
        //防止没有数据时页面出现错误提示
        $emptyarr = array('node_count' => '0', 'vm_tpl_count' => '0', 'vm_run_count' => '0', 'history_vm_count' => '0');
        $output_data['resource'] = !empty($_tmp) ? $_tmp : $emptyarr;

        $this->load->view('admin/system_virtual', $output_data);

    }

    /***
     * 虚拟机搜索 Ajax请求
     */
    public function vm_search()
    {
        $search = array();
        $search["page"] = intval($this->input->get_post('p'));
        $search["size"] = intval($this->input->get_post('s'));
        $search["page"] = max($search["page"], 1);
        $search["size"] = max($search["size"], 8);
        $search["order"] = 'vm_ins_uuid';
        $search["sort"] = 'desc';
        //按关键字搜索
        if ($this->input->get_post("vm", TRUE)) {
            $search["vm_name"] = $this->input->get_post("vm", TRUE);
            $search["like"] = 'vm_name';
        }

        $result = $this->System_model->vm_list($search);
        $obj = new stdClass();
        if($result){
            $obj->Page	= $search["page"];
            $obj->Size	= $search["size"];
            $obj->Count	= count($result['VmInstance']) === 0 ? 0 : $result['total'];
            $obj->PageCount = ceil($obj->Count/$search["size"]);
            $obj->Result = $result;
        }

        echo json_encode($obj);


    }


    /**
     * license管理页
     */
    public function license()
    {

        $output_data = array();
        $_tmp = $this->System_model->get_system_info();
        $output_data['PlantformVersion'] = $_tmp['PlantformVersion'];
        $output_data['AuthorizeTime'] = $_tmp['AuthorizeTime'];
        $output_data['LicenseTime'] = $_tmp['AuthorizeTime'];

        $this->load->view('admin/system_license', $output_data);

    }

    public function apitest()
    {


        $this->load->library('Data_Exchange', array('api_name' => 'scene_operate', 'message' => array()), 'test_get');


        $rerurn = $this->test_get->request(array(1));

        var_dump($rerurn);

        $str = '{"scene":{"id":"0001","uuid":"70616eef-ffee-11e5-a571-005056c00008","rootnet":{"zone":{"count":3,"items":[{"id":"001","name":"LAN1","eth":{"count":1,"items":[{"ip":"172.16.9.1","netmask":24,"gateway":"172.16.9.1","dns":"8.8.8.8"}]},"vm":{"count":2,"items":[{"templateuuid":"9FC5B0AD-3136-42E0-99FB-37905F2FEDAE","eth":{"count":2,"items":[{"ip":"172.16.9.2","netmask":24,"gateway":"172.16.9.1","portmap":{"count":2,"items":[{"localport":3389,"remoteport":2000},{"localport":3390,"remoteport":2001}]}},{"ip":"172.16.9.3","netmask":24,"gateway":"172.16.9.1"}]}},{"templateuuid":"9FC5B0AD-3136-42E0-99FB-37905F2FEDAE","eth":{"count":1,"items":[{"ip":"172.16.9.4","netmask":24,"gateway":"172.16.9.1"}]}}]}},{"id":"002","name":"LAN2","eth":{"count":1,"items":[{"ip":"172.16.10.1","netmask":24,"gateway":"172.16.10.1"}]},"vm":{"count":1,"items":[{"templateuuid":"9FC5B0AD-3136-42E0-99FB-37905F2FEDAE","eth":{"count":1,"items":[{"ip":"172.16.10.2","netmask":24,"gateway":"172.16.10.1"}]}}]}},{"id":"003","name":"LAN3","eth":{"count":1,"items":[{"ip":"172.16.11.1","netmask":24,"gateway":"172.16.11.1"}]},"vm":{"count":1,"items":[{"templateuuid":"9FC5B0AD-3136-42E0-99FB-37905F2FEDAE","eth":{"count":1,"items":[{"ip":"172.16.11.2","netmask":24,"gateway":"172.16.11.1"}]}}]}}],"connrel":{"count":2,"items":[{"srcid":"001","srcname":"LAN1","target":[{"id":"002","name":"LAN2"},{"id":"003","name":"LAN3"}]},{"srcid":"002","srcname":"LAN2","target":[{"id":"003","name":"LAN3"}]}]}}}},"opervm":{"construct":0,"templateuuid":"9FC5B0AD-3136-42E0-99FB-37905F2FEDAE","eth":{"ip":"172.16.8.2","netmask":24,"gateway":"172.16.8.1","dns":"8.8.8.8"},"portmap":{"count":2,"items":[{"localport":3389,"remoteport":2003},{"localport":3390,"remoteport":2004}]}},"resused":{"memory":512,"memoryunit":"M","disk":5,"diskunit":"G"}}';

        $this->load->library('Data_Exchange', array('api_name' => 'create_scenarios', 'message' => array("json" => $str, "host_id" => 1, "author" => 456)), 'test_post');


        $rerurn = $this->test_post->request();

        var_dump($rerurn);


        $this->load->library('Data_Exchange', array('api_name' => 'modify_scenarios', 'message' => array("json" => 444)), 'test_put');


        $rerurn = $this->test_put->request();

        var_dump($rerurn);


        $this->load->library('Data_Exchange', array('api_name' => 'delete_scenarios', 'message' => array()), 'test_delete');


        $rerurn = $this->test_delete->request(array(1));

        var_dump($rerurn);

    }

    /***
     * 验证用户名密码 Ajax请求
     */
    public function verify_user()
    {
        $username = $this->input->post("username", TRUE);
        $password = md5($this->input->post("password"));
        $where = array('UserAccount' => $username, 'UserPass' => $password, 'UserRole' => 1, 'IsDeleted' => 0);
        $this->load->model("User_model");
        $tmp = $this->User_model->verify_user($where);
        $this->interface_output->output_fomcat('js_Ajax', $tmp);
    }

    /***
     * 配置平台IP
     */
    public function modify_platform()
    {

        //$host_type = $this->input->post("host_type");
        $host_id = $this->input->post("id");
        $ip = $this->input->post("ip");
        $netmask = $this->input->post("netmask");
        $gateway = $this->input->post("gateway");

        $data = array(
            'host_ip' => $ip,
            'host_netmask' => $netmask,
            'host_gateway' => $gateway
        );
        $tmp = $this->System_model->modify_ip($data, $host_id);
        $this->interface_output->output_fomcat('js_Ajax', $tmp);

    }

    /***
     * 配置靶机IP
     */
    public function modify_router()
    {
        $host_id = $this->input->post('hostid');
        $router_ip = $this->input->post("routerIp");
        $router_netmask = $this->input->post("routerNetMask");
        $router_gateway = $this->input->post("routerGateway");

        $data = array(
            'root_router_ip' => $router_ip,
            'root_router_netmask' => $router_netmask,
            'root_router_gateway' => $router_gateway
        );

        $tmp = $this->System_model->modify_ip($data, $host_id);
        $this->interface_output->output_fomcat('js_Ajax', $tmp);


    }

    /***
     * 查看靶机IP修改进度
     */
    public function get_task_progress()
    {
        $task_uuid = $this->input->post('task_uuid', TRUE);

        $tmp = $this->System_model->get_task_progress($task_uuid);

        $this->interface_output->output_fomcat('js_Ajax', $tmp);

    }

    /***
     * 获取CPU使用率
     */
    public function get_cpu_use()
    {
        $ids = $this->input->get("ids");
        if (!empty($ids)) {
            $arr_ids = explode(":", $ids);//主机ID
            if (count($arr_ids) > 0) {
                foreach ($arr_ids as $id) {
                    $_tmp = $this->System_model->get_cpu_use($id);
                    //$arr_data[$id] = $_tmp;
                    $arr_data[] = $_tmp['data'];
                }
                echo json_encode($arr_data);
            }
        }
    }

    /***
     * 获取节点信息 Ajax请求
     */
    public function node_info()
    {
        $res = $this->System_model->get_sub_node();
        if (count($res) > 0) {
            $tmp['code'] = '00000';
            $tmp['msg'] = 'success';
            $tmp['data'] = $res;
        } else {
            $tmp['code'] = '0204';
            $tmp['msg'] = 'error';
            $tmp['data'] = array();
        }
        $this->interface_output->output_fomcat('js_Ajax', $tmp);
    }

    /***
     * 平台数据备份
     */
    public function backup()
    {

        $tmp = $this->System_model->backup();

        $this->interface_output->output_fomcat('js_Ajax', $tmp);

    }

    /***
     * 平台数据恢复
     */
    public function recover()
    {
        $tmp = $this->System_model->recover();

        $this->interface_output->output_fomcat('js_Ajax', $tmp);


    }

    /***
     * 登录日志 Ajax请求
     */
    public function loginlog()
    {
        $tmp = array('code' => '0000', 'msg' => 'success!', 'data' => array());
        $this->load->model('Log_model');
        $page = intval($this->input->get("p"));//页面
        $size = intval($this->input->get("s"));//获取记录数
        $page = max($page, 1);
        $size = max($size, 3);
        $offset = ($page - 1) * $size;
        $obj = new stdClass();
        $obj->Page = $page;
        $obj->Size = $size;
        $obj->Count = $this->Log_model->get_count(array());
        $obj->PageCount = ceil($obj->Count / $size);
        $obj->Result = $this->Log_model->get_log(array('limit' => array('limit' => $size, 'offset' => $offset)));
        $tmp['data'] = $obj;

        $this->interface_output->output_fomcat('js_Ajax', $tmp);

    }

    /***
     * 重启、关机节点
     */
    public function node_operate()
    {
        $host_id = $this->input->post('host_id', TRUE);
        $handle = $this->input->post('handle', TRUE);
        $tmp = array('code' => '0000', 'msg' => '操作成功!', 'data' => []);
        do {
            if (empty($host_id)) {
                $tmp = array('code' => '0351', 'msg' => '没有此节点!', 'data' => []);
                break;
            }
            if (!in_array($handle, array('reboot', 'shutdown'))) {
                $tmp = array('code' => '0353', 'msg' => '非法的操作!', 'data' => []);
                break;
            }
            $res = $this->System_model->node_operate($host_id, $handle);
            if ($res['code'] != '0000') {
                $tmp = array('code' => $res['code'], 'msg' => $res['msg'], 'data' => []);
                break;
            }

        } while (FALSE);

        $this->interface_output->output_fomcat('js_Ajax', $tmp);

    }

    /***
     * 删除节点
     */
    public function del_node()
    {
        $host_id = intval($this->input->post('host_id', TRUE));
        $tmp = array('code' => '0000', 'msg' => 'success!', 'data' => []);
        do {
            if ($host_id == 0) {
                $tmp = array('code' => '0351', 'msg' => '没有此节点!', 'data' => []);
                break;
            }
            $res = $this->System_model->del_node($host_id);
            if ($res['code'] != '0000') {
                $tmp = array('code' => $res['code'], 'msg' => $res['msg'], 'data' => []);
                break;
            }

        } while (FALSE);

        $this->interface_output->output_fomcat('js_Ajax', $tmp);
    }

    /***
     * 添加节点
     */
    public function add_node()
    {
        $description = $this->input->post('description', TRUE);
        $ip = $this->input->post('ip');
        $netmask = $this->input->post('netmask');
        $interface_port = intval($this->input->post('interface_port'));
        $vnc_server_port = intval($this->input->post('vnc_server_port'));

        $this->load->library('Data_validate');
        do {
            if (empty($description)) {
                $tmp = array('code' => '0455', 'msg' => '节点名称不能为空!', 'data' => []);
                break;
            }
            if (empty($ip)) {
                $tmp = array('code' => '0456', 'msg' => '节点IP不能为空!', 'data' => []);
                break;
            }
            if (!$this->data_validate->is_ip($ip)) {
                $tmp = array('code' => '0459', 'msg' => '节点IP格式错误!', 'data' => []);
                break;
            }
            if (!empty($netmask) && !$this->data_validate->is_ip($netmask)) {
                $tmp = array('code' => '0460', 'msg' => '子网掩码格式错误!', 'data' => []);
                break;
            }
            if (empty($interface_port)) {
                $tmp = array('code' => '0457', 'msg' => '服务端口不能为空!', 'data' => []);
                break;
            }
            if (empty($vnc_server_port)) {
                $tmp = array('code' => '0458', 'msg' => '远程桌面开放不能为空!', 'data' => []);
                break;
            }
            $data = array(
                'host_description' => $description,
                'host_ip' => $ip,
                'host_netmask' => $netmask,
                'interface_port' => $interface_port,
                'vnc_server_port' => $vnc_server_port
            );
            $tmp = $this->System_model->add_node($data);

        } while (FALSE);

        $this->interface_output->output_fomcat('js_Ajax', $tmp);

    }

    /***
     * 平台升级
     */
    public function platform_upgrade()
    {
        $filename = $this->input->post("fname");
        $type = $this->input->post("ftype");
        $code = $this->input->post("code");
        do {
            if (empty($filename)) {
                $tmp = array('code' => '0460', 'msg' => '请上传升级文件!', 'data' => []);
                break;
            }
            if (empty($type)) {
                $tmp = array('code' => '0460', 'msg' => '请选择升级类型!', 'data' => []);
                break;
            }
            $filename = getcwd() . $filename;
            if (!file_exists($filename)) {
                $tmp = array('code' => '0460', 'msg' => '升级文件不存在!', 'data' => []);
                break;
            }
            $data = array(
                'zip_file_path' => $filename,
                'php_project_path' => getcwd(),
                'php_project_type' => intval($code),
                'php_project_name' => config_item('platformSystem'),
            );//此处参数可能还有数据库信息
            $tmp = $this->System_model->upgrade($data);

        } while (FALSE);

        $this->interface_output->output_fomcat('js_Ajax', $tmp);


    }

    /***
     * 课件及实验升级
     */
    public function course_upgrade()
    {
        $filename = $this->input->post("fname");
        do {
            if (empty($filename)) {
                $tmp = array('code' => '0460', 'msg' => '请上传升级文件!', 'data' => []);
                break;
            }
            $filename = getcwd() . $filename;
            if (!file_exists($filename)) {
                $tmp = array('code' => '0460', 'msg' => '升级文件不存在!', 'data' => []);
                break;
            }
            $data = array(
                'file_path' => $filename,
            );
            $tmp = $this->System_model->course_upgrade($data);

        } while (FALSE);

        $this->interface_output->output_fomcat('js_Ajax', $tmp);


    }

    /***
     * 查看课件实验升级进度 Ajax
     */
    public function upgrade_progress()
    {
        $filename = $this->input->post("fname");
        do {
            if (empty($filename)) {
                $tmp = array('code' => '0460', 'msg' => '请上传升级文件!', 'data' => []);
                break;
            }
            $filename = getcwd() . $filename;
            if (!file_exists($filename)) {
                $tmp = array('code' => '0460', 'msg' => '升级文件不存在!', 'data' => []);
                break;
            }
            $data = array(
                'file_path' => $filename,
            );//此处参数可能还有数据库信息
            $tmp = $this->System_model->upgrade_progress($data);

        } while (FALSE);

        $this->interface_output->output_fomcat('js_Ajax', $tmp);

    }

    /***
     * 上传文件
     */
    public function upload()
    {
        $key = $this->input->post("key");
        $key2 = $this->input->post("key2");
        $filename = $this->input->post("fileName");//上传文件
        //获取扩展名
        $fileType = strtolower(strrchr($filename, '.'));
        $filename = $key2 . $key .$fileType;
        $config['file_name'] = $filename;
        $config['upload_path'] = getcwd().'/resources/files/system_update/';
        $config['allowed_types'] = 'sql|zip|gzip|rar|mp4|flv|rm|rmvb|qcow2|tar|doc|docx|xls|xlsx|jpg|jpeg|png|bmp';
        $config['max_size'] = 0;

        $this->load->library('upload', $config);
        //

        //if (!$this->upload->do_upload('file')) {//普通上传保留
        if (!$this->upload->huploadify($filename)) {
            $tmp = array('success' => FALSE, 'fileurl' => NULL, 'filename' => NULL, 'msg'=>'上传失败,请检查目录权限！');
        } else {
            $tmp = array('success' => TRUE, 'fileurl' => $filename, 'filename' => $filename, 'msg'=>'上传成功！');
        }
        $this->interface_output->output_fomcat('js_Upload', $tmp);
    }

    /***
     * 系统升级日志
     * @param int $per_page
     */
    public function upgrade_log($per_page = 1)
    {
        //左侧导航 选中状态
        $this->nav['left_nav_id'] = 3;
        $output_data = array();

        $offset = max(intval($per_page), 1);
        $perpage = 10;//每页记录数

        $data = array(
            'token' => md5("ecq3password1."),//config_item('upgrade_token')
            'page' => $offset,
            'size' => $perpage,
            'behavior' => 1
        );
        $res = $this->System_model->upgrade_log($data);
        //分页

        $output_data['total_rows'] = isset($res['_total']) ? $res['_total'] : 0;//获取总记录数
        $output_data['log_list'] = isset($res['_list']) ? $res['_list'] : [];

        $output_data['page_url'] = site_url('System/upgrade_log') . '/';
        $output_data['page_count'] = ceil($output_data['total_rows'] / 10);
        $output_data['page_pre'] = $offset;

        $this->load->view('admin/system_upgrade_log', $output_data);
    }

    /***
     * 下载备份SQL文件
     */
    public function download_file_sql()
    {
        $path = "";
        $version_file_path = $this->input->get("version_file_path");

        $file_name = basename($version_file_path);//下载文件名
        $file_dir = "/backups_db/";
        $path = $file_dir . $file_name;
        if (!file_exists($path)) {
            echo "<script>alert('当前下载文件不存在!');history.go(-1);</script>";
        } else {
            header("Content-type:text/html;charset=utf-8");
            $file_path = $path;
            $fp = fopen($file_path, "r");
            $file_size = filesize($file_path);
            //下载文件需要用到的头
            Header("Content-type: application/octet-stream");
            Header("Accept-Ranges: bytes");
            Header("Accept-Length:" . $file_size);
            Header("Content-Disposition: attachment; filename=" . $file_name);
            $buffer = 1024;
            $file_count = 0;
            //向浏览器返回数据
            while (!feof($fp) && $file_count < $file_size) {
                $file_con = fread($fp, $buffer);
                $file_count += $buffer;
                echo $file_con;
            }
            fclose($fp);
        }

    }

    /***
     * 升级系统SQL
     */
    public function update_system_sql()
    {
        $filename = $this->input->post("fname");
        $filename = getcwd() . $filename;
        if (file_exists($filename)) {
            $tmp = $this->System_model->update_system_sql($filename);
        }else{
            $tmp = $tmp = array('code' => 'error', 'msg' => '文件不存在!', 'data' => []);
        }

        $this->interface_output->output_fomcat('js_Ajax', $tmp);

    }

    /***
     * 恢复出厂设置恢复程序文件
     */
    public function restore_system_php()
    {
        $tmp = $this->System_model->restore_system_php();

        $this->interface_output->output_fomcat('js_Ajax', $tmp);

    }

    /***
     * 获取子节点列表
     */
    public function node_list()
    {
        $tmp = array('code' => '0000', 'msg' => 'success', 'data' => []);
        $host_ip = $this->input->get('Search', TRUE);
        $per_page = intval($this->input->get('per_page'));//当前页码
        $per_page = max($per_page, 1);
        $param = array('page' => $per_page, 'size' => 10);//默认一页显示10条记录
        //搜索节点
        if ($host_ip) {
            $param['host_ip'] = $host_ip;
        }
        $result = array();
        $res = $this->System_model->get_sub_node($param);
        if ($res) {
            foreach ($res['host'] as $node) {
                if ($node['host_type'] != 1) {
                    $result[] = $node;
                }
            }
        }
        $tmp['data'] = $result;

        $this->interface_output->output_fomcat('js_Ajax', $tmp);
    }

    /***
     * 虚拟机管理
     * @param $handle 'start','reboot'...
     */
    public function manage_vm($handle)
    {
        $handle = $this->uri->segment(3);
        $hostid	= intval($this->input->get_post("hid"));
        $uuid	= $this->input->get_post("uuid");
        $tmp = array('code' => '0000', 'msg' => 'success!', 'data' => []);
        do{
            //数据检查
            if ($hostid == 0) {
                $tmp = array('code' => '0500', 'msg' => '主机id不能为空!', 'data' => []);
                break;
            }
            if (empty($uuid)) {
                $tmp = array('code' => '05001', 'msg' => 'uuid不能为空!', 'data' => []);
                break;
            }
            if (!in_array($handle, array('start','resume','reboot','suspend', 'shutdown','destroy'))) {
                $tmp = array('code' => '0353', 'msg' => '非法的操作!', 'data' => []);
                break;
            }
            $tmp = $this->System_model->manage_vm($hostid, $uuid, $handle);


        }while(FALSE);

        $this->interface_output->output_fomcat('js_Ajax', $tmp);

    }

    /***
     * 查看当前有没有存在的实验
     */
    public function existenceTest()
    {
        $tmp = $this->System_model->existenceTest();
        
        $this->interface_output->output_fomcat('js_Ajax', $tmp);
    }



}