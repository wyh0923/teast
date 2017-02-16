<?php

/**
 * Created by PhpStorm.
 * User: kyx
 * Date: 2016/8/3
 * Time: 16:30
 */
class Package_model extends CI_Model{


    /**
     * 获取所有书
     * User:kyx
     * @param array archCode 培训方案编号 sonCode 课程体系编号
     * @return array
     */
    public function get_all_book($where){
        $this->db->select('p.PackageName,p.PackageID,p.PackageImg,ar.ArchitectureName,p.PackageAuthor,p.CreateTime,p.PackageDesc,p.PackageDiff,p.SectionNum,p.VideoNum,p.SingleSceneNum,p.NetSceneNum');
        $this->db->from('package as p');
        $this->db->join('architecture_package as ap','p.PackageID=ap.PackageID','left');
        $this->db->join('architecture as ar','ap.ArchitectureID=ar.ArchitectureID','left');
        $this->db->where('p.PackageStatus',1);
        $this->db->where('p.PackageParent',0);

        if( $where['archid'] != ''){
            if($where['sonid'] !=  ''){
                $this->db->where('ap.ArchitectureID',$where['sonid']);
            } else {
                $this->db->where("ar.ArchitectureParent",$where['archid']);
            }
        }

        if (isset($where['diff']) && $where['diff'] != ''){
            $this->db->where("p.PackageDiff", $where['diff']);
        }
        if (isset($where['exp']) && $where['exp'] != '') {
            if($where['exp'] == 0){
                $this->db->where("TheorySectionNum >", 0);
            }
            if($where['exp'] == 1){
                $this->db->where("SingleSceneNum >", 0);
            }
            if($where['exp'] == 2){
                $this->db->where("NetSceneNum >", 0);
            }
        }
        if (isset($where['search']) && $where['search'] != ''){
            $this->db->like("p.PackageName",$where['search']);
        }

        $this->db->group_by("p.PackageID");

        //排序
        if (isset($where['sort']) && count($where['sort']) > 0) {
            if ($where['sort'][0] == "diff") {
                $this->db->order_by("p.PackageDiff", ($where['sort'][1]=="asc"?"asc":"desc"));
                //解决难度等级排序时 数据变化问题
                $this->db->order_by("p.PackageID", ($where['sort'][1]=="asc"?"asc":"desc"));
            } else if ($where['sort'][0] == "time") {
                $this->db->order_by("p.SectionNum", ($where['sort'][1]=="asc"?"asc":"desc"));

            }
        } else{
            $this->db->order_by("p.PackageID", "desc");
        }

        //分页
        if(isset($where['num']) && isset($where['offset'])) {
            $this->db->limit($where['num'], $where['offset']);
        }
        $result = $this->db->get()->result_array();
        $data = array();

        //整理数组（课程属于多个体系的时候）
        if(count($result) > 0){
            $idArr = array();

            //$idArr = array_column($result,'PackageID'); //取出该页的PackageID

            //课程没有所属体系的去除
            foreach ($result as $key=>$val){
                $data[$val['PackageID']]['package'] = $val;
                //有所属体系的课程
                if($val['ArchitectureName'] != ''){
                    $idArr[] = $val['PackageID'];
                }
            }

            if(count($idArr) > 0){
                //获取课程所属的体系
                $architecture = $this->get_architecture_package($idArr);
                foreach ($idArr as $v){
                    $data[$v]['architecture'] = $architecture[$v];
                }
            }

        }//print_r($data);
        return $data;
    }
    /*
     * 查询课程和体系对应表
     * */
    public function get_architecture_package($where){
        $this->db->select("a.ArchitectureName,ap.PackageID");
        $this->db->from('architecture_package as ap');
        $this->db->join('architecture as a','a.ArchitectureID = ap.ArchitectureID','left');
        $this->db->where_in('ap.PackageID',$where);
        $architecture = $this->db->get()->result_array();
        //整理数组
        $architecture_data = array();
        foreach ($architecture as $key=>$val){
            $architecture_data[$val['PackageID']][] = $val['ArchitectureName'];
        }
        return $architecture_data;
    }

    /**
     * 获取课程详情
     * User:kyx
     * @param array  PackageCode 课程编号
     * @return array
     */
    public function get_book_detail($where){
        /*
         * 1.类别（体系）2.课时（节和章的总数） 理论节总数 实践节总数
         * 3 章 单元 节 思考题 视频时长
         * */
        $data = array();
        //所属体系
        $this->db->select("p.PackageName,p.PackageAuthor,p.PackageID,p.PackageImg,p.PackageDiff,p.PackageDesc,ar.ArchitectureName,p.SectionNum,p.PracticeSectionNum,p.TheorySectionNum,p.CreateTime");
        $this->db->from('package as p');
        $this->db->join('architecture_package as arp',' p.PackageID = arp.PackageID ','left');
        $this->db->join('architecture as ar','ar.ArchitectureID = arp.ArchitectureID','left');
        $this->db->where('p.PackageID',$where['PackageID']);
        $architecture = $this->db->get()->result_array();
        foreach ($architecture as $val){
            $data['ArchitectureName'][]=$val['ArchitectureName'];
        }
        $data['packagedesc'] = $architecture[0]['PackageDesc'];
        $data['packageid'] = $architecture[0]['PackageID'];
        $data['packagediff'] = $architecture[0]['PackageDiff'];
        $data['packageauthor'] = $architecture[0]['PackageAuthor'];
        $data['packagename'] = $architecture[0]['PackageName'];
        $data['packageimg'] = $architecture[0]['PackageImg'];
        $data['sectionnum'] = $architecture[0]['SectionNum'];
        $data['practicesectionNum'] = $architecture[0]['PracticeSectionNum'];
        $data['theorynum'] = $architecture[0]['TheorySectionNum'];
        $data['createtime'] = date('Y-m-d',$architecture[0]['CreateTime']);

        //章 单元 节 详情
        $detail = $this->get_detail_section(array('PackageParent'=>$where['PackageID']));
        if(count($detail) > 0){
            foreach ($detail as $key=>$val){
                $data['packlist'][$val['PackageID']]['PackageID'] = $val['PackageID'];
                $data['packlist'][$val['PackageID']]['PackageName'] = $val['PackageName'];
                $data['packlist'][$val['PackageID']]['PackageDesc'] = $val['PackageDesc'];

                //单元
                if($val['CourseID'] != ''){
                    $data['packlist'][$val['PackageID']]['courselist'][$val['CourseID']]['CourseID'] = $val['CourseID'];
                    $data['packlist'][$val['PackageID']]['courselist'][$val['CourseID']]['CourseName'] = $val['CourseName'];
                }
                //小节
                if($val['SectionID'] != ''){
                    $data['packlist'][$val['PackageID']]['courselist'][$val['CourseID']]['sectionlist'][$val['SectionID']]['SectionID'] = $val['SectionID'];
                    $data['packlist'][$val['PackageID']]['courselist'][$val['CourseID']]['sectionlist'][$val['SectionID']]['SectionName'] = $val['SectionName'];
                    $data['packlist'][$val['PackageID']]['courselist'][$val['CourseID']]['sectionlist'][$val['SectionID']]['VideoTime'] = $val['VideoTime'];
                    $data['packlist'][$val['PackageID']]['courselist'][$val['CourseID']]['sectionlist'][$val['SectionID']]['SectionName'] = $val['SectionName'];
                    $data['packlist'][$val['PackageID']]['courselist'][$val['CourseID']]['sectionlist'][$val['SectionID']]['SectionType'] = $val['SectionType'];
                }
                //随堂
                if($val['QuestionID'] != ''){
                    $data['packlist'][$val['PackageID']]['courselist'][$val['CourseID']]['sectionlist'][$val['SectionID']]['qusetion'][$val['QuestionID']]['QuestionID'] = $val['QuestionID'];
                }

            }
        }
        //章的个数
        $data['packnum'] = 0;
        if(isset($data['packlist']) && count($data['packlist']) > 0){
            $data['packnum'] = count($data['packlist']);
        }

        return $data;
    }

    /**
     * 获取课程下的章
     * User:kyx
     * @param array  PackageCode 课程编号
     * @return array
     */
    public function get_book_chapter($where){
        $this->db->select('PackageName,PackageDesc,PackageID');
        $this->db->from('package');
        $this->db->where($where);
        $result = $this->db->get()->result_array();
        return $result;
    }

    /**
     * 获取课程下的单元
     * User:kyx
     * @param array  PackageCode 课程编号
     * @return array
     */
    public function get_book_unit($where){
        $this->db->select('c.CourseName,c.CourseID');
        $this->db->from('package_course as pc');
        $this->db->join('course as c','pc.CourseID = c.CourseID','left');
        $this->db->where($where);
        $result = $this->db->get()->result_array();
        return $result;
    }

    /**
     * 获取课程下的节和视频
     * User:kyx
     * @param array  PackageCode 课程编号
     * @return array
     */
    public function get_book_section($where){
        $this->db->select('s.SectionID,s.SectionName,s.SectionType,s.VideoUrl,s.VideoTime');
        $this->db->from('course_section as cs');
        $this->db->join('section as s','cs.SectionID = s.SectionID','left');
        $this->db->order_by('cs.Index','asc');
        $this->db->where($where);
        $result = $this->db->get()->result_array();
        return $result;
    }

    /**
     * 获取节下的随堂练习
     * User:kyx
     * @param array  SectionCode 课程编号
     * @return array
     */
    public function get_section_question($where){
        $this->db->select('q.QuestionDesc,q.QuestionType,q.QuestionChoose,q.QuestionAnswer,q.QuestionID,sq.score,ResourceUrl,ResourceName');
        $this->db->from('section_question as sq');
        $this->db->join('question as q','q.QuestionID=sq.QuestionID');
        $this->db->where($where);
        $result = $this->db->get()->result_array();
        return $result;
    }

    /**
     * 获取所有书的总节数
     * User:kyx
     * @param array  SectionCode 课程编号
     * @return array
     */
    public function get_all_book_num($where){
        $this->db->select("p.PackageID");
        $this->db->from('package as p');
        $this->db->join('architecture_package as ap','p.PackageID=ap.PackageID','left');
        $this->db->join('architecture as ar','ap.ArchitectureID=ar.ArchitectureID','left');

        if( $where['archid'] != ''){
            if($where['sonid'] !=  ''){
                $this->db->where('ar.ArchitectureID',$where['sonid']);
            } else {
                $this->db->where("ArchitectureParent",$where['archid']);
            }
        }

        $this->db->where('PackageParent',0);
        $this->db->where("PackageStatus",1);

        if (isset($where['diff']) && $where['diff'] != ''){
            $this->db->where("PackageDiff", $where['diff']);
        }
        if (isset($where['exp']) && $where['exp'] != '') {
            if($where['exp'] == 0){
                $this->db->where('TheorySectionNum >', 0);
            }
            if($where['exp'] == 1){
                $this->db->where('SingleSceneNum >', 0);
            }
            if($where['exp'] == 2){
                $this->db->where('NetSceneNum >', 0);
            }
        }

        if (isset($where['search']) && $where['search'] != ''){
            $this->db->like("PackageName",$where['search']);
        }
        $sqlStr = $this->db->get_compiled_select();


        $this->db->select("count(pa.PackageID) as PackageNum, sum(pa.SectionNum) as SectionNum");
        $this->db->from("p_package as pa");
        $this->db->where("pa.PackageID in (" . $sqlStr . ")");

        $result = $this->db->get()->result_array();
        return $result;
    }

    /**
     * 判断参数是否正确（不确定的参数）
     * @param array  PackageID 值
     * 返回值 1 正确  2 不传值  3 不存在或错误
     */
    public function judge($condition)
    {
        do {
            // 判断传值不为空 并且 字符长度是否正确
            // 整数类型是否正确  !(intval($id) > 0)
            if ($condition['PackageID'] == '') {
                return 2; break;
            }

            if(intval($condition['PackageID']) < 0){
                return 3; break;
            }

            $result = $this->get_book_chapter($condition);
            if (count($result) > 0) {
                return 1; break;
            } else {
                return 3; break;
            }

        } while (FALSE);
    }

    /**
     * 获取学习任务课程详情
     * User:kyx
     * @param array  PackageCode 课程编号
     * @return array
     */
    public function get_study_detail($where){
        /*
         * 1.类别（体系）2.课时（节和章的总数） 理论节总数 实践节总数
         * 3 章 单元 节 思考题 视频时长
         * */
        $data = array();
        //课程信息
        $this->db->select("p.PackageName,t.TaskCode,TaskName,t.TaskType,t.TaskStartTime,t.TaskEndTime,t.TaskProcess,p.PackageAuthor,p.PackageID,p.PackageImg,p.PackageDiff,p.PackageDesc,p.SectionNum,p.PracticeSectionNum,p.TheorySectionNum");
        $this->db->from('task as t');
        $this->db->join('package as p','p.PackageID=t.PackageID');
        $this->db->where($where);
        $architecture = $this->db->get()->result_array();
        //没有该任务
        if(count($architecture) == 0){
            return $data;
        }
        $data = $architecture[0];
        //没小节的情况下
        $data['packnum'] = 0;

        //获得学习下的章 单元 小节 详情
        $this->db->select('pc.PackageID,p.PackageName,p.PackageDesc,cs.CourseID,c.CourseName,cs.SectionID,si.SectionInsID,si.SectionInsPoint,si.Finished,si.TaskID,si.SectionName,si.VideoTime,si.SectionType,pi.QuestionID');
        $this->db->from('package as p');
        $this->db->join('package_course as pc','p.PackageID = pc.PackageID','left');
        $this->db->join('course as c',"c.CourseID = pc.CourseID",'left');
        $this->db->join('course_section as cs','cs.CourseID = c.CourseID','left');
        $this->db->join('section_instance as si',"si.SectionID = cs.SectionID",'left');
        $this->db->join('practice_instance as pi',"pi.SectionInsID = si.SectionInsID",'left');
        $this->db->where(array('PackageParent'=>$architecture[0]['PackageID'],'si.TaskID'=>$where['TaskId']));
        $this->db->order_by('PackageIndex','asc');
        $this->db->order_by('si.SectionID','asc');
        $detail = $this->db->get()->result_array();

        if(count($detail) > 0){
            foreach ($detail as $key=>$val){
                $data['packlist'][$val['PackageID']]['PackageID'] = $val['PackageID'];
                $data['packlist'][$val['PackageID']]['PackageName'] = $val['PackageName'];
                $data['packlist'][$val['PackageID']]['PackageDesc'] = $val['PackageDesc'];
                $data['packlist'][$val['PackageID']]['courselist'][$val['CourseID']]['CourseID'] = $val['CourseID'];
                $data['packlist'][$val['PackageID']]['courselist'][$val['CourseID']]['CourseName'] = $val['CourseName'];
                $data['packlist'][$val['PackageID']]['courselist'][$val['CourseID']]['sectionlist'][$val['SectionID']]['SectionID'] = $val['SectionID'];
                $data['packlist'][$val['PackageID']]['courselist'][$val['CourseID']]['sectionlist'][$val['SectionID']]['SectionInsID'] = $val['SectionInsID'];
                $data['packlist'][$val['PackageID']]['courselist'][$val['CourseID']]['sectionlist'][$val['SectionID']]['SectionName'] = $val['SectionName'];
                $data['packlist'][$val['PackageID']]['courselist'][$val['CourseID']]['sectionlist'][$val['SectionID']]['VideoTime'] = $val['VideoTime'];
                $data['packlist'][$val['PackageID']]['courselist'][$val['CourseID']]['sectionlist'][$val['SectionID']]['SectionName'] = $val['SectionName'];
                $data['packlist'][$val['PackageID']]['courselist'][$val['CourseID']]['sectionlist'][$val['SectionID']]['SectionType'] = $val['SectionType'];
                $data['packlist'][$val['PackageID']]['courselist'][$val['CourseID']]['sectionlist'][$val['SectionID']]['TaskID'] = $val['TaskID'];
                $data['packlist'][$val['PackageID']]['courselist'][$val['CourseID']]['sectionlist'][$val['SectionID']]['Finished'] = $val['Finished'];
                $data['packlist'][$val['PackageID']]['courselist'][$val['CourseID']]['sectionlist'][$val['SectionID']]['SectionInsPoint'] = $val['SectionInsPoint'];

                if($val['QuestionID'] != ''){
                    $data['packlist'][$val['PackageID']]['courselist'][$val['CourseID']]['sectionlist'][$val['SectionID']]['qusetion'][$val['QuestionID']]['QuestionID'] = $val['QuestionID'];
                }

            }
            $data['packnum'] = count($data['packlist']);
        }

        return $data;
    }

    /**
     * 获取单元下的的节
     * User:kyx
     * @param array  CourseID 单元ID
     * @return array
     */
    public function study_course_section($where){
        $this->db->select('si.SectionID,si.SectionName,si.SectionType,si.VideoUrl,si.VideoTime,si.TaskID,si.SectionInsID,si.Finished,si.SectionInsPoint');
        $this->db->from('course_section as cs');
        $this->db->join('section_instance as si','si.SectionID = cs.SectionID','left');
        $this->db->group_by('si.SectionID');
        $this->db->order_by('cs.Index','asc');
        $this->db->where($where);
        $result = $this->db->get()->result_array();
        return $result;
    }

    /**
     * 获取课程下的节和题
     * User:kyx
     * @param array  PackageParent 父类id
     * @return array
     */
    public function get_detail_section($where){
        $this->db->select('pc.PackageID,p.PackageName,p.PackageDesc,c.CourseID,c.CourseName,s.*,q.*,sq.score');
        $this->db->from('package as p');
        $this->db->join('package_course as pc','p.PackageID = pc.PackageID','left');
        $this->db->join('course as c',"c.CourseID = pc.CourseID",'left');
        $this->db->join('course_section as cs','cs.CourseID = c.CourseID','left');
        $this->db->join('section as s',"s.SectionID = cs.SectionID",'left');
        $this->db->join('section_question as sq',"sq.SectionID = s.SectionID",'left');
        $this->db->join('question as q',"q.QuestionID = sq.QuestionID",'left');
        $this->db->where($where);
        $this->db->order_by('p.PackageID','asc');
        $this->db->order_by('c.CourseID','asc');
        $this->db->order_by('s.SectionID','asc');
        $result = $this->db->get()->result_array();
        return $result;
    }
    /**
     * 获取下发课程所需的小节和题目
     * User:kyx
     * @param array  PackageParent 父类id
     * @return array
     */
    public function get_instance($where){
        $result = $this->get_detail_section($where);
        $data = array();
        if(count($result) > 0){
            foreach ($result as $key=>$val){
                if($val['SectionID'] != ''){
                    $data['section'][$val['SectionID']]['SectionID'] = $val['SectionID'];
                    $data['section'][$val['SectionID']]['SectionName'] = $val['SectionName'];
                    $data['section'][$val['SectionID']]['SectionDoc'] = $val['SectionDoc'];
                    $data['section'][$val['SectionID']]['SectionDocType'] = $val['SectionDocType'];
                    $data['section'][$val['SectionID']]['SectionDiff'] = $val['SectionDiff'];
                    $data['section'][$val['SectionID']]['VideoUrl'] = $val['VideoUrl'];
                    $data['section'][$val['SectionID']]['VideoTime'] = $val['VideoTime'];
                    $data['section'][$val['SectionID']]['CtfID'] = $val['CtfID'];
                    $data['section'][$val['SectionID']]['SectionType'] = $val['SectionType'];
                    $data['section'][$val['SectionID']]['SectionDesc'] = $val['SectionDesc'];
                    $data['section'][$val['SectionID']]['SceneUUID'] = $val['SceneUUID'];
                }

                if($val['QuestionID'] != ''){
                    $data['question'][$val['SectionID']][$val['QuestionID']]['QuestionID'] = $val['QuestionID'];
                    $data['question'][$val['SectionID']][$val['QuestionID']]['QuestionDesc'] = $val['QuestionDesc'];
                    $data['question'][$val['SectionID']][$val['QuestionID']]['QuestionID'] = $val['QuestionID'];
                    $data['question'][$val['SectionID']][$val['QuestionID']]['QuestionType'] = $val['QuestionType'];
                    $data['question'][$val['SectionID']][$val['QuestionID']]['QuestionChoose'] = $val['QuestionChoose'];
                    $data['question'][$val['SectionID']][$val['QuestionID']]['QuestionPriv'] = $val['QuestionPriv'];
                    $data['question'][$val['SectionID']][$val['QuestionID']]['QuestionAnswer'] = $val['QuestionAnswer'];
                    $data['question'][$val['SectionID']][$val['QuestionID']]['QuestionLink'] = $val['QuestionLink'];
                    $data['question'][$val['SectionID']][$val['QuestionID']]['QuestionLinkType'] = $val['QuestionLinkType'];
                    $data['question'][$val['SectionID']][$val['QuestionID']]['QuestionDiff'] = $val['QuestionDiff'];
                    $data['question'][$val['SectionID']][$val['QuestionID']]['QuestionScore'] = $val['score'];
                    $data['question'][$val['SectionID']][$val['QuestionID']]['QuestionScene'] = $val['QuestionScene'];
                    $data['question'][$val['SectionID']][$val['QuestionID']]['ResourceUrl'] = $val['ResourceUrl'];
                    $data['question'][$val['SectionID']][$val['QuestionID']]['ResourceName'] = $val['ResourceName'];
                }

            }
        }

        return $data;
    }
    /**
     * 插入下发节和题目
     * User:kyx
     * @param array  PackageID 课程id  $data['data']['section'] 要插入的节 $data['data']['question'] 题
     * @return array
     */
    public function insert_instance($data){
        foreach ($data['data']['section'] as $val){
            $section = $val;
            $section['TaskID'] = $data['TaskID'];
            $section['TaskCode'] = $data['TaskCode'];
            $this->db->insert('section_instance',$section);
            $SectionInsID = $this->db->insert_id();
            if(empty($SectionInsID)){
                return FALSE;
            }
            if(isset($data['data']['question'][$val['SectionID']]) && is_array($data['data']['question'][$val['SectionID']])){
                foreach ($data['data']['question'][$val['SectionID']] as $v){
                    $question = $v;
                    $question['SectionInsID'] = $SectionInsID;
                    $question['TaskCode'] = $data['TaskCode'];
                    $this->db->insert('practice_instance',$question);
                    if($this->db->affected_rows() <= 0){
                        return FALSE;
                    }
                }
            }
        }
        return TRUE;
    }
}