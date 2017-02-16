<?php

/**
 * 日志类
 * User: kyx
 * Date: 2016/8/10
 * Time: 10:00
 */
class Log_user{
    public $CI;

    function __construct() {
        $this->CI =& get_instance();
    }

    /***
     * 日志写入数据库
     * @param array
     */
    public function add_log($data)
    {
        $data['CreateTime'] = time();
        //插入数据库
        $this->CI->db->insert('log', $data);
    }

    /***
     * 日志写入文件
     * @param array
     */
    public function add_file($data){
        $data['CreateTime'] = date("Y-m-d H:i:s",$data['CreateTime']);
        $dir = getcwd().'/application/client/logs/log_'.date("Ymd",time()).'.php';

        $fp=fopen($dir,"w");//如果不存在会自动创建

        $str = implode(' --- ',$data)."\n";
        file_put_contents($dir , $str , FILE_APPEND);
        fclose($fp);


    }

    /***
     * 接口调用日志写入
     * @param $data
     * @return bool
     */
    public function add_api_log($data)
    {
        $config =& get_config();
        $this->_log_path = ($config['log_path'] !== '') ? $config['log_path'] : APPPATH.'logs/';
        $this->_file_ext = (isset($config['log_file_extension']) && $config['log_file_extension'] !== '')
            ? ltrim($config['log_file_extension'], '.') : 'php';
        file_exists($this->_log_path) OR mkdir($this->_log_path, 0755, TRUE);
        $filepath = $this->_log_path.'log-'.date('Y-m-d').'.'.$this->_file_ext;
        $message = '';
        if ( ! file_exists($filepath))
        {
            $newfile = TRUE;
            // Only add protection to php files
            if ($this->_file_ext === 'php')
            {
                $message .= "<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>\n\n";
            }
        }
        if ( ! $fp = @fopen($filepath, 'ab'))
        {
            return FALSE;
        }
        flock($fp, LOCK_EX);
        $message .= implode(' --- ',$data)."\n";
        $result = fwrite($fp,$message);
        flock($fp, LOCK_UN);
        fclose($fp);
        if (isset($newfile) && $newfile === TRUE)
        {
            chmod($filepath, 0644);
        }
        return is_int($result);

    }

}