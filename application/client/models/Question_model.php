<?php
/**
 * Created by PhpStorm.
 * User: liuqi
 * Date: 2016/8/30
 * Time: 13:50
 */

class Question_model extends CI_Model
{
    /**
     * 获取题目
     */
    public function get_questions($where)
    {
        //p($where);
        $this->db->select('*');
        $this->db->from('question');

        if (!empty($where['search'])) {
            $this->db->group_start();
            $this->db->like(array('QuestionDesc' => $where['search']));
            $this->db->group_end();
        }

        if (isset($where['limit'])) {
            $this->db->limit($where['limit']['limit'], $where['limit']['offset']);
        }
        if(!empty($where['uname']))
        {
            $this->db->where(array('QuestionAuthor' => $where['uname']));
        }
        if(!empty($where['qtype']))
        {
            $this->db->where(array('QuestionType' => $where['qtype']));
        }
        if (isset($where['sort'])) {
            $this->db->order_by($where['sort']['field'], $where['sort']['order']);
        }else{
            $this->db->order_by('p_question.QuestionID', 'DESC');//默认排序
        }

        return $this->db->get()->result_array();
    }

    /***
     * 获取记录总数
     */
    public function get_count_questions($where)
    {
        $this->db->select('count(1) as count');
        $this->db->from('question');

        if (!empty($where['search'])) {
            $this->db->group_start();
            $this->db->like(array('QuestionDesc' => $where['search']));
            $this->db->group_end();
        }
        if(!empty($where['uname']))
        {
            $this->db->where(array('QuestionAuthor' => $where['uname']));
        }
        if(!empty($where['qtype']))
        {
            $this->db->where(array('QuestionType' => $where['qtype']));
        }

        $result = $this->db->get()->result_array();
        return isset($result[0]['count']) ? $result[0]['count'] : 0;

    }

    /**
     * 是否关联试卷
     */
    public function is_relation($qid)
    {
        $res = $this->db->select('QuestionID')
            ->from('exam_question')->where(array('QuestionID'=>$qid))
            ->get()->result_array();
        
        if(!empty($res))
        {
            $tmp['code'] = '0000';
            $tmp['msg'] = '已关联试卷';
            $tmp['data'] = array();
        } else {
            $tmp['code'] = '0317';
            $tmp['msg'] = '';
            $tmp['data'] = array();
        }
        return $tmp;

    }
    

    /***
     * 删除题目
     */
    public function del_question($qid)
    {
        $this->db->where('QuestionID', $qid);
        $this->db->delete('question');
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

    /**
     * 获取作者
     */
    public function get_teachers($where)
    {
        return $this->db->select('UserID,UserAccount,UserName')
            ->from('user')->where_in('UserRole',$where)
            ->get()->result_array();
    }

    /**
     * 获取场景
     */
    public function get_ctf($where)
    {
        $this->db->select('CtfID,CtfName,CtfContent')
            ->from('ctf');

        if (!empty($where['search'])) {
            $this->db->group_start();
            $this->db->like(array('CtfName' => $where['search']));
            $this->db->group_end();
        }

        if (isset($where['limit'])) {
            $this->db->limit($where['limit']['limit'], $where['limit']['offset']);
        }

        return $this->db->get()->result_array();
    }

    /**
     * 统计ctf
     */
    public function get_ctf_count($where)
    {
        $this->db->select('count(1) as count')
            ->from('ctf');

        if (!empty($where['search'])) {
            $this->db->group_start();
            $this->db->like(array('CtfName' => $where['search']));
            $this->db->group_end();
        }

        $result = $this->db->get()->result_array();
        return isset($result[0]['count']) ? $result[0]['count'] : 0;
    }

    /**
     * 新增题目
     */
    public function add_question($data)
    {
        $res = $this->db->select('QuestionDesc')->from('question')->where(array('QuestionDesc'=>$data['QuestionDesc']))->get()->result_array();

        if(!empty($res))
        {
            $tmp['code'] = '0386';
            $tmp['msg'] = '';
            $tmp['data'] = '';
        } else {
            $this->db->insert('question', $data);

            $num = $this->db->affected_rows();
            if ($num >= 1) {
                $tmp['code'] = '0000';
                $tmp['msg'] = 'success';
                $tmp['data'] = $this->db->insert_id();
            } else {
                $tmp['code'] = '0386';
                $tmp['msg'] = '';
                $tmp['data'] = '';
            }
        }

        return $tmp;
    }

    /**
     * 获取编辑的题目
     */
    public function get_one_question($qid)
    {
        $this->db->select('*')->from('question')->where(array('QuestionID'=>$qid));
        $res = $this->db->get()->result_array();
        return $res[0];
    }

    /**
     * 获取某一场景
     */
    public function get_one_scene($param)
    {
        $this->load->library('Data_exchange', array('api_name' => 'scene_list', 'message' => $param), 'get_sub');
        $sub_node = $this->get_sub->request();

        return $sub_node['RespBody']['Result']['SceneTemplate'];

    }

    /**
     * 获取某一ctf
     */
    public function get_one_ctf($ctfid)
    {
        $this->db->select('*')->from('ctf')->where(array('CtfID'=>$ctfid));
        $res = $this->db->get()->result_array();

        return $res;
    }


    /**
     * 修改题目
     * @param $data
     * @param $qid
     * @return mixed
     */
    public function mod_question($data, $qid)
    {
        if($data['QuestionLinkType'] == 0)
        {
            $data['QuestionScene'] = 1;
        } else {
            $data['QuestionScene'] = 2;
        }
        $this->db->where('QuestionID', $qid)->update('question', $data);
        if ($this->db->affected_rows() > 0) {
            $tmp['code'] = '0000';
            $tmp['msg'] = 'success';
            $tmp['data'] = array();
        } else {
            $tmp['code'] = '0318';
            $tmp['msg'] = '';
            $tmp['data'] = array();

        }
        return $tmp;
    }









}
