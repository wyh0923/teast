<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: whx
 * Date: 2016/9/29
 * Time: 17:03
 */

/***
 * 对原系统库结构进行处理
 * 
 */
class Deal extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
       $this->load->model('Deal_model');
    }

    /***
     * 处理原系统结构数据
     */
    public function index()
    {
        set_time_limit(0);
        $res=$this->Deal_model->updateStructure();
        //$this->Deal_model->insertMenu();
        // if($res){
        //     $res=$this->Deal_model->updateStructures();
        //     if($res){
        //         $this->Deal_model->updateStructurea();
        //     }
        // }
       var_dump($res);die;
    }

     /***
     * 处理原系统结构数据
     */
    public function updateDate()
    {
         $res=$this->Deal_model->updateDate();
       var_dump($res);die;
    }

}