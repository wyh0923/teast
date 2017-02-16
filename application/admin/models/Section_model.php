<?php

/**
 * Created by PhpStorm.
 * User: kouyunxia
 * Date: 2016/8/5
 * Time: 11:00
 */
class Section_model extends CI_Model{

    /**
     * 获取小节
     * User:kouyunxia
     * @param array SectionCode 小节编号
     * @return array
     */
    public function get_section($where){
        $this->db->select('SectionDiff,SectionName,SectionDesc,SectionDoc,SectionPoint,SectionID,SectionType,VideoUrl,CtfUrl,CtfUrlDesc,CtfContent,CtfResources,CtfServerID,CtfServerPort,CtfName,CtfResources');
        $this->db->from('section');
        $this->db->join('ctf',"ctf.CtfID = section.CtfID",'left');
        $this->db->where($where);
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
            if ($condition['SectionID'] == '') {
                return 2; break;
            }

            if(intval($condition['SectionID']) < 0){
                return 3; break;
            }

            $result = $this->get_section($condition);
            if (count($result) > 0) {
                return 1; break;
            } else {
                return 3; break;
            }

        } while (FALSE);
    }

    /**
     * 获取学习任务下的节
     * User:kyx
     * @param array  TaskID 任务ID SectionInsID 小节ID
     * @return array
     */
    public function get_study_section($where){
        $this->db->select('t.TaskName,t.TaskType,SectionID,SectionAnswerFinished,SectionName,SectionType,SectionDiff,SectionDesc,SectionDoc,VideoUrl,VideoTime,t.TaskID,SectionInsID,Finished,SectionVideoFinished,SectionInsPoint,SceneUUID,SceneInstanceUUID,TaskUUID,CtfUrl,CtfUrlDesc,CtfResources,CtfContent,CtfServerID,CtfServerPort,CtfName');
        $this->db->from('section_instance as si');
        $this->db->join('task as t','t.TaskId = si.TaskId','left');
        $this->db->join('ctf as c',"c.CtfID = si.CtfID",'left');
        $this->db->where($where);
        $result = $this->db->get()->result_array();
        return $result;
    }
    
    /**
     * 获取学习节下的随堂练习
     * User:kyx
     * @param array  SectionInsID 下发节ID
     * @return array
     */
    public function get_practice_instance($where){
        $this->db->select('QuestionDesc,QuestionType,QuestionChoose,QuestionAnswer,QuestionID,QuestionScore,Answer,score,judge');
        $this->db->from('practice_instance');
        $this->db->where($where);
        $result = $this->db->get()->result_array();
        return $result;
    }

    /**
     * 更新学习节下的随堂练习
     * User:kyx
     * @param array  
     * @return TRUE OR FALSE
     */
    public function update_practice_instance($where,$data){
        $flag = $this->db->update('practice_instance',$data,$where);
        if($flag){
            return TRUE;
        }else{
            return FALSE;
        }
    }

    /**
     * 更新学习节信息
     * User:kyx
     * @param array $where,$data
     * @return TRUE OR FALSE
     */
    public function update_section_instance($where,$data){
        $flag = $this->db->update('section_instance',$data,$where);
        if($flag){
            return TRUE;
        }else{
            return FALSE;
        }
    }

    /**
     * 获取学习节的一些信息
     * User:kyx
     * @param array $where,$data
     * @return array
     */
    public function get_section_info($where){
        $this->db->select("si.VideoUrl, count(pi.ID) as PracticeNum, sum(pi.Score) as PracticeScore, si.SectionDiff, si.SectionVideoFinished, si.SectionAnswerFinished");
        $this->db->from("section_instance as si");
        $this->db->join("practice_instance as pi", "si.SectionInsID=pi.SectionInsID", "left");
        $this->db->where($where);
        $result = $this->db->get()->result_array();
        return $result;
    }

    /**
     * 获取学习节
     * User:kyx
     * @param array $where
     * @return array
     */
    public function get_section_instance($where){
        $this->db->select("SectionInsID,SceneInstanceUUID,TaskUUID");
        $this->db->from("section_instance");
        $this->db->where($where);
        $this->db->order_by('SectionInsID','asc');
        $result = $this->db->get()->result_array();
        return $result;
    }
    
    /**
     * 获取该学员下的学习所下发的场景
     * User:kyx
     * @param array  userid 学员ID TaskSourceType 任务类型 SceneInstanceUUID 场景参数
     * @return array
     */
    public function is_exsist_scene($where){
        $this->db->select("t.TaskID,TaskName,SceneInstanceUUID,SectionInsID,SectionName");
        $this->db->from("section_instance as si");
        $this->db->join("task as t", "t.TaskID = si.TaskID", "left");
        $this->db->where($where);
        $result = $this->db->get()->result_array();
        return $result;
    }

    /**
     * 下发场景
     * User:kyx
     * @param array SceneTemplateUUID 场景模板uuid
     * @return array
     */
    public function create_scene($data){
        $this->load->library('Data_exchange', array('api_name' => 'create_scene', 'message' => $data), 'get_main');
        $res = $this->get_main->request(array($data['SceneTemplateUUID']));
        //var_dump($res);
        if ($res && isset($res['RespHead']['ErrorCode']) && $res["RespHead"]["ErrorCode"] === 0) {
            $tmp['code'] = '0000';
            $tmp['msg'] = 'success';
            $tmp['data'] = $res['RespBody']['Result'];
        } else if(isset($res['RespHead']['ErrorCode'])){
            //返回的错误编码
            $tmp['code'] = $res["RespHead"]["ErrorCode"];
            $tmp['msg'] = $res["RespHead"]["Message"];
            $tmp['data'] = $res['RespBody']['Result'];
        } else {
            $tmp['code'] = '0201';
            $tmp['msg'] = '请检查网络';
            $tmp['data'] = array();
        }
        return $tmp;
    }
    /**
     * 下发场景
     * User:kyx
     * @param array sceneinstanceuuid 场景模板uuid
     * @return array
     */
    public function del_scene($data){
        $this->load->library('Data_exchange', array('api_name' => 'del_scene', 'message' => array()), 'get_main');
        $res = $this->get_main->request(array($data['sceneinstanceuuid']));
        //print_r($res);
        if ($res && isset($res['RespHead']['ErrorCode']) && $res["RespHead"]["ErrorCode"] === 0) {
            $tmp['code'] = '0000';
            $tmp['msg'] = '删除场景成功';
            $tmp['data'] = $res['RespBody']['Result'];
        } else if(isset($res['RespHead']['ErrorCode'])){
            //返回的错误编码
            $tmp['code'] = $res["RespHead"]["ErrorCode"];
            $tmp['msg'] = $res["RespHead"]["Message"];
            $tmp['data'] = $res['RespBody']['Result'];
        } else {
            $tmp['code'] = '0201';
            $tmp['msg'] = '请检查网络';
            $tmp['data'] = array();
        }
        return $tmp;
    }

    /**
     * 获取下发场景进度
     * User:kyx
     * @param array TaskUUID 场景任务uuid
     * @return array
     */
    public function get_scene_progress($TaskUUID){
        $this->load->library('Data_exchange', array('api_name' => 'get_scene_progress', 'message' => array()), 'get_main');
        $res = $this->get_main->request(array($TaskUUID));
        if ($res && isset($res['RespHead']['ErrorCode']) && $res["RespHead"]["ErrorCode"] === 0) {
            $tmp['code'] = '0000';
            $tmp['msg'] = '场景进度';
            $tmp['data'] = $res['RespBody']['Result'];
        } else {
            $tmp['code'] = '0201';
            $tmp['msg'] = '请检查网络';
            $tmp['data'] = array();
        }
        return $tmp;
    }

    /**
     * 判断场景是否存在
     * User:kyx
     * @param array sceneinstanceuuid 场景模板uuid
     * @return array
     */
    public function judge_scene($data){
        $this->load->library('Data_exchange', array('api_name' => 'judge_scene', 'message' => array()), 'get_main');
        $res = $this->get_main->request(array($data['sceneinstanceuuid']));
        //var_dump($res);
        if ($res && isset($res['RespHead']['ErrorCode']) && $res["RespHead"]["ErrorCode"] === 0 && $res['RespBody']['Result'] == 1) {
            $tmp['code'] = '0000';
            $tmp['msg'] = '场景存在';
            $tmp['data'] = $res['RespBody']['Result'];
        }else if($res && isset($res['RespHead']['ErrorCode']) && $res["RespHead"]["ErrorCode"] === 0 && $res['RespBody']['Result'] == 0){
            $tmp['code'] = '0001';
            $tmp['msg'] = '场景不存在';
            $tmp['data'] = $res['RespBody']['Result'];
        } else if(isset($res['RespHead']['ErrorCode'])){
            //返回的错误编码
            $tmp['code'] = $res["RespHead"]["ErrorCode"];
            $tmp['msg'] = $res["RespHead"]["Message"];
            $tmp['data'] = $res['RespBody']['Result'];
        } else {
            $tmp['code'] = '0201';
            $tmp['msg'] = '请检查网络';
            $tmp['data'] = array();
        }
        return $tmp;
    }
    /**
     * 进入场景
     * User:kyx
     * @param array sceneinstanceuuid 场景模板uuid
     * @return array
     */
    public function enter_scene($data){
        $this->load->library('Data_exchange', array('api_name' => 'enter_scene', 'message' => array()), 'get_main');
        $res = $this->get_main->request(array($data['sceneinstanceuuid']));
        //print_r($res);
        if ($res && isset($res['RespHead']['ErrorCode']) && $res["RespHead"]["ErrorCode"] === 0) {
            $tmp['code'] = '0000';
            $tmp['msg'] = '进入场景';
            $tmp['data'] = $res['RespBody']['Result'];
        } else if(isset($res['RespHead']['ErrorCode'])){
            //返回的错误编码
            $tmp['code'] = $res["RespHead"]["ErrorCode"];
            $tmp['msg'] = $res["RespHead"]["Message"];
            $tmp['data'] = $res['RespBody']['Result'];
        } else {
            $tmp['code'] = '0201';
            $tmp['msg'] = '请检查网络';
            $tmp['data'] = array();
        }
        return $tmp;
    }
    /**
     * 判断场景是否存在
     * User:kyx
     * @param array sceneinstanceuuid 场景模板uuid  host_id 节点id
     * @return array
     */
    public function check_scene($data){
        $this->load->library('Data_exchange', array('api_name' => 'check_scene', 'message' => array()), 'get_main');
        $res = $this->get_main->request(array($data['host_id'],$data['sceneinstanceuuid']));
        //var_dump($res);
        if ($res && isset($res['RespHead']['ErrorCode']) && $res["RespHead"]["ErrorCode"] === 0) {
            $tmp['code'] = '0000';
            $tmp['msg'] = '判断场景';
            $tmp['data']['result'] = $res['RespBody']['Result'];
        } else {
            $tmp['code'] = '0201';
            $tmp['msg'] = '请检查网络';
            $tmp['data'] = array();
        }
        return $tmp;
    }

    /***
     * 查看某个场景模板是否有下发学习任务
     * @param $id
     * @return bool
     */
    public function check_study_by_scene($id)
    {
        $this->db->select("s.SectionID");
        $this->db->from("section as s");
        $this->db->join("section_instance as si", "s.SectionID = si.SectionID", "left");
        $this->db->join("task as t", "t.TaskID = si.TaskID", "left");
        $this->db->where("s.SceneUUID", $id);
        $this->db->where("t.TaskType !=", 2);
        $result = $this->db->get()->result_array();
        if(count($result)>0){
            return TRUE;
        }else{
            return FALSE;
        }
    }

    /***
     * 查看某个场景模板是否有下发考试任务
     * @param $id
     * @return bool
     */
    public function check_exam_by_scene($id)
    {
        $this->db->select("q.QuestionDesc,q.QuestionID");
        $this->db->from("question as q");
        $this->db->join("question_instance as qi", "q.QuestionID = qi.QuestionID", "left");
        $this->db->join("task as t", "t.TaskID = qi.TaskID", "left");
        $this->db->where("qi.QuestionLink", $id);
        $this->db->where("t.TaskType !=", 2);
        $result = $this->db->get()->result_array();
        if(count($result)>0){
            return TRUE;
        }else{
            return FALSE;
        }
    }
    
    /**
     * 获取小节下的资料
     * User:kouyunxia
     * @param array SectionCode 小节编号
     * @return array
     */
    public function get_section_tool($where)
    {
        $this->db->select('ToolName,ToolUrl');
        $this->db->from('section_tool as st');
        $this->db->join('tool as q','q.ID=st.ToolID');
        $this->db->where($where);
        $result = $this->db->get()->result_array();
        return $result;
    }

    /**
     * 新增小节
     */
    public function add_section($data)
    {
        $this->db->insert('section', $data);
        $num = $this->db->affected_rows();
        $sid = $this->db->insert_id();

        if ($num >= 1) {
            $tmp['code'] = '0000';
            $tmp['msg'] = 'success';
            $tmp['data'] = $sid;
        } else {
            $tmp['code'] = '0386';
            $tmp['msg'] = '工具添加失败';
            $tmp['data'] = '';
        }
        return $tmp;
    }

    /**
     * 新增单元、小节关联
     */
    public function add_course_section($data)
    {
        $this->db->insert('course_section', $data);
        $num = $this->db->affected_rows();

        if ($num >= 1) {
            $tmp['code'] = '0000';
            $tmp['msg'] = 'success';
            $tmp['data'] = '';
        } else {
            $tmp['code'] = '0386';
            $tmp['msg'] = '工具添加失败';
            $tmp['data'] = '';
        }
        return $tmp;
    }

    /**
     * 更新小节数量
     */
    public function update_section_num($data)
    {
        switch ($data['type'])
        {
            case 0:
                $this->db->where(array('PackageID'=>$data['cid']));
                $this->db->set('PackageSectionType', 1);
                $this->db->set('SectionNum', 'SectionNum+1', false);
                $this->db->set('TheorySectionNum', 'TheorySectionNum+1', false);
                $this->db->set('VideoNum', 'VideoNum+1', false);
                $this->db->update('package');
                break;
            case 1:
                $this->db->where(array('PackageID'=>$data['cid']));
                $this->db->set('PackageSectionType', 2);
                $this->db->set('SectionNum', 'SectionNum+1', false);
                $this->db->set('PracticeSectionNum', 'PracticeSectionNum+1', false);
                $this->db->set('SingleSceneNum', 'SingleSceneNum+1', false);
                $this->db->update('package');
                break;
            case 2:
            $this->db->where(array('PackageID'=>$data['cid']));
            $this->db->set('PackageSectionType', 2);
            $this->db->set('SectionNum', 'SectionNum+1', false);
            $this->db->set('PracticeSectionNum', 'PracticeSectionNum+1', false);
            $this->db->set('NetSceneNum', 'NetSceneNum+1', false);
            $this->db->update('package');
            break;
        }
    }
    
    /**
     * 小节与题目
     */
    public function add_section_question($data)
    {
        foreach ($data['questions'] as $q)
        {
            $question  = explode('@@@', $q);
            $info = array(
                'SectionID' => $data['SectionID'],
                'QuestionID' => $question[0],
                'Score' => $question[1],
            );
            $this->db->insert('section_question', $info);
            
            if($question[2] == 2)
            {
                if($data['SceneUUID'] != '')
                {
                    $this->db->where(array('QuestionID' => $question[0]))->update('question', array('QuestionLink'=>$data['SceneUUID'],'QuestionLinkType'=>2));
                }

                if($data['CtfID'] != '')
                {
                    $this->db->where(array('QuestionID' => $question[0]))->update('question', array('QuestionLink'=>$data['CtfID'],'QuestionLinkType'=>1));
                }
            }
        }
    }

    /**
     * 小节与工具
     */
    public function add_section_tool($data)
    {
        foreach ($data['toolChecked'] as $t)
        {
            $info = array(
                'SectionID' => $data['SectionID'],
                'ToolID' => $t,
            );

            $this->db->insert('section_tool', $info);
        }
    }

    /**
     * 读取小节
     */
    public function get_sections($secid)
    {
        $this->db->select('*')
            ->from('section')
            ->where(array('SectionID'=>$secid));
            $res = $this->db->get()->result_array();
        return $res[0] ? $res[0] : '';
    }

     /**
     * 读取ctfname
     */
    public function get_ctfname($ctfid)
    {
        $this->db->select('CtfName')
            ->from('ctf')
            ->where(array('CtfID'=>$ctfid));
            $res = $this->db->get()->result_array();
        return $res[0] ? $res[0] : '';
    }

    public function get_find_scene($value){
        $this->load->library ( 'Utilities' );

        $httpurl = $this->utilities->getWebServiceIp();
        $sceneurl = "api/v1.0/node_info/scene_tpl/0"."?scene_tpl_uuid=".$value;

        $url = $httpurl.$sceneurl;
        $Utilities = new Utilities();
        $result = $Utilities->request_interface('', $url, 'GET');

        if ( !empty($result['RespBody']['Result']["total"]) ) {
            $data['main'] = $result['RespBody']['Result']['SceneTemplate'];
        }else{
            $data['main'] = array();
        }

        return $data['main'];
    }


    /**
     * 获取小节相关工具
     */
    public function get_tools($secid)
    {
        $this->db->select('p_tool.ID, p_tool.ToolName, p_tool.ToolUrl');
        $this->db->from('p_section_tool');
        $this->db->join('p_tool',"p_tool.ID=p_section_tool.ToolID",'left');
        $this->db->where('SectionID',$secid);
        return $this->db->get()->result_array();
    }

    /**
     * 获取题目
     */
    public function get_questions($secid)
    {
        $this->db->select('p_section_question.Score, p_question.QuestionID, p_question.QuestionType, p_question.QuestionDesc, p_question.QuestionAuthor');
        $this->db->from('p_section_question');
        $this->db->join('p_question',"p_question.QuestionID=p_section_question.QuestionID",'left');
        $this->db->where('SectionID',$secid);
        return $this->db->get()->result_array();

    }

    /**
     * 更新小节
     */
    public function mod_section($data, $secid)
    {
        $this->db->update('section',$data,array('SectionID'=>$secid));

        $tmp['code'] = '0000';
        $tmp['msg'] = 'success';
        $tmp['data'] = '';

        return $tmp;

    }

    /**
     * 更新小节数量
     */
    public function mod_section_num($data)
    {
        $res = $this->db->select('PackageSectionType')->from('package')->where(array('PackageID'=>$data['cid']))->get()->result_array();

        //0>>理论节，1>>ctf实验，2>>网络实验
        //1.纯理论  2.有实验
        //如果原来的节类型和现在的不相同
        if($data['oldtype'] != $data['type']) {
            //理论--ctf
            if ($data['type'] == 1 && $res[0]['PackageSectionType'] == 1) {
                $this->db->where(array('PackageID' => $data['cid']));
                $this->db->set('PackageSectionType', 2);
                $this->db->set('PracticeSectionNum', 'PracticeSectionNum+1', false);
                $this->db->set('SingleSceneNum', 'SingleSceneNum+1', false);
                $this->db->set('TheorySectionNum', 'TheorySectionNum-1', false);
                $this->db->set('VideoNum', 'VideoNum-1', false);
                $this->db->update('package');
            }

            //理论--scene
            if ($data['type'] == 2 && $res[0]['PackageSectionType'] == 1) {
                $this->db->where(array('PackageID' => $data['cid']));
                $this->db->set('PackageSectionType', 2);
                $this->db->set('PracticeSectionNum', 'PracticeSectionNum+1', false);
                $this->db->set('NetSceneNum', 'NetSceneNum+1', false);
                $this->db->set('TheorySectionNum', 'TheorySectionNum-1', false);
                $this->db->set('VideoNum', 'VideoNum-1', false);
                $this->db->update('package');
            }

            //ctf--理论
            if ($data['type'] == 0 && $data['oldtype']==1 && $res[0]['PackageSectionType'] == 2) {
                $this->db->where(array('PackageID' => $data['cid']));
                $this->db->set('PackageSectionType', 1);
                $this->db->set('PracticeSectionNum', 'PracticeSectionNum-1', false);
                $this->db->set('SingleSceneNum', 'SingleSceneNum-1', false);
                $this->db->set('TheorySectionNum', 'TheorySectionNum+1', false);
                $this->db->set('VideoNum', 'VideoNum+1', false);
                $this->db->update('package');
            }

            //scene--理论
            if ($data['type'] == 0 && $data['oldtype']==2 && $res[0]['PackageSectionType'] == 2) {
                $this->db->where(array('PackageID' => $data['cid']));
                $this->db->set('PackageSectionType', 1);
                $this->db->set('PracticeSectionNum', 'PracticeSectionNum-1', false);
                $this->db->set('NetSceneNum', 'NetSceneNum-1', false);
                $this->db->set('TheorySectionNum', 'TheorySectionNum+1', false);
                $this->db->set('VideoNum', 'VideoNum+1', false);
                $this->db->update('package');
            }

            //scene--ctf
            if ($data['type'] == 1 && $data['oldtype']==2 && $res[0]['PackageSectionType'] == 2) {
                $this->db->where(array('PackageID' => $data['cid']));
                $this->db->set('SingleSceneNum', 'SingleSceneNum+1', false);
                $this->db->set('NetSceneNum', 'NetSceneNum-1', false);
                $this->db->update('package');
            }

            //ctf--scene
            if ($data['type'] == 2 && $data['oldtype']==1 && $res[0]['PackageSectionType'] == 2) {
                $this->db->where(array('PackageID' => $data['cid']));
                $this->db->set('SingleSceneNum', 'SingleSceneNum-1', false);
                $this->db->set('NetSceneNum', 'NetSceneNum+1', false);
                $this->db->update('package');
            }
            
        }

    }

    /**
     * 更新小节与题目
     */
    public function mod_section_question($data)
    {
        $this->db->delete('section_question', array('SectionID'=>$data['SectionID']));
        foreach ($data['questions'] as $q)
        {
            $question  = explode('@@@', $q);
            $info = array(
                'SectionID' => $data['SectionID'],
                'QuestionID' => $question[0],
                'Score' => $question[1],
            );
            $this->db->insert('section_question', $info);

            if($question[2] == 2)
            {
                if($data['SceneUUID'] != '')
                {
                    $this->db->where(array('QuestionID' => $question[0]))->update('question', array('QuestionLink'=>$data['SceneUUID'],'QuestionLinkType'=>2));
                }

                if($data['CtfID'] != '')
                {
                    $this->db->where(array('QuestionID' => $question[0]))->update('question', array('QuestionLink'=>$data['CtfID'],'QuestionLinkType'=>1));
                }
            }
        }
    }

    
    //清空小节与题目
    public function del_section_question($eid)
    {
        $this->db->delete('section_question', array('SectionID'=>$eid));
    }
    /**
     * 更新小节与资料
     */
    public function mod_section_tool($data)
    {
        $this->db->delete('section_tool', array('SectionID'=>$data['SectionID']));
        foreach ($data['toolChecked'] as $t)
        {
            $info = array(
                'SectionID' => $data['SectionID'],
                'ToolID' => $t,
            );

            $this->db->insert('section_tool', $info);
        }
    }

    //清空小节与资料
    public function del_section_tool($eid)
    {
        $this->db->delete('section_tool', array('SectionID'=>$eid));
    }

    /***
     * 获取视频上传目录
     * @return array
     */
    public function get_mount_path()
    {
        $result = array();
        $this->load->library('Data_exchange', array('api_name' => 'video_path', 'message' => ''), 'get_path');
        $sub_node = $this->get_path->request();
        //p($sub_node);die;
        if ($sub_node["RespHead"]["ErrorCode"] == 0) {
            $result = $sub_node["RespBody"]["Result"]["host_list"];
        }
        return $result;

    }
    
    /**
     * 新增资料
     */
    public function add_material($data)
    {
        $this->db->insert('tool', $data);
        $num = $this->db->affected_rows();
        $sid = $this->db->insert_id();

        if ($num >= 1) {
            $tmp['code'] = '0000';
            $tmp['msg'] = 'success';
            $tmp['data'] = $sid;
        } else {
            $tmp['code'] = '0386';
            $tmp['msg'] = '';
            $tmp['data'] = '';
        }
        return $tmp;
    }

    /**
     * 获取资料
     */
    public function get_datums($where)
    {
        $this->db->select('*')
            ->from('tool');

        if (!empty($where['search'])) {
            $this->db->group_start();
            $this->db->like(array('ToolName' => $where['search']));
            $this->db->group_end();
        }

        if (isset($where['limit'])) {
            $this->db->limit($where['limit']['limit'], $where['limit']['offset']);
        }

        return $this->db->get()->result_array();
    }

    /**
     * 统计资料
     */
    public function get_all_count($where)
    {
        $this->db->select('count(1) as count')
            ->from('tool');

        if (!empty($where['search'])) {
            $this->db->group_start();
            $this->db->like(array('ToolName' => $where['search']));
            $this->db->group_end();
        }

        $result = $this->db->get()->result_array();
        return isset($result[0]['count']) ? $result[0]['count'] : 0;
    }

    /**
     * 小节的资料
     */
    public function get_materail($secid)
    {
        $this->db->select('p_tool.ID, p_tool.ToolName, p_tool.ToolUrl')
            ->from('p_tool')
            ->join('p_section_tool', 'p_section_tool.ToolID=p_tool.ID', 'right')
            ->where('p_section_tool.SectionID', $secid);
        return $this->db->get()->result_array();

    }









}