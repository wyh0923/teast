<?php
/**
 * Created by PhpStorm.
 * User: liuqi
 * Date: 2016/8/29
 * Time: 16:29
 */

class Book_model extends CI_Model
{
    /**
     * 获取培训方案
     */
    public function get_plan($where)
    {
        $this->db->select('ArchitectureID, ArchitectureName, ArchitectureParent')
            ->from('architecture')
            ->where($where);

        return $this->db->get()->result_array();
    }

    /**
     * 获取作者
     */
    public function get_author()
    {
        return $this->db->select('UserID, UserName')
            ->from('user')
            ->where('UserName IN (SELECT PackageAuthor FROM p_package)', NULL, FALSE)
            ->get()->result_array();
    }

    /**
     * 获取课程有分页
     */
    public function get_course($where)
    {
        $this->db->select('p_package.PackageID, p_package.PackageAuthor, p_package.PackageName, p_package.PackageDiff, p_package.PackageStatus, p_package.SectionNum, p_package.PracticeSectionNum');
        $this->db->where(array('p_package.PackageParent'=>'0'));

        if(empty($where['pid']) && empty($where['aid']))
        {
            $this->db->from('p_package');
        }

        if(!empty($where['pid']) && empty($where['aid']))
        {
            $this->db->from('p_architecture')
                ->join('p_architecture_package', 'p_architecture_package.ArchitectureID=p_architecture.ArchitectureID')
                ->join('p_package', 'p_package.PackageID=p_architecture_package.PackageID')
                ->where('p_architecture.ArchitectureParent', $where['pid']);
        }

        if(!empty($where['aid']))
        {
            $this->db->from('p_architecture_package')
                ->join('p_package', 'p_package.PackageID=p_architecture_package.PackageID')
                ->where('p_architecture_package.ArchitectureID', $where['aid']);
        }

        if (isset($where['limit'])) {
            $this->db->limit($where['limit']['limit'], $where['limit']['offset']);
        }

        if (!empty($where['search'])) {
            $this->db->group_start();
            $this->db->like(array('p_package.PackageName' => $where['search']));
            $this->db->group_end();
        }

        if (isset($where['sort'])) {
            $this->db->order_by($where['sort']['field'], $where['sort']['order']);
        }else{
            $this->db->order_by('p_package.PackageID', 'DESC');//默认排序
        }

        $this->db->group_by('p_package.PackageID');
        return $this->db->get()->result_array();
        //$this->db->last_query();
    }

    /*
     * 获取引用数
     */
    public function get_quote($pid)
    {
        $this->db->select('count(1) as count');
        $this->db->from('architecture_package');
        $this->db->where(array('PackageID'=>$pid));
        $result = $this->db->get()->result_array();
        return isset($result[0]['count']) ? $result[0]['count'] : 0;
    }

    /**
     * 引用
     */
    public function quote_list($cid)
    {
        $res = $this->db->select('p_package.PackageName,p_architecture.ArchitectureName')
            ->where('p_package.PackageID',$cid)
            ->from('p_package')
            ->join('p_architecture_package','p_package.PackageID = p_architecture_package.PackageID')
            ->join('p_architecture','p_architecture_package.ArchitectureID = p_architecture.ArchitectureID')
            ->get()->result_array();

        if($this->db->affected_rows()>0)
        {
            $tmp['code'] = '0000';
            $tmp['msg'] = '';
            $tmp['data'] = $res;
        } else {
            $tmp['code'] = '0386';
            $tmp['msg'] = '';
            $tmp['data'] = '';
        }

        return $tmp;
    }

    /***
     * 获取记录总数
     */
    public function get_count($where)
    {
        $this->db->select('count(1) as count');
        $this->db->where(array('p_package.PackageParent'=>'0'));

        if(empty($where['pid']) && empty($where['aid']))
        {
            $this->db->from('p_package');
        }

        if(!empty($where['pid']) && empty($where['aid']))
        {
            $this->db->from('p_architecture')
                ->join('p_architecture_package', 'p_architecture_package.ArchitectureID=p_architecture.ArchitectureID')
                ->join('p_package', 'p_package.PackageID=p_architecture_package.PackageID')
                ->where('p_architecture.ArchitectureParent', $where['pid']);
        }

        if(!empty($where['aid']))
        {
            $this->db->from('p_architecture_package')
                ->join('p_package', 'p_package.PackageID=p_architecture_package.PackageID')
                ->where('p_architecture_package.ArchitectureID', $where['aid']);
        }

        if (!empty($where['search'])) {
            $this->db->group_start();
            $this->db->like(array('PackageName' => $where['search']));
            $this->db->group_end();
        }
        
        $result = $this->db->get()->result_array();
        //echo $this->db->last_query();die;
        return isset($result[0]['count']) ? $result[0]['count'] : 0;

    }

    public function get_ajax_course($where)
    {
        $this->db->select('p_package.PackageID, p_package.PackageAuthor, p_package.PackageName, p_package.PackageDiff, p_package.PackageStatus, p_package.SectionNum, p_package.PracticeSectionNum');
        $this->db->where(array('p_package.PackageParent'=>'0'));

        if(($where['pid']==0) && ($where['aid']==0) && ($where['ctype']==0) && empty($where['author']))
        {
            $this->db->from('p_package');
        }

        if(($where['pid']!=0) && ($where['aid']==0))
        {
            $this->db->from('p_architecture')
                ->join('p_architecture_package', 'p_architecture_package.ArchitectureID=p_architecture.ArchitectureID')
                ->join('p_package', 'p_package.PackageID=p_architecture_package.PackageID')
                ->where('p_architecture.ArchitectureParent', $where['pid']);
        }

        if(($where['aid']!=0))
        {
            $this->db->from('p_architecture_package')
                ->join('p_package', 'p_package.PackageID=p_architecture_package.PackageID')
                ->where('p_architecture_package.ArchitectureID', $where['aid']);
        }

        if (isset($where['limit'])) {
            $this->db->limit($where['limit']['limit'], $where['limit']['offset']);
        }

        if (($where['ctype']!=0)) {
            if(($where['pid']==0) && ($where['aid']==0) && empty($where['author']))
            {
                $this->db->from('p_package');
                $this->db->where(array('p_package.PackageSectionType'=>$where['ctype']));
            } else {
                $this->db->where(array('p_package.PackageSectionType'=>$where['ctype']));
            }
        }

        if (!empty($where['author'])) {
            if(($where['pid']==0) && ($where['aid']==0) && ($where['ctype']==0))
            {
                $this->db->from('p_package');
                $this->db->where(array('p_package.PackageAuthor'=>$where['author']));
            } else {
                $this->db->where(array('p_package.PackageAuthor'=>$where['author']));
            }
        }

        if(($where['pid']==0) && ($where['aid']==0) && ($where['ctype']!=0) && !empty($where['author']))
        {
            $this->db->from('p_package');
            $this->db->where(array('p_package.PackageSectionType'=>$where['ctype']));
            $this->db->where(array('p_package.PackageAuthor'=>$where['author']));

        }


        if (!empty($where['search'])) {
            $this->db->group_start();
            $this->db->like(array('p_package.PackageName' => $where['search']));
            $this->db->group_end();
        }

        if (isset($where['sort'])) {
            $this->db->order_by($where['sort']['field'], $where['sort']['order']);
        }

        return $this->db->get()->result_array();
    }

    public function get_ajax_count($where)
    {
        $this->db->select('count(1) as count');
        $this->db->where(array('p_package.PackageParent'=>'0'));

        if(($where['pid']==0) && ($where['aid']==0) && ($where['ctype']==0) && empty($where['author']))
        {
            $this->db->from('p_package');
        }

        if (($where['ctype']!=0)) {
            if(($where['pid']==0) && ($where['aid']==0) && empty($where['author']))
            {
                $this->db->from('p_package');
                $this->db->where(array('p_package.PackageSectionType'=>$where['ctype']));
            }
            else {
                $this->db->where(array('p_package.PackageSectionType'=>$where['ctype']));
            }
        }

        if (!empty($where['author'])) {
            if(($where['pid']==0) && ($where['aid']==0) && ($where['ctype']==0))
            {
                $this->db->from('p_package');
                $this->db->where(array('p_package.PackageAuthor'=>$where['author']));
            }
            else {
                $this->db->where(array('p_package.PackageAuthor'=>$where['author']));
            }
        }

        if(($where['pid']!=0) && ($where['aid']==0))
        {
            $this->db->from('p_architecture')
                ->join('p_architecture_package', 'p_architecture_package.ArchitectureID=p_architecture.ArchitectureID')
                ->join('p_package', 'p_package.PackageID=p_architecture_package.PackageID')
                ->where('p_architecture.ArchitectureParent', $where['pid']);
        }

        if(($where['aid']!=0))
        {
            $this->db->from('p_architecture_package')
                ->join('p_package', 'p_package.PackageID=p_architecture_package.PackageID')
                ->where('p_architecture_package.ArchitectureID', $where['aid']);
        }

        if(($where['pid']==0) && ($where['aid']==0) && ($where['ctype']!=0) && !empty($where['author']))
        {
            $this->db->from('p_package');
            $this->db->where(array('p_package.PackageSectionType'=>$where['ctype']));
            $this->db->where(array('p_package.PackageAuthor'=>$where['author']));

        }

        if (!empty($where['search'])) {
            $this->db->group_start();
            $this->db->like(array('PackageName' => $where['search']));
            $this->db->group_end();
        }

        $result = $this->db->get()->result_array();
        //echo $this->db->last_query();
        return isset($result[0]['count']) ? $result[0]['count'] : 0;

    }

    /**
     * 获取课程无分页
     */
    public function get_courses($where)
    {
        $this->db->select('p_package.PackageID, p_package.PackageName, p_package.TheorySectionNum, p_package.PracticeSectionNum')
            ->from('p_architecture_package')
            ->join('p_package', 'p_package.PackageID=p_architecture_package.PackageID')
            ->where('p_architecture_package.ArchitectureID', $where);

        return $this->db->get()->result_array();
    }

    /**
     * 获取所有课程
     */
    public function get_all_course($where)
    {
        $this->db->select('PackageID, PackageName, PackageAuthor, SectionNum')
            ->from('package')
            ->where(array('PackageParent'=>'0'));

        if (!empty($where['search'])) {
            $this->db->group_start();
            $this->db->like(array('PackageName' => $where['search']));
            $this->db->group_end();
        }

        if (isset($where['limit'])) {
            $this->db->limit($where['limit']['limit'], $where['limit']['offset']);
        }
        //echo $this->db->last_query();
        return $this->db->get()->result_array();
    }

    /**
     * 统计所有课程
     */
    public function get_all_count($where)
    {
        $this->db->select('count(1) as count')
            ->from('package')
            ->where(array('PackageParent'=>'0'));

        if (!empty($where['search'])) {
            $this->db->group_start();
            $this->db->like(array('PackageName' => $where['search']));
            $this->db->group_end();
        }

        //echo $this->db->last_query();

        $result = $this->db->get()->result_array();
        return isset($result[0]['count']) ? $result[0]['count'] : 0;
    }

    /**
     * 课程总数
     */
    public function count_course()
    {
        $this->db->select('count(1) as count');
        $this->db->from('p_package');
        $this->db->where(array('PackageParent'=>'0'));
        $result = $this->db->get()->result_array();
        return isset($result[0]['count']) ? $result[0]['count'] : 0;

    }

    /**
     * 小节总数
     */
    public function count_section()
    {
        $this->db->select('count(1) as count');
        $this->db->from('p_section');
        $result = $this->db->get()->result_array();
        return isset($result[0]['count']) ? $result[0]['count'] : 0;

    }

    //获取课程详情
    public function get_course_detail($where)
    {
        return $this->db->select('*')
            ->from('package')
            ->where('PackageID', $where)
            ->get()->result_array();
    }

    /**
     * 新增课程
     */
    public function add_book($data)
    {
        $this->db->select('PackageName');
        $this->db->from('package');
        $this->db->where('PackageName', $data['PackageName']);
        $this->db->limit(1);

        $res = $this->db->get()->result_array();
        if(!empty($res))
        {
            $tmp['code'] = '0386';
            $tmp['msg'] = '';
            $tmp['data'] = '';
        }else{
            $this->db->insert('package', $data);
            //echo $this->db->last_query();
            $cid = $this->db->insert_id();

            $num = $this->db->affected_rows();
            if ($num >= 1) {
                $tmp['code'] = '0000';
                $tmp['msg'] = 'success';
                $tmp['data'] = $cid;
            } else {
                $tmp['code'] = '0386';
                $tmp['msg'] = '';
                $tmp['data'] = '';
            }
        }

        return $tmp;
    }

    /**
     * 编辑课程
     */
    public function mod_book($data, $where)
    {
        $this->db->where('PackageID', $where);
        $this->db->update('package', $data);
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
     * 新增体系课程关系
     */
    public function add_ap($data)
    {
        $this->db->insert('architecture_package', $data);

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
     * 删除课程
     */
    public function del_book($cid)
    {
        //章
        $chapids = $this->db->select('PackageID')->from('package')->where(array('PackageParent'=>$cid))->get()->result_array();
        foreach ($chapids as $chid)
        {
            //单元
            $unitids = $this->db->select('CourseID')->from('package_course')->where(array('PackageID'=>$chid['PackageID']))->get()->result_array();
            foreach ($unitids as $unid)
            {
                //小节
                $secids = $this->db->select('SectionID')->from('course_section')->where(array('CourseID'=>$unid['CourseID']))->get()->result_array();
                foreach ($secids as $seid)
                {
                    $res = $this->db->select('SectionType')->from('section')->where(array('SectionID' => $seid['SectionID']))->get()->result_array();
                    //更新课时数
                    $this->db->where(array('PackageID'=>$cid));
                    $this->db->set('SectionNum', 'SectionNum-1', false);

                    if($res[0]['SectionType'] != 0) {
                        $this->db->set('PracticeSectionNum', 'PracticeSectionNum-1', false);
                    } else {
                        $this->db->set('TheorySectionNum', 'TheorySectionNum-1', false);
                    }
                    $this->db->update('package');

                    //删除小节
                    $this->db->delete('section', array('SectionID' => $seid['SectionID']));
                }

                //删除单元和小节关联
                $this->db->delete('course_section', array('CourseID'=>$unid['CourseID']));
                //删除单元
                $this->db->delete('course', array('CourseID'=>$unid['CourseID']));
            }
            //删除章和单元关联
            $this->db->delete('package_course', array('PackageID'=>$chid['PackageID']));
        }

        //删除章
        $this->db->delete('package', array('PackageParent'=>$cid));

        //删除课程
        $this->db->where('PackageID', $cid);
        $this->db->delete('package');

        //删除课程和体系
        $this->db->delete('architecture_package', array('PackageID'=>$cid));

        $tmp['code'] = '0000';
        $tmp['msg'] = 'success';
        $tmp['data'] = array();

        return $tmp;
    }

    /**
     * 是否正在学习
     */
    public function is_study($where)
    {
        $this->db->select('*')->from('task')->where($where)->limit(1)->get()->result_array();
        $num = $this->db->affected_rows ();

        if($num > 0)
        {
            $tmp['code'] = '0000';
            $tmp['msg'] = 'success';
            $tmp['data'] = array();
        } else {
            $tmp['code'] = '0377';
            $tmp['msg'] = '';
            $tmp['data'] = array();
        }

        return $tmp;
    }

    /***
     * 统计子类体系
     */
    public function get_count_sys($where)
    {
        $this->db->select('count(1) as count');
        $this->db->from('architecture')->where('ArchitectureParent', $where)->limit(1);

        $result = $this->db->get()->result_array();
        return isset($result[0]['count']) ? $result[0]['count'] : 0;

    }

    /**
     * 获取章
     */
    public function get_chapters($where)
    {
        return $this->db->select('PackageID, PackageName, PackageDesc')
            ->from('package')
            ->where('PackageParent', $where)
            ->get()->result_array();
    }

    /**
     * 获取单元
     */
    public function get_units($where)
    {
        return $this->db->select('p_course.CourseID, p_course.CourseName, p_course.CourseDesc')
            ->from('p_package_course')
            ->join('p_course', 'p_package_course.CourseID=p_course.CourseID')
            ->where('p_package_course.PackageID', $where)
            ->get()->result_array();
    }

    /**
     * 获取节
     */
    public function get_sections($where)
    {
        return $this->db->select('p_section.SectionID, p_section.SectionName, p_section.SectionType')
            ->from('p_course_section')
            ->join('p_section', 'p_course_section.SectionID=p_section.SectionID')
            ->where('p_course_section.CourseID', $where)
            ->get()->result_array();
    }

    /**
     * 新增章
     */
    public function add_chapter($data)
    {
        $res = $this->db->select('PackageName')->from('package')
            ->where('PackageName', $data['PackageName'])
            ->limit(1)->get()->result_array();
        if(!empty($res))
        {
            $tmp['code'] = '0318';
            $tmp['msg'] = '';
            $tmp['data'] = array();
        }
        else
        {
            $this->db->insert('package', $data);
            $tmp['code'] = '0000';
            $tmp['msg'] = 'success';
            $tmp['data'] = array();
        }

        return $tmp;
    }

    /**
     * 编辑章
     */
    public function mod_chapter($data, $where)
    {
        $this->db->where('PackageID', $where)
            ->update('package', $data);
        return array('code' => '0000', 'msg' => 'success!', 'data' => []);

    }

    /**
     * 删除章节
     */
    public function del_chapter($chaid)
    {
        $unis = $this->db->select('p_course.CourseID')->from('p_package_course')
            ->join('p_course', 'p_package_course.CourseID=p_course.CourseID')
            ->where('p_package_course.PackageID', $chaid)
            ->get()->result_array();
        if(!empty($unis))
        {
            foreach ($unis as $uni)
            {
                $secs = $this->db->select('p_section.SectionID')->from('p_course_section')
                    ->join('p_section', 'p_course_section.SectionID=p_section.SectionID')
                    ->where('p_course_section.CourseID', $uni['CourseID'])
                    ->get()->result_array();
                if(!empty($secs))
                {
                    foreach ($secs as $v)
                    { $this->db->delete('section', array('SectionID' => $v['SectionID'])); }
                }
                $this->db->delete('course', array('CourseID' => $uni['CourseID']));
                $this->db->delete('course_section', array('CourseID' => $uni['CourseID']));
            }
        }

        $this->db->delete('package', array('PackageID' => $chaid));
        $this->db->delete('package_course', array('PackageID' => $chaid));

        return array('code' => '0000', 'msg' => 'success!', 'data' => []);
    }

    /**
     * 新增单元
     */
    public function add_unit($info, $chaid)
    {
        $res = $this->db->select('CourseName')->from('course')
            ->where('CourseName', $info['CourseName'])
            ->limit(1)->get()->result_array();
        if(!empty($res))
        {
            $tmp['code'] = '0318';
            $tmp['msg'] = '';
            $tmp['data'] = array();
        }
        else
        {
            $this->db->insert('course', $info);
            $uniid = $this->db->insert_id();
            $this->db->insert('package_course', array('PackageID'=>$chaid, 'CourseID'=>$uniid));

            $tmp['code'] = '0000';
            $tmp['msg'] = 'success';
            $tmp['data'] = array();
        }

        return $tmp;
    }

    /**
     * 编辑单元
     */
    public function mod_unit($data, $uniid)
    {
        $this->db->where('CourseID', $uniid)
            ->update('course', $data);
        return array('code' => '0000', 'msg' => 'success!', 'data' => []);

    }

    /**
     * 删除单元
     */
    public function del_unit($uniid)
    {
        $secs = $this->db->select('p_section.SectionID')->from('p_course_section')
            ->join('p_section', 'p_course_section.SectionID=p_section.SectionID')
            ->where('p_course_section.CourseID', $uniid)
            ->get()->result_array();
        if(!empty($secs))
        {
            foreach ($secs as $v)
            { $this->db->delete('section', array('SectionID' => $v['SectionID'])); }
        }
        $this->db->delete('package_course', array('CourseID' => $uniid));
        $this->db->delete('course', array('CourseID' => $uniid));
        $this->db->delete('course_section', array('CourseID' => $uniid));

        return array('code' => '0000', 'msg' => 'success!', 'data' => []);
    }

    /**
     * 删除小节
     */
    public function del_section($data)
    {
        $this->db->delete('section', array('SectionID' => $data['secid']));
        $this->db->delete('course_section', array('SectionID' => $data['secid']));

        $this->db->where(array('PackageID'=>$data['cid']));
        $this->db->set('SectionNum', 'SectionNum-1', false);

        if($data['sectype'] != 0) {
            $this->db->set('PracticeSectionNum', 'PracticeSectionNum-1', false);
        } else {
            $this->db->set('TheorySectionNum', 'TheorySectionNum-1', false);
        }
        $this->db->update('package');

        return array('code' => '0000', 'msg' => 'success!', 'data' => []);
    }

    /**
     * 统计课时总数
     */
    public function get_nums($courseid)
    {
        $this->db->select('count(1) as count');
        $this->db->from('package_section')->where('PackageID', $courseid);

        $result = $this->db->get()->result_array();
        return isset($result[0]['count']) ? $result[0]['count'] : 0;
    }

    public function nums()
    {
        return $upnum = array('一', '二', '三', '四', '五', '六', '七', '八', '九', '十', '十一', '十二', '十三', '十四', '十五', '十六', '十七', '十八', '十九', '二十一', '二十二', '二十三', '二十四', '二十五', '二十六', '二十七', '二十八', '二十九', '三十一', '三十二', '三十三', '三十四', '三十五', '三十六', '三十七', '三十八', '三十九', '四十一', '四十二', '四十三', '四十四', '四十五', '四十六', '四十七', '四十八', '四十九');
    }










}