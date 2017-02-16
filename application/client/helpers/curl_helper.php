<?php
/**
 * Created by PhpStorm.
 * User: WKF
 * Date: 2016/7/28
 * Time: 15:47
 */


/*
 *  实现 Curl POST GET PUT DELETE
 * @param String $url  提交
 * @param String $post_data 提交数据
 * @param String $method  提交方法 GET,POST，PUT,DELETE
 */
function request_by_curl($url, $post_data, $method)
{

    $useragent = 'ECQ 3.0 (curl) ' . phpversion();
    $ch = curl_init();

    $this_header = array("content-type: application/x-www-form-urlencoded; charset=UTF-8");
    curl_setopt($ch, CURLOPT_HTTPHEADER, $this_header);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_USERAGENT, $useragent);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    $result = curl_exec($ch);
    curl_close($ch);

    return $result;

}
