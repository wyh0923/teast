<?php

/**
 * Created by PhpStorm.
 * User: kyx
 * Date: 2016/8/3
 * Time: 10:30
 */
class Log_model extends CI_Model{


    /**
     * 获取对应学员的日志
     * User:kyx
     * @param array UserCode 学员编号 search 搜索  分页 page offset
     * @return array
     */
    public function get_log($data){
        $this->db->select('LogTaskName,LogContent,LogTypeID,CreateTime');
        $this->db->from('log');
        $this->db->where('UserID', $data['UserID']);
        //搜索
        if($data['search']){
            $this->db->like('LogContent', $data['search']);
        }
        $this->db->order_by('CreateTime DESC');

        //分页
        if(isset($data['num'])){
            $this->db->limit($data['num'],$data['offset']);
        }
        $result = $this->db->get()->result_array();
        return $result;
    }
    /**
     * 添加日志
     * User:kyx
     * @param array
     * @return TRUE OR FALSE
     */
    public function insert($data){
        return $this->db->insert('log', $data);
    }

}