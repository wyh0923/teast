<?php

/**
 * Created by PhpStorm.
 * User: WKF
 * Date: 2016/8/23
 * Time: 14:34
 */
class Ctf_model extends CI_Model
{
    /*
     * 给出服务器视频地址
     *
     */
    public function get_ctf_url($ctfServerId,$ctfServerPort,$CtfUrl)
    {
        $ctfUrl = site_url();
        $this->load->library('Data_exchange', array('api_name' => 'get_ctf_url', 'message' => array('server_type'=>5)), 'get_ctf_url');
        $nodeInfoRes = $this->get_ctf_url->request();
        if ($nodeInfoRes["RespHead"]["ErrorCode"] == 0 && $nodeInfoRes["RespBody"]["Result"]["host_list"]) {
            $nodeList = $nodeInfoRes["RespBody"]["Result"]["host_list"];
            foreach($nodeList as $nodeItem) {
                if ($nodeItem["id"] == $ctfServerId) {
                    foreach($nodeItem["nat_list"] as $natItem) {
                        if ($natItem["int_port"] == $ctfServerPort){
                            $ctfUrl = "http://" . $natItem["root_router_ip"] . ":" . $natItem["ext_port"] . $CtfUrl;
                            break;
                        }
                    }
                    break;
                }
            }
        }
        return $ctfUrl;

    }

    /***
     * 获取所有ctfurl地址
     * @return array
     */
    public function get_ctf_all_url()
    {
        $ctfUrl = [];
        $this->load->library('Data_exchange', array('api_name' => 'get_ctf_url', 'message' => array('server_type'=>5)), 'get_ctf_url');
        $nodeInfoRes = $this->get_ctf_url->request();
        if ($nodeInfoRes["RespHead"]["ErrorCode"] == 0 && $nodeInfoRes["RespBody"]["Result"]["host_list"]) {
            $nodeList = $nodeInfoRes["RespBody"]["Result"]["host_list"];
            foreach($nodeList as $nodeItem) {
                    foreach($nodeItem["nat_list"] as $natItem) {
                            $ctfUrl[$nodeItem["id"]][$natItem["int_port"]] = "http://" . $natItem["root_router_ip"] . ":" . $natItem["ext_port"];
                    }
            }
        }
        return $ctfUrl;

    }

    /***
     * 获取CTF模板
     * @param $where
     * @return mixed
     */
    public function ctf_list($where)
    {
        $this->db->select('*');
        $this->db->from('ctf');
        if (isset($where['CreateTime']) && is_array($where['CreateTime']) && !empty($where['CreateTime']['starttime'])) {
            $this->db->where('CtfCreateTime >=', strtotime($where['CreateTime']['starttime']));
            $this->db->where('CtfCreateTime <=', strtotime($where['CreateTime']['endtime']));
        }
        if (!empty($where['search'])) {
            $this->db->group_start();
            $this->db->like(array('CtfName' => $where['search']));
            $this->db->group_end();
        }
        if (!empty($where['CtfType'])) {
            $this->db->where(array('CtfType' => $where['CtfType']));
        }
        if (!empty($where['AuthorID'])) {
            $this->db->where(array('AuthorID' => $where['AuthorID']));
        }
        $this->db->order_by('CtfID', 'DESC');//默认排序
        if (isset($where['limit'])) {
            $this->db->limit($where['limit']['limit'], $where['limit']['offset']);
        }
        $result = $this->db->get()->result_array();
        return $result;

    }

    /***
     * 获取CTF模板总数
     * @return int
     */
    public function ctf_list_count($where)
    {
        $this->db->select('count(1) as count');
        $this->db->from('ctf');
        if (isset($where['CreateTime']) && is_array($where['CreateTime']) && !empty($where['CreateTime']['starttime'])) {
            $this->db->where('CtfCreateTime >=', strtotime($where['CreateTime']['starttime']));
            $this->db->where('CtfCreateTime <=', strtotime($where['CreateTime']['endtime']));
        }
        if (!empty($where['search'])) {
            $this->db->group_start();
            $this->db->like(array('CtfName' => $where['search']));
            $this->db->group_end();
        }
        if (!empty($where['CtfType'])) {
            $this->db->where(array('CtfType' => $where['CtfType']));
        }
        if (!empty($where['AuthorID'])) {
            $this->db->where(array('AuthorID' => $where['AuthorID']));
        }
        $result = $this->db->get()->result_array();
        return isset($result[0]['count']) ? $result[0]['count'] : 0;

    }

    /***
     * 添加CTF模板
     * @return mixed
     */
    public function add_ctf($data)
    {
        return $this->db->insert('ctf', $data);
    }

    /***
     * 获取CTF模板信息
     * @param $id
     * @return array
     */
    public function get_ctf_info($id)
    {
        $this->db->select('*');
        $this->db->from('ctf');
        $this->db->where(array('CtfID' => $id));
        $this->db->limit(1);
        $result = $this->db->get()->result_array();
        return isset($result[0]) ? $result[0]: [];
    }

    /***
     * 删除CTF模板
     * @param $where
     * @return mixed
     */
    public function del_ctf($where)
    {
        $this->db->where_in('CtfID', $where);
        $this->db->delete( 'ctf');
        if ($this->db->affected_rows() > 0) {
            $tmp['code'] = '0000';
            $tmp['msg'] = 'success';
            $tmp['data'] = array();
        } else {
            $tmp['code'] = '0318';
            $tmp['msg'] = '删除失败';
            $tmp['data'] = array();

        }
        return $tmp;
    }

    /***
     * 修改CTF模板
     * @param $data
     * @param $where
     * @return mixed
     */
    public function update_ctf($data, $where)
    {
        $this->db->update('ctf', $data, $where);
        if ($this->db->affected_rows() > 0) {
            $tmp['code'] = '0000';
            $tmp['msg'] = 'success';
            $tmp['data'] = array();
        } else {
            $tmp['code'] = '0308';
            $tmp['msg'] = '未更新内容';
            $tmp['data'] = array();
        }
        return $tmp;
    }

    /****
     * 获取对应的ctf模板是否存在
     * @param $where
     * @return array
     */
    public function check_ctf($where)
    {
        $this->db->select('CtfID');
        $this->db->from('ctf');
        $this->db->where($where);
        $this->db->limit(1);
        $result = $this->db->get()->result_array();
        return isset($result[0]) ? $result[0]: [];

    }
}