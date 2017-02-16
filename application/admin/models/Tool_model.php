<?php

/**
 * Created by PhpStorm.
 * User: qirupengg
 * Date: 2016/9/14
 * Time: 11:50
 */
class Tool_model extends CI_Model
{
    private $tool_db;//工具库

    /***
     * 初始化工具库配置信息
     * Tool_model constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $db_config = $this->get_tool_db_config();
        $this->tool_db = $this->load->database ($db_config, TRUE );
    }

    /****
     * 获取工具数据库的配置
     * @return mixed
     */
    public function get_tool_db_config()
    {
        $this->load->library('Data_exchange', array('api_name' => 'get_tool_db', 'message' => ''), 'get_main');
        $res = $this->get_main->request();
        if ($res && $res["RespHead"]["ErrorCode"] === 0) {
            $nodeList = $res["RespBody"]["Result"]["host_list"];
            foreach($nodeList as $nodeItem) {
                foreach($nodeItem["nat_list"] as $natItem) {
                    if ($natItem["int_port"] == 3306){
                        $config['hostname'] =  $natItem["root_router_ip"] ;
                        $config['port']  = $natItem["ext_port"]  ;
                        break;
                    }
                }
            }
        }
        $config['username'] = 'root';
        $config['password'] = 'bachangV3.0@mysql';
        $config['database'] = 'tooldb';
        $config['dbdriver'] = 'mysqli';
        $config['dbprefix'] = 'p_';
        $config['pconnect'] = FALSE;
        $config['db_debug'] = FALSE;
        $config['cache_on'] = FALSE;
        $config['cachedir'] = '';
        $config['char_set'] = 'utf8';
        $config['dbcollat'] = 'utf8_general_ci';
        $config['swap_pre'] = '';
        $config['autoinit'] = FALSE;
        $config['stricton'] = FALSE;
        return $config;
    }
    
    /**
     * 获取符合条件的工具
     */
    public function get_all_tools($where)
    {
        $this->tool_db->select('p_tool.ID, p_tool.toolName, p_tool.updateTime, p_toolclassify.classifyName');
        $this->tool_db->from('p_tool');
        $this->tool_db->join('p_toolclassify', 'p_tool.classifyCode = p_toolclassify.ID', 'left');

        if (!empty($where['search'])) {
            $this->tool_db->like(array('p_tool.toolName' => $where['search']));
        }

        if(!empty($where['typeid']))
        {
            $this->tool_db->where('p_toolclassify.ID', $where['typeid']);
        }

        if (isset($where['limit'])) {
            $this->tool_db->limit($where['limit']['limit'], $where['limit']['offset']);
        }

        if (isset($where['sort'])) {
            $this->tool_db->order_by($where['sort']['field'], $where['sort']['order']);
        }else{
            $this->tool_db->order_by('p_tool.ID', 'DESC');//默认排序
        }

        $res = $this->tool_db->get()->result_array();

        return $res;
    }

    /**
     * 获取符合条件的分类
     */
    public function get_all_types($where)
    {
        $this->tool_db->select('ID, classifyName, classifyParent')
            ->from('p_toolclassify');
        $this->tool_db->where(array('classifyParent'=>0));

        if (!empty($where['search'])) {
            $this->tool_db->like(array('classifyName' => $where['search']));
        }

        if (isset($where['limit'])) {
            $this->tool_db->limit($where['limit']['limit'], $where['limit']['offset']);
        }

        $res = $this->tool_db->get()->result_array();

        return $res;
    }

    /**
     * 获取一级分类
     */
    public function get_first_types()
    {
        $this->tool_db->select('ID, classifyName')
            ->from('p_toolclassify')->where(array('classifyParent'=>0));

        $res = $this->tool_db->get()->result_array();
        //echo $this->db->last_query();

        return $res;
    }


    /**
     * 统计一级分类
     */
    public function get_first_num()
    {
        $this->db->select('count(1) as count')
            ->from('tool_types');

        $this->db->where('Pid', 0);

        $result = $this->db->get()->result_array();

        return isset($result[0]['count']) ? $result[0]['count'] : 0;

    }


    /***
     * 获取工具总数
     * @param $where
     * @return int
     */
    public function get_count($where)
    {
        $this->tool_db->select('count(1) as count');
        $this->tool_db->from('p_tool');
        $this->tool_db->join('p_toolclassify', 'p_tool.classifyCode = p_toolclassify.ID', 'left');

        if (!empty($where['search'])) {
            $this->tool_db->like(array('p_tool.toolName' => $where['search']));
        }

        if(!empty($where['typeid']))
        {
            $this->tool_db->where('p_toolclassify.ID', $where['typeid']);
        }
        $result = $this->tool_db->get()->result_array();
        return isset($result[0]['count']) ? $result[0]['count'] : 0;
    }

     /***
     * 获取分类总数
     * @param $where
     * @return int
     */
    public function get_count_type($where)
    {
        $this->tool_db->select('count(1) as count');
        $this->tool_db->from('p_toolclassify')->where(array('classifyParent'=>0));

        if (isset($where['search'])) {
            $this->tool_db->like(array('classifyName' => $where['search']));
        }

        $result = $this->tool_db->get()->result_array();
        return isset($result[0]['count']) ? $result[0]['count'] : 0;
    }

    /**
     * 获取分类总数
     */
    public function count_children($pid)
    {
        $this->db->select('count(1) as count');
        $this->db->from('tool_types');
        $this->db->where('Pid', $pid);
        $this->db->limit(1);
        $result = $this->db->get()->result_array();

        return isset($result[0]['count']) ? $result[0]['count'] : 0;
    }

    /***
     * 删除工具
     */
    public function del_tool($data)
    {
        $this->tool_db->where('ID', $data);
        $this->tool_db->delete('tool');
        if ($this->tool_db->affected_rows() > 0) {
            $tmp['code'] = '0000';
            $tmp['msg'] = 'success';
            $tmp['data'] = array();
        } else {
            $tmp['code'] = 'error';
            $tmp['msg'] = '删除失败';
            $tmp['data'] = array();

        }
        return $tmp;
    }

    /**
     * 某个工具详情
     */
    public function get_detail($pid)
    {
        $this->tool_db->select('p_tool.toolName,p_tool.description,p_toolclassify.classifyName');
        $this->tool_db->from('p_tool');
        $this->tool_db->join('p_toolclassify', 'p_tool.classifyCode = p_toolclassify.ID', 'left');
        $this->tool_db->where('p_tool.ID', $pid);

        $res = $this->tool_db->get()->result_array();
        return $res[0];
    }

    /**
     * 获取工具类型
     */
    public function get_tooltypes()
    {
        return $this->tool_db->select('ID, ClassifyName, classifyParent')->from('toolclassify')->get()->result_array();
    }

    /***
     * 获取工具上传目录
     * @return array
     */
    public function get_mount_path()
    {
        $result = array();
        $this->load->library('Data_exchange', array('api_name' => 'get_tool_db', 'message' => ''), 'get_path');
        $sub_node = $this->get_path->request();
        //p($sub_node);die;
        if ($sub_node["RespHead"]["ErrorCode"] == 0) {
            $result = $sub_node["RespBody"]["Result"]["host_list"];
        }
        return $result;

    }

    /**
     * 添加工具
     */
    public function add_tool($data)
    {
        $res = $this->tool_db->select('ID')->from('tool')->where(array('toolName'=>$data['toolName']))->get()->result_array();

        if(empty($res))
        {
            $this->tool_db->insert('tool', $data);
            $num = $this->tool_db->affected_rows();
            if ($num > 0) {
                $tmp['code'] = '0000';
                $tmp['msg'] = 'success';
                $tmp['data'] = '';
            } else {
                $tmp['code'] = '0386';
                $tmp['msg'] = '';
                $tmp['data'] = '';
            }
        } else {
            $tmp['code'] = '0387';
            $tmp['msg'] = '';
            $tmp['data'] = '';
        }

        return $tmp;
    }

    /**
     * 修改类名
     */
    public function mod_type($data, $where)
    {
        $this->tool_db->select('count(1) as count');
        $this->tool_db->from('toolclassify');
        $this->tool_db->where($data);
        $this->tool_db->limit(1);
        $res = $this->tool_db->get()->result_array();
        if($res[0]['count'] > 0)
        {
            $tmp['code'] = '0308';
            $tmp['msg'] = '';
            $tmp['data'] = array();
        }else{
            $this->tool_db->update('toolclassify', $data, $where);
            if ($this->tool_db->affected_rows() > 0) {
                $tmp['code'] = '0000';
                $tmp['msg'] = 'success';
                $tmp['data'] = array();
            } else {
                $tmp['code'] = '0308';
                $tmp['msg'] = '';
                $tmp['data'] = array();
            }
        }

        return $tmp;

    }

    /**
     * 修改工具名
     */
    public function mod_tname($data, $where)
    {
        $this->tool_db->select('count(1) as count');
        $this->tool_db->from('tool');
        $this->tool_db->where($data);
        $this->tool_db->limit(1);
        $res = $this->tool_db->get()->result_array();
        if($res[0]['count'] > 0)
        {
            $tmp['code'] = '0308';
            $tmp['msg'] = '';
            $tmp['data'] = array();
        }else{
            $this->tool_db->update('tool', $data, $where);
            if ($this->tool_db->affected_rows() > 0) {
                $tmp['code'] = '0000';
                $tmp['msg'] = 'success';
                $tmp['data'] = array();
            } else {
                $tmp['code'] = '0308';
                $tmp['msg'] = '';
                $tmp['data'] = array();
            }
        }

        return $tmp;

    }



    /**
     * 删除分类
     */
    public function del_type($data)
    {
        $this->tool_db->select('ID');
        $this->tool_db->from('toolclassify');
        $this->tool_db->where('classifyParent', $data['ID']);
        $res = $this->tool_db->get()->result_array();

        if(!empty($res))
        {
            foreach ($res as $v)
            {
                $this->tool_db->where($v);
                $this->tool_db->delete('toolclassify');
            }
        }

        $this->tool_db->where($data);
        $this->tool_db->delete('toolclassify');
        if ($this->tool_db->affected_rows() > 0) {
            $tmp['code'] = '0000';
            $tmp['msg'] = 'success';
            $tmp['data'] = array();
        } else {
            $tmp['code'] = 'error';
            $tmp['msg'] = '';
            $tmp['data'] = array();

        }
        return $tmp;
    }

    /**
     * 新增分类
     */
    public function add_type($data)
    {
        $this->tool_db->select('classifyName');
        $this->tool_db->from('p_toolclassify');
        $this->tool_db->where('classifyName', $data['classifyName']);
        $this->tool_db->limit(1);

        $res = $this->tool_db->get()->result_array();
        if(!empty($res))
        {
            $tmp['code'] = '0386';
            $tmp['msg'] = '';
            $tmp['data'] = '';
        }else{
            $this->tool_db->insert('p_toolclassify', $data);

            $num = $this->tool_db->affected_rows();
            if ($num >= 1) {
                $tmp['code'] = '0000';
                $tmp['msg'] = 'success';
                $tmp['data'] = '';
            } else {
                $tmp['code'] = '0386';
                $tmp['msg'] = '';
                $tmp['data'] = '';
            }
        }
        
        return $tmp;
    }

    /**
     * 获取子类
     */
    public function get_children($where)
    {
        $this->tool_db->select('ID, classifyName');
        $this->tool_db->from('toolclassify');
        $this->tool_db->where($where);
        $res = $this->tool_db->get()->result_array();

        if(!empty($res))
        {
            $tmp['code'] = '0000';
            $tmp['msg'] = 'success';
            $tmp['data'] = $res;
        } else {
            $tmp['code'] = '0386';
            $tmp['msg'] = '';
            $tmp['data'] = '';
        }

        return $tmp;
    }

    /**
     * 类下是否有工具
     */
    public function is_has_tool($where)
    {

        $this->tool_db->select('classifyParent');
        $this->tool_db->from('toolclassify');
        $this->tool_db->where(array('ID'=>$where['classifyCode']));
        $data = $this->tool_db->get()->result_array();

        if($data[0]['classifyParent'] != 0)
        {
            $this->tool_db->select('classifyCode');
            $this->tool_db->from('tool');
            $this->tool_db->where($where);
            $res = $this->tool_db->get()->result_array();

        } else {
            echo 12312;
            $this->tool_db->select('ID');
            $this->tool_db->from('p_toolclassify');
            $this->tool_db->join('p_tool','p_tool.classifyCode=p_toolclassify.ID');
            $this->tool_db->where(array('p_toolclassify.classifyParent'=>$where['classifyCode']));
            echo $this->tool_db->last_array();die;
            $res = $this->tool_db->get()->result_array();
        }

        p($res);die;

        if(!empty($res))
        {
            $tmp['code'] = '0000';
            $tmp['msg'] = 'success';
            $tmp['data'] = $res;
        } else {
            $tmp['code'] = '0386';
            $tmp['msg'] = '';
            $tmp['data'] = '';
        }

        return $tmp;
    }






















}
