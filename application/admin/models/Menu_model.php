<?php

/**
 * Created by PhpStorm.
 * User: qirupeng
 * Date: 2016/7/28
 * Time: 9:29
 */
class Menu_model extends CI_Model
{
    /**
     * 获取对应角色的菜单
     * @param int $role_id 角色ID
     * @return array
     */
    public function get_menu($role_id)
    {
        $data = array();
        $this->db->select('*');
        $this->db->from('menu');
        $this->db->where(array('role_id' => $role_id, 'status' => 1));//确定要显示的
        $this->db->order_by('index ASC,id ASC');
        $result = $this->db->get()->result_array();
        foreach ($result as $row)
        {
            $data[$row['pid']][$row['id']] = $row;
        }

        return $data;
    }

}