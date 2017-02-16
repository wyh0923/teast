<?php
/**
 * Created by PhpStorm.
 * User: kyx
 * Date: 2016/8/3
 * Time: 10:30
 */

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 学生端我的学习控制器
 *
 */
class Study extends ECQ_Controller{

    /**
     * 正在进行的学习列表页
     */
    public function listunderway(){
        $this->load->model ("Study_model");
        $this->load->library('Filter');

        $userID = $this->session->userdata('UserID');
        $data['UserName'] = $this->session->userdata('UserName');
        $search = $this->input->get('search');
        $page = $this->input->get('per_page');
        $search = $search ? urldecode($search) : '';//搜索字符串要转码
        //安全过滤
        $search = $this->security->xss_clean($search);
        $page = max(intval($page), 1);
        $num = 10;//每页记录数
        $offset = ($page - 1) * $num;
        $where = array('TaskSourceType'=>1,'TaskType !='=>2,'StudentID'=>$userID);
        $data['total'] = count($this->Study_model->study_list($where,$search));
        //echo $this->db->last_query();
        $data['data'] = $this->Study_model->study_list($where,$search,$num,$offset);

        //搜索
        $data['search'] = $search;
        $data['search_url'] = $this->filter->generateBaseUrl("search");

        //分页
        $per_page =  $this->filter->generateBaseUrl("per_page");
        $data['page_url'] = $per_page.'per_page=';;
        $data['page_count'] = ceil($data['total']/10);
        $data['page_pre'] = $page;

        //print_r($data['data']);
        $this->load->view('student/study_underway',$data);
    }

    /**
     * 已完成的学习列表页
     */
    public function listfinished(){
        $this->load->model ("Study_model");
        $this->load->library('Filter');

        $userID = $this->session->userdata('UserID');
        $data['UserName'] = $this->session->userdata('UserName');
        $search = $this->input->get('search');
        $page = $this->input->get('per_page');
        $search = $search ? urldecode($search) : '';//搜索字符串要转码
        //安全过滤
        $search = $this->security->xss_clean($search);
        $page = max(intval($page), 1);
        $num = 10;//每页记录数
        $offset = ($page - 1) * $num;
        $where = array('TaskSourceType'=>1,'TaskType'=>2,'StudentID'=>$userID);
        $data['total'] = count($this->Study_model->study_list($where,$search));
        //echo $this->db->last_query();
        $data['data'] = $this->Study_model->study_list($where,$search,$num,$offset);

        //搜索
        $data['search'] = $search;
        $data['search_url'] = $this->filter->generateBaseUrl("search");

        //分页
        $per_page =  $this->filter->generateBaseUrl("per_page");
        $data['page_url'] = $per_page.'per_page=';;
        $data['page_count'] = ceil($data['total']/10);
        $data['page_pre'] = $page;

        $this->load->view('student/study_finished',$data);
    }
    /**
     * 更新正在学习列表页
     * 
     */
    public function get_study_ajax(){
        $this->load->model ("Study_model");
        $this->load->library('Interface_output');

        $userID = $this->session->userdata('UserID');
        $search = $this->input->post('search');
        $page = $this->input->post('per_page');
        $search = $search ? urldecode($search) : '';//搜索字符串要转码
        //安全过滤
        $search = $this->security->xss_clean($search);
        $page = max(intval($page), 1);
        $num = 10;//每页记录数
        $offset = ($page - 1) * $num;
        $where = array('TaskSourceType'=>1,'TaskType !='=>2,'StudentID'=>$userID);
        $total = count($this->Study_model->study_list($where,$search));
        $result['result'] = $this->Study_model->study_list($where,$search,$num,$offset);
        $result['time'] = time();
        $result['username'] = $this->session->userdata('UserName');
        
        $output_data['code'] = '0000';
        $output_data['msg'] = $total;
        $output_data['data'] = $result;

        $this->interface_output->output_fomcat('js_Ajax', $output_data);
    }
    
    /**
     * 学习详情页
     * 正在进行、已完成
     */
    public function studydetail(){
        $this->load->model ("Package_model");
        $this->load->model ("Task_model");

        $userID = $this->session->userdata('UserID');
        $taskid = $this->input->get("taskid");
        do {
            if(intval($taskid) <= 0){
                redirect('Study/listunderway');
            }
            $result = $this->Task_model->get_task(array('TaskId'=>$taskid,'StudentID'=>$userID));
            if(count($result) == 0){
                redirect('Study/listunderway');
            }
            //左侧导航 选中状态
            $this->nav['left_nav_id'] = 48;
            if($result[0]['TaskType'] == 2){
                $this->nav['left_nav_id'] = 49;
            }

            //查询任务课程详情
            $detail = $this->Package_model->get_study_detail(array('TaskId'=>$taskid,'StudentID'=>$userID));
            if(count($detail) == 0){
                redirect('Study/listunderway');
            }
            $data['data'] = $detail;
            //print_r($data);
        }while(FALSE);

        $this->load->view('student/study_detail',$data);
    }

    /**
     * 学习小节页
     * 正在进行、已完成
     */
    public function studysection(){
        $this->load->model ("Package_model");
        $this->load->model ("Section_model");
        $this->load->model ("Task_model");
        $this->load->model("Video_model");
        $this->load->model("Ctf_model");
        $this->load->library ('Utilities');

        $userID = $this->session->userdata('UserID');

        $taskid = $this->input->get("taskid");
        $sectioninsid = $this->input->get("sectioninsid");
        do {
            if(intval($taskid) <= 0){
                redirect('Study/listunderway');
            }
            if(intval($sectioninsid) <= 0){
                redirect('Study/listunderway');
            }
            $result = $this->Task_model->get_task(array('TaskId'=>$taskid,'StudentID'=>$userID));
            if(count($result) == 0){
                redirect('Study/listunderway');
            }
            //左侧导航 选中状态
            $this->nav['left_nav_id'] = 48;
            if($result[0]['TaskType'] == 2){
                $this->nav['left_nav_id'] = 49;
            }
            //根据
            $data['section'] =$this->Section_model->get_study_section(array('si.TaskId'=>$taskid,'SectionInsID'=>$sectioninsid));
            //print_r($data['section']);
            //没有该小节
            if(count($data['section']) == 0){
                redirect('Study/studydetail?taskid='.$taskid);
            }
            if ($data['section'][0]['SectionType'] == 0 && $data['section'][0]['VideoUrl'] != null) {
                $data['section'][0]['VideoUrl'] = $this->Video_model->get_video_url($data['section'][0]['VideoUrl']);
            }

            if ($data['section'][0]['SectionType'] == 1 && $data['section'][0]['CtfUrl'] != '') {
                $data['section'][0]['CtfUrl'] = $this->Ctf_model->get_ctf_url($data['section'][0]['CtfServerID'],$data['section'][0]['CtfServerPort'],$data['section'][0]['CtfUrl']);
            }
            
            //小节资料
            $data['tool'] = $this->Section_model->get_section_tool(array('SectionID' => $data['section'][0]['SectionID']));
            //随堂练习题
            $data['question'] = $this->Section_model->get_practice_instance(array('SectionInsID' => $sectioninsid));
            $data['count_question'] = count($data['question']);
            if($data['count_question'] > 0){
                foreach ($data['question'] as $key=>$val){
                    $val['QuestionDesc'] = $this->utilities->clearMarkdown($val['QuestionDesc']);
                    $data['question'][$key]["QuestionDesc"] = preg_replace('/\[info\](.*)\[\/info\]/ms', ' ', $val["QuestionDesc"]);
                }
            }
            //marked格式
            $data['section'][0]['SectionDoc'] = $this->utilities->clearMarkdown($data['section'][0]['SectionDoc']);
            //日志
            $this->load->library('Log_user');
            $log = array(
                'LogTaskName' => $data['section'][0]['SectionName'],
                'LogContent' => '学习了“'.$data['section'][0]['SectionName'].'”小节',
                'LogTypeID' => 1,
                'LogResult' => site_url('Study/studysection?taskid='.$taskid.'&sectioninsid='.$sectioninsid),
                'UserID' => $userID
            );
            $this->log_user->add_log($log);
        }while(FALSE);

        $this->load->view('student/study_section',$data);
    }

    
    /**
     * 判断考试是否开始
     * $taskid 任务ID
     */
    public function is_start(){
        $this->load->model ("Task_model");
        $this->load->library('Interface_output');

        $taskid = $this->input->post("taskid");

        $output_data['data'] = array();
        do {
            if(intval($taskid) <= 0){
                $output_data['code'] = '0420';
                $output_data['msg'] = '参数错误!';
                break;
            }
            $userID = $this->session->userdata('UserID');
            $result = $this->Task_model->get_task(array('TaskId'=>$taskid,'StudentID'=>$userID));
            if(count($result) == 0){
                $output_data['code'] = '0420';
                $output_data['msg'] = '参数错误!';
                break;
            }
            if($result[0]['TaskStartTime'] > time()){
                $output_data['code'] = '0421';
                $output_data['msg'] = '该学习未开始!';
                break;
            }
            if($result[0]['TaskType'] == 2){
                $output_data['code'] = '0422';
                $output_data['msg'] = '该学习已结束!';
                break;
            }
            if($result[0]['TaskEndTime'] < time()){
                $this->Task_model->edit_task(array('TaskId'=>$taskid),array('TaskType'=>2));
                $output_data['code'] = '0422';
                $output_data['msg'] = '该学习已结束!';
                break;
            }
            $output_data['code'] = '0000';
            $output_data['msg'] = '开始学习';
            $this->Task_model->edit_task(array('TaskId'=>$taskid),array('TaskType'=>1));
        }while(FALSE);

        $this->interface_output->output_fomcat('js_Ajax', $output_data);
    }
    /**
     * 结束考试
     * $taskid 任务名 $scenetaskid 场景计划任务id
     */
    public function endstudy(){
        $this->load->model ("Task_model");
        $this->load->library('Interface_output');

        $taskid = $this->input->post("taskid");

        $output_data['data'] = array();
        do {
            if(intval($taskid) <= 0){
                $output_data['code'] = '0420';
                $output_data['msg'] = '参数错误!';
                break;
            }
            $this->Task_model->edit_task(array('TaskId'=>$taskid),array('TaskType'=>2));

            $output_data['code'] = '0000';
            $output_data['msg'] = '学习状态更改成功';
        }while(FALSE);

        $this->interface_output->output_fomcat('js_Ajax', $output_data);
    }

    /**
     * 提交随堂练习题
     * infos 题目答案
     */
    public function practice_Answer(){
        $this->load->model ("Section_model");
        $this->load->library('Interface_output');
        $infos = $this->input->post();

        $output_data['data'] = array();
        do {
            if(intval($infos['TaskID']) <= 0){
                $output_data['code'] = '0420';
                $output_data['msg'] = '参数错误!';
                break;
            }

            if(intval($infos['SectionInsID']) <= 0){
                $output_data['code'] = '0421';
                $output_data['msg'] = '参数错误!';
                break;
            }
            if(!is_array($infos['info'])){
                $output_data['code'] = '0423';
                $output_data['msg'] = '请填写答案!';
                break;
            }
            $question = $this->Section_model->get_practice_instance(array('SectionInsID' => $infos['SectionInsID']));
            $total = 0;
            foreach ($question as $key=>$val){
                $judge = -1;
                if(isset($infos['info'][$val['QuestionID']]) && !empty($infos['info'][$val['QuestionID']])){
                    $score = 0;
                    $question[$key]['judge'] = 0;//判断答案是否正确
                    //多选题
                    if($val['QuestionType'] == 2){
                        $questionAnswer = explode('|||',$val['QuestionAnswer']);
                        $answer = implode('|||',$infos['info'][$val['QuestionID']]);
                        $arr_diff = array_diff($questionAnswer,$infos['info'][$val['QuestionID']]);

                        if(count($arr_diff) == 0){
                            $total += $val['QuestionScore'];
                            $score = $val['QuestionScore'];
                            $judge = 1;
                        }
                    }else {
                        $answer = $infos['info'][$val['QuestionID']];
                        if($answer == $val['QuestionAnswer']){
                            $total += $val['QuestionScore'];
                            $score = $val['QuestionScore'];
                            $judge = 1;
                        }
                    }
                    $question[$key]['judge'] = $judge;
                    $where = array('SectionInsID'=>$infos['SectionInsID'],'QuestionID'=>$val['QuestionID']);
                    $data = array('Answer'=>$answer,'Score'=>$score,'judge'=>$judge);
                    //更新答案 得分
                    $this->Section_model->update_practice_instance($where,$data);
                }

            }
            $whereS = array('SectionInsID'=>$infos['SectionInsID']);
            $dataS = array('SectionAnswerFinished'=>1,'FinishedTime'=>time());
            //更新节 完成时间 答题完成状体
            $this->Section_model->update_section_instance($whereS,$dataS);

            $output_data['code'] = '0000';
            $output_data['msg'] = count($question);
            $output_data['data'] = $question;

        }while(FALSE);
        //print_r($output_data);
        $this->interface_output->output_fomcat('js_Ajax', $output_data);

    }
    /**
     * 小节计算得分
     * type 1 加载页面 2.视频算分 3.小节算分
     */
    public function sectionScore(){
        $this->load->model ("Section_model");
        $this->load->model ("Task_model");
        $this->load->library('Interface_output');

        $info = $this->input->post();
        $output_data['data'] = array();
        do{
            if(intval($info['type']) <= 0){
                $output_data['code'] = '0430';
                $output_data['msg'] = '参数错误!';
                break;
            }
            if(intval($info['taskid']) <= 0){
                $output_data['code'] = '0431';
                $output_data['msg'] = '参数错误!';
                break;
            }

            if(intval($info['sectioninsid']) <= 0){
                $output_data['code'] = '0432';
                $output_data['msg'] = '参数错误!';
                break;
            }
            $userID = $this->session->userdata('UserID');
            $result = $this->Task_model->get_task(array('TaskId'=>$info['taskid'],'StudentID'=>$userID));
            if(count($result) == 0){
                $output_data['code'] = '0433';
                $output_data['msg'] = '该学习已结束!';
                break;
            }
            if($result[0]['TaskType'] == 2){
                $output_data['code'] = '0001';
                $output_data['msg'] = '该学习已结束!';
                break;
            }
            if($result[0]['TaskEndTime'] < time()){
                $this->Task_model->edit_task(array('TaskId'=>$info['taskid']),array('TaskType'=>2));
                $output_data['code'] = '0001';
                $output_data['msg'] = '已到学习结束时间,学习结束!';
                break;
            }
            /*
             * 1.查询小节 获得类型（1 有视频有题，2 有视频无题，3 无视频有题，4 无视频无题）
             * 2.计算小节分数
             * 3.更改 小节积分
             * 4.累加任务积分
             * 5.累加个人积分
             * */
            $pointMap = array(5, 10, 20); //初难度 中难度 高难度
            $section = $this->Section_model->get_section_info(array('si.SectionInsID'=>$info['sectioninsid']));
            if(!empty($section[0]['VideoUrl']) && $section[0]['PracticeNum'] > 0){
                $sectionType = 1;
            }else if(!empty($section[0]['VideoUrl']) && $section[0]['PracticeNum'] == 0){
                $sectionType = 2;
            }else if(empty($section[0]['VideoUrl']) && $section[0]['PracticeNum'] > 0){
                $sectionType = 3;
            }else if(empty($section[0]['VideoUrl']) && $section[0]['PracticeNum'] == 0){
                $sectionType = 4;
            }
            $data = array();
            $where = array('SectionInsID'=>$info['sectioninsid']);

            $output_data['code'] = '0001';
            $sectionScore = 0;
            // $info['type']  2 视频  3 答题
            switch($sectionType) {
                case 1://有视频有题
                    switch($info['type']){
                        case 1: break;
                        case 2://计算视频得分
                            $videoScore = $pointMap[$section[0]["SectionDiff"]] * 0.6;
                            $sectionScore = $videoScore;

                            $practiceScore = ($section[0]["PracticeScore"] / 100.0) * $pointMap[$section[0]["SectionDiff"]] * 0.4;
                            //更新小节得分要算 相加
                            $data['SectionInsPoint'] = $videoScore + $practiceScore;
                            $data['SectionVideoFinished'] = 1;
                            if($section[0]["SectionAnswerFinished"] == 1){
                                $data['Finished'] = 2;
                                $output_data['code'] = '0000';
                            }
                            break;
                        case 3://计算答题得分
                            $practiceScore = ($section[0]["PracticeScore"] / 100.0) * $pointMap[$section[0]["SectionDiff"]] * 0.4;
                            $sectionScore = $practiceScore;

                            $videoFinishedScore = $pointMap[$section[0]["SectionDiff"]] * $section[0]["SectionVideoFinished"] * 0.6;
                            $data['SectionInsPoint'] = $videoFinishedScore + $practiceScore;
                            if($section[0]["SectionVideoFinished"] == 1){
                                $data['Finished'] = 2;
                                $output_data['code'] = '0000';
                            }
                            break;

                    }
                    break;
                case 2://有视频无题
                    switch($info['type']){
                        case 1: break;
                        case 2: //计算视频得分
                            $sectionScore = $pointMap[$section[0]["SectionDiff"]] * 1;
                            $data['SectionInsPoint'] = $sectionScore;
                            $data['SectionVideoFinished'] = 1;
                            $data['Finished'] = 2;
                            $output_data['code'] = '0000';
                            break;
                        case 3: break;//无题
                    }
                    break;
                case 3://无视频有题
                    switch($info['type']){
                        case 1: break;
                        case 2: break;
                        case 3: //计算答题得分
                            $sectionScore = ($section[0]["PracticeScore"] / 100.0) * $pointMap[$section[0]["SectionDiff"]] * 1;
                            $data['SectionInsPoint'] = $sectionScore;
                            $data['Finished'] = 2;
                            $output_data['code'] = '0000';
                            break;
                    }
                    break;
                case 4://无视频无题
                    $sectionScore = 0;
                    $data['SectionInsPoint'] = $sectionScore;
                    $data['Finished'] = 2;
                    $output_data['code'] = '0000';
                    break;
            }
            
            //更新 得分
            $data['FinishedTime'] = time();
            $this->Section_model->update_section_instance($where,$data);

            //更新任务得分
            $this->Task_model->edit_task_score(array('TaskID'=>$info['taskid']),array('TaskScore'=>$sectionScore));

            $userID = $this->session->userdata('UserID');
            //更新个人得分
            $this->User_model->edit_user_score(array('UserID'=>$userID),array('UserPoint'=>$sectionScore));
            
            //更改任务进度
            $this->Task_model->edit_task_process(array('TaskID'=>$info['taskid']));

            $output_data['msg'] = '恭喜！您已学完本小节，得分'.$sectionScore.'分';
            $videoQ = array(2=>'看完本小节视频',3=>'答完本小节题目');
            //有题有视频 时 [是否可以进入下一小节]
            if($sectionType == 1){

                $output_data['code'] = '0002';
                $sectionScore = $data['SectionInsPoint'];
                $output_data['msg'] = '恭喜！您'.$videoQ[$info['type']].'，得分：'.$sectionScore.'分';
                //视频和题 全学完
                if(($info['type'] == 2 && $section[0]["SectionAnswerFinished"] == 1)||($info['type'] == 3 && $section[0]["SectionVideoFinished"] == 1)){
                    $output_data['code'] = '0000';
                }

            }

            $output_data['data']['sectionScore'] = $sectionScore;
            $output_data['data']['sectionType'] = $sectionType;

        }while(FALSE);

        $this->interface_output->output_fomcat('js_Ajax', $output_data);

    }

    //进入下一小节
    public function next_section(){
        $this->load->model ("Section_model");
        $this->load->library('Interface_output');

        $info = $this->input->post();
        $output_data['data'] = array();
        do {
            if (intval($info['taskid']) <= 0) {
                $output_data['code'] = '0431';
                $output_data['msg'] = '参数错误!';
                break;
            }

            if (intval($info['sectioninsid']) <= 0) {
                $output_data['code'] = '0432';
                $output_data['msg'] = '参数错误!';
                break;
            }
            $result = $this->Section_model->get_section_instance(array('TaskID'=>$info['taskid'],'SectionInsID >'=>$info['sectioninsid']));

            $output_data['code'] = '0001';
            $output_data['msg'] = '已是最后一小节';

            if(count($result) > 0){
                $output_data['code'] = '0000';
                $output_data['msg'] = $result[0]['SectionInsID'];
            }



        }while(FALSE);

        $this->interface_output->output_fomcat('js_Ajax', $output_data);

    }
    /*
     * 判断学员下是否存在学习的场景
     * */
    public function is_exsist_scene(){
        $this->load->model ("Section_model");
        $this->load->library('Interface_output');

        $userID = $this->session->userdata('UserID');
        $scene = $this->Section_model->is_exsist_scene(array('StudentID'=>$userID,'TaskSourceType'=>1,'SceneInstanceUUID !='=>NULL));
        $output_data['code'] = '0000';
        $output_data['msg'] = count($scene);
        $output_data['data'] = count($scene)?$scene[0]:array();

        $this->interface_output->output_fomcat('js_Ajax', $output_data);
    }

    /*
     * 下发场景
     * */
    public function create_scene(){
        $this->load->model ("Section_model");
        $this->load->library('Interface_output');
        $output_data['data'] = array();
        $sceneuuid = $this->input->post("sceneuuid");
        $sectioninsid = $this->input->post("sectioninsid");
        $sectionname = $this->input->post("sectionname");
        do {
            if (intval($sectioninsid) <= 0) {
                $output_data['code'] = '0431';
                $output_data['msg'] = '参数错误!';
                break;
            }

            $output_data = $this->Section_model->create_scene(array('SceneTemplateUUID'=>$sceneuuid));

            if($output_data['code'] == '0000'){
                $userID = $this->session->userdata('UserID');
                $this->load->library('Log_user');
                //写日志
                $log = array(
                    'LogTaskName' => $sectionname,
                    'UserID' => $userID,
                    'LogContent' => '下发“'.$output_data['data']['SceneTemplate']['scene_name'].'”场景',
                    'LogTypeID' => 3,
                    'LogResult' => $output_data['data']['scene_ins_uuid']
                );
                $this->log_user->add_log($log);
                //更改小节实例表
                $data = array('SceneInstanceUUID'=>$output_data['data']['scene_ins_uuid'], 'TaskUUID'=>$output_data['data']['task_uuid']);
                $this->Section_model->update_section_instance(array('SectionInsID'=>$sectioninsid),$data);
            }

        }while(FALSE);

        $this->interface_output->output_fomcat('js_Ajax', $output_data);
    }
    /*
     * 删除场景
     * */
    public function del_scene(){
        $this->load->model ("Section_model");
        $this->load->library('Interface_output');

        $sceneinstanceuuid = $this->input->post("sceneinstanceuuid");
        $sectioninsid = $this->input->post("sectioninsid");
        
        do {
            if (intval($sectioninsid) <= 0) {
                $output_data['code'] = '0431';
                $output_data['msg'] = '参数错误!';
                $output_data['data'] = array();
                break;
            }
            $output_data = $this->Section_model->del_scene(array('sceneinstanceuuid'=>$sceneinstanceuuid));
            if($output_data['code'] == '0000'){
                $data = array('TaskUUID'=>null,'SceneInstanceUUID'=>null);
                $this->Section_model->update_section_instance(array('SectionInsID'=>$sectioninsid),$data);
            }

        }while(FALSE);

        $this->interface_output->output_fomcat('js_Ajax', $output_data);

    }
    /*
     * 检查场景按钮状态
     * */
    public function check_scene_status(){
        $this->load->model ("Section_model");
        $this->load->library('Interface_output');

        $sectioninsid = $this->input->post("sectioninsid");
        $output_data['data'] = array();
        $output_data['code'] = '0000';
        $output_data['msg'] = '没有该小节';
        do {
            if (intval($sectioninsid) <= 0) {
                $output_data['code'] = '0431';
                $output_data['msg'] = '参数错误!';
                break;
            }
            $section = $this->Section_model->get_section_instance(array('SectionInsID'=>$sectioninsid));

            /*
             * 1、根据传来的SectionInsId查出相关的SectionIns的信息
             * 2、如果SceneInsUUID==NULL && TaskUUID == NULL，无场景
             * 3、如果SceneInsUUID!=NULL && TaskUUID != NULL，正在下发，需要进而求出任务进度和相关信息
             * 3.1、如果任务是正在进行的，则SceneInfo相关信息
             * 3.1.1、如果任务进度到达100%，则置数据库为下发完成状态
             * 3.2、如果任务是失败的，则填写下发失败的相关信息，置数据库为无场景状态
             * 4、如果SceneInsUUID!=NULL && TaskUUID == NULL，下发完成
             * */
            if(count($section) > 0){
                if($section[0]['SceneInstanceUUID'] == NULL && $section[0]['TaskUUID'] == NULL){  //如果SceneInsUUID==NULL && TaskUUID == NULL，无场景

                    $output_data['code'] = 1;
                    $output_data['msg'] = '申请实验环境';

                    $output_data['data']['sceneInsUUID'] = $section[0]['SceneInstanceUUID'];
                    $output_data['data']['msg'] = '';
                    $output_data['data']['taskUUID'] = $section[0]['TaskUUID'];
                    $output_data['data']['taskProcess'] = '0';

                }else if($section[0]['SceneInstanceUUID'] != NULL && $section[0]['TaskUUID'] != NULL){  //如果SceneInsUUID!=NULL && TaskUUID != NULL，正在下发，需要进而求出任务进度和相关信息

                    $progress = $this->Section_model->get_scene_progress($section[0]['TaskUUID']);

                    $task_status =array( '正在排队，请耐心等待！','已收到请求','已经开始申请','申请失败','正在重试','申请成功','已撤销','已拒绝','正在申请中','排队等候中');
                    if($progress['code'] == '0000'){
                        $real_status = $progress['data']['task_status'];
                        if($real_status == 6){//success

                            $this->Section_model->update_section_instance(array('SectionInsID'=>$sectioninsid),array('TaskUUID'=>NULL));

                            $output_data['code'] = 3;
                            $output_data['msg'] = '进入场景';

                            $output_data['data']['sceneInsUUID'] = $section[0]['SceneInstanceUUID'];
                            $output_data['data']['msg'] = $task_status[$real_status-1];
                            $output_data['data']['taskUUID'] = $section[0]['TaskUUID'];
                            $output_data['data']['taskProcess'] = $progress['data']['task_percent'];

                        }else if($real_status == 4 || $real_status == 7 || $real_status ==8  ){ //失败
                            $data = array('TaskUUID'=>NULL,'SceneInstanceUUID'=>NULL);
                            $this->Section_model->update_section_instance(array('SectionInsID'=>$sectioninsid),$data);

                            $output_data['code'] = 4;
                            $output_data['msg'] = '下发失败，重新申请';
                            $output_data['data']['sceneInsUUID'] = $section[0]['SceneInstanceUUID'];
                            $output_data['data']['msg'] = $task_status[$real_status-1];
                            $output_data['data']['taskUUID'] = $section[0]['TaskUUID'];
                            $output_data['data']['taskProcess'] = $progress['data']['task_percent'];

                        }else{
                            $output_data['code'] = 2;
                            $output_data['msg'] = '正在下发';
                            $output_data['data']['sceneInsUUID'] = $section[0]['SceneInstanceUUID'];
                            $output_data['data']['msg'] = $task_status[$real_status-1];
                            $output_data['data']['taskUUID'] = $section[0]['TaskUUID'];
                            $output_data['data']['taskProcess'] = $progress['data']['task_percent'];
                        }
                    }
                }else if($section[0]['SceneInstanceUUID'] != NULL && $section[0]['TaskUUID'] == NULL){
                    //如果SceneInsUUID!=NULL && TaskUUID == NULL，下发完成，这里不判断场景是否还正常存在，节约资源
                    $output_data['code'] = 3;
                    $output_data['msg'] = '进入场景';

                    $output_data['data']['sceneInsUUID'] = $section[0]['SceneInstanceUUID'];
                    $output_data['data']['msg'] = '';
                    $output_data['data']['taskUUID'] = $section[0]['TaskUUID'];
                    $output_data['data']['taskProcess'] = '0';
                }
            }

        }while(FALSE);

        $this->interface_output->output_fomcat('js_Ajax', $output_data);

    }

    //判断场景
    public function judge_scene(){
        $this->load->model ("Section_model");
        $this->load->library('Interface_output');

        $sceneinstanceuuid = $this->input->post("sceneinstanceuuid");

        $output_data = $this->Section_model->judge_scene(array('sceneinstanceuuid'=>$sceneinstanceuuid));

        $this->interface_output->output_fomcat('js_Ajax', $output_data);
    }

    //场景不存在 清空数据库的值
    public function update_scene(){
        $this->load->model ("Section_model");
        $this->load->library('Interface_output');

        $sectioninsid = $this->input->post("sectioninsid");

        $output_data['data'] = array();
        do {
            if (intval($sectioninsid) <= 0) {
                $output_data['code'] = '0431';
                $output_data['msg'] = '参数错误!';
                break;
            }
            $data = array('TaskUUID'=>null,'SceneInstanceUUID'=>null);
            $this->Section_model->update_section_instance(array('SectionInsID'=>$sectioninsid),$data);

            $output_data['code'] = '0000';
            $output_data['msg'] = '更改成功';
        }while(FALSE);

        $this->interface_output->output_fomcat('js_Ajax', $output_data);
    }
    
    //进入场景
    public function enter_scene(){
        $this->load->model ("Section_model");
        $this->load->model ("System_model");
        $this->load->library('Interface_output');

        $sceneinstanceuuid = $this->input->post("sceneinstanceuuid");

        $output_data = $this->Section_model->enter_scene(array('sceneinstanceuuid'=>$sceneinstanceuuid));
        if($output_data['code'] == '0000'){
            $ip = $this->System_model->get_system_port(array('ctfip'=>$output_data['data']['ip']));
            if(count($ip) == 0){
                $output_data['code'] = '0331';
                $output_data['msg'] = 'IP与端口映射错误,请联系管理员进行设置';
                $output_data['data'] = array();

            }else{
                $output_data['data']['port'] = $ip[0]['localport'];
                $output_data['data']['ip'] = $_SERVER["SERVER_ADDR"];
            }

        }

        $this->interface_output->output_fomcat('js_Ajax', $output_data);
    }
    //场景页
    public function vm_vnc(){
        $data['uuid'] = $this->security->xss_clean( htmlspecialchars( $this->input->get('uuid') ) );
        $data['loguser'] = $this->security->xss_clean( htmlspecialchars( $this->input->get('loguser') ) );
        $data['logpwd'] = $this->security->xss_clean( htmlspecialchars( $this->input->get('logpwd') ) );
        $data['vmuuid'] = $this->security->xss_clean( htmlspecialchars( $this->input->get('vmuuid') ) );
        $data['token'] = $this->security->xss_clean( htmlspecialchars( $this->input->get('token') ) );
        $data['ip'] = $this->security->xss_clean( htmlspecialchars( $this->input->get('ip') ) );
        $data['port'] = $this->security->xss_clean( htmlspecialchars( $this->input->get('port') ) );
        $data['sid'] = $this->security->xss_clean( htmlspecialchars( $this->input->get('sid') ) );
        $data['host_id'] = $this->security->xss_clean( htmlspecialchars( $this->input->get('host_id') ) );
        $scene_end_time = $this->security->xss_clean( htmlspecialchars( $this->input->get('scene_end_time') ) );
        $data['scene_time'] = strtotime($scene_end_time) - time();//场景倒计时

        $data['sectionname'] = $this->security->xss_clean( htmlspecialchars( $this->input->get('sectionname') ) );
        $data['uurl'] = 'http://'.$data['ip'].':'.$data['port'].'/vnc_auto.html?token='.$data['token'];
        $this->load->view("student/study_vnc",$data);
    }
    
    //检查场景是否存在
    public function check_scene(){
        $this->load->model ("Section_model");
        $this->load->library('Interface_output');

        $sceneinstanceuuid = $this->input->post("sceneinstanceuuid");
        $host_id = $this->input->post("host_id");
        $sectioninsid = $this->input->post("sectioninsid");

        $output_data['data'] = array();
        do {
            if (intval($sectioninsid) <= 0) {
                $output_data['code'] = '0431';
                $output_data['msg'] = '参数错误!';
                break;
            }

            if (intval($host_id) <= 0) {
                $output_data['code'] = '0432';
                $output_data['msg'] = '参数错误!';
                break;
            }
            //检查场景接口
            $output_data = $this->Section_model->check_scene(array('sceneinstanceuuid'=>$sceneinstanceuuid,'host_id'=>$host_id));

            //不存在
            if($output_data['code'] == '0000' && $output_data['data']['result'] != 1){
                $output_data['code'] = '0001';
                $data = array('TaskUUID'=>null,'SceneInstanceUUID'=>null);
                $this->Section_model->update_section_instance(array('SectionInsID'=>$sectioninsid),$data);
                break;
            }

            //检查数据库
            $section = $this->Section_model->get_section_instance(array('SectionInsID'=>$sectioninsid,'SceneInstanceUUID !='=>NULL,'TaskUUID'=>NULL));
            if(count($section) == 0){
                $output_data['code'] = '0001';
                $output_data['msg'] = '数据库-场景不存在';
                break;
            }

        }while(FALSE);

        $this->interface_output->output_fomcat('js_Ajax', $output_data);
    }
    
}
