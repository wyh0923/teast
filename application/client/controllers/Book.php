<?php
/**
 * Created by PhpStorm.
 * User: kyx
 * Date: 2016/8/3
 * Time: 10:30
 */

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 学生端知识体系控制器
 *
 */
class Book extends ECQ_Controller
{

    /**
     * 课程列表页
     */
    public function lists(){
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
            redirect('Book/lists');
        }
        // 课程体系参数判断
        $son = $this->Architecture_model->judge(array( 'ArchitectureID' => $sonid));
        if ($son == 3) {
            redirect('Book/lists');
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

            } else{
                redirect('Book/lists');
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
        $data['book'] = $this->Package_model->get_all_book($condition);

        //搜索
        $data['search'] = $search;
        $data['search_url'] = $this->filter->generateBaseUrl("search");

        //分页
        $per_page =  $this->filter->generateBaseUrl("per_page");
        $data['page_url'] = $per_page.'per_page=';;
        $data['page_count'] = ceil($data['book_num']/10);
        $data['page_pre'] = $page;

        $this->load->view('student/book_list', $data);
    }


    /**
     * 课程详情页
     */
    public function bookdetail(){
        $packageID = $this->input->get('packageid');
        $this->load->model("Package_model");

        $pack = $this->Package_model->judge(array( 'PackageID' => $packageID,'PackageParent'=>0));
        if ($pack != 1) {
            redirect('Book/lists');
        }
        $data['data'] = $this->Package_model->get_book_detail(array('PackageID' => $packageID));
        
        $this->load->view('student/book_detail',$data);
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
        $this->load->view('student/section_detail',$data);
    }

    /**
     * 学生自学
     */
    public function createstudy(){
        $this->load->model("Package_model");
        $this->load->model("Task_model");
        $this->load->library('Interface_output');

        $output_data['data'] = array();
        do {
            $packageID = $this->input->post('packageid');
            $pack = $this->Package_model->judge(array('PackageID' => $packageID,'PackageParent'=>0));
            if ($pack != 1) {
                $output_data['code'] = '0411';
                $output_data['msg'] = '课程参数错误'.$pack;
                break;
            }
            //查询题目和小节
            $data = $this->Package_model->get_instance(array('PackageParent'=>$packageID));

            if(!isset($data['section'])){
                $output_data['code'] = '0412';
                $output_data['msg'] = '该课程下没有小节';
                break;
            }
            $package = $this->Package_model->get_book_chapter(array('PackageID'=>$packageID));
            
            $userID = $this->session->userdata('UserID');
            //插入任务表
            $now = time();
            $task['TaskCode'] = $now;
            $task['PackageID'] = $packageID;
            $task['TaskName'] = $package[0]['PackageName'];
            $task['TaskStartTime'] = $now;
            $task['TaskEndTime'] = $now + (60*60*24*30);
            $task['CreateTime'] = $now;
            $task['TaskSourceType'] = 1;
            $task['TaskType'] = 1;
            $task['TeacherID'] = $userID;
            $task['StudentID'] = $userID;
            $task['TaskTargetType'] = 1;
            $task['TaskType'] = 0;
            $task['TaskScore'] = 0;

            $taskid = $this->Task_model->insert_task($task);
            if(empty($taskid)){
                $output_data['code'] = '0413';
                $output_data['msg'] = '任务插入失败!';
                break;
            }
            $flag = $this->Package_model->insert_instance(array('TaskID' => $taskid,'TaskCode'=>$now,'data'=>$data));
            if($flag == FALSE){
                $output_data['code'] = '0414';
                $output_data['msg'] = '下发失败!';
            }
            $output_data['code'] = '0000';
            $output_data['msg'] = '下发成功!';
            $output_data['data']['taskid'] = $taskid;
            //日志
            $this->load->library('Log_user');
            $log = array(
                'LogTaskName' => $package[0]['PackageName'],
                'LogContent' => '自学了“'.$package[0]['PackageName'].'”课程',
                'LogTypeID' => 6,
                'LogResult' => site_url('Book/createstudy'),
                'UserID' => $userID
            );
            $this->log_user->add_log($log);
        } while (FALSE);

        $this->interface_output->output_fomcat('js_Ajax', $output_data);
    }

    /**
     * 检查该学员是否已有该课程
     */
    public function checkstudy(){
        $this->load->model ( "Task_model" );
        $this->load->model ( "Package_model" );
        $this->load->library('Interface_output');

        $packageid=$this->input->post('packageid');

        $userID = $this->session->userdata('UserID');
        $output_data['data'] = array();
        do {

            $pack = $this->Package_model->judge(array( 'PackageID' => intval($packageid),'PackageParent'=>0));
            if ($pack != 1) {
                $output_data['code'] = '0403';
                $output_data['msg'] = '参数错误';
                break;
            }
            $where = array('PackageID' => $packageid,'StudentID' => $userID,'TaskType !=' => 2);
            $res = $this->Task_model->get_task($where);
            if(count($res) > 0){
                $output_data['code'] = '0000';
                $output_data['msg'] = '已有该学习任务';
                $output_data['data'] = array('taskid'=>$res[0]['TaskID']);
                break;
            }
            $output_data['code'] = '0001';
            $output_data['msg'] = '没有该学习任务';
        } while (FALSE);

        $this->interface_output->output_fomcat('js_Ajax', $output_data);
    }


}
