<?php

/**
 * Created by PhpStorm.
 * User: WKF
 * Date: 2016/8/23
 * Time: 14:34
 */
class Video_model extends CI_Model
{
    /*
     * 给出服务器视频地址
     *
     */
    public function get_video_url($video_name)
    {


        $videoUrl = '';
        $this->load->library('Data_exchange', array('api_name' => 'get_video_url', 'message' => array('server_type'=>7)), 'get_video_url');
        $nodeInfoRes = $this->get_video_url->request();

        if ($nodeInfoRes["RespHead"]["ErrorCode"] == 0 && $nodeInfoRes["RespBody"]["Result"]["host_list"]) {

            foreach ($nodeInfoRes["RespBody"]["Result"]["host_list"] as $nodeItem) {

                foreach ($nodeItem["nat_list"] as $natItem) {
                    if ($natItem["int_port"] == config_item('videoIntPort')) {
                        $videoUrl = "http://" . $natItem["root_router_ip"] . ":" . $natItem["ext_port"] . "/train" . '/' . $video_name;
                        break;
                    }
                }
                break;

            }
        }
        return $videoUrl;


    }
}