<?php

/**
 * Created by PhpStorm.
 * User: kouyunxia
 * Date: 2016/8/3
 * Time: 15:00
 */
class Architecture_model extends CI_Model{

    
    /**
     * 获取体系
     * User:kouyunxia
     * @param array  ArchitectureCode 体系编号 或 ArchitectureParent 父类编号
     * @return array
     */
    public function get_arch($where){
        $this->db->select('ArchitectureID,ArchitectureName,ArchitectureParent');
        $this->db->from('architecture');
        $this->db->where($where);
        $this->db->	order_by('index','asc');
        $result = $this->db->get()->result_array();

        return $result;
    }

    /**
     * 判断参数是否正确（不确定的参数）
     * @param array  model 加载模型 action 方法 value 值
     * 返回值 1 正确  2 不传值  3 不存在或错误
     */
    public function judge($condition)
    {
        do {
            // 判断传值不为空 并且 字符长度是否正确
            // 整数类型是否正确  !(intval($id) > 0)
            if ($condition['ArchitectureID'] == '') {
                return 2; break;
            }

            if(intval($condition['ArchitectureID']) < 0){
                return 3; break;
            }

            $result = $this->get_arch($condition);
            if (count($result) > 0) {
                return 1; break;
            } else {
                return 3; break;
            }

        } while (FALSE);
    }

}