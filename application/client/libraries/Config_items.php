<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: WKF
 * Date: 2016/8/5
 * Time: 15:44
 */
/*
*配置项类
*/

class Config_items
{

    static $knowledge_system_type = array(

        '0' => '全部',
        '1' => '理论',
        '2' => '单机实验',
        '3' => '网络实验'

    );

    static $knowledge_system_level = array(

        '0' => '全部',
        '1' => '初级',
        '2' => '中级',
        '3' => '高级'
    );
    /***
     * 日志类型
     * @var array
     */
    static $log_type = array(

        '1' => '学习',
        '2' => '考试',
        '3' => '下发场景',
        '4' => '登录',
        '5' => '登出',
        '6' => '自学',
    );
    /***
     * CTF场景类型
     * @var array
     */
    static $ctf_type = array(

        '1' => 'WEB安全',
        '2' => '数据隐写',
        '3' => '密码学',
        '4' => '逆向分析',
        '5' => '编程技术',
        '6' => '取证',
        '7' => '安全入门',
        '8' => '系统安全',
        '9' => '缓冲区溢出'
    );
    /***
     * 场景模板区域个数
     * @var array
     */
    static $zone_type = array(

        '1' => '一安全区',
        '2' => '二安全区',
        '3' => '三安全区',
        '4' => '四安全区'
    );
    /***
     * CPU类型
     * @var array
     */
    static $cpu_type = array(

        '1' => '单核',
        '2' => '双核',
        '3' => '三核',
        '4' => '四核'
    );
    /***
     * 内存类型
     * @var array
     */
    static $memory_type = array(

        '512' => '512M',
        '1024' => '1G',
        '2048' => '2G',
        '4096' => '4G'
    );

}