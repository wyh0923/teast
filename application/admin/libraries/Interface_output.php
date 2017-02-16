<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: WKF
 * Date: 2016/7/27
 * Time: 9:37
 */
/*
 * 接口输出类
 *
 * 实现对各种接口格式的封装
 */

class Interface_output
{

    /*
     * 输出给JS的接口格式
     *  code '0000' 表示成功 ，'0100' 表示 0100-0199 表示系统级别错误 '0200' 0200-0299 代表用户级别提示失败错误
     *  msg  '消息文字'
     *  data  默认数组输出
    */
    static $js_Ajax = array('code', 'msg', 'data');
    /***
     * @var array
     *  success 'true'|'false'
     *  fileurl 文件完整地址
     *  fname   文件名
     *  showinfo 信息提示
     */
    static $js_Upload = array('success', 'fileurl', 'filename', 'msg');

    /*
     * 统一输出格式出口
     * @param  $template Sting 模版名称
     * @param  $data Array 模版需要的数据格式
     * 实例：$template=$js_Ajax 时 $data 数组必须定义 code ,msg,data 三个标签
     * @return String
     */
    public function output_fomcat($template, $data)
    {

        foreach (self::$$template as $key => $value) {

            $_tmp[$value] = $data[$value];

        }

        echo json_encode($_tmp);

    }


}

/*
$tmp['code']='00000';
$tmp['msg']='这是一个消息';
$tmp['data']=array('title'=>'abcd','return'=>[1,2,3,4,5,6],'page'=>1);

$output=new Output();
$output->output_fomcat('js_Ajax',$tmp);*/
