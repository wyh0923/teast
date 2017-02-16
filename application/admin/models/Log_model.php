<?php

/**
 * Created by PhpStorm.
 * User: qirupeng
 * Date: 2016/8/5
 * Time: 17:41
 */
class Log_model extends CI_Model
{
    /***
     * 获取日志
     * @param $where
     * @return mixed
     */
    public function get_log($where)
    {
        $this->db->select('LogTaskName,LogContent,LogTypeID,l.CreateTime,UserName');
        $this->db->from('log as l');
        $this->db->join('user as u','l.UserID=u.UserID');
        if (isset($where['CreateTime']) && is_array($where['CreateTime']) && !empty($where['CreateTime']['starttime'])) {
            $this->db->where('l.CreateTime >=',strtotime($where['CreateTime']['starttime']));
            $this->db->where('l.CreateTime <=',strtotime($where['CreateTime']['endtime']));
        }
        if (isset($where['search'])){
            $this->db->like(array('LogContent' => $where['search']));
        }
        if (isset($where['limit'])) {
            $this->db->limit($where['limit']['limit'], $where['limit']['offset']);
        }
        $this->db->order_by('l.LogID', 'DESC');
        $result = $this->db->get()->result_array();
        return $result;
    }

    /***
     * 获取日志记录总数
     * @param $where
     * @return int
     */
    public function get_count($where)
    {
        $this->db->select('count(1) as count');
        $this->db->from('log as l');
        $this->db->join('user as u','l.UserID=u.UserID');
        if (isset($where['CreateTime']) && is_array($where['CreateTime']) && !empty($where['CreateTime']['starttime'])) {
            $this->db->where('l.CreateTime >=',strtotime($where['CreateTime']['starttime']));
            $this->db->where('l.CreateTime <=',strtotime($where['CreateTime']['endtime']));
        }
        if (!empty($where['search'])){
            $this->db->like(array('LogContent' => $where['search']));
        }
        $result = $this->db->get()->row_array();
        return isset($result['count']) ? $result['count'] : 0;
    }

}