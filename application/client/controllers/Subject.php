<?php
/**
 * Created by PhpStorm.
 * User: WKF
 * Date: 2016/8/12
 * Time: 11:38
 */
class Subject extends ECQ_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->account = $this->session->userdata('Account');
        $this->author = $this->session->userdata('UserName');
        $this->userid = $this->session->userdata('UserID');
        $this->load->model('Book_model');
        $this->load->model('Section_model');
        $this->load->model('Question_model');
        $this->load->model('Exam_model');
        $this->load->model('Plan_model');
        $this->load->model('Scene_model');
        $this->load->library('Interface_output');

    }

    //我的知识体系
    public function mysystem()
    {
        //从url里获取参数,TODO数据安全过滤
        $parameter = $this->uri->uri_to_assoc(3);
        $uri_segment = (count($parameter) * 2) + 1;
        //$per_page = $this->uri->segment($uri_segment) === NULL ? 1 : $this->uri->segment($uri_segment);
        $per_page = $this->uri->segment($uri_segment) == 'Subject' ? 1 : $this->uri->segment($uri_segment);
        $search = array_key_exists('search', $parameter) ? urldecode($parameter['search']) : '';
        $sort = array_key_exists('sort', $parameter) ? $parameter['sort'] : '';

        $search = $this->security->xss_clean($search);

        $page = max(intval($per_page), 1);

        $perpage = 10;//每页记录数
        $offset = ($page - 1) * $perpage;

        $where = array(
            'Author'=>$this->account,
            'limit' => array('limit' => $perpage, 'offset' => $offset)
        );

        $pageurl = '';//页面url拼接

        //排序方式:CreateTime DESC
        /*if (!empty($sort)) {
            $where['sort'] = array('field' => explode("%20", $sort)[0], 'order' => explode("%20", $sort)[1]);
            $pageurl .= '/sort/' . $sort;
        }*/

        if(!empty($search)){
            $where['search'] = $search;//搜索字符串要转码
            $pageurl .= '/search/' . $search;
        }

        if (!empty($sort)) {
            $where['sort'] = array('field' => explode("%20", $sort)[0], 'order' => explode("%20", $sort)[1]);
            //$order_by = explode("%20", $sort)[0] .' '. explode("%20", $sort)[1];
            $pageurl .= '/sort/' . $sort;
        }

        //$plans = $this->Plan_model->sum_archlist($offset, $perpage, $search='', $order_by, $author=$this->userid);
        //p($where);
        $plans = $this->Plan_model->get_plans($where);
        //p($plans);die;

        //分页
        $this->load->helper('util');
        $data['total_rows'] = $this->Plan_model->get_count($where);;//获取总记录数

        $data['pages'] = get_pages(site_url('Subject/mysystem'), $data['total_rows']);

        $page_count = ceil($data['total_rows']/10);

        $data['plans'] = $plans;

        $data['search'] = $search;
        //$data['sort'] = isset($where['sort']) ? $where['sort'] : '';
        if(!empty($sort))
        {$data['sort'] = array('field' => explode("%20", $sort)[0], 'order' => explode("%20", $sort)[1]);}
        else{
            $data['sort'] = '';
        }

        $data['page_url']=site_url('Subject/mysystem'). $pageurl.'/';

        $data['page_count'] = $page_count;

        $data['page_pre']=$page;

        $this->load->view('teacher/arch_list', $data);
    }

    /**
     * 新增体系
     */
    public function addsys()
    {
        if ($data = $this->input->post(NULL, TRUE)) {
            $tmp = array('code' => '0000', 'msg' => 'success!', 'data' => []);

            $info = array(
                'ArchitectureName' => $data['name'],
                'ArchitectureParent' => $data['pid'],
                'Author' => $this->account,
            );

            $res = $this->Plan_model->add_plan($info);
            if ($res['code'] != '0000') {
                $tmp = array('code' => $res['code'], 'msg' => $res['msg'], 'data' => []);
            }

            $this->interface_output->output_fomcat('js_Ajax', $tmp);
        }
    }

    /**
     * 编辑方案名称
     */
    public function modplan()
    {
        if ($data = $this->input->post(NULL, TRUE)) {
            $tmp = array('code' => '0000', 'msg' => 'success!', 'data' => []);

            $info = array('ArchitectureName' => $data['name']);
            $where = array('ArchitectureID' => $data['pid']);

            $res = $this->Plan_model->mod_plan($info, $where);
            if ($res['code'] != '0000') {
                $tmp = array('code' => $res['code'], 'msg' => $res['msg'], 'data' => []);
            }

            $this->interface_output->output_fomcat('js_Ajax', $tmp);
        }
    }

    /**
     * 新增方案
     */
    public function addplan()
    {
        if ($data = $this->input->post(NULL, TRUE)) {
            $tmp = array('code' => '0000', 'msg' => 'success!', 'data' => []);

            $info = array(
                'ArchitectureName' => $data['name'],
                'ArchitectureParent' => '0',
                'Author' => $this->account,
            );

            $res = $this->Plan_model->add_plan($info);
            if ($res['code'] != '0000') {
                $tmp = array('code' => $res['code'], 'msg' => $res['msg'], 'data' => []);
            }

            $this->interface_output->output_fomcat('js_Ajax', $tmp);
        }
    }

    /**
     * 删除方案
     */
    public function delplan()
    {
        $pid = $this->security->xss_clean ($this->input->post('code'));

        $tmp = array('code' => '0000', 'msg' => 'success!', 'data' => []);

        $res=$this->Plan_model->del_plan($pid);

        if ($res['code'] != '0000') {
            $tmp = array('code' => $res['code'], 'msg' => $res['msg'], 'data' => []);
        }

        $this->interface_output->output_fomcat('js_Ajax', $tmp);
    }

    /**
     * 编辑方案
     */
    public function editplan()
    {
        $pid = $this->uri->segment(4);
        $data['pid'] = $pid;

        //获取培训方案
        $data['plan'] = $this->Plan_model->get_plan(array('ArchitectureID' => $pid));
        
        //获取体系
        $sys = $this->Plan_model->get_sys(array('ArchitectureParent' => $pid));
        foreach ($sys as $k => $v)
        {
            $sys[$k]['courses'] = $this->Book_model->get_courses($v['ArchitectureID']);
        }

        foreach ($sys as $k => $v)
        {
            $sys[$k]['counts'] = count($v['courses']);
        }

        $data['sys'] = $sys;
        
        //作者
        $data['author'] = $this->Book_model->get_author();

        //课程总数
        $data['coursenum'] = $this->Book_model->count_course();

        //小节总数
        $data['sectionnum'] = $this->Book_model->count_section();

        //培训方案
        $where1 = array('ArchitectureParent' => 0);
        $data['trains'] = $this->Book_model->get_plan($where1);

        $where = array('ArchitectureParent' => 0, 'Author'=>$this->account);
        $data['train'] = $this->Book_model->get_plan($where);


        //课程体系
        $where2 = array('ArchitectureParent !=' => 0);
        $data['csys'] = $this->Book_model->get_plan($where2);

        $this->load->view('teacher/arch_edit', $data);
    }

    /**
     * 所有课程
     */
    public function all_course()
    {
        $search = $this->input->post('keyword');
        $perpage = intval($this->input->post('percount'));//每页记录数
        $curpage = intval($this->input->post('page'));

        //安全过滤
        $search = $this->security->xss_clean($search);

        $page = max(intval($curpage), 1);
        $offset = ($page - 1) * $perpage;
        $where = array(
            'search' => $search,//搜索字符串要转码
            'limit' => array('limit' => $perpage, 'offset' => $offset)
        );

        $output_data['data'] = $this->Book_model->get_all_course($where);
        $output_data['count'] = $this->Book_model->get_all_count($where);;//获取总记录数
        $output_data['pagecount'] = ceil($output_data['count'] / $perpage);
        $output_data['currentpage'] = $page;
        echo json_encode($output_data);
    }

    /**
     * ajax获取课程
     */
    public function ajax_course()
    {
        $diff = $this->input->post('diskSize');
        $ctime = $this->input->post('memorySize');
        $search = $this->input->post('keyword');
        $perpage = intval($this->input->post('percount'));//每页记录数
        $curpage = intval($this->input->post('page'));
        $pid= intval($this->input->post('cpu'));
        $aid = intval($this->input->post('osType'));
        $ctype = intval($this->input->post('ctype'));
        $author = $this->input->post('author');

        //安全过滤
        $search = $this->security->xss_clean($search);

        $page = max(intval($curpage), 1);
        $offset = ($page - 1) * $perpage;
        $where = array(
            'pid' => $pid,//培训方案
            'aid' => $aid,//体系
            'ctype' => $ctype,
            'author' => $author,
            'search' => $search,//搜索字符串要转码
            'limit' => array('limit' => $perpage, 'offset' => $offset)
        );

        if(!empty($diff))
        {
            if($diff == 'fa-sort-amount-asc')
            {
                $where['sort']['field'] = 'p_package.PackageDiff';
                $where['sort']['order'] = 'ASC';
            }
            if($diff == 'fa-sort-amount-desc')
            {
                $where['sort']['field'] = 'p_package.PackageDiff';
                $where['sort']['order'] = 'DESC';
            }
        }

        if(!empty($ctime))
        {
            if($ctime == 'fa-sort-amount-asc')
            {
                $where['sort']['field'] = 'p_package.PublicTime';
                $where['sort']['order'] = 'ASC';
            }
            if($ctime == 'fa-sort-amount-desc')
            {
                $where['sort']['field'] = 'p_package.PublicTime';
                $where['sort']['order'] = 'DESC';
            }
        }

        $output_data['data'] = $this->Book_model->get_ajax_course($where);
        $output_data['count'] = $this->Book_model->get_ajax_count($where);;//获取总记录数
        $output_data['pagecount'] = ceil($output_data['count'] / $perpage);
        $output_data['currentpage'] = $page;
        echo json_encode($output_data);
    }

    /**
     * 获取体系
     */
    public function ajaxSysFilter(){

        $pid = $this->input->post('pid');
        if(empty($pid))
        {
            $where = array('ArchitectureParent !=' => '0');
        }
        else
        {
            $where = array('ArchitectureParent' => $pid);
        }
        $res = $this->Plan_model->get_sys($where);
        
        echo json_encode($res);
    }

    /**
     * 选择课程
     */
    public function optcourse()
    {
        if ($data = $this->input->post(NULL, TRUE)) {
            $tmp = array('code' => '0000', 'msg' => 'success!', 'data' => []);

            $info = array('aid' => $data['aid']);
            $where = $data['cid'];

            $res = $this->Plan_model->opt_course($info, $where);
            if ($res['code'] != '0000') {
                $tmp = array('code' => $res['code'], 'msg' => $res['msg'], 'data' => []);
            }

            $this->interface_output->output_fomcat('js_Ajax', $tmp);
        }
    }

    //编辑体系名称及所属方案
    public function modsysname()
    {
        if ($data = $this->input->post(NULL, TRUE)) {
            $tmp = array('code' => '0000', 'msg' => 'success!', 'data' => []);

            $where = array('ArchitectureID' => $data['aid']);
            $info = array('ArchitectureName' => $data['aname'], 'ArchitectureParent' => $data['pid']);

            $res = $this->Plan_model->mod_sysname($info, $where);
            if ($res['code'] != '0000') {
                $tmp = array('code' => $res['code'], 'msg' => $res['msg'], 'data' => []);
            }

            $this->interface_output->output_fomcat('js_Ajax', $tmp);
        }
    }

    /**
     * 删除体系
     */
    public function delsys()
    {
        if ($data = $this->input->post(NULL, TRUE)) {
            $tmp = array('code' => '0000', 'msg' => 'success!', 'data' => []);

            $where = array('ArchitectureID' => $data['aid']);

            $res = $this->Plan_model->del_sys($where);
            if ($res['code'] != '0000') {
                $tmp = array('code' => $res['code'], 'msg' => $res['msg'], 'data' => []);
            }

            $this->interface_output->output_fomcat('js_Ajax', $tmp);
        }
    }

     /**
     * 删除课程
     */
    public function delcourse()
    {
        if ($data = $this->input->post(NULL, TRUE)) {
            $tmp = array('code' => '0000', 'msg' => 'success!', 'data' => []);

            $where = array('PackageID' => $data['cid']);

            $res = $this->Plan_model->del_course($where);
            if ($res['code'] != '0000') {
                $tmp = array('code' => $res['code'], 'msg' => $res['msg'], 'data' => []);
            }

            $this->interface_output->output_fomcat('js_Ajax', $tmp);
        }
    }

    /**============================================================================================================*/

    /**
     * 我的课程
     */
    public function mybook()
    {
        //从url里获取参数,TODO数据安全过滤
        $parameter = $this->uri->uri_to_assoc(3);
        $uri_segment = (count($parameter) * 2) + 1;
        $per_page = $this->uri->segment($uri_segment) === NULL ? 1 : $this->uri->segment($uri_segment);
        $search = array_key_exists('search', $parameter) ? urldecode($parameter['search']) : '';
        $pid = array_key_exists('pid', $parameter) ? urldecode($parameter['pid']) : '';
        $aid = array_key_exists('aid', $parameter) ? urldecode($parameter['aid']) : '';
        $sort = array_key_exists('sort', $parameter) ? $parameter['sort'] : '';

        $search = $this->security->xss_clean($search);

        $page = max(intval($per_page), 1);
        $perpage = 10;//每页记录数
        $offset = ($page - 1) * $perpage;

        //培训方案
        $where1 = array('ArchitectureParent' => '0', 'Author'=>$this->account);

        $data['trains'] = $this->Book_model->get_plan($where1);

        $pageurl = '';//页面url拼接

        //课程体系
        if(!empty($pid)){
            $where2 = array('ArchitectureParent' => $pid);
            $pageurl .= '/pid/' . $pid;

        }else{
            $where2 = array('ArchitectureParent !=' => '0', 'Author'=>$this->account);
        }

        if(!empty($pid) && !empty($aid))
        {
            $pageurl .= '/aid/'. $aid;
        }

        $data['sys'] = $this->Book_model->get_plan($where2);

        $where = array(
            'author' => $this->author,
            'pid' => $pid,
            'aid' => $aid,
            'limit' => array('limit' => $perpage, 'offset' => $offset)
        );

        //排序方式
        if (!empty($sort)) {
            $where['sort'] = array('field' => explode("%20", $sort)[0], 'order' => explode("%20", $sort)[1]);
            $pageurl .= '/sort/' . $sort;
        }
        if(!empty($search)){
            $where['search'] = $search;//搜索字符串要转码
            $pageurl .= '/search/' . $search;
        }
        //p($where);
        //获取课程
        $courses = $this->Book_model->get_course($where);
        foreach ($courses as $ks => $s)
        {
            $quote = $this->Book_model->get_quote($s['PackageID']);
            $courses[$ks]['quoteNum'] = $quote;
        }

        $data['courses'] = $courses;

        $data['search'] = $search;
        $data['pid'] = $pid;
        $data['aid'] = $aid;
        //分页
        $this->load->helper('util');
        $data['total_rows'] = $this->Book_model->get_count($where);;//获取总记录数
        $data['pages'] = get_pages(site_url('Subject/mybook'), $data['total_rows']);

        $page_count = ceil($data['total_rows']/10);
        $data['sort'] = isset($where['sort']) ? $where['sort'] : '';

        $data['page_url']=site_url('Subject/mybook'). $pageurl.'/';

        if(!empty($pid))
        {
            if($page_count == 0)
            {
                $data['page_url']=site_url('Subject/mybook').'/';
                $page_count = 1;
            }
        }

        $data['page_count'] = $page_count;

        $data['page_pre']=$page;


        $this->load->view('teacher/books_list', $data);
    }

    /**
     * 引用
     */
    public function quote_list()
    {
        $cid = $this->input->post('cid');
        $result = $this->Book_model->quote_list($cid);
        echo json_encode($result);
    }

    /**
     * 课程结构
     */
    public function courseframe()
    {
        //课程ID
        $cid = $this->uri->segment(4);
        $data['cid'] = $cid;

        //获取章
        $chapters = $this->Book_model->get_chapters($cid);
        $chapters_num = count($chapters);
        $upnum = $this->Book_model->nums();
        $upnum_chapters = array_slice($upnum, 0, $chapters_num);

        foreach ($upnum_chapters as $k => $v)
        { $upnum_chapters[$k] = '第'.$v.'章'; }

        //获取单元
        foreach ($chapters as $v)
        { $units[] = $this->Book_model->get_units($v['PackageID']); }

        //获取节
        if(!empty($units))
        {
            //课时总数
            $course_nums = 0;
            foreach ($units as $key => $unit)
            {
                foreach ($unit as $k => $v)
                {
                    $units[$key][$k]['sections'] = $this->Book_model->get_sections($v['CourseID']);
                    $course_nums += count($units[$key][$k]['sections']);
                }
            }

            foreach ($units as $v)
            {
                $units_num = count($v);
                $upnum_units = array_slice($upnum, 0, $units_num);
                foreach ($upnum_units as $kk => $vv)
                { $upnum_units[$kk] = '第'.$vv.'单元'; }
                $new_units[] = array_combine($upnum_units, $v);
            }

            for($i=0; $i<$chapters_num;$i++)
            { $chapters[$i]['units'] = $new_units[$i]; }
        }

        $new_chapters = array_combine($upnum_chapters, $chapters);

        $data['chapters']  = $new_chapters;

        //是否正在学习
        $result = $this->Book_model->is_study(array('PackageID'=>$cid,'TaskType'=>1));
        if($result['code'] == '0000')
        {
            $data['status'] = 1;
        } else {
            $data['status'] = 0;
        }

        $this->load->view('teacher/courseframe', $data);
    }

    /**
     * 新增章
     */
    public function addchapter()
    {
        if ($data = $this->input->post(NULL, TRUE)) {
            $tmp = array('code' => '0000', 'msg' => 'success!', 'data' => []);

            $info = array(
                'PackageType' => 2,
                'PackageName' => $data['name'],
                'PackageDesc' => $data['desc'],
                'PackageParent' => $data['cid'],
            );

            $res = $this->Book_model->add_chapter($info);
            if ($res['code'] != '0000') {
                $tmp = array('code' => $res['code'], 'msg' => $res['msg'], 'data' => []);
            }

            $this->interface_output->output_fomcat('js_Ajax', $tmp);
        }
    }

    /**
     * 编辑章
     */
    public function modchapter()
    {
        if ($data = $this->input->post(NULL, TRUE)) {

            $info = array(
                'PackageName' => $data['name'],
                'PackageDesc' => $data['desc'],
            );

            $tmp = $this->Book_model->mod_chapter($info, $data['chaid']);

            $this->interface_output->output_fomcat('js_Ajax', $tmp);
        }
    }

    /**
     * 删除章节
     */
    public function delchapter()
    {
        if ($data = $this->input->post(NULL, TRUE)) {

            $tmp = $this->Book_model->del_chapter($data['chaid']);

            $this->interface_output->output_fomcat('js_Ajax', $tmp);
        }
    }

    /**
     * 新增单元
     */
    public function addunit()
    {
        if ($data = $this->input->post(NULL, TRUE)) {
            $tmp = array('code' => '0000', 'msg' => 'success!', 'data' => []);

            $info = array(
                'CourseName' => $data['name'],
                'CourseDesc' => $data['desc'],
            );

            $res = $this->Book_model->add_unit($info, $data['chaid']);
            if ($res['code'] != '0000') {
                $tmp = array('code' => $res['code'], 'msg' => $res['msg'], 'data' => []);
            }

            $this->interface_output->output_fomcat('js_Ajax', $tmp);
        }
    }

    /**
     * 编辑单元
     */
    public function modunit()
    {
        if ($data = $this->input->post(NULL, TRUE)) {

            $info = array(
                'CourseName' => $data['name'],
                'CourseDesc' => $data['desc'],
            );

            $tmp = $this->Book_model->mod_unit($info, $data['uniid']);

            $this->interface_output->output_fomcat('js_Ajax', $tmp);
        }
    }

    /**
     * 删除单元及小节
     */
    public function delunit()
    {
        if ($data = $this->input->post(NULL, TRUE)) {

            $tmp = $this->Book_model->del_unit($data['uniid']);

            $this->interface_output->output_fomcat('js_Ajax', $tmp);
        }
    }

    /**
     * 新增小节
     */
    public function addsection()
    {
        $parameter = $this->uri->uri_to_assoc(3);
        $cid = array_key_exists('cid', $parameter) ? $parameter['cid'] : '';
        $uniid = array_key_exists('uniid', $parameter) ? $parameter['uniid'] : '';

        $data['cid'] = $cid;
        $data['uniid'] = $uniid;

        $data['upload_data'] = $this->get_video_dir();

        $this->load->view('teacher/section_add', $data);
    }

    /***
     * 获取上传目录
     * @return array
     */
    private function get_video_dir()
    {
        $output_data = [];
        $target_dir = '';
        $node_id = '';
        //如果不存在就获取
        if ($this->session->tempdata('video_dir') === NULL) {
            $result = $this->Section_model->get_mount_path();
            //p($result);die;
            if (!empty($result)) {
                foreach ($result as $row) {
                    $target_dir = $row['mnt_target_data_path'] . '/';
                    $node_id = $row["id"];
                }
                //此地址应该缓存一下，太慢了5分钟自动过期
                $this->session->set_tempdata('video_dir', $target_dir, 300);
                $this->session->set_tempdata('node_video_id', $node_id, 300);
            }
        } else {
            $target_dir = $this->session->tempdata('video_dir');
            $node_id = $this->session->tempdata('node_video_id');
        }
        $output_data['video_dir'] = $target_dir;
        $output_data['node_video_id'] = $node_id;
        return $output_data;

    }

    /**
     *  上传视频
     *  $suffix 文件后缀 $filename 文件名 $uploadDir 文件路径
     */
    public function uploadvideo()
    {
        $key = $this->input->post("key1");
        $key2 = $this->input->post("key2");
        $filename = $this->input->post("fileName");//上传文件
        $upload_path = $this->input->post("videoDir");//上传文件

        //获取扩展名
        $fileType = strtolower(strrchr($filename, '.'));
        $filename = $key2 . $key . $fileType;
        $config['file_name'] = $filename;
        $config['upload_path'] = $upload_path;
        $config['allowed_types'] = 'mp4|flv';
        $config['max_size'] = 0;

        $this->load->library('upload', $config);
        if (!$this->upload->huploadify($filename)) {
            $tmp = array('success' => FALSE, 'fileurl' => NULL, 'filename' => NULL, 'msg' => '上传失败！');
        } else {
            $tmp = array('success' => TRUE, 'fileurl' => $upload_path . $filename, 'filename' => $filename, 'msg' => '上传成功！');
        }
        $this->interface_output->output_fomcat('js_Upload', $tmp);

    }

    /**
     * 新增小节操作
     */
    public function dosection()
    {
        if ($data = $this->input->post(NULL, TRUE)) {

            //p($data);die;
            $info = array(
                'SectionPoint' => $data['SectionPoint'],
                'SectionDocType' => $data['SectionDocType'],
                'SectionType' => $data['SectionType'],
                'SceneUUID' => $data['SceneUUID'],
                'CtfID' => $data['CtfCode'],
                'VideoTime' => $data['VideoTime'],
                'SectionName' => $data['SectionName'],
                'SectionDesc' => $data['SectionDesc'],
                'SectionDiff' => $data['grade'],
                'SectionDoc' => $data['SectionDoc'],
                'VideoUrl' => $data['VideoName'],
            );
            
            $res = $this->Section_model->add_section($info);
            
            if ($res['code'] == '0000')
            {
                $unit_section = array(
                    'CourseID' => $data['uniid'],
                    'SectionID' => $res['data']
                );
                //单元与小节
                $this->Section_model->add_course_section($unit_section);

                //更新p_package 表 小节数量
                $package_section = array(
                    'cid' => $data['cid'],
                    'type' => $data['SectionType']
                );
                $this->Section_model->update_section_num($package_section);

                //小节与题目
                if(!empty($data['quesLast']))
                {
                    $section_question = array(
                        'SectionID' => $res['data'],
                        'questions' => $data['quesLast'],
                        'SceneUUID' => $data['SceneUUID'],
                        'CtfID' => $data['CtfCode'],
                    );

                    $this->Section_model->add_section_question($section_question);
                }

                //小节与工具资料
                if(!empty($data['toolChecked']))
                {
                    $section_tool = array(
                        'SectionID' => $res['data'],
                        'toolChecked' => $data['toolChecked'],
                    );

                    $this->Section_model->add_section_tool($section_tool);
                }

                $tmp = array('code' => $res['code'], 'msg' => $res['msg'], 'data' => []);
            }
            $this->interface_output->output_fomcat('js_Ajax', $tmp);
        }
    }

    /**
     * 场景
     */
    public function scenelist()
    {
        $search = $this->input->post('keyword');
        $perpage = intval($this->input->post('percount'));//每页记录数
        $curpage = intval($this->input->post('page'));

        //安全过滤
        $search = $this->security->xss_clean($search);

        $page = max(intval($curpage), 1);
        $offset = ($page - 1) * $perpage;
        $where = array(
            'limit' => array('limit' => $perpage, 'offset' => $offset)
        );
        if (!empty($search)) {
            $where['scene_name'] = $search;//搜索字符串要转码
            $where['like'] = 'scene_name';//搜索字符串要转码
        }
        $where["page"] = $page;
        $where["size"] = max($perpage, 5);

//        p($where);
        $result = $this->Scene_model->scene_list($where);

        $output_data['data'] = isset($result['SceneTemplate']) ? $result['SceneTemplate'] : [];
        $output_data['count'] = isset($result['total']) ? $result['total'] : 0;//获取总记录数
        $output_data['pagecount'] = ceil($output_data['count'] / $perpage);
        $output_data['currentpage'] = $page;
        echo json_encode($output_data);
    }

    /**
     * 工具列表
     */
    public function tools()
    {
        $this->load->model('Tool_model');

        $search = $this->input->post('keyword');
        $perpage = intval($this->input->post('percount'));//每页记录数
        $curpage = intval($this->input->post('page'));

        //安全过滤
        $search = $this->security->xss_clean($search);

        $page = max(intval($curpage), 1);
        $offset = ($page - 1) * $perpage;
        $where = array(
            'search' => $search,//搜索字符串要转码
            'limit' => array('limit' => $perpage, 'offset' => $offset)
        );

        $output_data['data'] = $this->Tool_model->get_all_tools($where);
        $output_data['count'] = $this->Tool_model->get_count($where);;//获取总记录数
        $output_data['pagecount'] = ceil($output_data['count'] / $perpage);
        $output_data['currentpage'] = $page;
        echo json_encode($output_data);
    }

    /**
     * 编辑小节
     */
    public function editsection()
    {
        $parameter = $this->uri->uri_to_assoc(3);
        $cid = array_key_exists('cid', $parameter) ? $parameter['cid'] : '';
        $secid = array_key_exists('secid', $parameter) ? $parameter['secid'] : '';
        
        //读取小节
        $section = $this->Section_model->get_sections($secid);

        //ctf
        $data['ctfname'] ='';
        if($section['CtfID'])
        {
            $ctf = $this->Section_model->get_ctfname($section['CtfID']);
            $data['ctfname'] = $ctf['CtfName'];
        }

        //场景
        $data['scene_name'] = '';
        if (!empty($section['SceneUUID'])) {
            $Sectionscene_info  = $this->Section_model->get_find_scene($section['SceneUUID']);

            if (count($Sectionscene_info)){
                $data['scene_name'] = $Sectionscene_info[0]['scene_name'];
            }
        }

        //工具资料
        $materail = $this->Section_model->get_materail($secid);

        //题目
        $question = $this->Section_model->get_questions($secid);

        $data['cid'] = $cid;
        $data['secid'] = $secid;
        $data['question'] = $question;
        $data['materail'] = $materail;
        $data['section'] = $section;
        $data['upload_data'] = $this->get_video_dir();

        $this->load->view('teacher/section_edit', $data);
    }

    /**
     * 编辑小节操作
     */
    public function modsection()
    {
        if ($data = $this->input->post(NULL, TRUE)) {

            //p($data);die;
            //如果类型有变 不同字段值清空更新
            $info = array(
                'SectionPoint' => $data['SectionPoint'],
                'SectionDocType' => $data['SectionDocType'],
                'SectionType' => $data['SectionType'],
                'SceneUUID' => $data['SceneUUID'],
                'CtfID' => $data['CtfCode'],
                'SectionName' => $data['SectionName'],
                'SectionDesc' => $data['SectionDesc'],
                'SectionDiff' => $data['grade'],
                'SectionDoc' => $data['SectionDoc'],
                'VideoUrl' => $data['VideoName'],
                'VideoTime' => $data['VideoTime'],

            );

            $res = $this->Section_model->mod_section($info, $data['secid']);

            if ($res['code'] == '0000')
            {
                //更新p_package 表 小节数量
                $package_section = array(
                    'cid' => $data['cid'],
                    'type' => $data['SectionType'],
                    'oldtype' => $data['oldtype']
                );
                //如果类型有变  不同字段数量有加有减
                $this->Section_model->mod_section_num($package_section);

                //小节与题目
                if(isset($data['quesLast']))
                {
                    $section_question = array(
                        'SectionID' => $data['secid'],
                        'questions' => $data['quesLast'],
                        'SceneUUID' => $data['SceneUUID'],
                        'CtfID' => $data['CtfCode'],
                    );

                    //清空重新添加
                    $this->Section_model->mod_section_question($section_question);

                } else {
                    //清空
                    $this->Section_model->del_section_question($data['secid']);
                }

                //小节与工具资料
                if(isset($data['toolChecked']))
                {
                    $section_tool = array(
                        'SectionID' => $data['secid'],
                        'toolChecked' => $data['toolChecked'],
                    );

                    $this->Section_model->mod_section_tool($section_tool);

                } else {
                    $this->Section_model->del_section_tool($data['secid']);

                }

                $tmp = array('code' => '0000', 'msg' => '', 'data' => []);
            }
            $this->interface_output->output_fomcat('js_Ajax', $tmp);
        }
    }

    /**
     * 删除小节
     */
    public function delsection()
    {
        if ($data = $this->input->post(NULL, TRUE)) {

            $tmp = $this->Book_model->del_section($data);

            $this->interface_output->output_fomcat('js_Ajax', $tmp);
        }
    }

    /**
     * 是否正在学习
     */
    public function isstudy()
    {
        $tmp = array('code' => '0000', 'msg' => 'success!', 'data' => []);
        do {
            $cid = $this->input->post('cid', TRUE);

            if (empty($cid)) {
                $tmp = array('code' => '0316', 'msg' => '参数错误!', 'data' => []);
                break;
            }

            $result = $this->Book_model->is_study(array('PackageID'=>$cid));
            if ($result['code'] != '0000') {
                $tmp['code'] = $result['code'];
                $tmp['msg'] = $result['msg'];
                $tmp['data'] = array();
                break;
            }

        } while (FALSE);

        $this->interface_output->output_fomcat('js_Ajax', $tmp);
    }

    /**
     * 删除课程
     */
    public function delbook()
    {
        $tmp = array('code' => '0000', 'msg' => 'success!', 'data' => []);
        do {
            $cid = $this->input->post('codes', TRUE);

            if (empty($cid)) {
                $tmp = array('code' => '0316', 'msg' => '参数错误!', 'data' => []);
                break;
            }

            $result = $this->Book_model->del_book($cid);
            if ($result['code'] != '0000') {
                $tmp['code'] = $result['code'];
                $tmp['msg'] = $result['msg'];
                $tmp['data'] = array();
                break;
            }

        } while (FALSE);

        $this->interface_output->output_fomcat('js_Ajax', $tmp);
    }

    /**
     * 显示编辑我的课程
     */
    public function editbook()
    {
        $cid = $this->uri->segment(4);
        $data['cid'] = $cid;

        $course = $this->Book_model->get_course_detail($cid)[0];

        $data['course'] = $course;

        $this->load->view('teacher/book_edit', $data);
    }

    /**
     * 编辑我的课程操作
     */
    public function modbook()
    {
        if ($data = $this->input->post(NULL, TRUE)) {
            $tmp = array('code' => '0000', 'msg' => 'success!', 'data' => []);

            if($data['img']=='')
            {
                $img = 'logo.png';
            } else {
                $img = $data['img'];
            }
            $info = array(
                'PackageName' => $data['name'],
                'PackageDesc' => $data['desc'],
                'PackageDiff' => $data['level'],
                'PackageStatus' => $data['status'],
                'PackageImg' => $img,
            );
            $cid = $data['cid'];

            $res = $this->Book_model->mod_book($info, $cid);
            if ($res['code'] != '0000') {
                $tmp = array('code' => $res['code'], 'msg' => $res['msg'], 'data' => []);
            }
            $this->interface_output->output_fomcat('js_Ajax', $tmp);
        }
    }

    /**
     * 显示新增课程
     */
    public function addbook()
    {
        //获取培训方案
        $where = array('ArchitectureParent' => '0', 'Author'=>$this->account);
        $plan = $this->Book_model->get_plan($where);
        foreach ($plan as $k => $v)
        {
            $count = $this->Book_model->get_count_sys($v['ArchitectureID']);
            if($count == 0){ unset($plan[$k]); }
        }
        $data['plan'] = $plan;

        $this->load->view('teacher/book_add', $data);
    }

    /**
     * 获取体系
     */
    public function getsys()
    {
        $pid = $this->input->post('pid');

        $where = array('ArchitectureParent'=> $pid);

        $tmp = array('code' => '0000', 'msg' => 'success!', 'data' => []);

        $res = $this->Plan_model->get_sys($where);
        if(empty($res))
        {
            $tmp = array('code' => '0307', 'msg' => '', 'data' => []);
        }
        else
        {
            $tmp['data'] = $res;
        }

        $this->interface_output->output_fomcat('js_Ajax', $tmp);
    }

    /**
     * 新增课程操作
     */
    public function doaddbook()
    {
        if ($data = $this->input->post(NULL, TRUE)) {

            if($data['img']=='')
            {
                $img = 'logo.png';
            } else {
                $img = $data['img'];
            }

            $info1 = array(
                'PackageAuthor'=>$this->author,
                'PackageParent'=>'0',
                'PackageType'=>1,
                'PackageName' => $data['name'],
                'PackageDesc' => $data['desc'],
                'PackageDiff' => $data['level'],
                'PackageStatus' => $data['status'],
                'PackageImg' => $img,
                'CreateTime' => time(),
            );

            $tmp = $this->Book_model->add_book($info1);
            if($tmp['code'] == '0000')
            {
                $info2 = array(
                    'ArchitectureID' => $data['aid'],
                    'PackageID' => $tmp['data']
                );

                $this->Book_model->add_ap($info2);
            }

            $this->interface_output->output_fomcat('js_Ajax', $tmp);
        }
    }

    /***
     * 上传
     */
    public function upimg()
    {
        $name = explode('.',$_FILES['upload']['name']);
        $suffix = array_pop($name);

        $ext = array('png', 'jpg', 'gif', 'jpeg', 'PNG', 'JPG', 'GIF', 'JPEG');
        if(!in_array($suffix, $ext)){
            $data = array('status'=>0,'filenames'=>'图片的格式必须为png,gif,jpg,jpeg,PGN,GIF,JPG,JPEG');
            echo json_encode($data);
            return false;
        }
        $filenames = 'PIC_'.time();
        $filename = $filenames.'.'.$suffix;
        $uploadDir = getcwd().'/resources/files/img/course/';
        $res = move_uploaded_file($_FILES['upload']['tmp_name'],$uploadDir.$filename);
        if($res){
            $data = array('status'=>1,'filenames'=>$filename);
            echo json_encode($data);
            return false;
        }else{
            $data = array('status'=>0,'filenames'=>'未知错误');
            echo json_encode($data);
            return false;
        }
    }

    /**
     * 上传图片
     */
    public function uploadimg()
    {
        $filename = $this->input->post("filename");//上传文件
        //p($filename);DIE;
        $filetype = strtolower(strrchr($filename, '.'));
        $filename = 'PIC_' . time() . $filetype;
        $config['file_name'] = $filename;
        $config['upload_path'] = getcwd() . '/resources/files/picture/';
        $config['allowed_types'] = 'gif|png|jpg|jpeg';
        $config['max_size'] = 1024*1024;

        $this->load->library('upload', $config);
        //p($this->upload->data());
        if (!$this->upload->do_upload('file')) {
            $tmp = array('code' => '0388', 'msg' => '上传错误!', 'data' => []);
        } else {
            //必须有这个返回
            $tmp = array('code' => '0000', 'msg' => '上传成功!', 'data' => array('filename' => $config['upload_path'] . $filename, 'siteurl'=>base_url(). 'resources/files/picture/', 'base'=>'/resources/files/picture/'));

        }
        $this->load->library('Interface_output');
        $this->interface_output->output_fomcat('js_Ajax', $tmp);
    }

    /**
     * 新增资料
     */
    public function addmaterial()
    {
        if ($data = $this->input->post(NULL, TRUE)) {

            $info = array(
                'ToolAuthor'=>$this->account,
                'ToolName' => $data['ToololdName'],
                'ToolDesc' => $data['tooldesc'],
                'ToolUrl' => 'resources/files/data/'.$data['Toolurl'],
                'Created' => time(),
            );

            $tmp = $this->Section_model->add_material($info);

            $this->interface_output->output_fomcat('js_Ajax', $tmp);
        }
    }

    /**
     * 上传资料
     */
    public function uploadtoolRes()
    {
        $key = $this->input->post("key");
        $key2 = $this->input->post("key2");
        $filename = $this->input->post("fileName");//上传文件

        //获取扩展名
        $fileType = strtolower(strrchr($filename, '.'));
        $filename = $key2 . $key . $fileType;
        $upload_path = getcwd().'/resources/files/data/';
        $config['file_name'] = $filename;
        $config['upload_path'] = $upload_path;
        $config['allowed_types'] = 'zip|gzip|rar|qcow2|tar|doc|docx|xls|xlsx|jpg|jpeg|png|bmp';
        $config['max_size'] = 0;

        $this->load->library('upload', $config);
        if (!$this->upload->huploadify($filename)) {
            $tmp = array('success' => FALSE, 'fileurl' => NULL, 'filename' => NULL, 'msg' => '上传失败！');
        } else {
            $tmp = array('success' => TRUE, 'fileurl' => $upload_path . $filename, 'filename' => $filename, 'msg' => '上传成功！');
        }
        $this->interface_output->output_fomcat('js_Upload', $tmp);
    }

    /**
     * 选择资料列表
     */
    public function datumlist()
    {
        $search = $this->input->post('keyword');
        $perpage = intval($this->input->post('percount'));//每页记录数
        $curpage = intval($this->input->post('page'));

        //安全过滤
        $search = $this->security->xss_clean($search);

        $page = max(intval($curpage), 1);
        $offset = ($page - 1) * $perpage;
        $where = array(
            'search' => $search,//搜索字符串要转码
            'limit' => array('limit' => $perpage, 'offset' => $offset)
        );

        $output_data['data'] = $this->Section_model->get_datums($where);
        $output_data['count'] = $this->Section_model->get_all_count($where);;//获取总记录数
        $output_data['pagecount'] = ceil($output_data['count'] / $perpage);
        $output_data['currentpage'] = $page;
        echo json_encode($output_data);
    }

    /**============================================================================================================*/

    //我的试卷
    public function myexam()
    {
        //从url里获取参数,TODO数据安全过滤
        $parameter = $this->uri->uri_to_assoc(3);
        $uri_segment = (count($parameter) * 2) + 1;
        $per_page = $this->uri->segment($uri_segment) === NULL ? 1 : $this->uri->segment($uri_segment);
        $search = array_key_exists('search', $parameter) ? urldecode($parameter['search']) : '';
        $time = array_key_exists('time', $parameter) ? $parameter['time'] : '';
        $sort = array_key_exists('sort', $parameter) ? $parameter['sort'] : '';

        $search = $this->security->xss_clean($search);

        $page = max(intval($per_page), 1);
        $perpage = 10;//每页记录数
        $offset = ($page - 1) * $perpage;

        $where = array(
            'UserID' => $this->userid,
            'limit' => array('limit' => $perpage, 'offset' => $offset)
        );

        $pageurl = '';//页面url拼接

        if (!empty($search)) {
            $where['search'] = $search;//搜索字符串要转码
            $pageurl .= '/search/' . $search;
        }

        //按时间范围查询
        if (!empty($time)) {
            $time = str_replace('%20', ' ', $time);
            $where['CreateTime'] = array('starttime' => explode("_", $time)[0], 'endtime' => explode("_", $time)[1]);
            $pageurl .= '/time/' . $time;
        }
        //排序方式:CreateTime DESC
        if (!empty($sort)) {
            $where['sort'] = array('field' => explode("%20", $sort)[0], 'order' => explode("%20", $sort)[1]);
            $pageurl .= '/sort/' . $sort;
        }

        //获取课程
        $data['exams'] = $this->Exam_model->get_exams($where);

        $data['search'] = $search;
        $data['time'] = isset($where['CreateTime']) ? $where['CreateTime'] : '';
        $data['sort'] = isset($where['sort']) ? $where['sort'] : '';

        //分页
        $this->load->helper('util');
        $data['total_rows'] = $this->Exam_model->get_count_exams($where);;//获取总记录数
        $data['pages'] = get_pages(site_url('Subject/myexam'), $data['total_rows']);

        $page_count = ceil($data['total_rows']/10);
        $data['page_url'] = site_url('Subject/myexam') . $pageurl.'/';
        $data['page_count'] = $page_count;
        $data['page_pre']=$page;

        $this->load->view('teacher/exam_list', $data);
    }

    /**
     * 显示编辑试卷
     */
    public function editexam()
    {
        $parameter = $this->uri->uri_to_assoc(3);
        $eid = array_key_exists('eid', $parameter) ? $parameter['eid'] : '';
        $name = array_key_exists('name', $parameter) ? urldecode($parameter['name']) : '';
        $diff = array_key_exists('diff', $parameter) ? $parameter['diff'] : '';

        $data['eid'] = $eid;
        $data['ExamName'] = $name;
        $data['ExamDiff'] = $diff;

        $questions = $this->Exam_model->edit_exam($eid);

        foreach ($questions as $kq => $q)
        {
            $str = str_replace(PHP_EOL, '', $q['QuestionDesc']);
            $questions[$kq]['QuestionDesc'] = preg_replace('/\[info\].*\[\/info\]/s', '', $str);

            if($q['QuestionLinkType']==2)
            {
                $param = array('scene_tpl_uuid'=>$q['QuestionLink']);
                $scene = $this->Question_model->get_one_scene($param);
                if(!empty($scene))
                {
                    $questions[$kq]['QuestionLink'] = $scene[0]['scene_name'];
                }
            }

            //ctf
            if($q['QuestionLinkType']==1)
            {

                $ctf = $this->Question_model->get_one_ctf($q['QuestionLink']);
                if(!empty($ctf[0]['CtfUrl']))
                {
                    $this->load->model('Ctf_model');
                    if($ctf[0]['CtfUrl'] != ''){
                        $questions[$kq]['CtfUrl'] = $this->Ctf_model->get_ctf_url($ctf[0]['CtfServerID'],$ctf[0]['CtfServerPort'],$ctf[0]['CtfUrl']);
                    }
                    $questions[$kq]['QuestionLink'] = $ctf[0]['CtfName'];

                } else {
                    $questions[$kq]['CtfUrl'] = base_url().'resources/files/ctf/'.$ctf[0]['CtfResources'];
                    $questions[$kq]['QuestionLink'] = $ctf[0]['CtfName'];

                }
            }
        }
        $data['exam_question'] = $questions;

        $this->load->view('teacher/exam_edit', $data);
    }

    /**
     * 操作编辑试卷
     */
    public function modexam()
    {
        if ($data = $this->input->post(NULL, TRUE)) {
            
            $eid = $data['examid'];
            $info = array(
                'ExamDiff' => $data['level'],
                'ExamName' => $data['examname'],
            );

            $tmp = $this->Exam_model->mod_exam($info, $eid);

            if($tmp['code']=='0000')
            {
                $tmp = $this->Exam_model->add_eq($data['lastArr'], $eid);
            }

            $this->interface_output->output_fomcat('js_Ajax', $tmp);
        }
    }

    /**
     * 试卷任务是否完成
     */
    public function isfinish()
    {
        $eid = $this->security->xss_clean ($this->input->post('qid'));

        $tmp = $this->Exam_model->is_finish($eid);

        $this->interface_output->output_fomcat('js_Ajax', $tmp);
    }
    
    /**
     * 删除试卷
     */
    public function delexam()
    {
        $eid = $this->security->xss_clean ($this->input->post('code'));

        $tmp = array('code' => '0000', 'msg' => 'success!', 'data' => []);

        $res=$this->Exam_model->del_exam($eid);

        if ($res['code'] != '0000') {
            $tmp = array('code' => $res['code'], 'msg' => $res['msg'], 'data' => []);
        }

        $this->interface_output->output_fomcat('js_Ajax', $tmp);
    }

    /**
     * 新增试卷
     */
    public function addexam()
    {
        $this->load->view('teacher/exam_add');
    }

    /**
     * 新增试卷操作
     */
    public function doaddexam()
    {
        if ($data = $this->input->post(NULL, TRUE)) {

            $info = array(
                'ExamDiff' => $data['level'],
                'ExamName' => $data['examname'],
                'TeacherID' => $this->userid,
                'data' => $data['data'],
            );

            $res = $this->Exam_model->add_exam($info);

            if($res['code'] == '0000')
            {
                $eid = $res['data'];
                $tmp = $this->Exam_model->add_exam_question($data['data'], $eid);
            } else {
                $tmp = array('code' => '0399', 'msg' => '', 'data' => []);
            }

            $this->interface_output->output_fomcat('js_Ajax', $tmp);
        }
    }

    /**
     * 获取所有题目
     */
    public function all_question()
    {
        $search = $this->input->post('keyword');
        $perpage = intval($this->input->post('percount'));//每页记录数
        $curpage = intval($this->input->post('page'));
        $question_type=$this->input->post('question_type');//题目类型

        //安全过滤
        $search = $this->security->xss_clean($search);

        $page = max(intval($curpage), 1);
        $offset = ($page - 1) * $perpage;
        $where = array(
            'search' => $search,//搜索字符串要转码
            'limit' => array('limit' => $perpage, 'offset' => $offset),
        	'QuestionType' => $question_type
        );

        //p($where);
        $questions = $this->Exam_model->get_all_question($where);
        foreach ($questions as $kq => $q)
        {
            $questions[$kq]['ResourceUrl'] = json_decode($q['ResourceUrl']);
            $questions[$kq]['ResourceName'] = json_decode($q['ResourceName']);

            $str = str_replace(PHP_EOL, '', $q['QuestionDesc']);
            $questions[$kq]['QuestionDesc'] = preg_replace('/\[info\].*\[\/info\]/s', '', $str);

            if($q['QuestionLinkType']==2)
            {
                $param = array('scene_tpl_uuid'=>$q['QuestionLink']);
                $scene = $this->Question_model->get_one_scene($param);
                if(!empty($scene))
                {
                    $questions[$kq]['QuestionLink'] = $scene[0]['scene_name'];
                }
            }

            //ctf
            if($q['QuestionLinkType']==1)
            {
                $ctf = $this->Question_model->get_one_ctf($q['QuestionLink']);
                if(!empty($ctf[0]['CtfUrl']))
                {
                    $this->load->model('Ctf_model');
                    if($ctf[0]['CtfUrl'] != ''){
                        $questions[$kq]['CtfUrl'] = $this->Ctf_model->get_ctf_url($ctf[0]['CtfServerID'],$ctf[0]['CtfServerPort'],$ctf[0]['CtfUrl']);
                    }
                    $questions[$kq]['QuestionLink'] = $ctf[0]['CtfName'];

                } else {
                    if(!empty($ctf)){
	                	$questions[$kq]['CtfUrl'] = base_url().'resources/files/ctf/'.$ctf[0]['CtfResources'];
	                    $questions[$kq]['QuestionLink'] = $ctf[0]['CtfName'];
                    }
                }
            }
        }
        $output_data['data'] = $questions;

        $output_data['count'] = $this->Exam_model->get_all_count($where);;//获取总记录数
        $output_data['pagecount'] = ceil($output_data['count'] / $perpage);
        $output_data['currentpage'] = $page;
        echo json_encode($output_data);
    }

    /**============================================================================================================*/

    //所有题目
    public function questionlist()
    {
        //从url里获取参数,TODO数据安全过滤
        $parameter = $this->uri->uri_to_assoc(3);
        $uri_segment = (count($parameter) * 2) + 1;
        $per_page = $this->uri->segment($uri_segment) === NULL ? 1 : $this->uri->segment($uri_segment);
        $search = array_key_exists('search', $parameter) ? urldecode($parameter['search']) : '';
        $sort = array_key_exists('sort', $parameter) ? $parameter['sort'] : '';
        $uname = array_key_exists('uname', $parameter) ? urldecode($parameter['uname']) : '';
        $qtype = array_key_exists('qtype', $parameter) ? urldecode($parameter['qtype']) : '';

        $search = $this->security->xss_clean($search);

        $page = max(intval($per_page), 1);
        $perpage = 10;//每页记录数
        $offset = ($page - 1) * $perpage;

        $where = array(
            'limit' => array('limit' => $perpage, 'offset' => $offset)
        );

        $pageurl = '';//页面url拼接
        if(!empty($uname))
        {
            $where['uname'] = $uname;
            $pageurl .= '/uname/'. $uname;
        }

        if(!empty($qtype))
        {
            $where['qtype'] = (int)$qtype;
            $pageurl .= '/qtype/'. $qtype;
        }

        if (!empty($search)) {
            $where['search'] = $search;//搜索字符串要转码
            $pageurl .= '/search/'. $search;
        }

        //排序方式
        if (!empty($sort)) {
            $where['sort'] = array('field' => explode("%20", $sort)[0], 'order' => explode("%20", $sort)[1]);
            $pageurl .= '/sort/' . $sort;
        }

        //获取题目
        $questions = $this->Question_model->get_questions($where);
        //p($questions);
        foreach ($questions as $kq => $q)
        {
            $str = str_replace(PHP_EOL, '', $q['QuestionDesc']);
            $questions[$kq]['QuestionDesc'] = preg_replace('/\[info\].*\[\/info\]/s', '', $str);

            if($q['QuestionLinkType']==2)
            {
                $param = array('scene_tpl_uuid'=>$q['QuestionLink']);
                $scene = $this->Question_model->get_one_scene($param);
                if(!empty($scene))
                {
                    $questions[$kq]['changjing'] = $scene[0]['scene_name'];
                }
            }

            //ctf
            if($q['QuestionLinkType']==1)
            {
                $ctf = $this->Question_model->get_one_ctf($q['QuestionLink']);

                if(!empty($ctf[0]['CtfUrl']))
                {
                    $this->load->model('Ctf_model');
                    if($ctf[0]['CtfUrl'] != ''){
                        $questions[$kq]['CtfUrl'] = $this->Ctf_model->get_ctf_url($ctf[0]['CtfServerID'],$ctf[0]['CtfServerPort'],$ctf[0]['CtfUrl']);
                    }
                    $questions[$kq]['changjing'] = $ctf[0]['CtfName'];

                } else {
                    if(!empty($ctf))
                    {
                        $questions[$kq]['CtfUrl'] = base_url().'resources/files/ctf/'.$ctf[0]['CtfResources'];
                        $questions[$kq]['changjing'] = $ctf[0]['CtfName'];
                    }
                }
            }
        }

        $data['questions'] = $questions;

        //获取作者
        $teachers = $this->Question_model->get_teachers(array(2,1));

        $data['teachers'] = $teachers;
        $data['search'] = $search;
        $data['uname'] = $uname;
        $data['author'] = $this->author;
        $data['qtype'] = $qtype;
        $data['sort'] = isset($where['sort']) ? $where['sort'] : '';

        //分页
        $this->load->helper('util');
        $data['total_rows'] = $this->Question_model->get_count_questions($where);;//获取总记录数
        $data['pages'] = get_pages(site_url('Subject/questionlist'), $data['total_rows']);

        $page_count = ceil($data['total_rows']/10);

        $data['page_url']=site_url('Subject/questionlist').$pageurl .'/';

        $data['page_count'] = $page_count;

        $data['page_pre']=$page;


        $this->load->view('teacher/question_list', $data);
    }

    /**
     * 新增题目
     */
    public function addquestion()
    {
        $this->load->view('teacher/question_add');
    }

    /**
     * 新增题目操作
     */
    public function doaddquestion()
    {
        if ($data = $this->input->post(NULL, TRUE)) {
            $urlstr = '';
            $namestr = '';
            if(!empty($data['ResourceUrl']))
            { $urlstr = json_encode(implode(',', $data['ResourceUrl'])); }

            if(!empty($data['ResourceName']))
            { $namestr = json_encode(implode(',', $data['ResourceName'])); }

            $info = array(
                'QuestionDesc' => $data['QuestionDesc'],
                'QuestionType' => $data['QuestionType'],
                'QuestionDiff' => $data['QuestionDiff'],
                'QuestionAnswer' => $data['QuestionAnswer'],
                'QuestionChoose' => $data['choosearray'],
                'QuestionLink' => $data['QuestionLink'],
                'ResourceUrl' => $urlstr,
                'ResourceName' => $namestr,
                'QuestionLinkType' => $data['QuestionLinkType'],
                'QuestionAuthor' => $this->author,
                'CreateTime' => time(),
            );

            if($data['QuestionLinkType'] != 0)
            {
                $info['QuestionScene'] = 2;
            }
            //p($info);die;

            $tmp = $this->Question_model->add_question($info);

            $this->interface_output->output_fomcat('js_Ajax', $tmp);
        }
    }

    /**
     *  上传视频
     *  $suffix 文件后缀 $filename 文件名 $uploadDir 文件路径
     */
    public function accessory()
    {
        $key = $this->input->post("key");
        $key2 = $this->input->post("key2");
        $filename = $this->input->post("fileName");//上传文件
        //获取扩展名
        $fileType = strtolower(strrchr($filename, '.'));
        $filename = $key2 . $key . $fileType;
        $upload_path = getcwd() . '/resources/files/question/';
        $config['file_name'] = $filename;
        $config['upload_path'] = $upload_path;
        $config['allowed_types'] = 'mp4|flv';
        $config['max_size'] = 0;

        $this->load->library('upload', $config);
        if (!$this->upload->huploadify($filename)) {
            $tmp = array('success' => FALSE, 'fileurl' => NULL, 'filename' => NULL, 'msg' => '上传失败！');
        } else {
            $tmp = array('success' => TRUE, 'fileurl' => '/resources/files/question/' . $filename, 'filename' => $filename, 'msg' => '上传成功！');
        }
        $this->interface_output->output_fomcat('js_Upload', $tmp);

    }

    /**
     * 获取ctf场景
     */
    public function ctflist()
    {
        $search = $this->input->post('keyword');
        $perpage = intval($this->input->post('percount'));//每页记录数
        $curpage = intval($this->input->post('page'));

        //安全过滤
        $search = $this->security->xss_clean($search);

        $page = max(intval($curpage), 1);
        $offset = ($page - 1) * $perpage;
        $where = array(
            'search' => $search,//搜索字符串要转码
            'limit' => array('limit' => $perpage, 'offset' => $offset)
        );

        $output_data['data'] = $this->Question_model->get_ctf($where);
        $output_data['count'] = $this->Question_model->get_ctf_count($where);;//获取总记录数
        $output_data['pagecount'] = ceil($output_data['count'] / $perpage);
        $output_data['currentpage'] = $page;
        echo json_encode($output_data);
    }

    /***
     * 上传CSV文件
     */
    public function uploadcsv($type = 'course')
    {
        $key = $this->input->post("key");
        $key2 = $this->input->post("key2");
        //$filename = $this->input->post("fileName");//上传文件
        $filename = $key2 . $key .'.csv';
        $config['file_name'] = $filename;
        $config['upload_path'] = getcwd() . '/resources/files/csv/';
        $config['allowed_types'] = 'csv';
        $config['max_size'] = 0;
        
        $this->load->library('upload', $config);//非切片上传
        if (!$this->upload->do_upload('file')) {
            $tmp = array('code' => '0388', 'msg' => '上传错误!', 'data' => []);
        } else {
            //必须有这个返回
            $data = $type == 'student' ? $this->_resolve_csv($filename) : $this->_resolve_teacher_csv($filename);
            $tmp = array('code' => '0000', 'msg' => '上传成功!', 'data' => array('filename' => $filename,
                'contents' => $data));
        }
        //$res = $this->upload->uploadfile($_FILES['file'], array('filename' => $filename), $config['upload_path']);
        $this->interface_output->output_fomcat('js_Ajax', $tmp);

    }
    
    /**
     * 显示编辑题目
     */
    public function editquestion()
    {
        $qid = $this->uri->segment(4);
        $data['qid'] = $qid;

        //题目信息
        $question = $this->Question_model->get_one_question($qid);

        $urlarr = array();
        $namearr = array();
        if($question['ResourceUrl'])
        { $urlarr = explode(',',json_decode($question['ResourceUrl'])); }

        if($question['ResourceName'])
        { $namearr = explode(',',json_decode($question['ResourceName'])); }

        $count = count($urlarr);
        $resarr = array();

        for($i=0;$i<$count;$i++)
        {
            $resarr[] = array('url'=>$urlarr[$i], 'name'=>$namearr[$i]);
        }

        $scenename = '';

        //scene
        if($question['QuestionLinkType']==2)
        {
            $param = array('scene_tpl_uuid'=>$question['QuestionLink']);
            $scene = $this->Question_model->get_one_scene($param);
            if(!empty($scene))
            {
                $scenename = $scene[0]['scene_name'];
            }
        }
        
        //ctf
        if($question['QuestionLinkType']==1)
        {
            $ctf = $this->Question_model->get_one_ctf($question['QuestionLink']);
            if(!empty($ctf))
            {
                $scenename = $ctf[0]['CtfName'];
            }
        }

        $data['resarr'] = $resarr;
        $data['scenename'] = $scenename;
        $data['question'] = $question;
        $this->load->view('teacher/question_edit', $data);
    }

    /**
     * 编辑题目操作
     */
    public function modquestion()
    {
        if ($data = $this->input->post(NULL, TRUE)) {

            $info = array(
                'QuestionDesc' => $data['QuestionDesc'],
                'QuestionType' => $data['QuestionType'],
                'QuestionDiff' => $data['QuestionDiff'],
                'QuestionAnswer' => $data['QuestionAnswer'],
                'QuestionChoose' => $data['choosearray'],
                'QuestionLinkType' => $data['QuestionLinkType'],
                'QuestionLink' => $data['QuestionLink'],
            );

            if(!empty($data['ResourceUrl']))
            {
                $urlstr = json_encode(implode(',',$data['ResourceUrl']));
                $info['ResourceUrl'] = $urlstr;
            } else {
                $info['ResourceUrl'] = '';
            }

            if(!empty($data['ResourceName']))
            {
                $namestr = json_encode(implode(',',$data['ResourceName']));
                $info['ResourceName'] = $namestr;
            } else {
                $info['ResourceName'] = '';
            }
            //p($info);die;

            $tmp = $this->Question_model->mod_question($info, $data['qid']);
            
            $this->interface_output->output_fomcat('js_Ajax', $tmp);
        }
    }

    /**
     * 是否关联试卷
     */
    public function isrelation()
    {
        $qid = $this->security->xss_clean ($this->input->post('qid'));

        $tmp=$this->Question_model->is_relation($qid);

        $this->interface_output->output_fomcat('js_Ajax', $tmp);
    }

    /**
     * 删除题目
     */
    public function delquestion()
    {
        $qid = $this->security->xss_clean ($this->input->post('cid'));

        $tmp = array('code' => '0000', 'msg' => 'success!', 'data' => []);

        $res=$this->Question_model->del_question($qid);

        if ($res['code'] != '0000') {
            $tmp = array('code' => $res['code'], 'msg' => $res['msg'], 'data' => []);
        }

        $this->interface_output->output_fomcat('js_Ajax', $tmp);
    }

    /**============================================================================================================*/

    //所有工具列表
    public function toollist()
    {
        $this->load->model('Tool_model');

        //从url里获取参数
        $parameter = $this->uri->uri_to_assoc(3);
        $uri_segment = (count($parameter) * 2) + 1;
        $per_page = $this->uri->segment($uri_segment) === NULL ? 1 : $this->uri->segment($uri_segment);
        $search = array_key_exists('search', $parameter) ? urldecode($parameter['search']) : '';
        $type = array_key_exists('type', $parameter) ? $parameter['type'] : '';
        $sort = array_key_exists('sort', $parameter) ? $parameter['sort'] : '';

        //安全过滤
        $search = $this->security->xss_clean($search);

        $page = max(intval($per_page), 1);
        $perpage = 10;//每页记录数
        $offset = ($page - 1) * $perpage;


        $where = array(
            'limit' => array('limit' => $perpage, 'offset' => $offset)
        );
        $pageurl = '';//页面url拼接

        if(!empty($type))
        {
            $where['typeid'] = $type;
            $pageurl .= '/type/'. $type;
        }

        if (!empty($search)) {
            $where['search'] = $search;//搜索字符串要转码
            $pageurl .= '/search/' . $search;
        }

        //排序方式:CreateTime DESC
        if (!empty($sort)) {
            $where['sort'] = array('field' => explode("%20", $sort)[0], 'order' => explode("%20", $sort)[1]);
            $pageurl .= '/sort/' . $sort;
        }

        //工具类型
        $tool_types = $this->Tool_model->get_tooltypes();
        $data['tool_types'] = $this->singlearr($tool_types);

        $data['tool_list'] = $this->Tool_model->get_all_tools($where);

        $data['search'] = $search;
        $data['ts'] = $type ? $type : '';
        $data['time'] = isset($where['updateTime']) ? $where['updateTime'] : '';
        $data['sort'] = isset($where['sort']) ? $where['sort'] : '';

        //分页
        $this->load->helper('util');
        $data['total_rows'] = $this->Tool_model->get_count($where);;//获取总记录数
        $data['pages'] = get_pages(site_url('Subject/toollist'), $data['total_rows']);

        $page_count = ceil($data['total_rows']/10);

        $data['page_url'] = site_url('Subject/toollist') . $pageurl.'/';

        $data['page_count']=$page_count;
        $data['page_pre']=$page;


        $this->load->view('teacher/tool_list', $data);
    }

    /***
     * 获取工具详细信息
     */
    public function get_detail()
    {
        $this->load->model('Tool_model');

        $tmp = array('code' => '0000', 'msg' => 'success!', 'data' => []);
        do {
            $toolcode = intval($this->input->post('code', TRUE));
            if ($toolcode == 0) {
                $tmp = array('code' => '0316', 'msg' => '参数错误!', 'data' => []);
                break;
            }
            $result = $this->Tool_model->get_detail($toolcode);
            if (empty($result)) {
                $tmp = array('code' => '0316', 'msg' => '找不到工具信息!', 'data' => []);
                break;
            }
            $tmp['data'] = $result;


        } while (FALSE);

        $this->interface_output->output_fomcat('js_Ajax', $tmp);

    }

    /***
     * 删除工具
     */
    public function del_tool()
    {
        do {
            $this->load->model('Tool_model');

            $toolcode = $this->input->post('toolcode', TRUE);
            if (empty($toolcode)) {
                $tmp = array('code' => '0316', 'msg' => '参数错误!', 'data' => []);
                break;
            }

            $tmp = $this->Tool_model->del_tool($toolcode);

        } while (FALSE);

        $this->interface_output->output_fomcat('js_Ajax', $tmp);

    }
    
    //添加工具
    public function addtool()
    {
        $this->load->model('Tool_model');

        if($data = $this->input->post(NULL, TRUE))
        {
            //p($data);die;
            $info = array(
                'toolCode' => $data['toolCode'],
                'classifyCode' => $data['ToolType'],
                'toolName' => $data['ToolName'],
                'description' => $data['ToolDesc'],
                'uploadName' => $data['ToolUrl'],
                'updateTime' => date('Y-m-d H:i:s', time()),
                'toolOwner' => $this->account,
                'toolPath' => '/resources/files/',
                'toolSuffix' => "." . explode(".", $data["ToolUrl"])[1],

            );

            $tmp = $this->Tool_model->add_tool($info);

            $this->interface_output->output_fomcat('js_Ajax', $tmp);

        }
        else
        {
            $this->load->helper('util');
            //工具类型
            $tool_types = $this->Tool_model->get_tooltypes();
            $data['tool_types'] = $this->singlearr($tool_types);
            //p($data['tool_types']);
            $data['upload_data'] = $this->get_target_dir();
            $data['toolcode'] = get_unique_code();

            $this->load->view('teacher/addtool', $data);
        }

    }

    /***
     * 获取上传目录
     * @return array
     */
    private function get_target_dir()
    {
        $this->load->model('Tool_model');

        $output_data = [];
        $target_dir = '';
        $node_id = '';
        //如果不存在就获取
        if ($this->session->tempdata('tool_dir') === NULL) {
            $result = $this->Tool_model->get_mount_path();
            //p($result);die;
            if (!empty($result)) {
                foreach ($result as $row) {
                    $target_dir = $row['mnt_target_data_path'] . '/';
                    $node_id = $row["id"];
                }
                //此地址应该缓存一下，太慢了5分钟自动过期
                $this->session->set_tempdata('tool_dir', $target_dir, 300);
                $this->session->set_tempdata('node_tool_id', $node_id, 300);
            }
        } else {
            $target_dir = $this->session->tempdata('tool_dir');
            $node_id = $this->session->tempdata('node_tool_id');
        }
        $output_data['tool_dir'] = $target_dir;
        $output_data['node_tool_id'] = $node_id;
        return $output_data;

    }


    /**
     * 上传工具
     */
    public function upload_tool()
    {
        $key = $this->input->post("key");
        $key2 = $this->input->post("key2");
        $filename = $this->input->post("fileName");//上传文件
        $upload_path = $this->input->post("toolDir");//上传文件

        //获取扩展名
        $fileType = strtolower(strrchr($filename, '.'));
        $filename = $key2 . $key . $fileType;
        $config['file_name'] = $filename;
        $config['upload_path'] = $upload_path;
        $config['allowed_types'] = 'zip|gzip|rar|qcow2|tar|doc|docx|xls|xlsx|jpg|jpeg|png|bmp';
        $config['max_size'] = 0;

        $this->load->library('upload', $config);
        if (!$this->upload->huploadify($filename)) {
            $tmp = array('success' => FALSE, 'fileurl' => NULL, 'filename' => NULL, 'msg' => '上传失败！');
        } else {
            $tmp = array('success' => TRUE, 'fileurl' => $upload_path . $filename, 'filename' => $filename, 'msg' => '上传成功！');
        }
        $this->interface_output->output_fomcat('js_Upload', $tmp);
    }

    //分类管理
    public function toolcate()
    {
        //从url里获取参数
        $parameter = $this->uri->uri_to_assoc(3);
        $uri_segment = (count($parameter) * 2) + 1;
        $per_page = $this->uri->segment($uri_segment) === NULL ? 1 : $this->uri->segment($uri_segment);
        $search = array_key_exists('search', $parameter) ? urldecode($parameter['search']) : '';

        //安全过滤
        $search = $this->security->xss_clean($search);

        $page = max(intval($per_page), 1);
        $perpage = 10;//每页记录数
        $offset = ($page - 1) * $perpage;

        $where = array(
            'search' => $search,//搜索字符串要转码
            'limit' => array('limit' => $perpage, 'offset' => $offset)
        );

        $this->load->model('Tool_model');

        //工具分类
        $type_list = $this->Tool_model->get_all_types($where);
        foreach ($type_list as $kt => $t) {
            $tmp = $this->Tool_model->get_children(array('classifyParent'=> $t['ID']));
            if($tmp['code']=='0000')
            {
                $type_list[$kt]['mark'] = 1;
            } else {
                $type_list[$kt]['mark'] = 0;
            }

        }
        
        $data['type_list'] = $this->category($type_list);

        //一级分类
        $data['first_list'] = $this->Tool_model->get_first_types();

        $data['search'] = $search;

        //分页
        $this->load->helper('util');
        $data['total_rows'] = $this->Tool_model->get_count_type($where);;//获取总记录数
        $data['pages'] = get_pages(site_url('Subject/toolcate'), $data['total_rows']);

        $page_count = ceil($data['total_rows']/10);

        if(!empty($search))
        {
            if($page_count == 0)
            {
                $data['page_url']=site_url('Subject/toolcate').'/';
                $page_count = 1;
            }else{

                $data['page_url']=site_url('Subject/toolcate').'/'.'search/'.$search .'/';
            }
        }else{

            $data['page_url']=site_url('Subject/toolcate').'/';
        }

        $data['page_count'] = $page_count;
        $data['page_pre']=$page;

        $this->load->view('teacher/toolcate', $data);
    }

    /**
     *获取子类
     */
    public function getchild()
    {
        $this->load->model('Tool_model');

        $typeId=$this->input->post('pid');

        $where = array('classifyParent'=> $typeId);

        $tmp=$this->Tool_model->get_children($where);

        $this->interface_output->output_fomcat('js_Ajax', $tmp);
    }

    //组合一维数组
    public function singlearr($cate, $html='>>', $pid=0, $level=0){
        $arr = array();
        foreach ($cate as $v) {
            if($v['classifyParent'] == $pid){
                $v['level'] = $level + 1;
                $v['html'] = str_repeat($html, $level);
                $arr[] = $v;
                $arr = array_merge($arr, $this->singlearr($cate, $html, $v['ID'], $level+1));
            }
        }
        return $arr;
    }

    //组合多维数组
    public function category($cate, $name='child', $pid=0)
    {
        $arr = array();
        foreach ($cate as $v) {
            if($v['classifyParent'] == $pid){
                $v[$name] = $this->category($cate, $name , $v['ID']);
                $arr[] = $v;
            }
        }

        return $arr;
    }

    /**
    *编辑分类名
    */
    public function modtype()
    {
        $this->load->model('Tool_model');

        $typeName=$this->security->xss_clean ($this->input->post('typeName'));
        $typeId=$this->security->xss_clean ($this->input->post('typeId'));

        $where = array('ID'=> $typeId);
        $data = array('classifyName' => $typeName);

        $tmp = $this->Tool_model->mod_type($data, $where);

        $this->interface_output->output_fomcat('js_Ajax', $tmp);
    }

    /**
    *编辑工具名称
    */
    public function modtname()
    {
        $this->load->model('Tool_model');

        $toolname=$this->security->xss_clean ($this->input->post('toolname'));
        $cid=$this->security->xss_clean ($this->input->post('cid'));

        $where = array('ID'=> $cid);
        $data = array('toolName' => $toolname);

        $tmp = $this->Tool_model->mod_tname($data, $where);

        $this->interface_output->output_fomcat('js_Ajax', $tmp);
    }



    /**
     * 删除分类
     */
    public function deltype()
    {
        $this->load->model('Tool_model');

        $typeId=$this->input->post('typeId');

        $where = array('ID'=> $typeId);

        $tmp=$this->Tool_model->del_type($where);

        $this->interface_output->output_fomcat('js_Ajax', $tmp);
    }

    /**
     * 新增分类
     */
    public function addtype()
    {
        $this->load->model('Tool_model');

        if ($data = $this->input->post(NULL, TRUE)) {

            $info = array(
                'classifyName' => $data['TypeName'],
                'classifyParent' => $data['Pid'],
                'create_time' => date('Y-m-d H:i:s', time()),
            );

            $tmp = $this->Tool_model->add_type($info);

            $this->interface_output->output_fomcat('js_Ajax', $tmp);

        }
    }



}