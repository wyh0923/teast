<?php
/**
 * Created by PhpStorm.
 * User: qirupeng
 * Date: 2016/7/28
 * Time: 14:09
 */
defined('BASEPATH') OR exit('No direct script access allowed');

if ( ! function_exists('get_nav'))
{
    /**
     * 获取导航菜单
     * @param $data
     * @return array
     */
    function get_nav($data)
    {
        if (isset($data[0])){
            return $data[0];
        }else{
            return array();
        }

    }
}

if ( ! function_exists('current_nav_id'))
{
    /**
     * 获取当前导航菜单
     * @param array $data
     * @param string $url
     * @return int
     */
    function current_nav_id($data, $url)
    {
        if (is_array($data)){
            $id = 0;//默认系统管理
            foreach ($data as $row)
            {
                if (strpos($row['url'], $url) !== false){
                    $id = $row['id'];
                    break;
                }else if (isset($row['sub'])){
                    foreach ($row['sub'] as $sub)
                    {
                        if (strpos($sub['url'], $url) !== false) {
                            $id = $sub['id'];
                            break 2;
                        }
                    }
                }
            }
            return $id;
        }else{
            return 0;
        }

    }
}

if ( ! function_exists('get_left_nav'))
{
    /**
     * 获取子菜单
     * @param $data
     * @param $id
     * @return array
     */
    function get_left_nav($data, $id)
    {
        if (isset($data[$id])){
            //得到子菜单只考虑到了第二级
            $arr = $data[$id];
            foreach ($arr as &$row)
            {
                if (isset($data[$row['id']])){
                    $row['sub'] = $data[$row['id']];
                }
            }
            unset($row);
            return $arr;
        }else{
            return array();
        }

    }
}
if ( ! function_exists('current_title'))
{
    /**
     * 获取当前title
     * @param array $data
     * @param string $url
     * @return int
     */
    function current_title($data, $url)
    {
        if (is_array($data)){
            $id = '';
            foreach ($data as $row)
            {
                if (strpos($row['url'], $url) !== false){
                    $id = $row['title'];
                    break;
                }else if (isset($row['sub'])){
                    foreach ($row['sub'] as $sub)
                    {
                        if (strpos($sub['url'], $url) !== false) {
                            $id = $sub['title'];
                            break 2;
                        }
                    }
                }
            }
            return $id;
        }else{
            return '';
        }

    }
}