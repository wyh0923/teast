<?php

/**
 * Created by PhpStorm.
 * User: qirupeng
 * Date: 2016/8/30
 * Time: 11:27
 */
class Scene_model extends CI_Model
{
    /***
     * 场景模板列表
     * @param array $param
     * @return array
     */
    public function scene_list($param = array())
    {
        $result = array();
        $this->load->library('Data_exchange', array('api_name' => 'scene_list', 'message' => $param), 'get_sub');
        $sub_node = $this->get_sub->request();
        if ($sub_node && $sub_node["RespHead"]["ErrorCode"] === 0) {
            $result = $sub_node['RespBody']['Result'];
        }
        return $result;
    }

    /***
     * 获取场景模板信息
     * @param $id
     * @return array
     */
    public function get_sceneinfo($id)
    {
        $result = array();
        $this->load->library('Data_exchange', array('api_name' => 'scene_info', 'message' => array('scene_tpl_uuid' => $id)), 'get_sub');
        $sub_node = $this->get_sub->request();
        if ($sub_node && $sub_node["RespHead"]["ErrorCode"] === 0) {
            $result = $sub_node['RespBody']['Result']['SceneTemplate'][0];
        }
        return $result;

    }

    /***
     * 检测场景是否存在
     * @param $name
     * @return array
     */
    public function check_scene_name($name)
    {
        $result = array();
        $this->load->library('Data_exchange', array('api_name' => 'check_scene_name', 'message' => array('scene_name' => $name)), 'get_sub');
        $sub_node = $this->get_sub->request();
        if ($sub_node && $sub_node["RespHead"]["ErrorCode"] === 0) {
            $result = $sub_node['RespBody']['Result']['SceneTemplate'];
        }
        return $result;

    }

    /***
     * 创建场景模板
     * @param $data
     * @return mixed
     */
    public function add_scene($data)
    {
        $this->load->library('Data_exchange', array('api_name' => 'create_scenarios', 'message' => $data), 'get_scene');
        $res = $this->get_scene->request();
        if ($res && $res["RespHead"]["ErrorCode"] === 0) {
            $tmp['code'] = '0000';
            $tmp['msg'] = 'success';
            $tmp['data'] = [];
        } else {
            $tmp['code'] = 'error';
            $tmp['msg'] = isset($res['RespHead']['Message']) ? $res['RespHead']['Message'] : '创建失败';
            $tmp['data'] = [];
        }
        return $tmp;

    }

    /***
     * 删除场景模板
     * @param $id
     * @return bool
     */
    public function del_scene_tpl($id)
    {
        $this->load->library('Data_exchange', array('api_name' => 'del_scene_tpl', 'message' => ''), 'get_scene');
        $sub_node = $this->get_scene->request(array($id));
        if ($sub_node && $sub_node["RespHead"]["ErrorCode"] === 0) {
            return TRUE;
        }
        return FALSE;

    }

    /***
     * 获取虚拟机模板列表
     * @param $param
     * @return array
     */
    public function get_vm_list($param)
    {
        $result = array();
        $this->load->library('Data_exchange', array('api_name' => 'get_vm_list', 'message' => $param), 'get_sub');
        $sub_node = $this->get_sub->request();
        if ($sub_node && $sub_node["RespHead"]["ErrorCode"] === 0) {
            $result = $sub_node['RespBody']['Result'];
        }
        return $result;

    }

    /***
     * 获取操作系统类别列表
     * @return array
     */
    public function get_os_type()
    {
        $result = array();
        $this->load->library('Data_exchange', array('api_name' => 'os_type', 'message' => ''), 'get_os');
        $sub_node = $this->get_os->request();
        if ($sub_node && $sub_node["RespHead"]["ErrorCode"] === 0) {
            $result = $sub_node['RespBody']['Result'];
        }
        return $result;

    }

    /***
     * 获取虚拟机模板信息
     * @param $code
     * @param $host_id
     * @return array
     */
    public function get_vm_info($code, $host_id)
    {
        $result = array();
        $this->load->library('Data_exchange', array('api_name' => 'get_vm_info', 'message' => array('vm_tpl_uuid'=>$code)), 'get_vm');
        $sub_node = $this->get_vm->request(array($host_id));
        if ($sub_node && $sub_node["RespHead"]["ErrorCode"] === 0) {
            $result = $sub_node['RespBody']['Result']['VmTemplate'][0];
        }
        return $result;

    }

    /***
     * 删除虚拟机模板
     * @param $code
     * @param $host_id
     * @return array
     */
    public function del_vm($code, $host_id)
    {
        $data = array(
            'json' => '{"v_vmtemplate":{"vm_tpl_uuid":"'.$code.'"}}'
        );
        $this->load->library('Data_exchange', array('api_name' => 'del_vm', 'message' => $data), 'del_vm');
        $res = $this->del_vm->request(array($host_id));
        if ($res && $res["RespHead"]["ErrorCode"] === 0) {
            $tmp['code'] = '0000';
            $tmp['msg'] = 'success';
            $tmp['data'] = [];
        } else {
            $tmp['code'] = 'error';
            $tmp['msg'] = isset($res['RespHead']['Message']) ? $res['RespHead']['Message'] : '删除失败';
            $tmp['data'] = [];
        }
        return $tmp;

    }
    /***
     * 获取虚拟机上传目录
     * @return array
     */
    public function get_mount_path()
    {
        $result = array();
        $this->load->library('Data_exchange', array('api_name' => 'mount_path', 'message' => ''), 'get_path');
        $sub_node = $this->get_path->request();
        if ($sub_node && $sub_node["RespHead"]["ErrorCode"] === 0) {
            $result = $sub_node['RespBody']['Result']['Host'];
        }
        return $result;

    }

    /***
     * 创建虚拟机
     * @param $node_id
     * @param $data array array('json'=>'')
     * @return mixed
     */
    public function create_vm($node_id, $data)
    {
        $this->load->library('Data_exchange', array('api_name' => 'create_vm', 'message' => $data), 'create_vm');
        $res = $this->create_vm->request(array($node_id));
        if ($res && $res["RespHead"]["ErrorCode"] === 0) {
            $tmp['code'] = '0000';
            $tmp['msg'] = 'success';
            $tmp['data'] = [];
        } else {
            $tmp['code'] = 'error';
            $tmp['msg'] = isset($res['RespHead']['Message']) ? $res['RespHead']['Message'] : '创建失败';
            $tmp['data'] = [];
        }
        return $tmp;

    }

    /***
     * 更新虚拟机模板
     * @param $node_id
     * @param $data
     * @return mixed
     */
    public function update_vm($node_id, $data)
    {
        $this->load->library('Data_exchange', array('api_name' => 'update_vm', 'message' => $data), 'update_vm');
        $res = $this->update_vm->request(array($node_id));
        if ($res && $res["RespHead"]["ErrorCode"] === 0) {
            $tmp['code'] = '0000';
            $tmp['msg'] = 'success';
            $tmp['data'] = [];
        } else {
            $tmp['code'] = 'error';
            $tmp['msg'] = isset($res['RespHead']['Message']) ? $res['RespHead']['Message'] : '更新失败';
            $tmp['data'] = [];
        }
        return $tmp;

    }

}