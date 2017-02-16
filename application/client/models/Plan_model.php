<?php
/**
 * Created by PhpStorm.
 * User: liuqi
 * Date: 2016/8/30
 * Time: 16:35
 */

class Plan_model extends CI_Model
{
    /**
     * 获取方案体系有分页
     */
    public function get_plans($where)
    {
        $this->db->select('base.*, count(distinct(pp.PackageID)) as PackageCount, sum(IFNULL(pp.SectionNum,0))as SectionNum, sum(IFNULL(pp.PracticeSectionNum,0)) as TestNum , group_concat(distinct(IFNULL(pa.ArchitectureName,""))) as books')
            ->from('p_architecture as base')
            ->join('p_architecture as pa', 'pa.ArchitectureParent=base.ArchitectureID', 'left')
            ->join('p_architecture_package as pap', 'pap.ArchitectureID=pa.ArchitectureID','left')
            ->join('p_package as pp', 'pp.PackageID=pap.PackageID', 'left')
            ->group_by('base.ArchitectureID')
            ->where(array('base.ArchitectureParent'=>0, 'base.Author'=>$where['Author']));

        if (!empty($where['search'])) {
            $this->db->group_start();
            $this->db->like(array('base.ArchitectureName' => $where['search']));
            $this->db->group_end();
        }

        if (isset($where['sort'])) {
            $this->db->order_by($where['sort']['field'], $where['sort']['order']);
        }else{
            $this->db->order_by('base.ArchitectureID', 'DESC');//默认排序
        }

        if (isset($where['limit'])) {
            $this->db->limit($where['limit']['limit'], $where['limit']['offset']);
        }
        return $this->db->get()->result_array();
    }

    /**
     * 获取方案体系
     */
    public function get_plan($where)
    {
        $this->db->select('ArchitectureName')
            ->from('architecture')
            ->where($where);

        return $this->db->get()->result_array();
    }
    
    /***
     * 获取记录总数
     */
    public function get_count($where)
    {
        $this->db->select('count(1) as count');
        $this->db->from('architecture')->where(array('ArchitectureParent'=>'0', 'Author'=>$where['Author']));

        if (!empty($where['search'])) {
            $this->db->group_start();
            $this->db->like(array('ArchitectureName' => $where['search']));
            $this->db->group_end();
        }
        $result = $this->db->get()->result_array();
        return isset($result[0]['count']) ? $result[0]['count'] : 0;

    }

    /**
     * 新增方案
     */
    public function add_plan($data)
    {
        $this->db->select('ArchitectureName');
        $this->db->from('architecture');
        $this->db->where('ArchitectureName', $data['ArchitectureName']);
        $this->db->limit(1);

        $res = $this->db->get()->result_array();
        if(!empty($res))
        {
            $tmp['code'] = '0386';
            $tmp['msg'] = '';
            $tmp['data'] = '';
        }else{
            $this->db->insert('architecture', $data);

            $num = $this->db->affected_rows();
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
     * 修改方案名称
     */
    public function mod_plan($data, $where)
    {
        $this->db->select('ArchitectureName');
        $this->db->from('architecture');
        $this->db->where('ArchitectureName', $data['ArchitectureName']);
        $this->db->limit(1);

        $res = $this->db->get()->result_array();
        if(!empty($res))
        {
            $tmp['code'] = '0386';
            $tmp['msg'] = '';
            $tmp['data'] = '';
        }else{
            $this->db->where('ArchitectureID', $where['ArchitectureID']);
            $this->db->update('architecture', $data);

            $num = $this->db->affected_rows();
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

    /***
     * 删除方案
     */
    public function del_plan($pid)
    {
        $this->db->where('ArchitectureID', $pid);
        $this->db->delete('architecture');
        if ($this->db->affected_rows() > 0) {
            $res = $this->db->select('ArchitectureID')->from('architecture')->where(array('ArchitectureParent'=>$pid))->get()->result_array();
            if($res)
            {
                foreach ($res as $re) {
                    $this->db->delete('architecture_package', array('ArchitectureID'=>$re['ArchitectureID']));
                }
            }
            $this->db->delete('architecture', array('ArchitectureParent'=>$pid));

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

    /**
     * 获取体系
     */
    public function get_sys($where)
    {
        $this->db->select('ArchitectureID, ArchitectureName');
        $this->db->from('architecture');
        $this->db->where($where);
        return $this->db->get()->result_array();

    }

    /**
     * 统计课时
     */
    public function cout_sections($pid)
    {
        $result = $this->db->select('count(1)as SectionNum')
            ->from('p_architecture')
            ->join('p_architecture_package', 'p_architecture_package.ArchitectureID=p_architecture.ArchitectureID', 'left')
            ->join('p_package as pa', 'pa.PackageID=p_architecture_package.PackageID', 'left')
            ->join('p_package as pb', 'pa.PackageID=pb.PackageParent', 'left')
            ->join('p_package_course', 'pb.PackageID=p_package_course.PackageID', 'left')
            ->join('p_course', 'p_course.CourseID=p_package_course.CourseID', 'left')
            ->join('p_course_section', 'p_course.CourseID=p_course_section.CourseID', 'left')
            ->join('p_section', 'p_section.SectionID=p_course_section.SectionID', 'left')
            ->where(array('p_architecture.ArchitectureParent'=>$pid))
            ->get()->result_array();
        return isset($result[0]['SectionNum']) ? $result[0]['SectionNum'] : 0;

    }
    
    /**
     * 选择课程
     */
    public function opt_course($data, $where)
    {
       foreach ($where as $w)
       {
           $d = array('PackageID'=>$w, 'ArchitectureID' => $data['aid']);
           $this->db->select('ArchitectureID');
           $this->db->from('architecture_package');
           $this->db->where($d);
           $this->db->limit(1);
           $res = $this->db->get()->result_array();
           if(empty($res))
           { $this->db->insert('architecture_package', $d); }
       }
        
        $num = $this->db->affected_rows();
        if ($num >= 1) {
            $tmp['code'] = '0000';
            $tmp['msg'] = 'success';
            $tmp['data'] = '';
        } else {
            $tmp['code'] = '0386';
            $tmp['msg'] = '';
            $tmp['data'] = '';
        }
        
        return $tmp;
    }

    /**
     * 编辑体系名称及所属方案
     */
    public function mod_sysname($data, $where)
    {
        $this->db->where($where);
        $this->db->update('architecture', $data);

        $num = $this->db->affected_rows();
        if ($num >= 1) {
            $tmp['code'] = '0000';
            $tmp['msg'] = 'success';
            $tmp['data'] = '';
        } else {
            $tmp['code'] = '0386';
            $tmp['msg'] = '';
            $tmp['data'] = '';
        }

        return $tmp;
    }

    /***
     * 删除体系
     */
    public function del_sys($data)
    {
        $this->db->delete('architecture', $data);
        if ($this->db->affected_rows() > 0) {
            $this->db->delete('architecture_package', $data);

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
     * 删除课程
     */
    public function del_course($data)
    {
        $this->db->delete('architecture_package', $data);
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















}