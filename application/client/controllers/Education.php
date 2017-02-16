<?php
/**
 * Created by PhpStorm.
 * User: kyx
 * Date: 2016/8/29
 * Time: 10:00
 */

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 教师端 教学任务管理控制器
 *
 */
class Education extends ECQ_Controller{

    /**
     * 课程列表页
     */
    public function edubook(){
        $this->load->model("Architecture_model");
        $this->load->model("Package_model");
        $this->load->library ('Filter');

        $archid = $this->input->get('archid');
        $sonid = $this->input->get('sonid');
        $diff = $this->input->get('diff');
        $exp = $this->input->get('exp');
        $search = $this->input->get('search');
        $sort = $this->input->get('sort');
        $page = $this->input->get('per_page');

        $search = $search ? urldecode($search) : '';//搜索字符串要转码
        $search = $this->security->xss_clean($search);//安全过滤

        $data['filter'] = $this->filter->getFilterData(0b01010101);

        // 培训方案参数判断
        $arch = $this->Architecture_model->judge(array('ArchitectureID' => $archid));
        if ($arch === 3) {
            redirect('Education/edubook');
        }
        // 课程体系参数判断
        $son = $this->Architecture_model->judge(array( 'ArchitectureID' => $sonid));
        if ($son == 3) {
            redirect('Education/edubook');
        }
        $data["Sort"] = array();
        $data["Sort"]["time"] = array();
        $data["Sort"]["diff"] = array();

        $url = $this->filter->generateBaseUrl("sort");

        $data["Sort"]["diff"]["url"] = $url."sort=diff|asc";
        $data["Sort"]["diff"]["icon"] = "fa fa-sort fw";
        $data["Sort"]["time"]["url"] = $url."sort=time|asc";
        $data["Sort"]["time"]["icon"] = "fa fa-sort fw";
        $sortList = array();
        if ($sort != "") {
            $sortList = explode("|",$sort);
            if ($sortList[0] == "diff"){

                $data["Sort"]["diff"]["url"] = $url."sort=diff|".(($sortList[1]=="asc")?"desc":"asc");
                $data["Sort"]["diff"]["icon"] = "fa fa-sort-amount-" . (($sortList[1]=="asc")?"asc":"desc") . " fw";

            } else if ($sortList[0] == "time") {

                $data["Sort"]["time"]["url"] = $url."sort=time|".(($sortList[1]=="asc")?"desc":"asc");
                $data["Sort"]["time"]["icon"] = "fa fa-sort-amount-" . (($sortList[1]=="asc")?"asc":"desc") . " fw";

            }else{
                redirect('Education/edubook');
            }
        }

        $condition = array('archid' => $archid, 'sonid' => $sonid, 'diff' => $diff, 'exp' => $exp, 'search' => $search);
        $total_num = $this->Package_model->get_all_book_num($condition);

        //书的总数
        $data['book_num'] = $total_num[0]['PackageNum'];
        //总节数
        $data['section_num'] = $total_num[0]['SectionNum'];

        //书列表
        $page = max(intval($page), 1);
        $num = 10;//每页记录数
        $offset = ($page - 1) * $num;
        $condition['offset'] = $offset;
        $condition['num'] = $num;
        $condition['sort'] = $sortList;

        //课程信息
        $data['book'] =  $this->Package_model->get_all_book($condition);

        //分页
        $per_page =  $this->filter->generateBaseUrl("per_page");
        $data['page_url'] = $per_page.'per_page=';;
        $data['page_count'] = ceil($data['book_num']/10);
        $data['page_pre'] = $page;

        $data['search'] = $search;
        $data['search_url'] = $this->filter->generateBaseUrl("search");

        //下发弹窗时间
        $data['starttime'] = date('Y-m-d H:i:s',time());
        $data['endtime'] = date('Y-m-d H:i:s',strtotime("+1 month"));

        $this->load->view('teacher/edu_book', $data);
    }

    /**
     * 课程详情页
     */
    public function bookdetail(){
        $packageID = $this->input->get('packageid');
        $this->load->model("Package_model");
        //左侧导航 选中状态
        $this->nav['left_nav_id'] = 38;

        $pack = $this->Package_model->judge(array( 'PackageID' => $packageID,'PackageParent'=>0));
        if ($pack != 1) {
            redirect('Book/lists');
        }
        $data['data'] = $this->Package_model->get_book_detail(array('PackageID' => $packageID));
        //print_r($data['data']);
        //下发弹窗时间
        $data['starttime'] = date('Y-m-d H:i:s',time());
        $data['endtime'] = date('Y-m-d H:i:s',strtotime("+1 month"));

        $this->load->view('teacher/edu_book_detail',$data);
    }

    /**
     * 小节详情页
     */
    public function sectiondetail(){
        $this->load->model("Package_model");
        $this->load->model("Section_model");
        $this->load->model("Video_model");
        $this->load->model("Ctf_model");
        $this->load->library ('Utilities');
        //左侧导航 选中状态
        $this->nav['left_nav_id'] = 38;

        $sectionID = $this->input->get('sectionid');
        $packageID = $this->input->get('packageid');

        $pack = $this->Package_model->judge(array('PackageID' => $packageID,'PackageParent'=>0));
        if ($pack != 1) {

            redirect('Book/bookdetail?packageid='.$packageID);

        }
        $sec = $this->Section_model->judge(array('SectionID' => $sectionID));
        if ($sec != 1) {
            redirect('Book/bookdetail?packageid='.$packageID);
        }

        //课程名称
        $data = array();
        $package = $this->Package_model->get_book_chapter(array('PackageID' => $packageID));
        $data['packagename'] = $package[0]['PackageName'];
        $data['packageid'] = $package[0]['PackageID'];
        //获取小节
        $data['section'] = $this->Section_model->get_section(array('SectionID' => $sectionID));

        if ($data['section'][0]['SectionType'] == 0 && $data['section'][0]['VideoUrl'] != null) {
            $data['section'][0]['VideoUrl'] = $this->Video_model->get_video_url($data['section'][0]['VideoUrl']);
        }

        if ($data['section'][0]['SectionType'] == 1 && $data['section'][0]['CtfUrl'] != '') {
            $data['section'][0]['CtfUrl'] = $this->Ctf_model->get_ctf_url($data['section'][0]['CtfServerID'],$data['section'][0]['CtfServerPort'],$data['section'][0]['CtfUrl']);
        }

        //marked格式
        $data['section'][0]['SectionDoc'] = $this->utilities->clearMarkdown($data['section'][0]['SectionDoc']);
        //小节资料
        $data['tool'] = $this->Section_model->get_section_tool(array('SectionID' => $sectionID));
        //随堂练习题
        $data['question'] = $this->Package_model->get_section_question(array('SectionID' => $sectionID));
        if(count($data['question']) > 0){
            foreach ($data['question'] as $key=>$val){
                $val['QuestionDesc'] = $this->utilities->clearMarkdown($val['QuestionDesc']);
                $data['question'][$key]["QuestionDesc"] = preg_replace('/\[info\](.*)\[\/info\]/ms', ' ', $val["QuestionDesc"]);
            }
        }
        $this->load->view('teacher/edu_section_detail',$data);
    }
    //已下发学习任务列表页
    public function studylist(){
        $this->load->model("Task_model");
        $this->load->library ('Filter');
        
        $search = $this->input->get('search');
        $page = $this->input->get('per_page');
        $userID = $this->session->userdata('UserID');
        $sort = $this->input->get("sort");
        
        $search = $search ? urldecode($search) : '';//搜索字符串要转码
        $search = $this->security->xss_clean($search);//安全过滤
        $page = max(intval($page), 1);
        $num = 10;//每页记录数
        $offset = ($page - 1) * $num;

        $url = $this->filter->generateBaseUrl("sort");
        $sortList = '';
        $data["Sort"]["TaskStartTime"]["url"] = $url."sort=TaskStartTime|asc";
        $data["Sort"]["TaskStartTime"]["icon"] = "fa fa-sort fw";
        if ($sort != "") {
            $sortList = explode("|",$sort);
            if ($sortList[0] == "TaskStartTime"){

                $data["Sort"]["TaskStartTime"]["url"] = $url."sort=TaskStartTime|".(($sortList[1]=="asc")?"desc":"asc");
                $data["Sort"]["TaskStartTime"]["icon"] = "fa fa-sort-amount-" . (($sortList[1]=="asc")?"asc":"desc") . " fw";

            } else{
                redirect('Education/studylist');
            }
        }
        $where = array('TaskSourceType'=>1,'TeacherID'=>$userID);
        $data['total'] = count($this->Task_model->study_list($where,$search));
        $data['data'] = $this->Task_model->study_list($where,$search,$num,$offset,$sortList);

        //分页
        $per_page =  $this->filter->generateBaseUrl("per_page");
        $data['page_url'] = $per_page.'per_page=';;
        $data['page_count'] = ceil($data['total']/10);
        $data['page_pre'] = $page;

        $data['search'] = $search;
        $data['search_url'] = $this->filter->generateBaseUrl("search");
        //print_r($data);
        $this->load->view('teacher/study_list',$data);
    }
    //已下发学习任务列表页 [ajax实时更新]
    public function ajax_study_list(){
        $this->load->model("Task_model");
        $this->load->library('Interface_output');

        $search = $this->input->post('search');
        $page = $this->input->post('per_page');
        $userID = $this->session->userdata('UserID');
        $sort = $this->input->post("sort");
        $taskcode = $this->input->post("taskcode");

        $search = $search ? urldecode($search) : '';//搜索字符串要转码
        $search = $this->security->xss_clean($search);//安全过滤
        $page = max(intval($page), 1);
        $num = 10;//每页记录数
        $offset = ($page - 1) * $num;

        $sortList = '';
        if ($sort != "") {
            $sortList = explode("|",$sort);
        }
        $where = array('TaskSourceType'=>1,'TeacherID'=>$userID);
        if($taskcode != ''){
            $where['TaskCode'] = $taskcode;
        }
        $result = $this->Task_model->study_list($where,$search,$num,$offset,$sortList);
        $output_data['code'] = '0000';
        $output_data['msg'] = '已下发学习任务列表页';
        $output_data['data'] = $result;

        $this->interface_output->output_fomcat('js_Ajax', $output_data);
    }
    //学习任务详情页
    public function studydetail(){
        $this->load->model("Task_model");
        $this->load->library ('Filter');
        //左侧导航 选中状态
        $this->nav['left_nav_id'] = 39;

        $taskcode = $this->input->get('taskcode');
        $page = $this->input->get('per_page');
        $sort = $this->input->get("sort");
        $userID = $this->session->userdata('UserID');
        do{
            if(strlen($taskcode) < 10){
                redirect('Education/studylist');
            }
            $where = array('t.TaskCode'=>$taskcode,'TeacherID'=>$userID);
            $task = $this->Task_model->study_list($where);
            if(count($task) == 0){
                redirect('Education/studylist');
            }
            $data['task'] = $task[0];
            $page = max(intval($page), 1);
            $num = 10;//每页记录数
            $offset = ($page - 1) * $num;

            $url = $this->filter->generateBaseUrl("sort");
            $sortList = '';
            $data["Sort"]["TaskProcess"]["url"] = $url."sort=TaskProcess|asc";
            $data["Sort"]["TaskProcess"]["icon"] = "fa fa-sort fw";
            $data["Sort"]["TaskScore"]["url"] = $url."sort=TaskScore|asc";
            $data["Sort"]["TaskScore"]["icon"] = "fa fa-sort fw";
            if ($sort != "") {
                $sortList = explode("|",$sort);
                if ($sortList[0] == "TaskProcess"){

                    $data["Sort"]["TaskProcess"]["url"] = $url."sort=TaskProcess|".(($sortList[1]=="asc")?"desc":"asc");
                    $data["Sort"]["TaskProcess"]["icon"] = "fa fa-sort-amount-" . (($sortList[1]=="asc")?"asc":"desc") . " fw";

                }else if ($sortList[0] == "TaskScore"){

                    $data["Sort"]["TaskScore"]["url"] = $url."sort=TaskScore|".(($sortList[1]=="asc")?"desc":"asc");
                    $data["Sort"]["TaskScore"]["icon"] = "fa fa-sort-amount-" . (($sortList[1]=="asc")?"asc":"desc") . " fw";

                } else {
                    redirect('Education/studydetail?taskcode='.$taskcode);
                }
            }

            //学员情况
            $data['total'] = count($this->Task_model->study_student_list($where));//var_dump($sortList);die;
            $data['student'] = $this->Task_model->study_student_list($where,$num,$offset,$sortList);
            //echo $this->db->last_query();

            $data['page_url'] = site_url('Education/studydetail').'?taskcode='.$taskcode.'&per_page=';
            $data['page_count'] = ceil($data['total']/10);
            $data['page_pre'] = $page;
        }while(FALSE);
        $this->load->view('teacher/study_detail',$data);
    }

    //考试任务管理
    public function eduexam(){
        $this->load->model("Task_model");
        $this->load->library('Filter');

        $search = $this->input->get('search');
        $page = $this->input->get('per_page');
        $diff = $this->input->get('diff');
        $examtype = $this->input->get('examtype');
        $sort = $this->input->get("sort");

        $search = $search ? urldecode($search) : '';//搜索字符串要转码
        $search = $this->security->xss_clean($search);//安全过滤

        if(intval($diff) < 0){
            redirect('Education/eduexam');
        }
        //试卷类型 搜索
        $data['typeArr'] = array();
        if(!empty($examtype)){
            $type = array(1,2,4,8,16);
            $examtype = explode(',',trim($examtype,','));
            $data['typeArr'] = $examtype;
            //循环判断类型
            foreach ($examtype as $val){
                if(!in_array($val,$type)){
                    redirect('Education/eduexam');
                }
            }
        }
        
        $page = max(intval($page), 1);
        $num = 10;//每页记录数
        $offset = ($page - 1) * $num;

        $data['filter'] = $this->filter->getFilterData(0b010000);
        $url = $this->filter->generateBaseUrl("sort");
        $sortList = '';
        $data["Sort"]["UserName"]["url"] = $url."sort=UserName|asc";
        $data["Sort"]["UserName"]["icon"] = "fa fa-sort fw";
        $data["Sort"]["ExamDiff"]["url"] = $url."sort=ExamDiff|asc";
        $data["Sort"]["ExamDiff"]["icon"] = "fa fa-sort fw";
        $data["Sort"]["CreateTime"]["url"] = $url."sort=CreateTime|asc";
        $data["Sort"]["CreateTime"]["icon"] = "fa fa-sort fw";
        if ($sort != "") {
            $sortList = explode("|",$sort);
            if ($sortList[0] == "UserName"){

                $data["Sort"]["UserName"]["url"] = $url."sort=UserName|".(($sortList[1]=="asc")?"desc":"asc");
                $data["Sort"]["UserName"]["icon"] = "fa fa-sort-amount-" . (($sortList[1]=="asc")?"asc":"desc") . " fw";

            } else if($sortList[0] == "ExamDiff") {
                $data["Sort"]["ExamDiff"]["url"] = $url."sort=ExamDiff|".(($sortList[1]=="asc")?"desc":"asc");
                $data["Sort"]["ExamDiff"]["icon"] = "fa fa-sort-amount-" . (($sortList[1]=="asc")?"asc":"desc") . " fw";

            } else if($sortList[0] == "CreateTime") {
                $sortList[0] = 'e.CreateTime';
                $data["Sort"]["CreateTime"]["url"] = $url."sort=CreateTime|".(($sortList[1]=="asc")?"desc":"asc");
                $data["Sort"]["CreateTime"]["icon"] = "fa fa-sort-amount-" . (($sortList[1]=="asc")?"asc":"desc") . " fw";
            }else{
                redirect('Education/eduexam');
            }
        }
        $where = array('search'=>$search,'diff'=>$diff,'examtype'=>$examtype);
        $data['total'] = count($this->Task_model->teacher_exam_list($where));
        $data['data'] = $this->Task_model->teacher_exam_list($where,$num,$offset,$sortList);

        //分页
        $per_page =  $this->filter->generateBaseUrl("per_page");
        $data['page_url'] = $per_page.'per_page=';;
        $data['page_count'] = ceil($data['total']/10);
        $data['page_pre'] = $page;

        $data['search'] = $search;
        $data['search_url'] = $this->filter->generateBaseUrl("search");

        //下发弹窗时间
        $data['starttime'] = date('Y-m-d H:i:s',time());
        $data['endtime'] = date('Y-m-d H:i:s',strtotime("+1 month"));

        $this->load->view('teacher/edu_exam',$data);
    }
    //所有老师创建的试卷--详情
    public function examquestion(){
        $this->load->model("Exam_model");
        $this->load->model("Ctf_model");
        $this->load->library ('Utilities');
        
        $examid = $this->input->get('examid');
        do{
            if(intval($examid) <= 0){
                redirect('Education/eduexam');
            }
            $info = $this->Exam_model->get_exam_question(array('e.ExamID'=>$examid));
            if(count($info) == 0){
                redirect('Education/eduexam');
            }
            foreach ($info as $key=>$val){
                $val['QuestionDesc'] = $this->utilities->clearMarkdown($val['QuestionDesc']);
                $info[$key]["QuestionDesc"] = str_replace(array('[info]','[/info]'), '', $val["QuestionDesc"]);
                if($val['CtfUrl'] != ''){
                    $info[$key]['CtfUrl'] = $this->Ctf_model->get_ctf_url($val['CtfServerID'],$val['CtfServerPort'],$val['CtfUrl']);
                }
            }
            $data['data'] = $info;

        }while(FALSE);
        $this->load->view('teacher/exam_question',$data);
    }

    //已下发考试任务管理
    public function examtask(){
        $this->load->model("Task_model");
        $this->load->library('Filter');

        $diff = $this->input->get('diff');
        $search = $this->input->get('search');
        $page = $this->input->get('per_page');
        $userID = $this->session->userdata('UserID');
        $sort = $this->input->get("sort");

        $search = $search ? urldecode($search) : '';//搜索字符串要转码
        $search = $this->security->xss_clean($search);//安全过滤
        $diffArr = array(0,1,2);
        if (!in_array($diff,$diffArr)) {
            redirect('Education/examtask');
        }
        $data['filter'] = $this->filter->getFilterData(0b010000);

        $page = max(intval($page), 1);
        $num = 10;//每页记录数
        $offset = ($page - 1) * $num;

        $url = $this->filter->generateBaseUrl("sort");
        $sortList = '';
        $data["Sort"]["CreateTime"]["url"] = $url."sort=CreateTime|asc";
        $data["Sort"]["CreateTime"]["icon"] = "fa fa-sort fw";
        if ($sort != "") {
            $sortList = explode("|",$sort);
            if($sortList[0] == "CreateTime") {
                $sortList[0] = 't.CreateTime';
                $data["Sort"]["CreateTime"]["url"] = $url."sort=CreateTime|".(($sortList[1]=="asc")?"desc":"asc");
                $data["Sort"]["CreateTime"]["icon"] = "fa fa-sort-amount-" . (($sortList[1]=="asc")?"asc":"desc") . " fw";
            }else{
                redirect('Education/examtask');
            }
        }
        $where = array('TaskSourceType'=>2,'t.TeacherID'=>$userID);
        $data['total'] = count($this->Task_model->exam_task($where,$search,$diff));
        $data['data'] = $this->Task_model->exam_task($where,$search,$diff,$num,$offset,$sortList);

        //搜索
        $data['search'] = $search;
        $data['search_url'] = $this->filter->generateBaseUrl("search");

        //分页
        $per_page =  $this->filter->generateBaseUrl("per_page");
        $data['page_url'] = $per_page.'per_page=';;
        $data['page_count'] = ceil($data['total']/10);
        $data['page_pre'] = $page;
        
        $this->load->view('teacher/exam_task',$data);
    }
    //已下发考试任务管理 [ajax 实时更新]
    public function ajax_exam_task(){
        $this->load->model("Task_model");
        $this->load->library('Interface_output');

        $diff = $this->input->post('diff');
        $search = $this->input->post('search');
        $page = $this->input->post('per_page');
        $userID = $this->session->userdata('UserID');
        $sort = $this->input->post("sort");
        $taskcode = $this->input->post("taskcode"); //统计详情页 实时更新所需

        $search = $search ? urldecode($search) : '';//搜索字符串要转码
        $search = $this->security->xss_clean($search);//安全过滤
        $page = max(intval($page), 1);
        $num = 10;//每页记录数
        $offset = ($page - 1) * $num;

        $sortList = '';
        if ($sort != "") {
            $sortList = explode("|",$sort);
        }
        $where = array('TaskSourceType'=>2,'t.TeacherID'=>$userID);
        if($taskcode != ''){
            $where['TaskCode'] = $taskcode;
        }

        $result = $this->Task_model->exam_task($where,$search,$diff,$num,$offset,$sortList);

        $output_data['code'] = '0000';
        $output_data['msg'] = '已下发考试任务列表页';
        $output_data['data'] = $result;

        $this->interface_output->output_fomcat('js_Ajax', $output_data);

    }
    //考试任务详情页
    public function examdetail(){
        $this->load->model("Task_model");
        $this->load->library ('Filter');
        //左侧导航 选中状态
        $this->nav['left_nav_id'] = 42;

        $taskcode = $this->input->get('taskcode');
        $page = $this->input->get('per_page');
        $userID = $this->session->userdata('UserID');
        do{
            if(strlen($taskcode) != 10){
                redirect('Education/examtask');
            }
            $where = array('TaskCode'=>$taskcode,'t.TeacherID'=>$userID);
            $task = $this->Task_model->exam_task($where);
            if(count($task) == 0){
                redirect('Education/examtask');
            }

            $data['task'] = $task[0];
            $page = max(intval($page), 1);
            $num = 10;//每页记录数
            $offset = ($page - 1) * $num;
            
            //学员情况
            $data['total'] = count($this->Task_model->exam_student_list($where));
            //排名详情
            $data['student'] = $this->Task_model->exam_student_list($where,$num,$offset);
            //top10
            $data['top_student'] = $this->Task_model->exam_student_list($where,$num,0);
            //echo $this->db->last_query();

            $data['page_url'] = site_url('Education/examdetail').'?taskcode='.$taskcode.'&per_page=';
            $data['page_count'] = ceil($data['total']/10);
            $data['page_pre'] = $page;
        }while(FALSE);
        $this->load->view('teacher/exam_detail',$data);
    }

    //删除学习任务
    public function del_study(){
        $this->load->model("Task_model");
        $this->load->library('Interface_output');

        $code = $this->input->post("code");
        $code = json_decode($code);
        $output_data['data'] = array();
        do {
            //数组 传参数判断
            $flag = 0;
            if(is_array($code)){
                foreach ($code as $val){
                    if(strlen($val) != 10){
                        $flag = 1;
                    }
                }
            }
            if($flag == 1){
                $output_data['code'] = '0434';
                $output_data['msg'] = '参数错误!';
                break;
            }

            //单个的参数错误
            if (!is_array($code) && strlen($code) != 10) {

                $output_data['code'] = '0431';
                $output_data['msg'] = '参数错误!';
                break;
            }
            $codeArr = array();
            //判断是否有正在学习的 [单个删除时的判断]
            if (!is_array($code)){
                $result = $this->Task_model->get_task(array('TaskCode'=>$code,'TaskType'=>1));
                if(count($result) > 0){
                    $output_data['code'] = '0433';
                    $output_data['msg'] = '删除失败,有正在学习的学员!';
                    break;
                }
                $codeArr[] = $code;
            }else{
                $codeArr = $code;
            }

            $info = $this->Task_model->get_task_score($codeArr); //获得用户学习任务积分
            $this->Task_model->del_user_study_score($info); //删除用户学习任务积分
            //删除单个或多个
            $this->Task_model->del_task($codeArr); //删除任务
            $this->Task_model->del_section_instance($codeArr); //删除下发的小节
            $this->Task_model->del_practice_instance($codeArr); //删除下发的随堂练习
            
            $output_data['code'] = '0000';
            $output_data['msg'] = '删除成功!';


        }while(FALSE);

        $this->interface_output->output_fomcat('js_Ajax', $output_data);
    }
    //结束任务
    public function end_study(){
        $this->load->model("Task_model");
        $this->load->library('Interface_output');

        $taskcode = $this->input->post("taskcode");
        $output_data['data'] = array();
        do {
            if(strlen($taskcode) != 10){
                $output_data['code'] = '0431';
                $output_data['msg'] = '参数错误!';
                break;
            }

            $flag = $this->Task_model->edit_task(array('TaskCode'=>$taskcode),array('TaskType'=>2));

            $output_data['code'] = '0000';
            $output_data['msg'] = '更改结束状态,更改失败!';
            if($flag){
                $output_data['code'] = '0000';
                $output_data['msg'] = '更改结束状态,更改成功!';
            }

        }while(FALSE);

        $this->interface_output->output_fomcat('js_Ajax', $output_data);
    }
    //删除考试任务
    public function del_exam(){
        $this->load->model("Task_model");
        $this->load->model("Exam_model");
        $this->load->library('Interface_output');

        $code = $this->input->post("code");
        $scenetaskid = $this->input->post("scenetaskid");
        $code = json_decode($code);
        $output_data['data'] = array();
        do {

            if(intval($scenetaskid) < 0){
                $output_data['code'] = '0432';
                $output_data['msg'] = '参数错误!';
                break;
            }
            //数组 传参数判断
            $flag = 0;
            if(is_array($code)){
                foreach ($code as $val){
                    if(strlen($val) != 10){
                        $flag = 1;
                    }
                }
            }
            if($flag == 1){
                $output_data['code'] = '0434';
                $output_data['msg'] = '参数错误!';
                break;
            }
            //单个的参数错误
            if (!is_array($code) && strlen($code) != 10) {

                $output_data['code'] = '0431';
                $output_data['msg'] = '参数错误!';
                break;
            }
            $codeArr = array();
            //判断是否有正在学习的 [单个删除时的判断]
            if (!is_array($code)) {
                $result = $this->Task_model->get_task(array('TaskCode'=>$code,'TaskType'=>1));
                if(count($result) > 0){
                    $output_data['code'] = '0433';
                    $output_data['msg'] = '删除失败,有正在考试的学员!';
                    break;
                }

                if($scenetaskid != 0){
                    //删除计划任务
                    $this->Exam_model->del_task_scene($scenetaskid);
                }
                $codeArr[] = $code;
            }else{
                $codeArr = $code;
            }


            //删除单个或多个
            $this->Task_model->del_task($codeArr);//删除任务
            $this->Task_model->del_question_instance($codeArr);//删除下发题目实例表表
            $output_data['code'] = '0000';
            $output_data['msg'] = '删除成功!';


        }while(FALSE);

        $this->interface_output->output_fomcat('js_Ajax', $output_data);
    }
    //结束考试任务
    public function end_exam(){
        $this->load->model("Task_model");
        $this->load->model("Exam_model");
        $this->load->library('Interface_output');

        $taskcode = $this->input->post("taskcode");
        $scenetaskid = $this->input->post("scenetaskid");
        $output_data['data'] = array();
        $data = array();
        do {
            if(strlen($taskcode) != 10){
                $output_data['code'] = '0431';
                $output_data['msg'] = '参数错误!';
                break;
            }
            if(intval($scenetaskid) < 0){
                $output_data['code'] = '0432';
                $output_data['msg'] = '参数错误!';
                break;
            }
            if($scenetaskid != 0){
                //删除计划任务
                $task_scene = $this->Exam_model->del_task_scene($scenetaskid);
                //场景计划任务删除成功[恢复数据库SceneTaskID]
                if($task_scene['code'] == '0000'){
                    $data['SceneTaskID'] = 0;
                }
            }
            $data['TaskType'] = 2;
            $data['TeaEnd'] = 1;
            $flag = $this->Task_model->edit_task(array('TaskCode'=>$taskcode),$data);

            $output_data['code'] = '0000';
            $output_data['msg'] = '更改结束状态,更改失败!';
            if($flag){
                $output_data['code'] = '0000';
                $output_data['msg'] = '更改结束状态,更改成功!';
            }

        }while(FALSE);

        $this->interface_output->output_fomcat('js_Ajax', $output_data);
    }
    //查看学员考试试卷
    public function studentexam(){
        $this->load->model ("Task_model");
        $this->load->model ("Exam_model");
        $this->load->model ("Ctf_model");
        $this->load->library ('Utilities');

        $taskid = $this->input->get('taskid');

        do {
            if(intval($taskid) <= 0){
                redirect('Education/examtask');
            }
            $result = $this->Task_model->get_task(array('TaskId'=>$taskid));
            $data['task']=$result;
            if(count($result) == 0){
                redirect('Education/examtask');
            }
            if($result[0]['TaskType'] != 2){
                //考试未结束';
                redirect('Education/examdetail?taskcode='.$result[0]['TaskCode']);
            }

            $data['data'] = $this->Exam_model->get_question_instance_info(array('qi.TaskId'=>$taskid));
            if(count($data['data']) == 0){
                //任务类型错误 或者没有题
                redirect('Education/examtask');
            }

            foreach ($data['data'] as $key=>$val){
                if($val['CtfUrl'] != ''){
                    $data['data'][$key]['CtfUrl'] = $this->Ctf_model->get_ctf_url($val['CtfServerID'],$val['CtfServerPort'],$val['CtfUrl']);
                }
                $val['QuestionDesc'] = $this->utilities->clearMarkdown($val['QuestionDesc']);
                $data['data'][$key]["QuestionDesc"] = str_replace(array('[info]','[/info]'), '', $val["QuestionDesc"]);
            }

        }while(FALSE);

        $this->load->view('teacher/student_exam',$data);
    }

    //考试任务详情页 实时更新数据
    public function score_student(){
        $this->load->model("Task_model");
        $this->load->library ('Filter');
        $this->load->library('Interface_output');

        $taskcode = $this->input->post('taskcode');
        $page = $this->input->post('per_page');
        $userID = $this->session->userdata('UserID');
        $output_data['data'] = array();
        do{
            if(strlen($taskcode) != 10){
                $output_data['code'] = '0431';
                $output_data['msg'] = '参数错误!';
                break;
            }
            $where = array('TaskCode'=>$taskcode,'t.TeacherID'=>$userID);
            $task = $this->Task_model->exam_task($where);
            if(count($task) == 0){
                $output_data['code'] = '0432';
                $output_data['msg'] = '参数错误!';
                break;
            }

            $page = max(intval($page), 1);
            $num = 10;//每页记录数
            $offset = ($page - 1) * $num;

            //排名详情
            $student['page_student'] = $this->Task_model->exam_student_list($where,$num,$offset);
            //top10
            $student['top_student'] = $this->Task_model->exam_student_list($where,$num,0);
            //图形 [显示所有学生]
            $student['all_student'] = $this->Task_model->exam_student_list($where);
            $output_data['code'] = '0000';
            $output_data['msg'] = count($student['page_student']);
            $output_data['data'] = $student;

        }while(FALSE);

        $this->interface_output->output_fomcat('js_Ajax', $output_data);
    }
    //下发弹框 该老师创建的班级
    public function class_list(){
        $this->load->model("Class_model");

        $search = $this->input->post('keyword');
        $page = $this->input->post('page');
        $perpage = intval($this->input->post('percount'));//每页记录数
        $userID = $this->session->userdata('UserID');

        $search = $search ? urldecode($search) : '';//搜索字符串要转码
        $search = $this->security->xss_clean($search);//安全过滤

        $page = max(intval($page), 1);
        $offset = ($page - 1) * $perpage;

        $where = array('TeacherID'=>$userID,'search' => $search, 'num' => $perpage,'offset'=>$offset);
        $output_data['data'] = $this->Class_model->get_class($where);

        $output_data['count'] = count($this->Class_model->get_class(array('TeacherID'=>$userID)));//获取总记录数
        $output_data['pagecount'] = ceil($output_data['count'] / $perpage);
        $output_data['currentpage'] = $page;
        echo json_encode($output_data);
    }
    //下发弹框 学员列表
    public function student_list(){
        $this->load->model("User_model");

        $search = $this->input->post('keyword');
        $page = $this->input->post('page');
        $perpage = intval($this->input->post('percount'));//每页记录数

        $search = $search ? urldecode($search) : '';//搜索字符串要转码
        $search = $this->security->xss_clean($search);//安全过滤
        $output_data['data'] = array();

        $page = max(intval($page), 1);
        $offset = ($page - 1) * $perpage;

        $where = array(
            'search' => $search,//搜索字符串要转码
            'limit' => array('limit' => $perpage, 'offset' => $offset)
        );

        $output_data['data'] = $this->User_model->get_all_student($where);

        $output_data['count'] = $this->User_model->get_count($where, 3);//获取总记录数
        $output_data['pagecount'] = ceil($output_data['count'] / $perpage);
        $output_data['currentpage'] = $page;
        echo json_encode($output_data);

    }

    //下发学习任务
    public function create_study(){
        $this->load->model("Class_model");
        $this->load->model("Package_model");
        $this->load->model("Task_model");
        $this->load->library('Interface_output');

        $infos = $this->input->post('infos');
        $calssList = $this->input->post('calssList');
        $studentList = $this->input->post('studentList');

        $output_data['data'] = array();
        do{
            if(intval($infos['id']) <= 0){
                $output_data['code'] = '0431';
                $output_data['msg'] = '参数错误!';
                break;
            }
            if(isset($calssList) && !is_array($calssList)){
                $output_data['code'] = '0432';
                $output_data['msg'] = '参数错误!';
                break;
            }
            if(isset($studentList) && !is_array($studentList)){
                $output_data['code'] = '0433';
                $output_data['msg'] = '参数错误!';
                break;
            }
            if(strlen($infos['starttime']) <1 || strlen($infos['endtime']) <1){
                $output_data['code'] = '0434';
                $output_data['msg'] = '请选择开始或结束时间!';
                break;
            }
            if($infos['starttime']  >= $infos['endtime']){
                $output_data['code'] = '0435';
                $output_data['msg'] = '开始时间不能大于等于结束时间';
                break;
            }

            if($infos['starttime']  < $infos['nowtime'] || $infos['endtime']  < $infos['nowtime']){
                $output_data['code'] = '0436';
                $output_data['msg'] = '学习开始时间或结束时间不能小于当前时间';
                break;
            }

            $startTime = strtotime($infos['starttime']);
            $endTime = strtotime($infos['endtime']);
            if(!$startTime || !$endTime){
                $output_data['code'] = '0437';
                $output_data['msg'] = '时间参数错误!';
                break;
            }
            //查询题目和小节
            $data = $this->Package_model->get_instance(array('PackageParent'=>$infos['id']));
            if(!isset($data['section'])){
                $output_data['code'] = '0438';
                $output_data['msg'] = '该课程下没有小节';
                break;
            }
            //处理学生和班级
            //班级
            if(isset($calssList)){
                $task = $this->Class_model->get_class_student($calssList);
                if(count($task) == 0){
                    $output_data['code'] = '0439';
                    $output_data['msg'] = '所选的班级，存在没有学员的班级';
                    break;
                }

            }
            //学员
            if(isset($studentList)){
                foreach ($studentList as $val){
                    $task[] = array('StudentID'=>$val,'ClassID'=>NULL);
                }
            }
            //生成任务类型 TaskTargetType的值 1 学员 2 班级 3 混合
            $TaskTargetType = 3;
            if(!isset($calssList) && isset($studentList)){
                $TaskTargetType = 1;
            }else if(isset($calssList) && !isset($studentList)){
                $TaskTargetType = 2;
            }

            $output_data['code'] = '0000';
            $output_data['msg'] = '下发成功!';

            $teacherID = $this->session->userdata('UserID');
            $taskCode = time();
            foreach ($task as $k=>$val){
                $task[$k]['TaskCode'] = $taskCode;
                $task[$k]['PackageID'] = $infos['id'];
                $task[$k]['TaskName'] = $infos['taskname'];
                $task[$k]['TeacherID'] = $teacherID;
                $task[$k]['TaskSourceType'] = 1;
                $task[$k]['TaskTargetType'] = $TaskTargetType;
                $task[$k]['TaskStartTime'] = $startTime;
                $task[$k]['TaskEndTime'] = $endTime;
                $task[$k]['CreateTime'] = time();
                $task[$k]['TaskType'] = 0;
                $task[$k]['TaskScore'] = 0;
                //循环插入数据库[需要 taskid]
                $taskid = $this->Task_model->insert_task($task[$k]);
                if(empty($taskid)){
                    $output_data['code'] = '0001';
                    $output_data['msg'] = '任务插入失败!';
                    break;
                }
                $flag = $this->Package_model->insert_instance(array('TaskID' => $taskid,'TaskCode'=>$taskCode,'data'=>$data));
                if($flag == FALSE){
                    $output_data['code'] = '0001';
                    $output_data['msg'] = '下发失败!';
                }
            }

        }while(FALSE);

        $this->interface_output->output_fomcat('js_Ajax', $output_data);
    }

    //下发考试任务
    public function create_exam(){
        $this->load->model("Class_model");
        $this->load->model("Task_model");
        $this->load->model("Exam_model");
        $this->load->library('Interface_output');

        $infos = $this->input->post('infos');
        $calssList = $this->input->post('calssList');
        $studentList = $this->input->post('studentList');
        $infos['taskdesc'] = $this->security->xss_clean($infos['taskdesc']);

        $output_data['data'] = array();
        do{
            if(intval($infos['id']) <= 0){
                $output_data['code'] = '0431';
                $output_data['msg'] = '参数错误!';
                break;
            }
            if(isset($calssList) && !is_array($calssList)){
                $output_data['code'] = '0432';
                $output_data['msg'] = '参数错误!';
                break;
            }
            if(isset($studentList) && !is_array($studentList)){
                $output_data['code'] = '0433';
                $output_data['msg'] = '参数错误!';
                break;
            }
            if(strlen($infos['starttime']) <1 || strlen($infos['endtime']) <1){
                $output_data['code'] = '0434';
                $output_data['msg'] = '请选择开始或结束时间!';
                break;
            }
            if($infos['starttime']  >= $infos['endtime']){
                $output_data['code'] = '0435';
                $output_data['msg'] = '开始时间不能大于等于结束时间';
                break;
            }

            if($infos['starttime']  < $infos['nowtime'] || $infos['endtime']  < $infos['nowtime']){
                $output_data['code'] = '0436';
                $output_data['msg'] = '考试开始时间或结束时间不能小于当前时间';
                break;
            }

            $startTime = strtotime($infos['starttime']);
            $endTime = strtotime($infos['endtime']);
            if(!$startTime || !$endTime){
                $output_data['code'] = '0434';
                $output_data['msg'] = '时间参数错误!';
                break;
            }

            //班级
            if(isset($calssList)){
                $task = $this->Class_model->get_class_student($calssList);
                if(count($task) == 0){
                    $output_data['code'] = '0437';
                    $output_data['msg'] = '所选的班级，存在没有学员的班级';
                    break;
                }

            }
            //学员
            if(isset($studentList)){
                foreach ($studentList as $val){
                    $task[] = array('StudentID'=>$val,'ClassID'=>NULL);
                }
            }
            //获取试卷下的题目
            $exam_question = $this->Exam_model->get_exam_question_infos(array('ExamId'=>$infos['id']));
            if(count($exam_question) == 0){
                $output_data['code'] = '0437';
                $output_data['msg'] = '该试卷下没有题';
                break;
            }
            $now_ques_scene = 0;
            //判断试卷中是否有场景题
            foreach ($exam_question as $qes){
                if($qes['QuestionLinkType'] == 2 && $qes['QuestionLink'] != ''){
                    $now_ques_scene = 1;
                }
            }
            //下发带场景题的试卷时判断是否有在该时间段内的场景考试任务
            $scene_where = array('QuestionLinkType'=>2,'TaskType !='=>2,'TaskSourceType'=>2);
            $scene_time = $this->Task_model->get_scene_task_time($scene_where,$startTime,$endTime);
            if($now_ques_scene == 1 && count($scene_time) > 0 && $scene_time[0]['startEnd'] != 6){
                $output_data['code'] = '0438';
                $output_data['msg'] = '选择的时间段与现存带场景题的考试重复';
                break;
            }

            //查询 题目中是否存在场景
            $scene_question = $this->Exam_model->get_exam_question_infos(array('ExamId'=>$infos['id'],'QuestionLinkType'=>2));
            if(count($scene_question) > 0 && $scene_question[0]['QuestionLink'] == ''){
                $output_data['code'] = '0439';
                $output_data['msg'] = '场景题UUID为空,不能下发';
                break;
            }

            $SceneTaskID = '';
            $scene = array();
            // 存在场景题 下发计划任务
            if(count($scene_question) > 0){
                $scene_uuid = '';
                //场景模板
                foreach ($task as $k=>$val){
                    $scene_uuid .= $scene_question[0]['QuestionLink'].',';
                }
                $datas_scene = array(
                    'start_time' => date('Y-m-d H:i:s',$startTime),
                    'end_time' => date('Y-m-d H:i:s',$endTime),
                    'time_diff' => 20,
                    'scene_tpl_uuid_list' => trim($scene_uuid,',')
                );
                $scene = $this->Task_model->create_task_scene($datas_scene);//var_dump($scene);

                if($scene['code'] != '0000'){
                    $output_data['code'] = $scene['code'];
                    $output_data['msg'] = $scene['msg'];
                    $output_data['data'] = $scene['data'];
                    break;
                }else{
                    $SceneTaskID =$scene['data']['id'];
                    $userID = $this->session->userdata('UserID');
                    //日志
                    $this->load->library('Log_user');
                    $log = array(
                        'LogTaskName' => $infos['taskname'],
                        'LogContent' => '下发了“'.$infos['taskname'].'”考试任务的计划任务',
                        'LogTypeID' => 3,
                        'LogResult' => site_url('Education/eduexam'),
                        'UserID' => $userID
                    );
                    $this->log_user->add_log($log);
                }
            }

            //生成任务类型 TaskTargetType的值 1 学员 2 班级 3 混合
            $TaskTargetType = 3;
            if(!isset($calssList) && isset($studentList)){
                $TaskTargetType = 1;
            }else if(isset($calssList) && !isset($studentList)){
                $TaskTargetType = 2;
            }

            $output_data['code'] = '0000';
            $output_data['msg'] = '下发成功!';
            
            $teacherID = $this->session->userdata('UserID');
            $taskCode = time();
            $taskidArr = array();
            foreach ($task as $k=>$val){
                $task[$k]['TaskCode'] = $taskCode;
                $task[$k]['ExamID'] = $infos['id'];
                $task[$k]['TaskName'] = $infos['taskname'];
                $task[$k]['TeacherID'] = $teacherID;
                $task[$k]['TaskSourceType'] = 2;
                $task[$k]['TaskDesc'] = $infos['taskdesc'];
                $task[$k]['TaskTargetType'] = $TaskTargetType;
                $task[$k]['TaskStartTime'] = $startTime;
                $task[$k]['TaskEndTime'] = $endTime;
                $task[$k]['CreateTime'] = time();
                $task[$k]['SceneTaskID'] = $SceneTaskID;
                $task[$k]['TaskType'] = 0;
                $task[$k]['TaskScore'] = 0;

                //循环插入数据库[需要 taskid]
                $taskid = $this->Task_model->insert_task($task[$k]);
                $taskidArr[] = $taskid;
                if(empty($taskid)){
                    $output_data['code'] = '0001';
                    $output_data['msg'] = '任务插入失败!';
                    break;
                }
                
                $flag = $this->Exam_model->insert_question_instance(array('TaskID' => $taskid,'TaskCode'=>$taskCode,'data'=>$exam_question));
                if($flag == FALSE){
                    $output_data['code'] = '0001';
                    $output_data['msg'] = '下发失败!';
                }

            }
            //更改题目下发实例表表
            if(count($scene_question) > 0 && $scene['code'] == '0000'){

                foreach ($scene['data']['task_id_list'] as $key=>$val) {
                    $where = array('TaskID'=>$taskidArr[$key],'QuestionID'=>$scene_question[0]['QuestionID']);
                    $data = array('SceneInstanceUUID'=>$val['scene_ins_uuid'], 'TaskUUID'=>$val['task_uuid']);
                    $this->Task_model->edit_question_instance($where,$data);
                }
            }

        }while(FALSE);

        $this->interface_output->output_fomcat('js_Ajax', $output_data);
    }

}