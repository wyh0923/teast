<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: qirupeng
 * Date: 2016/8/22
 * Time: 16:50
 */
class Train extends ECQ_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Ctf_model');
        $this->load->model('Scene_model');
        $this->load->library('Interface_output');
        $this->load->helper('util');
    }

    /***
     * ctf实训管理
     */
    public function ctflist()
    {
        $output_data = array();
        //从url里获取参数
        $parameter = $this->uri->uri_to_assoc(3);
        $uri_segment = (count($parameter) * 2) + 1;
        $per_page = $this->uri->segment($uri_segment) === NULL ? 1 : $this->uri->segment($uri_segment);
        $search = array_key_exists('search', $parameter) ? urldecode($parameter['search']) : '';
        $time = array_key_exists('time', $parameter) ? $parameter['time'] : '';

        $type = array_key_exists('type', $parameter) ? intval($parameter['type']) : '';
        $author = array_key_exists('author', $parameter) ? intval($parameter['author']) : '';


        $search = $this->security->xss_clean($search);

        $page = max(intval($per_page), 1);
        $perpage = 10;//每页记录数
        $offset = ($page - 1) * $perpage;

        $where = array(
            'limit' => array('limit' => $perpage, 'offset' => $offset)
        );
        $pageurl = '';//页面url拼接
        //CTF类型
        if (!empty($type)) {
            $where['CtfType'] = $type;
            $pageurl .= '/type/' . $type;
        }
        //author
        if (!empty($author)) {
            $where['AuthorID'] = $author;
            $pageurl .= '/author/' . $author;
        }
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


        $output_data['ctf_list'] = $this->Ctf_model->ctf_list($where);
        //ctfurl处理
        foreach ($output_data['ctf_list'] as &$row) {
            $row['CtfUrl'] = $this->get_ctf_all_url($row['CtfServerID'], $row['CtfServerPort'], $row['CtfUrl']);
        }
        unset($row);
        $this->load->model('User_model');
        $output_data['author_list'] = $this->User_model->get_all_ctf_author([]);
        $output_data['search'] = $search;
        $output_data['type'] = $type;
        $output_data['author'] = $author;
        $output_data['time'] = isset($where['CreateTime']) ? $where['CreateTime'] : '';
        //分页
        $output_data['total_rows'] = $this->Ctf_model->ctf_list_count($where);//获取总记录数

        $output_data['page_url'] = site_url('Train/ctflist') . $pageurl . '/';
        $output_data['page_count'] = ceil($output_data['total_rows'] / 10);
        $output_data['page_pre'] = $page;

        //类型
        $this->load->library('Config_items');
        $output_data['ctf_type'] = Config_items::$ctf_type;

        $this->load->view('teacher/train_ctflist', $output_data);
    }

    /***
     * 获取ctfurl
     * @param $ctfServerId
     * @param $ctfServerPort
     * @param $CtfUrl
     * @return string
     */
    private function get_ctf_all_url($ctfServerId, $ctfServerPort, $CtfUrl)
    {
        static $res;
        if ($res == NULL) {
            $res = $this->Ctf_model->get_ctf_all_url();
        }
        if (isset($res[$ctfServerId][$ctfServerPort])) {
            return $res[$ctfServerId][$ctfServerPort] . $CtfUrl;
        } else {
            return $CtfUrl;
        }
    }

    /***
     * 场景模板管理
     */
    public function scenelist()
    {
        $output_data = array();
        //从url里获取参数
        $parameter = $this->uri->uri_to_assoc(3);
        $uri_segment = (count($parameter) * 2) + 1;
        $per_page = $this->uri->segment($uri_segment) === NULL ? 1 : $this->uri->segment($uri_segment);
        $search = array_key_exists('search', $parameter) ? urldecode($parameter['search']) : '';

        $type = array_key_exists('type', $parameter) ? intval($parameter['type']) : '';
        $author = array_key_exists('author', $parameter) ? intval($parameter['author']) : '';

        $search = $this->security->xss_clean($search);

        $page = max(intval($per_page), 1);
        $perpage = 10;//每页记录数

        $where = array(
            'order' => 'create_time',
            'sort' => 'desc'
        );
        $pageurl = '';//页面url拼接
        //CTF类型
        if (!empty($type)) {
            $where['zone_count'] = $type;
            $pageurl .= '/type/' . $type;
        }
        //author
        if (!empty($author)) {
            $where['author'] = $author;
            $pageurl .= '/author/' . $author;
        }
        if (!empty($search)) {
            $where['scene_name'] = $search;//搜索字符串要转码
            $where['like'] = 'scene_name';//搜索字符串要转码
            $pageurl .= '/search/' . $search;
        }
        $where["page"] = $page;
        $where["size"] = max($perpage, 10);

        $result = $this->Scene_model->scene_list($where);
        $output_data['scene_list'] = isset($result['SceneTemplate']) ? $result['SceneTemplate'] : [];
        $this->load->model('User_model');
        $output_data['author_list'] = $this->User_model->get_all_scene_author([]);
        $output_data['search'] = $search;
        $output_data['type'] = $type;
        $output_data['author'] = $author;
        //分页
        $output_data['total_rows'] = isset($result['total']) ? $result['total'] : 0;//获取总记录数

        $output_data['page_url'] = site_url('Train/scenelist') . $pageurl . '/';
        $output_data['page_count'] = ceil($output_data['total_rows'] / 10);
        $output_data['page_pre'] = $page;

        //区域个数
        $this->load->library('Config_items');
        $output_data['zone_type'] = Config_items::$zone_type;
        $this->load->view('teacher/train_scenelist', $output_data);
    }

    /***
     * 获取场景模板信息
     */
    public function sceneinfo()
    {
        $tmp = array('code' => '0000', 'msg' => 'success!', 'data' => []);
        do {
            $id = $this->input->post('code', TRUE);
            if (empty($id)) {
                $tmp = array('code' => '0555', 'msg' => '参数错误!', 'data' => []);
                break;
            }
            $result = $this->Scene_model->get_sceneinfo($id);
            if (empty($result)) {
                $tmp = array('code' => '0556', 'msg' => '找不到模板信息!', 'data' => []);
                break;
            }
            $tmp['data'] = $result;

        } while (FALSE);

        $this->interface_output->output_fomcat('js_Ajax', $tmp);

    }

    /***
     * 删除场景模板
     */
    public function del_scene_tpl()
    {
        $id = $this->input->post('code', TRUE);
        $tmp = array('code' => '0000', 'msg' => 'success!', 'data' => []);
        do {
            if (empty($id)) {
                $tmp = array('code' => '0557', 'msg' => '没有此场景模板!', 'data' => []);
                break;
            }

            $this->load->model('Section_model');
            //是否下发过此场景的学习
            if ($this->Section_model->check_study_by_scene($id)) {
                $tmp = array('code' => '0558', 'msg' => '该场景已下发学习任务,不能删除!', 'data' => []);
                break;
            }
            //是否下发过此场景的考试
            if ($this->Section_model->check_exam_by_scene($id)) {
                $tmp = array('code' => '0559', 'msg' => '该场景已下发考试任务,不能删除!', 'data' => []);
                break;
            }
            $res = $this->Scene_model->del_scene_tpl($id);
            if (!$res) {
                $tmp = array('code' => '0560', 'msg' => '删除失败!', 'data' => []);
                break;
            }

        } while (FALSE);

        $this->interface_output->output_fomcat('js_Ajax', $tmp);
    }

    /***
     * 场景模板制作
     */
    public function scenecreate()
    {
        $output_data = array();
        //版本
        $this->load->model('System_model');
        $sysinfo = $this->System_model->get_system_info();
        $output_data["versions"] = substr($sysinfo['LicenseKey'], 17, 1);
        //场景os类型
        $output_data["os_type"] = $this->Scene_model->get_os_type();
        $this->load->view('teacher/train_scenecreate', $output_data);

    }


    /***
     * 创建场景模板
     */
    public function add_scene()
    {
        $data = $this->input->post(NULL, TRUE);

        $tmp = array('code' => '0000', 'msg' => 'success!', 'data' => []);
        $this->load->library('Data_validate');//验证类引入
        $this->load->helper('util');
        do {
            //数据检查
            if (empty($data['name'])) {
                $tmp = array('code' => '0309', 'msg' => '场景名称不能为空!', 'data' => []);
                break;
            }
            if (empty($data['desc'])) {
                $tmp = array('code' => '0309', 'msg' => '场景描述不能为空!', 'data' => []);
                break;
            }
            if (empty($data['code'])) {
                $tmp = array('code' => '0309', 'msg' => '没有选择虚拟机！', 'data' => []);
                break;
            }
            $scenes = json_decode($data['code'], true);
            $hostArr = explode(',', trim($data['host_id'], ','));
            $host_id = $hostArr[0];
            foreach ($hostArr as $key => $item) {
                if ($item != $host_id) {
                    $tmp = array('code' => '0393', 'msg' => '虚拟机模板在不同的服务器上，不能组建场景', 'data' => []);
                    break;
                }
            }
            //是否重复
            $result = $this->Scene_model->check_scene_name($data['name']);
            if (!empty($result)) {
                $tmp = array('code' => '0315', 'msg' => '模板名已经被占用!', 'data' => []);
                break;
            }
            $template = '{"scene":{"id":"0001","name":"","desc":"","uuid":"","rootnet":{"zone":{"count":3,"items":[{"id":"001","name":"LAN1","eth":{"count":1,"items":[{"ip":"172.16.12.1","netmask":24,"gateway":"172.16.12.1","dns":"8.8.8.8"}]},"vm":{"count":2,"items":[{"templateuuid":"9FC5B0AD-3136-42E0-99FB-37905F2FEDAE","eth":{"count":2,"items":[{"ip":"172.16.12.2","netmask":24,"gateway":"172.16.12.1"}]}},{"templateuuid":"9FC5B0AD-3136-42E0-99FB-37905F2FEDAE","eth":{"count":1,"items":[{"ip":"172.16.12.3","netmask":24,"gateway":"172.16.12.1"}]}}]}}],"connrel":{"count":2,"items":[{"srcid":"001","srcname":"LAN1","target":[{"id":"002","name":"LAN2"},{"id":"003","name":"LAN3"}]}]}}}},"opervm":{"construct":0,"templateuuid":"","eth":{"ip":"172.16.11.2","netmask":24,"gateway":"172.16.11.1","dns":"8.8.8.8"},"portmap":{"count":2,"items":[{"localport":3389,"remoteport":2003},{"localport":3390,"remoteport":2004}]}},"resused":{"memory":512,"memoryunit":"M","disk":5,"diskunit":"G"}}';
            $template_data = json_decode($template, true);

            $num = count($scenes);
            /*if ($num < 2) {
                $tmp = array('code' => '0315', 'msg' => '目标机没有选择!', 'data' => []);
                break;
            }*/
            //参数初始化
            $vmarr = array();
            $cuarr = array();
            $disknum = 0;
            $memorynum = 0;
            $arrNum = range(0, 254);
            $errorflage = FALSE;

            foreach ($scenes as $k => $v) {
                if ($k != 0) {
                    $vnum = count($v['items']);
                    $vmarr[$k]['id'] = '00' . $k;
                    $vmarr[$k]['name'] = 'LAN' . $k;
                    $vmarr[$k]['eth']['count'] = 1;
                    $vmarr[$k]['eth']['items'] = array(array(
                        'ip' => "172.16." . ($k + 11) . ".1",
                        'netmask' => 24,
                        'gateway' => "172.16." . ($k + 11) . ".1",
                        'dns' => "8.8.8.8"
                    ));
                    $vmarr[$k]['vm']['count'] = $vnum;
                    $lanIP = array();
                    foreach ($v['items'] as $key => $value) {
                        if ($value['docker_cmd'] != '') {
                            $Ipnum = explode('.', $value['docker_cmd'])[3];
                            if (in_array($Ipnum, $arrNum)) {
                                unset($arrNum[$Ipnum - 1]);
                            }
                            $lanIP[] = $value['docker_cmd'];
                        } else {
                            $randNum = array_rand($arrNum, 1);
                            $scenes[$k]['items'][$key]['docker_cmd'] = "172.16." . ($k + 11) . "." . $randNum;
                            unset($arrNum[$randNum - 1]);
                        }
                        $vmarr[$k]['vm']['items'][$key] = array(
                            'templateuuid' => $value['uuid'],
                            'eth' => array('count' => 1, 'items' => array(array('ip' => $scenes[$k]['items'][$key]['docker_cmd'], 'netmask' => 24, 'gateway' => "172.16." . ($k + 11) . ".1")))
                        );
                        $disknum += $value['disk_size'];
                        $memorynum += $value['memory_size'];
                    }

                    $lanIP_c = array_unique($lanIP);
                    //$errorCode = 0;
                    foreach ($lanIP_c as $val) {
                        if (explode('.', $val)[2] != ($k + 11)) {
                            //$errorCode = 1;
                            $tmp = array('code' => '0600', 'msg' => "LAN" . $k . "区存在不属于该区的ip", 'data' => []);
                            $errorflage = TRUE;
                        } else {
                            $n = 0;
                            foreach ($v['items'] as $t) {
                                if ($val == $t['docker_cmd'])
                                    $n++;
                            }
                            if ($n > 1) {
                                //$errorCode = 2;
                                $tmp = array('code' => '0601', 'msg' => "LAN" . $k . "区存在相同的ip", 'data' => []);
                                $errorflage = TRUE;
                            }
                        }
                    }
                } else {
                    //操作区

                    //$vnum = count($v['items']);
                    $vmarr[$k]['id'] = '000';
                    $vmarr[$k]['name'] = 'OPER';
                    $vmarr[$k]['eth']['count'] = 1;
                    $vmarr[$k]['eth']['items'] = array(array(
                        'ip' => "172.16." . ($k + 11) . ".1",
                        'netmask' => 24,
                        'gateway' => "172.16." . ($k + 11) . ".1",
                        'dns' => "8.8.8.8"
                    ));

                    foreach ($v['items'] as $key => $value) {
                        $disknum += $value['disk_size'];
                        $memorynum += $value['memory_size'];
                    }
                    if ($scenes[$k]['items'][0]['docker_cmd'] != '') {
                        if (explode('.', $scenes[$k]['items'][0]['docker_cmd'])[2] != ($k + 11)) {
                            $tmp = array('code' => '0393', 'msg' => '操作区存在不属于该区的ip', 'data' => []);
                            $errorflage = TRUE;
                        }
                    }
                }
                if ($errorflage) break;//有错误退出
                //判断连通性
                for ($i = 0; $i < $num; $i++) {
                    $cnum = pow(2, $i);
                    if ($k != $i) {
                        if (($cnum & $v['links']) != true) {
                            $qu = 'LAN' . $k;
                            $qnum = '00' . $k;
                            if ($k == 0) {
                                $qu = 'OPER';
                                $qnum = '000';
                            }
                            $cuarr[$k]['srcid'] = $qnum;
                            $cuarr[$k]['srcname'] = $qu;
                            $cnum = 'LAN' . $i;
                            $cu = '00' . $i;
                            if ($i == 0) {
                                $cu = '000';
                                $cnum = 'OPER';
                            }
                            $cuarr[$k]['target'][$i] = array('id' => $cu, 'name' => $cnum);
                            $cuarr[$k]['target'] = array_values($cuarr[$k]['target']);
                        }
                    }
                }
            }
            if ($errorflage) break;//有错误退出

            $pcuarr = array_values($cuarr);

            $template_data['scene']['rootnet']['zone']['items'] = $vmarr;
            $template_data['scene']['rootnet']['zone']['connrel'] = array('count' => count($pcuarr), 'items' => $pcuarr);

            $template_data['scene']['id'] = '0001';
            $template_data['scene']['name'] = $data['name'];
            $template_data['scene']['desc'] = $data['desc'];
            $template_data['scene']['uuid'] = get_uuid();
            $template_data['scene']['author'] = $this->session->userdata('UserID');
            $template_data['scene']['rootnet']['zone']['count'] = $num;

            $template_data['scene']['author'] = $this->session->userdata('UserID');
            $template_data['opervm']['templateuuid'] = $scenes[0]['items'][0]['uuid'];
            //$disknum += $scenes[0]['items'][0]['disk_size'];
            //$memorynum += $scenes[0]['items'][0]['memory_size'];
            $template_data['resused']['disk'] = $disknum;
            $template_data['resused']['memory'] = $memorynum;
            $output_data = array(
                "json" => json_encode($template_data),
                "host_id" => $host_id,
                "author" => $this->session->userdata('UserID')
            );
            $result = $this->Scene_model->add_scene($output_data);
            if ($result['code'] != '0000') {
                $tmp['code'] = $result['code'];
                $tmp['msg'] = $result['msg'];
                $tmp['data'] = [];
                break;
            }

        } while (FALSE);

        $this->interface_output->output_fomcat('js_Ajax', $tmp);

    }

    /***
     * 虚拟机模板管理
     */
    public function vmlist()
    {
        $output_data = array();
        //从url里获取参数
        $parameter = $this->uri->uri_to_assoc(3);
        $uri_segment = (count($parameter) * 2) + 1;
        $per_page = $this->uri->segment($uri_segment) === NULL ? 1 : $this->uri->segment($uri_segment);
        $search = array_key_exists('search', $parameter) ? urldecode($parameter['search']) : '';

        $cpu = array_key_exists('cpu', $parameter) ? intval($parameter['cpu']) : '';
        $memory = array_key_exists('memory', $parameter) ? intval($parameter['memory']) : '';
        $os = array_key_exists('os', $parameter) ? intval($parameter['os']) : '';

        $search = $this->security->xss_clean($search);

        $page = max(intval($per_page), 1);
        $perpage = 10;//每页记录数

        $where = array(
            'order' => 'create_time',
            'sort' => 'desc'
        );
        $pageurl = '';//页面url拼接
        //CPU类型
        if (!empty($cpu)) {
            $where['cpu'] = $cpu;
            $pageurl .= '/cpu/' . $cpu;
        }
        //内存
        if (!empty($memory)) {
            $where['memory_size'] = $memory;
            $pageurl .= '/memory/' . $memory;
        }
        //操作系统
        if (!empty($os)) {
            $where['os_type_id'] = $os;
            $pageurl .= '/os/' . $os;
        }
        if (!empty($search)) {
            $where['vm_display_name'] = $search;//搜索字符串要转码
            $where['like'] = 'vm_display_name';
            $pageurl .= '/search/' . $search;
        }
        $where["page"] = $page;
        $where["size"] = max($perpage, 10);

        $result = $this->Scene_model->get_vm_list($where);
        $output_data['vm_list'] = isset($result['VmTemplate']) ? $result['VmTemplate'] : [];

        $output_data['search'] = $search;
        $output_data['cpu'] = $cpu;
        $output_data['memory'] = $memory;
        $output_data['os'] = $os;
        $output_data['os_type'] = $this->Scene_model->get_os_type();//操作系统类型
        //分页
        $output_data['total_rows'] = isset($result['total']) ? $result['total'] : 0;//获取总记录数

        $output_data['page_url'] = site_url('Train/vmlist') . $pageurl . '/';
        $output_data['page_count'] = ceil($output_data['total_rows'] / 10);
        $output_data['page_pre'] = $page;

        //cpu,memory items
        $this->load->library('Config_items');
        $output_data['cpu_type'] = Config_items::$cpu_type;
        $output_data['memory_type'] = Config_items::$memory_type;

        $this->load->view('teacher/train_vmlist', $output_data);
    }

    /***
     * 新增虚拟机模板
     */
    public function add_vm()
    {
        $this->title = '实训内容管理-新增虚拟机模板';
        $this->nav['left_nav_id'] = 25;
        //cpu,memory items
        $this->load->library('Config_items');
        $output_data['cpu_type'] = Config_items::$cpu_type;
        $output_data['memory_type'] = Config_items::$memory_type;
        $output_data['os_type'] = $this->Scene_model->get_os_type();//操作系统类型
        $output_data['upload_data'] = $this->get_target_dir();

        $this->load->view('teacher/train_add_vm', $output_data);

    }

    /***
     * 获取上传目录
     * @return array
     */
    private function get_target_dir()
    {
        $output_data = [];
        $target_dir = '';
        $node_id = '';
        //如果不存在就获取
        if ($this->session->tempdata('target_dir') === NULL) {
            $result = $this->Scene_model->get_mount_path();
            if (!empty($result)) {
                foreach ($result as $row) {
                    if ($row['best_host']) {
                        $target_dir = $row['mount_web_server_path'] . '/';
                        $node_id = $row["id"];
                        break;
                    }
                }
                //此地址应该缓存一下，太慢了5分钟自动过期
                $this->session->set_tempdata('target_dir', $target_dir, 300);
                $this->session->set_tempdata('node_id', $node_id, 300);
            }
        } else {
            $target_dir = $this->session->tempdata('target_dir');
            $node_id = $this->session->tempdata('node_id');
        }
        $output_data['target_dir'] = $target_dir;
        $output_data['node_id'] = $node_id;
        return $output_data;

    }

    /***
     * 获取虚拟机模板信息
     */
    public function vminfo()
    {
        $tmp = array('code' => '0000', 'msg' => 'success!', 'data' => []);
        do {
            $code = $this->input->post('code', TRUE);
            $host_id = $this->input->post('host_id', TRUE);
            if (empty($code) OR empty($host_id)) {
                $tmp = array('code' => '0316', 'msg' => '参数错误!', 'data' => []);
                break;
            }
            $result = $this->Scene_model->get_vm_info($code, $host_id);
            if (empty($result)) {
                $tmp = array('code' => '0316', 'msg' => '找不到模板信息!', 'data' => []);
                break;
            }
            $tmp['data'] = $result;

        } while (FALSE);

        $this->interface_output->output_fomcat('js_Ajax', $tmp);

    }

    /***
     * 删除虚拟机模板
     */
    public function del_vm()
    {
        $tmp = array('code' => '0000', 'msg' => 'success!', 'data' => []);
        do {
            $code = $this->input->post('code', TRUE);
            $host_id = $this->input->post('host_id', TRUE);
            if (empty($code) OR empty($host_id)) {
                $tmp = array('code' => '0316', 'msg' => '参数错误!', 'data' => []);
                break;
            }
            $result = $this->Scene_model->del_vm($code, $host_id);
            if ($result['code'] != '0000') {
                $tmp['code'] = $result['code'];
                $tmp['msg'] = $result['msg'];
                $tmp['data'] = array();
                break;
            }

        } while (FALSE);

        $this->interface_output->output_fomcat('js_Ajax', $tmp);

    }

    /***
     * 获取虚拟机模板列表
     */
    public function get_vm_list()
    {
        $search = $this->input->post('keyword');
        $percount = intval($this->input->post('percount'));
        $curpage = intval($this->input->post('page'));
        $tpl_type = intval($this->input->post('cpu'));//模板类型操作机目标机
        $osType = $this->input->post('osType', TRUE);
        //安全过滤
        $search = $this->security->xss_clean($search);
        $page = max(intval($curpage), 1);

        $where = array();
        if (!empty($tpl_type)) {
            $where['vm_tpl_type'] = $tpl_type;
        }
        if (!empty($osType)) {
            $where['os_type_id'] = $osType;
        }
        if (!empty($search)) {
            $where['order'] = 'create_time';
            $where['sort'] = 'desc';
            $where['vm_display_name'] = $search;//vm_tpl_name
            $where['like'] = 'vm_display_name';
        }
        $where['page'] = $curpage;
        $where['size'] = min($percount, 5);

        $result = $this->Scene_model->get_vm_list($where);
        $output_data['data'] = isset($result['VmTemplate']) ? $result['VmTemplate'] : [];
        $output_data['count'] = isset($result['total']) ? $result['total'] : 0;//获取总记录数
        $output_data['pagecount'] = ceil($output_data['count'] / $where['size']);
        $output_data['currentpage'] = $page;
        echo json_encode($output_data);
    }

    /***
     * 添加CTF模板
     */
    public function addctf()
    {
        if ($data = $this->input->post(NULL, TRUE)) {
            $tmp = array('code' => '0000', 'msg' => '新增成功！', 'data' => []);
            $this->load->library('Data_validate');//验证类引入
            do {
                //数据检查
                if (empty($data['ctfname'])) {
                    $tmp = array('code' => '0309', 'msg' => '模板名称不能为空!', 'data' => []);
                    break;
                }
                if (empty($data['ctfcontent'])) {
                    $tmp = array('code' => '03091', 'msg' => '场景内容不能为空!', 'data' => []);
                    break;
                }
                if (empty($data['ctfresources'])) {
                    $tmp = array('code' => '03092', 'msg' => '资源不能为空!', 'data' => []);
                    break;
                }
                $res = $this->Ctf_model->check_ctf(array('CtfName' => $data['ctfname']));
                if (!empty($res)) {
                    $tmp = array('code' => '03162', 'msg' => '模板名称已存在!', 'data' => []);
                    break;
                }
                $input_data = array(
                    'ctfName' => $data['ctfname'],
                    'ctfContent' => $data['ctfcontent'],
                    'ctfType' => $data['ctftype'],
                    'ctfDiff' => $data['ctfdiff'],
                    'ctfResources' => $data['ctfresources'],
                    'AuthorID' => $this->session->userdata('UserID'),
                    'ctfCreateTime' => time(),
                );
                $result = $this->Ctf_model->add_ctf($input_data);
                if (!$result) {
                    $tmp = array('code' => '0316', 'msg' => '模板创建失败!', 'data' => []);
                    break;
                }

            } while (FALSE);

            $this->interface_output->output_fomcat('js_Ajax', $tmp);
        }
    }

    /***
     * 获取CTF模板信息
     */
    public function get_ctf_info()
    {
        $tmp = array('code' => '0000', 'msg' => 'success!', 'data' => []);
        do {
            $id = intval($this->input->post('code', TRUE));
            if ($id == 0) {
                $tmp = array('code' => '0316', 'msg' => '参数错误!', 'data' => []);
                break;
            }
            $result = $this->Ctf_model->get_ctf_info($id);
            if (empty($result)) {
                $tmp = array('code' => '0316', 'msg' => '找不到模板信息!', 'data' => []);
                break;
            }
            $tmp['data'] = $result;

        } while (FALSE);

        $this->interface_output->output_fomcat('js_Ajax', $tmp);
    }

    /***
     * 编辑CTF模板
     */
    public function edit_ctf()
    {
        $tmp = array('code' => '0000', 'msg' => 'success!', 'data' => []);
        do {
            $data = $this->input->post(NULL, TRUE);
            $this->load->library('Data_validate');//验证类引入
            //数据检查
            if (intval($data['CtfID']) == 0) {
                $tmp = array('code' => '0320', 'msg' => '非法操作!', 'data' => []);
                break;
            }
            if (empty($data['ctfname'])) {
                $tmp = array('code' => '0309', 'msg' => '模板名称不能为空!', 'data' => []);
                break;
            }
            if (empty($data['ctfcontent'])) {
                $tmp = array('code' => '03091', 'msg' => '场景内容不能为空!', 'data' => []);
                break;
            }
            if (empty($data['ctfresources'])) {
                $tmp = array('code' => '03092', 'msg' => '资源不能为空!', 'data' => []);
                break;
            }
            $res = $this->Ctf_model->check_ctf(array('CtfName' => $data['ctfname'], 'CtfID !=' => $data['CtfID']));
            if (!empty($res)) {
                $tmp = array('code' => '03162', 'msg' => '模板名称已存在!', 'data' => []);
                break;
            }
            $info = array(
                'ctfName' => $data['ctfname'],
                'ctfContent' => $data['ctfcontent'],
                'ctfType' => $data['ctftype'],
                'ctfDiff' => $data['ctfdiff'],
                'ctfResources' => $data['ctfresources']
            );

            $result = $this->Ctf_model->update_ctf($info, array('CtfID' => intval($data['CtfID'])));
            if ($result['code'] != '0000') {
                $tmp = array('code' => $result['code'], 'msg' => $result['msg'], 'data' => []);
                break;
            }

        } while (FALSE);

        $this->interface_output->output_fomcat('js_Ajax', $tmp);
    }

    /***
     * 删除CTF模板
     */
    public function del_ctf()
    {
        $tmp = array('code' => '0000', 'msg' => 'success!', 'data' => []);
        do {
            $usercode = $this->input->post('codes', TRUE);
            if (empty($usercode)) {
                $tmp = array('code' => '0316', 'msg' => '参数错误!', 'data' => []);
                break;
            }
            $user_data = json_decode($usercode);
            //是否单个删除
            if (is_int($user_data)) $user_data = array($user_data);
            $result = $this->Ctf_model->del_ctf($user_data);
            if ($result['code'] != '0000') {
                $tmp['code'] = $result['code'];
                $tmp['msg'] = $result['msg'];
                $tmp['data'] = array();
                break;
            }

        } while (FALSE);

        $this->interface_output->output_fomcat('js_Ajax', $tmp);
    }

    /***
     * 文件上传
     */
    public function upload_ctf()
    {
        $key = $this->input->post("key");
        $key2 = $this->input->post("key2");
        $filename = $this->input->post("fileName");//上传文件
        //获取扩展名
        $fileType = strtolower(strrchr($filename, '.'));
        $filename = $key2 . $key . $fileType;
        $upload_path = getcwd() . '/resources/files/ctf/';
        $config['file_name'] = $filename;
        $config['upload_path'] = $upload_path;
        $config['allowed_types'] = 'zip|gzip|rar|qcow2|tar|doc|docx|xls|xlsx|jpg|jpeg|png|bmp';
        $config['max_size'] = 0;

        $this->load->library('upload', $config);
        if (!$this->upload->huploadify($filename)) {
            $tmp = array('success' => FALSE, 'fileurl' => NULL, 'filename' => NULL, 'msg' => '上传失败,请检查目录权限！');
        } else {
            $tmp = array('success' => TRUE, 'fileurl' => '/resources/files/ctf/' . $filename, 'filename' => $filename, 'msg' => '上传成功！');
        }
        $this->interface_output->output_fomcat('js_Upload', $tmp);
    }

    /***
     * 上传虚拟机模板
     */
    public function upload_vm()
    {
        $key = $this->input->post("key1");
        $key2 = $this->input->post("key2");
        $filename = $this->input->post("fileName");//上传文件
        $upload_path = $this->input->post("targetDir");//上传文件
        //获取扩展名
        $fileType = strtolower(strrchr($filename, '.'));
        $filename = $key2 . $key . $fileType;
        //$upload_path = getcwd() . $upload_path;
        $config['file_name'] = $filename;
        $config['upload_path'] = $upload_path;
        $config['allowed_types'] = 'qcow2';
        $config['max_size'] = 0;

        $this->load->library('upload', $config);
        if (!$this->upload->huploadify($filename)) {
            $tmp = array('success' => FALSE, 'fileurl' => NULL, 'filename' => NULL, 'msg' => '上传失败,请检查目录权限！');
        } else {
            $tmp = array('success' => TRUE, 'fileurl' => $upload_path . $filename, 'filename' => $filename, 'msg' => '上传成功！');
        }
        $this->interface_output->output_fomcat('js_Upload', $tmp);
    }

    /***
     * 新增虚拟机模板
     */
    public function create_vm()
    {
        $data = $this->input->post(NULL, TRUE);
        $tmp = array('code' => '0000', 'msg' => '创建成功!', 'data' => []);
        $this->load->library('Data_validate');//验证类引入
        $this->load->helper('util');
        do {
            //数据检查
            if (empty($data['NodeId'])) {
                $tmp = array('code' => '0309', 'msg' => '参数错误！', 'data' => []);
                break;
            }
            if (empty($data['VmTemplateName']) OR empty($data['VmTemplateFileName'])) {
                $tmp = array('code' => '0309', 'msg' => '请先上传一个qcow2文件!', 'data' => []);
                break;
            }
            if (empty($data['VmTemplateShowName'])) {
                $tmp = array('code' => '0309', 'msg' => '模板名称不能为空!', 'data' => []);
                break;
            }
            if (empty($data['VmTemplateCpu'])) {
                $tmp = array('code' => '0309', 'msg' => '没有选择CPU类型！', 'data' => []);
                break;
            }
            if (empty($data['VmTemplateOs'])) {
                $tmp = array('code' => '0309', 'msg' => '没有选择操作系统类型！', 'data' => []);
                break;
            }
            if (empty($data['VmTemplateLeak'])) {
                $tmp = array('code' => '0309', 'msg' => '漏洞信息不能为空！', 'data' => []);
                break;
            }
            //ip判断
            if (!empty($data['VmTemplateDocker_cmd']) && !$this->data_validate->is_intranet_ip($data['VmTemplateDocker_cmd'])) {
                $tmp = array('code' => '0309', 'msg' => 'IP填写不正确,例172.16.1[0-9].[0-254]', 'data' => []);
                break;
            }
            $template_data['v_vmtemplate'] = array();
            $template_data['v_vmtemplate']['vm_tpl_uuid'] = get_uuid();
            $template_data["v_vmtemplate"]["vm_tpl_name"] = $data['VmTemplateName'];
            $template_data["v_vmtemplate"]["vm_display_name"] = $data['VmTemplateShowName'];
            $template_data["v_vmtemplate"]["cpu"] = $data['VmTemplateCpu'];
            $template_data["v_vmtemplate"]["memory_size"] = $data['VmTemplateMemory'];
            $template_data["v_vmtemplate"]["vm_tpl_type"] = $data['VmTemplateType'];
            $template_data["v_vmtemplate"]["disk_size"] = "100";
            $template_data["v_vmtemplate"]["os_type_id"] = $data['VmTemplateOs'];
            $template_data["v_vmtemplate"]["user_name"] = $data['VmTemplateUserName'];
            $template_data["v_vmtemplate"]["user_pwd"] = $data['VmTemplatePassword'];
            $template_data["v_vmtemplate"]["docker_cmd"] = $data['VmTemplateDocker_cmd'];

            $template_data["v_vmtemplate"]["compute_offering"] = "";
            $template_data["v_vmtemplate"]["disk_offering"] = "";
            $template_data["v_vmtemplate"]["vm_tpl_snp_name"] = $data['VmTemplateSnapName'];
            $template_data["v_vmtemplate"]["clone_for_test"] = "";
            $template_data["v_vmtemplate"]["vm_type"] = "";
            $template_data["v_vmtemplate"]["vns_pwd"] = "";

            $template_data["v_vmtemplate"]["data_store_path"] = $data['VmTemplateFileName'];
            $template_data["v_vmtemplate"]["author"] = $this->session->userdata('UserID');
            $template_data["v_vmtemplate"]["description"] = $data['VmTemplateLeak'];
            $template_data["v_vmtemplate"]["func_type"] = '';

            $node_id = $data['NodeId'];
            $output_data = array(
                "json" => json_encode($template_data),
            );
            $result = $this->Scene_model->create_vm($node_id, $output_data);
            if ($result['code'] != '0000') {
                $tmp['code'] = $result['code'];
                $tmp['msg'] = $result['msg'];
                $tmp['data'] = [];
                break;
            }

        } while (FALSE);

        $this->interface_output->output_fomcat('js_Ajax', $tmp);


    }

    /***
     * 编辑虚拟机模板
     */
    public function edit_vm()
    {
        $this->title = '实训内容管理-编辑虚拟机模板';
        $this->nav['left_nav_id'] = 25;
        if ($data = $this->input->post(NULL, TRUE)) {
            $tmp = array('code' => '0000', 'msg' => '修改成功!', 'data' => []);
            $this->load->library('Data_validate');//验证类引入
            $this->load->helper('util');
            do {
                //数据检查
                if (empty($data['host_id']) OR empty($data['vm_tpl_uuid'])) {
                    $tmp = array('code' => '0309', 'msg' => '参数错误！', 'data' => []);
                    break;
                }
                if (empty($data['VmTemplateName']) OR empty($data['VmTemplateFileName'])) {
                    $tmp = array('code' => '0309', 'msg' => '请先上传一个qcow2文件!', 'data' => []);
                    break;
                }
                if (empty($data['VmTemplateName'])) {
                    $tmp = array('code' => '0309', 'msg' => '模板名称不能为空!', 'data' => []);
                    break;
                }
                if (empty($data['VmTemplateCpu'])) {
                    $tmp = array('code' => '0309', 'msg' => '没有选择CPU类型！', 'data' => []);
                    break;
                }
                if (empty($data['VmTemplateOs'])) {
                    $tmp = array('code' => '0309', 'msg' => '没有选择操作系统类型！', 'data' => []);
                    break;
                }
                if (empty($data['VmTemplateLeak'])) {
                    $tmp = array('code' => '0309', 'msg' => '漏洞信息不能为空！', 'data' => []);
                    break;
                }
                //ip判断
                if (!empty($data['VmTemplateDocker_cmd']) && !$this->data_validate->is_intranet_ip($data['VmTemplateDocker_cmd'])) {
                    $tmp = array('code' => '0309', 'msg' => 'IP填写不正确,例172.16.1[0-9].[0-254]', 'data' => []);
                    break;
                }
                $template_data['v_vmtemplate'] = array();
                $template_data['v_vmtemplate']['vm_tpl_uuid'] = $data['vm_tpl_uuid'];
                $template_data["v_vmtemplate"]["vm_display_name"] = $data['VmTemplateName'];
                $template_data["v_vmtemplate"]["cpu"] = $data['VmTemplateCpu'];
                $template_data["v_vmtemplate"]["memory_size"] = $data['VmTemplateMemory'];
                $template_data["v_vmtemplate"]["disk_size"] = $data['VmTemplateDisk'];
                $template_data["v_vmtemplate"]["os_type_id"] = $data['VmTemplateOs'];
                $template_data["v_vmtemplate"]["user_name"] = $data['VmTemplateUserName'];
                $template_data["v_vmtemplate"]["user_pwd"] = $data['VmTemplatePassword'];

                $template_data["v_vmtemplate"]["compute_offering"] = "";
                $template_data["v_vmtemplate"]["disk_offering"] = "";
                $template_data["v_vmtemplate"]["vm_tpl_snp_name"] = $data['VmTemplateSnapName'];
                $template_data["v_vmtemplate"]["clone_for_test"] = "";
                $template_data["v_vmtemplate"]["vm_type"] = "";
                $template_data["v_vmtemplate"]["vns_pwd"] = "";
                $template_data["v_vmtemplate"]["docker_cmd"] = $data['VmTemplateDocker_cmd'];

                $template_data["v_vmtemplate"]["data_store_path"] = $data['VmTemplateFileName'];
                $template_data["v_vmtemplate"]["author"] = $this->session->userdata('UserID');
                $template_data["v_vmtemplate"]["description"] = $data['VmTemplateLeak'];
                $template_data["v_vmtemplate"]["func_type"] = '';

                $node_id = $data['host_id'];
                $output_data = "json=" . json_encode($template_data);
                $result = $this->Scene_model->update_vm($node_id, $output_data);
                if ($result['code'] != '0000') {
                    $tmp['code'] = $result['code'];
                    $tmp['msg'] = $result['msg'];
                    $tmp['data'] = [];
                    break;
                }

            } while (FALSE);

            $this->interface_output->output_fomcat('js_Ajax', $tmp);


        } else {
            $output_data = [];
            $code = $this->input->get('code', TRUE);
            $host_id = $this->input->get('host_id', TRUE);
            if (empty($code) OR empty($host_id)) {
                redirect(site_url('Train/vmlist'));
            }
            $result = $this->Scene_model->get_vm_info($code, $host_id);
            if (empty($result)) {
                redirect(site_url('Train/vmlist'));
            }
            //cpu,memory items
            $this->load->library('Config_items');
            $output_data['cpu_type'] = Config_items::$cpu_type;
            $output_data['memory_type'] = Config_items::$memory_type;
            $output_data['os_type'] = $this->Scene_model->get_os_type();//操作系统类型
            $output_data['vm'] = $result;

            $this->load->view('teacher/train_edit_vm', $output_data);

        }

    }

    /***
     * 启动场景测试
     */
    public function start_scene()
    {
        $this->load->model("Section_model");
        $this->load->model("Issue_model");
        $this->load->library('Interface_output');
        $tmp = array('code' => '0000', 'msg' => 'success!', 'data' => []);
        $sceneuuid = $this->input->post("sceneuuid", TRUE);
        $scenename = $this->security->xss_clean(htmlspecialchars($this->input->post('scenename')));
        $userid = $this->session->userdata('UserID');
        do {
            //首先查找数据库里是否有原来的申请实例
            $result = $this->Issue_model->get_instance(array('SceneTemplateUUID' => $sceneuuid, 'UserID'=>$userid));

            if ($result && $result[0]['SceneInstanceUUID'] != NULL) {
                $tmp['data']['scene_ins_uuid'] = $result[0]['SceneInstanceUUID'];
                $tmp['data']['task_uuid'] = $result[0]['TaskUUID'];
                break;
            }
            $output_data = $this->Section_model->create_scene(array('SceneTemplateUUID' => $sceneuuid));
            if ($output_data['code'] != '0000') {
                $tmp = $output_data;
                break;
            }
            //如果有就更新，没有就插入
            if ($result){
                $this->Issue_model->update_instance(array('SceneTemplateUUID' => $sceneuuid,'UserID'=>$userid),array( 'SceneInstanceUUID' => $output_data['data']['scene_ins_uuid'], 'TaskUUID' => $output_data['data']['task_uuid'],
                    'CreateTime' => time(),'SceneName'=>$scenename));
            }else{
                $data = array('SceneTemplateUUID' => $sceneuuid, 'SceneInstanceUUID' => $output_data['data']['scene_ins_uuid'], 'TaskUUID' => $output_data['data']['task_uuid'],
                    'CreateTime' => time(),'SceneName'=>$scenename,'UserID'=>$userid);
                $this->Issue_model->add_instance($data);
            }
            $tmp['data']['scene_ins_uuid'] = $output_data['data']['scene_ins_uuid'];
            $tmp['data']['task_uuid'] = $output_data['data']['task_uuid'];
        } while (FALSE);
        $this->interface_output->output_fomcat('js_Ajax', $tmp);
    }

    /***
     * 检测场景是否在节点上存在
     */
    public function check_scene_in_node()
    {
        $id = $this->input->post('sceneInsUUID', TRUE);
        $tmp = array('code' => '0000', 'msg' => 'success!', 'data' => []);
        do {
            if (empty($id)) {
                $tmp = array('code' => '0557', 'msg' => '没有此场景下发模板!', 'data' => []);
                break;
            }

            $this->load->model('Issue_model');

            $res = $this->Issue_model->check_scene_in_node($id);
            if ($res['code'] != '0000') {
                $tmp['code'] = $res['code'];
                $tmp['msg'] = $res['msg'];
                $tmp['data'] = [];
                break;
            }

        } while (FALSE);

        $this->interface_output->output_fomcat('js_Ajax', $tmp);


    }

    /***
     * 进入场景
     */
    public function enter_scene()
    {
        $this->load->model("Section_model");
        $this->load->model("System_model");
        $this->load->library('Interface_output');

        $sceneinstanceuuid = $this->input->post("sceneinstanceuuid");
        $output_data = $this->Section_model->enter_scene(array('sceneinstanceuuid' => $sceneinstanceuuid));
        if ($output_data['code'] == '0000') {
            $ip = $this->System_model->get_system_port(array('ctfip' => $output_data['data']['ip']));
            if(count($ip) == 0){
                $output_data['code'] = '0331';
                $output_data['msg'] = 'IP与端口映射错误,请联系管理员进行设置';
                $output_data['data'] = array();

            }else {
                $output_data['data']['port'] = isset($ip[0]['localport']) ? $ip[0]['localport'] : '';
                $output_data['data']['ip'] = $_SERVER["SERVER_ADDR"];
            }
        }

        $this->interface_output->output_fomcat('js_Ajax', $output_data);
    }

    /***
     * 场景页
     */
    public function vm_vnc()
    {
        $data['uuid'] = $this->security->xss_clean(htmlspecialchars($this->input->get('uuid')));
        $data['loguser'] = $this->security->xss_clean(htmlspecialchars($this->input->get('loguser')));
        $data['logpwd'] = $this->security->xss_clean(htmlspecialchars($this->input->get('logpwd')));
        $data['vmuuid'] = $this->security->xss_clean(htmlspecialchars($this->input->get('vmuuid')));
        $data['token'] = $this->security->xss_clean(htmlspecialchars($this->input->get('token')));
        $data['ip'] = $this->security->xss_clean(htmlspecialchars($this->input->get('ip')));
        $data['port'] = $this->security->xss_clean(htmlspecialchars($this->input->get('port')));
        $data['sid'] = $this->security->xss_clean(htmlspecialchars($this->input->get('sid')));
        $data['host_id'] = $this->security->xss_clean(htmlspecialchars($this->input->get('host_id')));
        $scene_end_time = $this->security->xss_clean( htmlspecialchars( $this->input->get('scene_end_time') ) );
        $data['scene_time'] = strtotime($scene_end_time) - time();//场景倒计时

        $data['sectionname'] = $this->security->xss_clean(htmlspecialchars($this->input->get('SectionInsNametitle')));
        $data['uurl'] = 'http://' . $data['ip'] . ':' . $data['port'] . '/vnc_auto.html?token=' . $data['token'];
        $this->load->view("teacher/train_vnc", $data);
    }

    /*
     * 删除场景
     * */
    public function del_scene()
    {
        $this->load->model("Section_model");
        $this->load->library('Interface_output');

        $sceneinstanceuuid = $this->input->post("sceneinstanceuuid");

        $output_data = $this->Section_model->del_scene(array('sceneinstanceuuid' => $sceneinstanceuuid));
        //更新测试用例表
        $this->load->model("Issue_model");
        $data = array('TaskUUID' => null, 'SceneInstanceUUID' => null, 'CreateTime' => time());
        $this->Issue_model->update_instance(array('SceneInstanceUUID' => $sceneinstanceuuid), $data);


        $this->interface_output->output_fomcat('js_Ajax', $output_data);

    }

    /*
     * 检查场景按钮状态
     * */
    public function check_scene_status()
    {
        $this->load->model("Section_model");
        $this->load->model("Issue_model");
        $this->load->library('Interface_output');

        $sectioninsid = $this->input->post("SceneTemplateUUID");
        $output_data['data'] = array();
        $output_data['code'] = '0000';
        $output_data['msg'] = '下发成功';
        $userid = $this->session->userdata('UserID');
        do {
            $section = $this->Issue_model->get_instance(array('SceneTemplateUUID' => $sectioninsid,'UserID'=>$userid));
            if (!$section) {
                $output_data['code'] = 1;
                $output_data['msg'] = '申请实验环境';

                $output_data['data']['sceneInsUUID'] = '';
                $output_data['data']['msg'] = '';
                $output_data['data']['taskUUID'] = '';
                $output_data['data']['taskProcess'] = '0';
                break;
            }
            if ($section[0]['SceneInstanceUUID'] == NULL && $section[0]['TaskUUID'] == NULL) {
                $output_data['code'] = 1;
                $output_data['msg'] = '申请实验环境';

                $output_data['data']['sceneInsUUID'] = $section[0]['SceneInstanceUUID'];
                $output_data['data']['msg'] = '';
                $output_data['data']['taskUUID'] = $section[0]['TaskUUID'];
                $output_data['data']['taskProcess'] = '0';
                break;
            }
            if ($section[0]['SceneInstanceUUID'] != NULL && $section[0]['TaskUUID'] == NULL) {
                $output_data['code'] = 3;
                $output_data['msg'] = '进入场景';

                $output_data['data']['sceneInsUUID'] = $section[0]['SceneInstanceUUID'];
                $output_data['data']['msg'] = '';
                $output_data['data']['taskUUID'] = $section[0]['TaskUUID'];
                $output_data['data']['taskProcess'] = '0';
                break;
            }

            $progress = $this->Section_model->get_scene_progress($section[0]['TaskUUID']);
            $task_status = array('正在排队，请耐心等待！', '已收到请求', '已经开始申请', '申请失败', '正在重试', '申请成功', '已撤销', '已拒绝', '正在申请中', '排队等候中');
            if ($progress['code'] != '0000') {
                $output_data['code'] = 4;
                $output_data['msg'] = '下发失败，重新申请';

                $output_data['data']['sceneInsUUID'] = $section[0]['SceneInstanceUUID'];
                $output_data['data']['msg'] = '';
                $output_data['data']['taskUUID'] = $section[0]['TaskUUID'];
                $output_data['data']['taskProcess'] = '0';
                break;
            }
            $real_status = $progress['data']['task_status'];
            if ($real_status == 6) {//success

                $this->Issue_model->update_instance(array('SceneTemplateUUID' => $sectioninsid,'UserID'=>$userid), array('TaskUUID' => NULL));

                $output_data['code'] = 3;
                $output_data['msg'] = '进入场景';

                $output_data['data']['sceneInsUUID'] = $section[0]['SceneInstanceUUID'];
                $output_data['data']['msg'] = $task_status[$real_status - 1];
                $output_data['data']['taskUUID'] = $section[0]['TaskUUID'];
                $output_data['data']['taskProcess'] = $progress['data']['task_percent'];
                break;

            }
            if ($real_status == 4 || $real_status == 7 || $real_status == 8) { //失败
                $data = array('TaskUUID' => NULL, 'SceneInstanceUUID' => NULL);
                $this->Issue_model->update_instance(array('SceneTemplateUUID' => $sectioninsid,'UserID'=>$userid), $data);

                $output_data['code'] = 4;
                $output_data['msg'] = '下发失败，重新申请';
                $output_data['data']['sceneInsUUID'] = $section[0]['SceneInstanceUUID'];
                $output_data['data']['msg'] = $task_status[$real_status - 1];
                $output_data['data']['taskUUID'] = $section[0]['TaskUUID'];
                $output_data['data']['taskProcess'] = $progress['data']['task_percent'];
                break;

            } else {
                $output_data['code'] = 2;
                $output_data['msg'] = '正在下发';
                $output_data['data']['sceneInsUUID'] = $section[0]['SceneInstanceUUID'];
                $output_data['data']['msg'] = $task_status[$real_status - 1];
                $output_data['data']['taskUUID'] = $section[0]['TaskUUID'];
                $output_data['data']['taskProcess'] = $progress['data']['task_percent'];
                break;
            }

        } while (FALSE);

        $this->interface_output->output_fomcat('js_Ajax', $output_data);

    }

    /***
     * 检测场景是否存在
     */
    public function check_scene()
    {
        $this->load->model("Section_model");
        $this->load->model("Issue_model");
        $this->load->library('Interface_output');

        $sceneinstanceuuid = $this->input->post("sceneinstanceuuid");
        $host_id = $this->input->post("host_id");

        $output_data['data'] = array();
        do {

            if (intval($host_id) <= 0) {
                $output_data['code'] = '0432';
                $output_data['msg'] = '参数错误!';
                break;
            }
            //检查数据库
            $section = $this->Issue_model->get_instance(array('SceneInstanceUUID' => $sceneinstanceuuid, 'TaskUUID' => NULL));
            if (count($section) == 0) {
                $output_data['code'] = '0001';
                $output_data['msg'] = '数据库-场景不存在';
                break;
            }
            //检查场景接口
            $output_data = $this->Section_model->check_scene(array('sceneinstanceuuid' => $sceneinstanceuuid, 'host_id' => $host_id));

            //不存在
            if ($output_data['code'] == '0000' && $output_data['data']['result'] != 1) {
                $output_data['code'] = '0001';
                $data = array('TaskUUID' => null, 'SceneInstanceUUID' => null);
                $this->Issue_model->update_instance(array('SceneInstanceUUID' => $sceneinstanceuuid), $data);
            }
        } while (FALSE);

        $this->interface_output->output_fomcat('js_Ajax', $output_data);
    }

    /***
     * 检测用户下发
     */
    public function check_issue()
    {
        $this->load->model("Issue_model");
        $this->load->library('Interface_output');
        $tmp = array('code' => '0000', 'msg' => 'success!', 'data' => []);

        //$taskuuid = $this->input->post("taskuuid");
        $userid = $this->session->userdata('UserID');
        do{
            //更新测试用例表一小时内的场景所有用户的
            $data = array('TaskUUID' => NULL, 'SceneInstanceUUID' => NULL);
            $this->Issue_model->update_instance(array('CreateTime <=' => time()-3600,'SceneInstanceUUID !=' => NULL), $data);
            //检查用户是否有下发
            $result = $this->Issue_model->get_instance(array('SceneInstanceUUID !=' => NULL,'UserID'=>$userid));
            if ($result) {
                $tmp = array('code' => '0666', 'msg' => 'error!', 'data' =>[] );//此场景下发了已经
                $tmp['data']['template_uuid'] = $result[0]['SceneTemplateUUID'];
                $tmp['data']['scene_ins_uuid'] = $result[0]['SceneInstanceUUID'];
                $tmp['data']['task_uuid'] = $result[0]['TaskUUID'];
                $tmp['data']['scenename'] = $result[0]['SceneName'];
                break;
            }
        }while(FALSE);

        $this->interface_output->output_fomcat('js_Ajax', $tmp);
    }

    /***
     * 计划任务清理测试
     */
    private function del_task($uuid, $taskid)
    {
        $this->load->model("Exam_model");
        $this->load->model("Issue_model");
        $this->load->library('Interface_output');

        $tmp = array('code' => '0000', 'msg' => 'success!', 'data' => []);
        do {
            if (empty($uuid)) {
                $tmp = array('code' => '0557', 'msg' => '没有此场景模板!', 'data' => []);
                break;
            }

            if (empty($taskid)) {
                $tmp = array('code' => '0557', 'msg' => '没有此计划任务!', 'data' => []);
                break;
            }

            $this->load->model('Section_model');
            //是否下发过此场景的学习
            if ($this->Section_model->check_study_by_scene($uuid)) {
                $tmp = array('code' => '0558', 'msg' => '该场景已下发学习任务,不能删除!', 'data' => []);
                break;
            }
            //是否下发过此场景的考试
            if ($this->Section_model->check_exam_by_scene($uuid)) {
                $tmp = array('code' => '0559', 'msg' => '该场景已下发考试任务,不能删除!', 'data' => []);
                break;
            }
            $del = $this->Exam_model->del_task_scene($taskid);
            if ($del['code'] != '0000') {
                $tmp['data'] = array();
                $tmp['code'] = 'error';
                $tmp['msg'] = '删除失败';
                break;
            }

        } while (FALSE);
        return $tmp;
    }

}