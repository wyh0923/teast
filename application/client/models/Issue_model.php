<?php

/**
 * Created by PhpStorm.
 * User: qirupeng
 * Date: 2016/9/5
 * Time: 16:07
 */
class Issue_model extends CI_Model
{
    /***
     * 获取下发信息
     * @param $where
     * @return mixed
     */
    public function get_instance($where)
    {
        $this->db->select('*');
        $this->db->from('issue');
        $this->db->where($where);
        $this->db->order_by('CreateTime','DESC');
        $this->db->limit(1);
        $result = $this->db->get()->result_array();
        return $result;

    }
    /***
     * 更新
     * @param $where
     * @param $data
     * @return bool
     */
    public function update_instance($where, $data)
    {
        $flag = $this->db->update('issue',$data,$where);
        if($flag){
            return TRUE;
        }else{
            return FALSE;
        }
    }

    /***
     * 插入测试记录
     * @param $data
     */
    public function add_instance($data)
    {
        $this->db->insert('issue', $data);
    }

    /***
     * 检测场景是否在节点上存在
     * @param $id
     * @return mixed
     */
    public function check_scene_in_node($id)
    {
        $this->load->library('Data_exchange', array('api_name' => 'judge_scene', 'message' => ''), 'check_scene');
        $res = $this->check_scene->request(array($id));
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

}