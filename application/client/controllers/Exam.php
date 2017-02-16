<?php
/**
 * Created by PhpStorm.
 * User: kyx
 * Date: 2016/8/3
 * Time: 10:30
 */

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 学生端我的考试控制器
 *
 */
class Exam extends ECQ_Controller{

    /**
     * 正在进行的考试列表页
     */
    public function listunderway(){
        $this->load->model ("Exam_model");
        $this->load->library ('Filter');

        $userID = $this->session->userdata('UserID');
        $search = $this->input->get("search");
        $page = $this->input->get("per_page");
        $sort = $this->input->get('sort');
        $search = $search ? urldecode($search) : '';//搜索字符串要转码
        //安全过滤
        $search = $this->security->xss_clean($search);
        $page = max(intval($page), 1);
        $num = 10;
        $offset = ($page - 1) * $num;
        $url = $this->filter->generateBaseUrl("sort");
        $sortList = '';
        $data["Sort"]["TaskTime"]["url"] = $url."sort=TaskTime|asc";
        $data["Sort"]["TaskTime"]["icon"] = "fa fa-sort fw";
        $data["Sort"]["ExamType"]["url"] = $url."sort=ExamType|asc";
        $data["Sort"]["ExamType"]["icon"] = "fa fa-sort fw";
        $data["Sort"]["UserName"]["url"] = $url."sort=UserName|asc";
        $data["Sort"]["UserName"]["icon"] = "fa fa-sort fw";
        $data["Sort"]["Stime"]["url"] = $url."sort=Stime|asc";
        $data["Sort"]["Stime"]["icon"] = "fa fa-sort fw";
        if ($sort != "") {
            $sortList = explode("|",$sort);
            if ($sortList[0] == "TaskTime"){

                $data["Sort"]["TaskTime"]["url"] = $url."sort=TaskTime|".(($sortList[1]=="asc")?"desc":"asc");
                $data["Sort"]["TaskTime"]["icon"] = "fa fa-sort-amount-" . (($sortList[1]=="asc")?"asc":"desc") . " fw";

            } else if ($sortList[0] == "ExamType") {

                $data["Sort"]["ExamType"]["url"] = $url."sort=ExamType|".(($sortList[1]=="asc")?"desc":"asc");
                $data["Sort"]["ExamType"]["icon"] = "fa fa-sort-amount-" . (($sortList[1]=="asc")?"asc":"desc") . " fw";

            } else if($sortList[0] == "UserName") {

                $data["Sort"]["UserName"]["url"] = $url."sort=UserName|".(($sortList[1]=="asc")?"desc":"asc");
                $data["Sort"]["UserName"]["icon"] = "fa fa-sort-amount-" . (($sortList[1]=="asc")?"asc":"desc") . " fw";

            } else if($sortList[0] == "UserName") {

                $data["Sort"]["Stime"]["url"] = $url."sort=Stime|".(($sortList[1]=="asc")?"desc":"asc");
                $data["Sort"]["Stime"]["icon"] = "fa fa-sort-amount-" . (($sortList[1]=="asc")?"asc":"desc") . " fw";

            }else{
                redirect('Exam/listunderway');
            }
        }
        $where = array('TaskSourceType'=>2,'TaskType !='=>2,'StudentID'=>$userID);
        $data['total'] = count($this->Exam_model->exam_list($where,$search));
        //数据
        $data['data'] = $this->Exam_model->exam_list($where,$search,$offset,$num,$sortList);

        //搜索
        $data['search'] = $search;
        $data['search_url'] = $this->filter->generateBaseUrl("search");

        //分页
        $per_page =  $this->filter->generateBaseUrl("per_page");
        $data['page_url'] = $per_page.'per_page=';;
        $data['page_count'] = ceil($data['total']/10);
        $data['page_pre'] = $page;

        $this->load->view('student/exam_underway',$data);
    }

    /**
     * 已完成的考试列表页
     */
    public function listfinished(){
        $this->load->model ("Exam_model");
        $this->load->library ('Filter');
        
        $userID = $this->session->userdata('UserID');
        $search = $this->input->get("search");
        $page = $this->input->get("per_page");
        $sort = $this->input->get("sort");
        $search = $search ? urldecode($search) : '';//搜索字符串要转码
        //安全过滤
        $search = $this->security->xss_clean($search);
        $page = max(intval($page), 1);
        $num = 10;
        $offset = ($page - 1) * $num;

        $url = $this->filter->generateBaseUrl("sort");
        $sortList = '';
        $data["Sort"]["TaskTime"]["url"] = $url."sort=TaskTime|asc";
        $data["Sort"]["TaskTime"]["icon"] = "fa fa-sort fw";
        $data["Sort"]["ExamType"]["url"] = $url."sort=ExamType|asc";
        $data["Sort"]["ExamType"]["icon"] = "fa fa-sort fw";
        $data["Sort"]["UserName"]["url"] = $url."sort=UserName|asc";
        $data["Sort"]["UserName"]["icon"] = "fa fa-sort fw";
        $data["Sort"]["Stime"]["url"] = $url."sort=Stime|asc";
        $data["Sort"]["Stime"]["icon"] = "fa fa-sort fw";
        $data["Sort"]["TaskStartTime"]["url"] = $url."sort=TaskStartTime|asc";
        $data["Sort"]["TaskStartTime"]["icon"] = "fa fa-sort fw";
        $data["Sort"]["TaskEndTime"]["url"] = $url."sort=TaskEndTime|asc";
        $data["Sort"]["TaskEndTime"]["icon"] = "fa fa-sort fw";
        if ($sort != "") {
            $sortList = explode("|",$sort);
            if ($sortList[0] == "TaskTime"){

                $data["Sort"]["TaskTime"]["url"] = $url."sort=TaskTime|".(($sortList[1]=="asc")?"desc":"asc");
                $data["Sort"]["TaskTime"]["icon"] = "fa fa-sort-amount-" . (($sortList[1]=="asc")?"asc":"desc") . " fw";

            } else if ($sortList[0] == "ExamType") {

                $data["Sort"]["ExamType"]["url"] = $url."sort=ExamType|".(($sortList[1]=="asc")?"desc":"asc");
                $data["Sort"]["ExamType"]["icon"] = "fa fa-sort-amount-" . (($sortList[1]=="asc")?"asc":"desc") . " fw";

            } else if($sortList[0] == "UserName") {

                $data["Sort"]["UserName"]["url"] = $url."sort=UserName|".(($sortList[1]=="asc")?"desc":"asc");
                $data["Sort"]["UserName"]["icon"] = "fa fa-sort-amount-" . (($sortList[1]=="asc")?"asc":"desc") . " fw";

            } else if($sortList[0] == "UserName") {

                $data["Sort"]["Stime"]["url"] = $url."sort=Stime|".(($sortList[1]=="asc")?"desc":"asc");
                $data["Sort"]["Stime"]["icon"] = "fa fa-sort-amount-" . (($sortList[1]=="asc")?"asc":"desc") . " fw";

            } else if($sortList[0] == "TaskStartTime") {

                $data["Sort"]["TaskStartTime"]["url"] = $url."sort=TaskStartTime|".(($sortList[1]=="asc")?"desc":"asc");
                $data["Sort"]["TaskStartTime"]["icon"] = "fa fa-sort-amount-" . (($sortList[1]=="asc")?"asc":"desc") . " fw";

            } else if($sortList[0] == "TaskEndTime") {

                $data["Sort"]["TaskEndTime"]["url"] = $url."sort=TaskEndTime|".(($sortList[1]=="asc")?"desc":"asc");
                $data["Sort"]["TaskEndTime"]["icon"] = "fa fa-sort-amount-" . (($sortList[1]=="asc")?"asc":"desc") . " fw";

            }else{
                redirect('Exam/listfinished');
            }
        }

        $where = array('TaskSourceType'=>2,'TaskType'=>2,'StudentID'=>$userID);
        $data['total'] = count($this->Exam_model->exam_list($where,$search));
        //echo $this->db->last_query();
        $data['data'] = $this->Exam_model->exam_list($where,$search,$offset,$num,$sortList);

        //搜索
        $data['search'] = $search;
        $data['search_url'] = $this->filter->generateBaseUrl("search");

        //分页
        $per_page =  $this->filter->generateBaseUrl("per_page");
        $data['page_url'] = $per_page.'per_page=';;
        $data['page_count'] = ceil($data['total']/10);
        $data['page_pre'] = $page;

        $this->load->view('student/exam_finished',$data);
    }
    /**
     * 实时更新未开始考试列表
     */
    public function get_exam_list(){
        $this->load->model ("Exam_model");
        $this->load->library('Interface_output');

        $userID = $this->session->userdata('UserID');
        $search = $this->input->post("search");
        $page = $this->input->post("per_page");
        $sort = $this->input->post("sort");
        $search = $search ? urldecode($search) : '';//搜索字符串要转码
        //安全过滤
        $search = $this->security->xss_clean($search);
        $page = max(intval($page), 1);
        $num = 10;
        $offset = ($page - 1) * $num;
        $sortList = '';
        if ($sort != "") {
            $sortList = explode("|",$sort);
        }
        $where = array('TaskSourceType'=>2,'TaskType !='=>2,'StudentID'=>$userID);
        $output_data['msg'] = count($this->Exam_model->exam_list($where,$search));
        //echo $this->db->last_query();
        $output_data['data'] = $this->Exam_model->exam_list($where,$search,$offset,$num,$sortList);
        $output_data['code'] = '0000';
        $this->interface_output->output_fomcat('js_Ajax', $output_data);
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
                $output_data['code'] = '0421';
                $output_data['msg'] = '该考试未开始!';
                break;
            }
            if($result[0]['TaskStartTime'] > time()){
                $output_data['code'] = '0422';
                $output_data['msg'] = '该考试未开始!';
                break;
            }
            if($result[0]['TeaEnd'] == 1){
                $output_data['code'] = '0001';
                $output_data['msg'] = '老师强制结束了考试!';
                break;
            }
            if($result[0]['TaskType'] == 2){
                $output_data['code'] = '0001';
                $output_data['msg'] = '该考试已结束!';
                break;
            }

            if($result[0]['TaskEndTime'] < time()){
                $this->Task_model->edit_task(array('TaskId'=>$taskid),array('TaskType'=>2));
                $output_data['code'] = '0001';
                $output_data['msg'] = '已到考试结束时间,考试结束!';
                break;
            }
            $output_data['code'] = '0000';
            $output_data['msg'] = '开始考试';
            $output_data['data']['currentTime'] = date('Y/m/d H:i:s');
            $output_data['data']['endTime'] = date('Y/m/d H:i:s',$result[0]['TaskEndTime']);
            $this->Task_model->edit_task(array('TaskId'=>$taskid),array('TaskType'=>1));
        }while(FALSE);

        $this->interface_output->output_fomcat('js_Ajax', $output_data);
    }
    /**
     * 结束考试
     * $taskid 任务id
     */
    public function endexam(){
        $this->load->model ("Task_model");
        $this->load->model ("Exam_model");
        $this->load->library('Interface_output');

        $taskid = $this->input->post("taskid");

        $output_data['data'] = array();
        do {
            if(intval($taskid) <= 0){
                $output_data['code'] = '0420';
                $output_data['msg'] = '参数错误!';
                break;
            }

            $result = $this->Exam_model->get_question_instance_info(array('qi.TaskId'=>$taskid));
            if(count($result) == 0){
                $output_data['code'] = '0421';
                $output_data['msg'] = '参数错误!';
                break;
            }
            $sceneUUID = '';
            foreach ($result as $v){

                if($v['SceneInstanceUUID'] != ''){
                    $sceneUUID = $v['SceneInstanceUUID'];
                }
            }
            //更改数据库 任务类型和得分
            $this->Task_model->edit_task(array('TaskId'=>$taskid),array('TaskType'=>2,'TaskFinishedTime'=>time()));


            $finally = $this->Task_model->get_task(array('TaskCode'=>$result[0]['TaskCode'],'TaskType !='=>2));
            // 最后一个提交试卷 并且 SceneTaskID != 0  删除计划任务
            if(count($finally) == 0 && $result[0]['SceneTaskID'] != 0){

                $task_scene = $this->Exam_model->del_task_scene($result[0]['SceneTaskID']);
                //计划任务删除成功 [SceneTaskID值为0]
                if($task_scene['code'] == '0000'){
                    $this->Task_model->edit_task(array('TaskCode'=>$result[0]['TaskCode']),array('SceneTaskID'=>0));
                }

                //存在场景   不是最后一个提交试卷 -->删除场景
            }else if(!empty($sceneUUID) && $result[0]['SceneTaskID'] != 0){

                //删除场景接口
                $this->del_scene($sceneUUID);

                $data = array('TaskUUID'=>null,'SceneInstanceUUID'=>null);
                $this->Exam_model->edit_question_instance(array('TaskId'=>$taskid),$data);

            }

            $output_data['code'] = '0000';
            $output_data['msg'] = '考试状态更改成功';

        }while(FALSE);
        
        $this->interface_output->output_fomcat('js_Ajax', $output_data);
    }

    /**
     * 考试页
     */
    public function exampaper(){
        $this->load->model ("Task_model");
        $this->load->model ("Exam_model");
        $this->load->model ("Ctf_model");
        $this->load->library ('Utilities');
        $data = array();
        $taskid = $this->input->get("taskid");
        if(intval($taskid) <= 0){
            redirect('Exam/listunderway');
        }
        $userID = $this->session->userdata('UserID');
        $result = $this->Task_model->get_task(array('TaskId'=>$taskid,'StudentID'=>$userID));
        if(count($result) == 0){
            redirect('Exam/listunderway');
        }
        if($result[0]['TaskType'] == 2){
            //考试已结束
            redirect('Exam/examshow?taskid='.$taskid);
        }


        $data['data'] = $this->Exam_model->get_question_instance_info(array('qi.TaskId'=>$taskid));
        $data['sceneinstanceuuid'] = '';
        $data['taskuuid'] = '';
        $data['id'] = '';
        foreach ($data['data'] as $key=>$val){
            $val['QuestionDesc'] = $this->utilities->clearMarkdown($val['QuestionDesc']);
            $data['data'][$key]["QuestionDesc"] = str_replace(array('[info]','[/info]'), '', $val["QuestionDesc"]);
            if($val['CtfUrl'] != ''){
                $data['data'][$key]['CtfUrl'] = $this->Ctf_model->get_ctf_url($val['CtfServerID'],$val['CtfServerPort'],$val['CtfUrl']);
            }

            if($val['SceneInstanceUUID'] != ''){
                $data['sceneinstanceuuid'] = $val['SceneInstanceUUID'];
                $data['id'] = $val['ID'];
                $data['taskuuid'] = $val['TaskUUID'];
            }
        }
        //日志
        $this->load->library('Log_user');
        $log = array(
            'LogTaskName' => $data['data'][0]['TaskName'],
            'LogContent' => '参加了名为“'.$data['data'][0]['TaskName'].'”考试',
            'LogTypeID' => 2,
            'LogResult' => site_url('Exam/exampaper?taskid='.$taskid),
            'UserID' => $this->session->userdata('UserID')
        );
        $this->log_user->add_log($log);
        //print_r($data);
        $this->load->view('student/exam_paper',$data);
    }

    /*
     * 保存试卷答案
     * 答案是实时变化，所以需要不停发送ajax调用次保存答案方法
     * */
    public function saveanswer(){
        $this->load->model ("Exam_model");
        $this->load->library('Interface_output');

        $info = $this->input->post();
        //参数判断
        $output_data['data'] = array();
        do {
            if(intval($info['taskid']) <= 0){
                $output_data['code'] = '0420';
                $output_data['msg'] = '参数错误!';
                break;
            }
            if(intval($info['questionid']) <= 0){
                $output_data['code'] = '0420';
                $output_data['msg'] = '参数错误!';
                break;
            }
            $where = array('TaskID'=>$info['taskid'],'QuestionID'=>$info['questionid']);
            $data = array('Answer'=>$info['answer']);
            $this->Exam_model->edit_question_instance($where,$data);
            $output_data['code'] = '0000';
            $output_data['msg'] = '提交成功';

        }while(FALSE);

        $this->interface_output->output_fomcat('js_Ajax', $output_data);
    }

    /*
     * 提交试卷（提交后跳转到展示页面）
     *
     * */
    public function handpaper(){
        /*
         * 查询所有题
         * 判断得分
         * 更改数据库 试卷的总得分
         * */
        $this->load->model ("Exam_model");
        $this->load->model ("Task_model");
        $this->load->library('Interface_output');
        $output_data['data'] =array();

        $taskid = $this->input->post('taskid');
        do {
            if(intval($taskid) <= 0){
                $output_data['code'] = '0420';
                $output_data['msg'] = '参数错误!';
                break;
            }

            $result = $this->Exam_model->get_question_instance_info(array('qi.TaskId'=>$taskid));
            if(count($result) == 0){
                $output_data['code'] = '0421';
                $output_data['msg'] = '参数错误!';
                break;
            }
            $total = 0;
            $sceneUUID = '';
            foreach ($result as $v){
                if($v['QuestionType'] == 2){
                    $questionanswer = explode('|||',$v['QuestionAnswer']);
                    $answer = explode('|||',$v['Answer']);
                    $arr_diff = array_diff($questionanswer, $answer);
                    if(count($arr_diff) == 0){
                        $total += $v['QuestionScore'];
                    }
                }else {
                    if($v['Answer'] == $v['QuestionAnswer']){
                        $total += $v['QuestionScore'];
                    }
                }
                if($v['SceneInstanceUUID'] != ''){
                    $sceneUUID = $v['SceneInstanceUUID'];
                }
            }
            //更改数据库 任务类型和得分
            $this->Task_model->edit_task(array('TaskId'=>$taskid),array('TaskType'=>2,'TaskScore'=>$total,'TaskFinishedTime'=>time()));


            $finally = $this->Task_model->get_task(array('TaskCode'=>$result[0]['TaskCode'],'TaskType !='=>2));
            // 最后一个提交试卷 并且 SceneTaskID != 0  删除计划任务
            if(count($finally) == 0 && $result[0]['SceneTaskID'] != 0){

                $task_scene = $this->Exam_model->del_task_scene($result[0]['SceneTaskID']);
                //计划任务删除成功 [SceneTaskID值为0]
                if($task_scene['code'] == '0000'){
                    $this->Task_model->edit_task(array('TaskCode'=>$result[0]['TaskCode']),array('SceneTaskID'=>0));
                }

            //存在场景   不是最后一个提交试卷 -->删除场景
            }else if(!empty($sceneUUID) && $result[0]['SceneTaskID'] != 0){

                //删除场景接口
                $this->del_scene($sceneUUID);
                //清空数据库
                $data = array('TaskUUID'=>null,'SceneInstanceUUID'=>null);
                $this->Exam_model->edit_question_instance(array('TaskId'=>$taskid),$data);

            }

            $output_data['code'] = '0000';
            $output_data['msg'] = '考试提交成功';

        }while(FALSE);

        $this->interface_output->output_fomcat('js_Ajax', $output_data);
    }

    /**
     * 考试完成 试卷展示页
     */
    public function examshow(){
        $this->load->model ("Task_model");
        $this->load->model ("Exam_model");
        $this->load->model ("Ctf_model");
        $this->load->library ('Utilities');

        $taskid = $this->input->get('taskid');

        do {
            if(intval($taskid) <= 0){
                redirect('Exam/listfinished');
            }
            $userID = $this->session->userdata('UserID');
            $result = $this->Task_model->get_task(array('TaskId'=>$taskid,'StudentID'=>$userID));
            $data['task']=$result;
            if(count($result) == 0){
                redirect('Exam/listfinished');
            }
            if($result[0]['TaskType'] != 2){
                //考试未结束';
                redirect('Exam/exampaper?taskid='.$taskid);
            }

            $data['data'] = $this->Exam_model->get_question_instance_info(array('qi.TaskId'=>$taskid));

            if(count($data['data']) == 0){
                //任务类型错误 或者没有题
                redirect('Exam/listfinished');
            }

            foreach ($data['data'] as $key=>$val){

                if($val['CtfUrl'] != ''){
                    $data['data'][$key]['CtfUrl'] = $this->Ctf_model->get_ctf_url($val['CtfServerID'],$val['CtfServerPort'],$val['CtfUrl']);
                }

                $val['QuestionDesc'] = $this->utilities->clearMarkdown($val['QuestionDesc']);
                $data['data'][$key]["QuestionDesc"] = str_replace(array('[info]','[/info]'), '', $val["QuestionDesc"]);
            }

        }while(FALSE);

        $this->load->view('student/exam_show',$data);

    }

    //删除场景 直接返回值 [原因：删除场景需要至少5秒才有返回值]
    function del_scene($sceneUUID){
        $this->load->model ("Section_model");
        $this->Section_model->del_scene(array('sceneinstanceuuid'=>$sceneUUID));
        return true;
    }

    //场景不存在 清空数据库的值
    public function update_scene(){
        $this->load->model ("Exam_model");
        $this->load->library('Interface_output');

        $id = $this->input->post("id");

        $output_data['data'] = array();
        do {
            if (intval($id) <= 0) {
                $output_data['code'] = '0431';
                $output_data['msg'] = '参数错误!';
                break;
            }
            $data = array('TaskUUID'=>null,'SceneInstanceUUID'=>null);
            $this->Exam_model->edit_question_instance(array('ID'=>$id),$data);

            $output_data['code'] = '0000';
            $output_data['msg'] = '更改成功';
        }while(FALSE);

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
        $this->load->view("student/exam_vnc",$data);
    }
    
    //检查场景是否存在
    public function check_scene(){
        $this->load->model ("Section_model");
        $this->load->model ("Exam_model");
        $this->load->library('Interface_output');

        $sceneinstanceuuid = $this->input->post("sceneinstanceuuid");
        $host_id = $this->input->post("host_id");
        $sid = $this->input->post("sid");

        $output_data['data'] = array();
        do {
            if (intval($sid) <= 0) {
                $output_data['code'] = '0431';
                $output_data['msg'] = '参数错误!';
                break;
            }

            if (intval($host_id) <= 0) {
                $output_data['code'] = '0432';
                $output_data['msg'] = '参数错误!';
                break;
            }
            //检查数据库
            $section = $this->Exam_model->get_question_instance(array('ID'=>$sid,'SceneInstanceUUID !='=>NULL,'TaskUUID !='=>NULL));

            if(count($section) == 0){
                $output_data['code'] = '0001';
                $output_data['msg'] = '数据库-场景不存在';
                break;
            }
            //检查场景接口
            $output_data = $this->Section_model->check_scene(array('sceneinstanceuuid'=>$sceneinstanceuuid,'host_id'=>$host_id));

            //不存在
            if($output_data['code'] == '0000' && $output_data['data']['result'] != 1){
                $output_data['code'] = '0001';
                $data = array('TaskUUID'=>null,'SceneInstanceUUID'=>null);
                $this->Section_model->update_section_instance(array('SectionInsID'=>$sid),$data);
            }
        }while(FALSE);

        $this->interface_output->output_fomcat('js_Ajax', $output_data);
    }

}
