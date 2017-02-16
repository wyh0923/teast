<?php

/**
 * Created by PhpStorm.
 * User: WKF
 * Date: 2016/7/22
 * Time: 16:15
 */
class System_model extends CI_Model
{
    /**
     * 系统信息
     * @return array
     */

    public function get_system_info()
    {
        $data = [];
        $query = $this->db->get("system_info");

        foreach ($query->result() as $row) {

            $data[$row->InfoName] = $row->InfoValue;
        }

        return $data;

    }
    /**
     * 获取系统端口映射
     * @return array
     */

    public function get_system_port($where)
    {
        $this->db->select('localport');
        $this->db->from('system_port');
        $this->db->where($where);
        $result = $this->db->get()->result_array();
        return $result;


    }

    /***
     * 获取平台主节点跟子节点信息
     * @return array
     */
    public function get_node()
    {
        $result = array();
        $result['main_node'] = $this->get_web_node();
        $res = $this->get_sub_node();
        if ($res) {
            foreach ($res['host'] as $node) {
                if ($node['host_type'] == 1) {
                    $result['sub_node'] = $node;
                    break;
                }
            }
        }

        return $result;
    }

    /***
     * 获取web节点信息
     */
    public function get_web_node()
    {
        $result = array();
        //获取主节点信息
        $this->load->library('Data_exchange', array('api_name' => 'get_node', 'message' => ''), 'get_main');
        $main_node = $this->get_main->request();
        if ($main_node && $main_node["RespHead"]["ErrorCode"] === 0) {
            $result = $main_node['RespBody']['Result']['Host'][0];
        }
        return $result;
    }

    /***
     * 获取节点信息
     */
    public function get_sub_node($param = '')
    {
        $result = array();
        $this->load->library('Data_exchange', array('api_name' => 'get_sub_node', 'message' => $param), 'get_sub');
        $sub_node = $this->get_sub->request();
        if ($sub_node && $sub_node["RespHead"]["ErrorCode"] === 0) {
            $result['host'] = $sub_node['RespBody']['Result']['Host'];
            $result['total'] = $sub_node['RespBody']['Result']['total'];
        }
        return $result;
    }

    /***
     * 获取虚拟机资源统计
     * @return array
     */
    public function get_host_resource()
    {
        $result = array();
        $this->load->library('Data_exchange', array('api_name' => 'host_resource', 'message' => ''), 'get_sub');
        $sub_node = $this->get_sub->request();
        if ($sub_node && $sub_node["RespHead"]["ErrorCode"] === 0) {
            $result = $sub_node['RespBody']['Result'];
        }
        return $result;
    }

    /***
     * 虚拟机列表
     * @param $param
     * @return array
     */
    public function vm_list($param = array())
    {
        $result = array();
        $this->load->library('Data_exchange', array('api_name' => 'vm_list', 'message' => $param), 'get_sub');
        $sub_node = $this->get_sub->request();
        if ($sub_node && $sub_node["RespHead"]["ErrorCode"] === 0) {
            $result = $sub_node['RespBody']['Result'];
        }
        return $result;

    }

    /***
     * 配置IP信息
     * @param array $data
     * @param int $host_id
     * @return array
     */
    public function modify_ip($data, $host_id)
    {
        $json = json_encode($data);
        $this->load->library('Data_exchange', array('api_name' => 'modify_ip', 'message' => array('json' => $json)), 'get_main');
        $res = $this->get_main->request(array($host_id));
        if ($res && $res["RespHead"]["ErrorCode"] === 0) {
            $tmp['code'] = '0000';
            $tmp['msg'] = 'success';
            $tmp['data'] = $res['RespBody']['Result'];
        } else if ($res) {
            $tmp['code'] = $res["RespHead"]["ErrorCode"];
            $tmp['msg'] = isset($res['RespHead']['Message']) ? $res['RespHead']['Message'] : '配置失败';
            $tmp['data'] = [];
        } else {
            $tmp['code'] = '0202';
            $tmp['msg'] = '配置失败，请检查网络是否畅通';
            $tmp['data'] = [];
        }
        return $tmp;
    }

    /***
     * 虚拟机管理
     * @param $hostid
     * @param $uuid
     * @param $handle
     * @return mixed
     */
    public function manage_vm($hostid, $uuid, $handle)
    {
        $this->load->library('Data_exchange', array('api_name' => 'manage_vm', 'message' => 'handle='.$handle), 'get_main');
        $res = $this->get_main->request(array($hostid, $uuid));
        if ($res && $res["RespHead"]["ErrorCode"] === 0) {
            $tmp['code'] = '0000';
            $tmp['msg'] = '请求成功' . $handle;
            $tmp['data'] = $res['RespBody']['Result'];
        } else {
            $tmp['code'] = 'error';
            $tmp['msg'] = '请求失败';
            $tmp['data'] = array();
        }
        return $tmp;

    }

    /***
     * 获取配置执行进度
     * @param $task_uuid
     * @return array
     */
    public function get_task_progress($task_uuid)
    {
        $this->load->library('Data_exchange', array('api_name' => 'task_progress', 'message' => array()), 'get_main');
        $res = $this->get_main->request(array($task_uuid));
        if ($res && $res["RespHead"]["ErrorCode"] === 0) {
            $tmp['code'] = '0000';
            $tmp['msg'] = 'success';
            $tmp['data'] = $res['RespBody']['Result'];
        } else if ($res) {
            $tmp['code'] = $res["RespHead"]["ErrorCode"];
            $tmp['msg'] = isset($res['RespHead']['Message']) ? $res['RespHead']['Message'] : '配置失败';
            $tmp['data'] = [];
        } else {
            $tmp['code'] = '0201';
            $tmp['msg'] = '配置失败，请检查网络是否畅通';
            $tmp['data'] = [];
        }
        return $tmp;
    }

    /***
     * 获取cpu使用率
     * @param int $id 主机ID
     */
    public function get_cpu_use($id)
    {
        $this->load->library('Data_exchange', array('api_name' => 'cpu_use', 'message' => array()), 'get_main');
        $res = $this->get_main->request(array($id));
        if ($res && $res["RespHead"]["ErrorCode"] === 0) {
            $tmp['code'] = '0000';
            $tmp['msg'] = 'success';
            $tmp['data'] = $res['RespBody']['Result']['cpu_use'];
        } else {
            $tmp['code'] = '0201';
            $tmp['msg'] = 'error';
            $tmp['data'] = 0;
        }
        return $tmp;

    }

    /***
     * 获取数据库信息
     * @return array
     */
    public function get_db_data()
    {
        $info_arr = array();
        $info_arr['db_ip'] = $this->db->hostname;
        if ($info_arr['db_ip'] == "127.0.0.1") {
            $info_arr['db_ip'] = $_SERVER["SERVER_ADDR"];
        }
        $_tmp = $this->get_system_info();
        $info_arr['dc_ip'] = $_tmp['DCenterIP'];

        if ($info_arr['dc_ip'] == "127.0.0.1") {
            $info_arr['dc_ip'] = $_SERVER["SERVER_ADDR"];
        }
        $info_arr['token'] = md5("ecq3password1.");
        $info_arr['db_name'] = $this->db->database;
        return $info_arr;
    }

    /***
     * 升级系统SQL
     * @return mixed
     */
    public function update_system_sql($filename)
    {
        $data = $this->get_db_data();
        $data['file_path'] = $filename;
        $this->load->library('Data_exchange', array('api_name' => 'recover', 'message' => $data, 'port' => '5000'), 'get_main');
        $res = $this->get_main->request();
        if ($res && $res["RespHead"]["ErrorCode"] === 0) {
            $tmp['code'] = '0000';
            $tmp['msg'] = 'success';
            $tmp['data'] = $res['RespBody']['Result'];
        } else if ($res) {
            $tmp['code'] = $res["RespHead"]["ErrorCode"];
            $tmp['msg'] = isset($res['RespHead']['Message']) ? $res['RespHead']['Message'] : '升级失败';
            $tmp['data'] = [];
        } else {
            $tmp['code'] = '0203';
            $tmp['msg'] = '失败，请检查网络是否畅通';
            $tmp['data'] = [];
        }
        return $tmp;
    }

    /***
     * 平台备份
     * @return array
     */
    public function backup()
    {
        $data = $this->get_db_data();
        $this->load->library('Data_exchange', array('api_name' => 'backup', 'message' => $data, 'port' => '5000'), 'get_main');
        $res = $this->get_main->request();
        if ($res && $res["RespHead"]["ErrorCode"] === 0) {
            $tmp['code'] = '0000';
            $tmp['msg'] = 'success';
            $tmp['data'] = $res['RespBody']['Result'];
        } else if ($res) {
            $tmp['code'] = $res["RespHead"]["ErrorCode"];
            $tmp['msg'] = isset($res['RespHead']['Message']) ? $res['RespHead']['Message'] : '备份失败';
            $tmp['data'] = [];
        } else {
            $tmp['code'] = '0203';
            $tmp['msg'] = '失败，请检查网络是否畅通';
            $tmp['data'] = [];
        }
        return $tmp;
    }

    /***
     * 数据恢复
     * @return array
     */
    public function recover()
    {
        $data = $this->get_db_data();
        $data['file_path'] = "/ecq3train-phoenix-bak/ecq3_bak_sql.zip";
        $this->load->library('Data_exchange', array('api_name' => 'recover', 'message' => $data, 'port' => '5000'), 'get_main');
        $res = $this->get_main->request();
        if ($res && $res["RespHead"]["ErrorCode"] === 0) {
            $tmp['code'] = '0000';
            $tmp['msg'] = 'success';
            $tmp['data'] = $res['RespBody']['Result'];
        } else if ($res) {
            $tmp['code'] = $res["RespHead"]["ErrorCode"];
            $tmp['msg'] = isset($res['RespHead']['Message']) ? $res['RespHead']['Message'] : '恢复失败';
            $tmp['data'] = [];
        } else {
            $tmp['code'] = '0203';
            $tmp['msg'] = '失败，请检查网络是否畅通';
            $tmp['data'] = [];
        }
        return $tmp;
    }

    /***
     * 恢复程序文件
     * @return mixed
     */
    public function restore_system_php()
    {
        $data = $this->get_db_data();
        $data['file_path'] = "/ecq3train-phoenix-bak/ecq3_bak_php";
        $data['php_project_path'] = getcwd();
        $data['ecq3_php_project_backups_path'] = $data['file_path'];
        $this->load->library('Data_exchange', array('api_name' => 'restore_php', 'message' => $data, 'port' => '5000'), 'get_main');
        $res = $this->get_main->request();
        if ($res && $res["RespHead"]["ErrorCode"] === 0) {
            $tmp['code'] = '0000';
            $tmp['msg'] = 'success';
            $tmp['data'] = $res['RespBody']['Result'];
        } else if ($res) {
            $tmp['code'] = $res["RespHead"]["ErrorCode"];
            $tmp['msg'] = isset($res['RespHead']['Message']) ? $res['RespHead']['Message'] : '恢复失败';
            $tmp['data'] = [];
        } else {
            $tmp['code'] = '0203';
            $tmp['msg'] = '失败，请检查网络是否畅通';
            $tmp['data'] = [];
        }
        return $tmp;
    }

    /***
     * 系统升级
     * @return mixed
     */
    public function upgrade($data)
    {
        $db_data = $this->get_db_data();
        $data = array_merge($data, $db_data);
        $this->load->library('Data_exchange', array('api_name' => 'upgrade', 'message' => $data, 'port' => '5000'), 'get_main');
        $res = $this->get_main->request();
        if ($res && $res["RespHead"]["ErrorCode"] === 0) {
            $tmp['code'] = '0000';
            $tmp['msg'] = 'success';
            $tmp['data'] = $res['RespBody']['Result'];
        } else if ($res) {
            $tmp['code'] = $res["RespHead"]["ErrorCode"];
            $tmp['msg'] = isset($res['RespHead']['Message']) ? $res['RespHead']['Message'] : '升级失败';
            $tmp['data'] = [];
        } else {
            $tmp['code'] = '0201';
            $tmp['msg'] = '失败，请检查网络是否畅通';
            $tmp['data'] = [];
        }
        return $tmp;
    }

    /***
     * 课件实验升级
     * @param $data
     * @return mixed
     */
    public function course_upgrade($data)
    {
        $db_data = $this->get_db_data();
        $data = array_merge($data, $db_data);
        $this->load->library('Data_exchange', array('api_name' => 'course_upgrade', 'message' => $data, 'port' => '5000'), 'get_main');
        $res = $this->get_main->request();
        if ($res && $res["RespHead"]["ErrorCode"] === 0) {
            $tmp['code'] = '0000';
            $tmp['msg'] = 'success';
            $tmp['data'] = $res['RespBody']['Result'];
        } else if ($res) {
            $tmp['code'] = $res["RespHead"]["ErrorCode"];
            $tmp['msg'] = isset($res['RespHead']['Message']) ? $res['RespHead']['Message'] : '升级失败';
            $tmp['data'] = [];
        } else {
            $tmp['code'] = '0201';
            $tmp['msg'] = '失败，请检查网络是否畅通';
            $tmp['data'] = [];
        }
        return $tmp;

    }

    /***
     * 课件实验升级进度
     * @param $data
     * @return mixed
     */
    public function upgrade_progress($data)
    {
        $this->load->library('Data_exchange', array('api_name' => 'upgrade_progress', 'message' => $data, 'port' => '5000'), 'get_main');
        $res = $this->get_main->request();
        if ($res && $res["RespHead"]["ErrorCode"] === 0) {
            $tmp['code'] = '0000';
            $tmp['msg'] = 'success';
            $tmp['data'] = $res['RespBody']['Result'];
        } else if ($res) {
            $tmp['code'] = $res["RespHead"]["ErrorCode"];
            $tmp['msg'] = isset($res['RespHead']['Message']) ? $res['RespHead']['Message'] : '升级失败';
            $tmp['data'] = [];
        } else {
            $tmp['code'] = '0203';
            $tmp['msg'] = '失败，请检查网络是否畅通';
            $tmp['data'] = [];
        }
        return $tmp;

    }

    /***
     * 升级日志查询
     * @param $data
     * @return array
     */
    public function upgrade_log($data)
    {
        $result = array();
        $this->load->library('Data_exchange', array('api_name' => 'upgrade_log', 'message' => $data, 'port' => '5000'), 'get_sub');
        $sub_node = $this->get_sub->request();
        if ($sub_node && $sub_node["RespHead"]["ErrorCode"] === 0) {
            $result = $sub_node['RespBody']['Result'];
        }
        return $result;

    }

    /***
     * 重启节点
     * @param $id
     * @return mixed
     */
    public function node_operate($host_id, $handle)
    {
        $this->load->library('Data_exchange', array('api_name' => 'node_operate', 'message' => array('handle' => $handle)), 'get_main');
        $res = $this->get_main->request(array($host_id));
        if ($res && $res["RespHead"]["ErrorCode"] === 0) {
            $tmp['code'] = '0000';
            $tmp['msg'] = 'success';
            $tmp['data'] = $res['RespBody']['Result'];
        }else if($res["RespHead"]["ErrorCode"] != 0){
            $tmp['code'] = $res["RespHead"]["ErrorCode"];
            $tmp['msg'] = $res["RespHead"]["ErrorMessage"];
            $tmp['data'] = [];
        }else {
            $tmp['code'] = '0201';
            $tmp['msg'] = '服务未启动';
            $tmp['data'] = 0;
        }
        return $tmp;

    }

    /***
     * 删除节点
     * @param $host_id
     * @return mixed
     */
    public function del_node($host_id)
    {
        $this->load->library('Data_exchange', array('api_name' => 'delete_node', 'message' => ''), 'get_main');
        $res = $this->get_main->request(array($host_id));
        if ($res && $res["RespHead"]["ErrorCode"] === 0) {
            $tmp['code'] = '0000';
            $tmp['msg'] = 'success';
            $tmp['data'] = $res['RespBody']['Result'];
        } else if ($res) {
            $tmp['code'] = $res["RespHead"]["ErrorCode"];
            $tmp['msg'] = $res['RespHead']['Message'];
            $tmp['data'] = [];
        } else {
            $tmp['code'] = '0201';
            $tmp['msg'] = '失败，请检查网络是否畅通';
            $tmp['data'] = [];
        }
        return $tmp;

    }

    /***
     * 添加节点
     * @param $data
     * @return mixed
     */
    public function add_node($data)
    {
        $json = json_encode($data);
        $this->load->library('Data_exchange', array('api_name' => 'add_node', 'message' => array('json' => $json)), 'get_main');
        $res = $this->get_main->request();
        if ($res && $res["RespHead"]["ErrorCode"] === 0) {
            $tmp['code'] = '0000';
            $tmp['msg'] = 'success';
            $tmp['data'] = $res['RespBody']['Result'];
        } else if ($res) {
            $tmp['code'] = $res["RespHead"]["ErrorCode"];
            $tmp['msg'] = $res['RespHead']['Message'];
            $tmp['data'] = [];
        } else {
            $tmp['code'] = '0201';
            $tmp['msg'] = '失败，请检查网络是否畅通';
            $tmp['data'] = [];
        }
        return $tmp;

    }

    /***
     * 系统信息统计
     * @return mixed
     */
    public function summary()
    {
        $sql = <<<EOD
select sum(a) as class, sum(b) as tea, sum(c) as stu, sum(d) as package, sum(e) as exp, sum(f) as section from (
   select count(*) as a, 0 as b, 0 as c, 0 as d, 0 as e, 0 as f from  p_class 
 union 
   select 0 as a, count(*) as b, 0 as c, 0 as d, 0 as e, 0 as f from  p_user where UserRole = 2 and IsDeleted=0 
 union 
   select 0 as a, 0 as b, count(*) as c, 0 as d, 0 as e, 0 as f from p_user where UserRole = 3 and IsDeleted=0 
 union
   select 0 as a, 0 as b, 0 as c, count(*) as d, 0 as e, 0 as f from  p_package where PackageParent='0'
 union 
   select 0 as a, 0 as b, 0 as c, 0 as d, sum(PracticeSectionNum) as e, 0 as f from p_package where PackageType=1 
 union 
   select 0 as a, 0 as b, 0 as c, 0 as d, 0 as e, sum(SectionNum) as f from p_package where PackageType=1)
as result
EOD;

        $query = $this->db->query($sql);
        return $query->result_array()[0];
    }

    /***
     * 查看当前有没有存在的实验
     */
    public function existenceTest()
    {
        $this->load->library('Data_exchange', array('api_name' => 'scene_all','message' => array('host_id'=>0)), 'get_main');
        $res = $this->get_main->request();
        if($res){
            if(!empty($res["RespBody"]["Result"])){
                $tmp['code'] = '0001';
                $tmp['msg'] = '有正在进行的实验';
                $tmp['data'] = [];
            }else{
                $tmp['code'] = '0000';
                $tmp['msg'] = '';
                $tmp['data'] = [];
            }
        }else{
                $tmp['code'] = '0201';
                $tmp['msg'] = '失败，请检查网络是否畅通';
                $tmp['data'] = [];
        }

        return $tmp;

    }


}