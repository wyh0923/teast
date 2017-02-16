<?php

/**
 * Created by PhpStorm.
 * User: kyx
 * Date: 2016/8/15
 * Time: 10:00
 */
class Study_model extends CI_Model{

    /**
     * 获取学习列表
     * User:kyx
     * @param array $where 条件 $search 搜索 $offset $num 分页
     * @return array
     */
    public function study_list($where,$search='',$num='',$offset=''){
        $this->db->select('t.TaskID,t.ClassID,c.ClassName,u.UserName,t.TaskName,t.TaskProcess,p.PackageName,p.PackageImg,p.SectionNum,p.PackageDiff,p.PackageDesc,p.PackageAuthor,TaskEndTime,TaskStartTime,TaskScore');
        $this->db->from('task as t');
        $this->db->join('package as p','t.PackageID = p.PackageID','left');
        $this->db->join('user as u','u.UserID = t.TeacherID','left');
        $this->db->join('class as c','t.ClassID = c.ClassID','left');
        $this->db->where($where);
        if($search != ''){
            $this->db->like("t.TaskName",$search);
        }
        $this->db->order_by('t.TaskID', 'desc');
        if($offset >= 0 && $num != '') {
            $this->db->limit($num, $offset);
        }
        $result = $this->db->get()->result_array();
        return $result;
    }
    
}