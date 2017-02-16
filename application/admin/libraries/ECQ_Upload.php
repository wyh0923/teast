<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: qirupeng
 * Date: 2016/8/8
 * Time: 10:58
 */

/***
 * 分段上传类扩展
 * Class Segment_upload
 */
class ECQ_Upload extends CI_Upload
{
    /***
     * Segment_upload constructor.
     * @param array $config
     */
    public function __construct(array $config = array())
    {
        parent::__construct($config);
    }

    /***
     * Huploadify 插件分段上传
     * @param $filename
     * @param $keys
     * @param $uploaddir
     * @return bool
     */
    public function huploadify($filename)
    {
        $fileTypes = array('.jpg', '.jpeg', '.gif', '.png', '.bmp', '.mp4', '.flv', '.rm', '.rmvb',
            '.qcow2', '.rar', '.tar', '.gz', '.zip', '.gzip',
            '.doc', '.docx', '.xls', '.xlsx', '.sql','.pdf');
        $fileType = strtolower(strrchr($filename, '.'));
        if (!in_array($fileType, $fileTypes)) return FALSE;
        //如果目录不存在创建
        if (!is_dir($this->upload_path)) {
            @mkdir($this->upload_path);
        }
        if (!$this->validate_upload_path()) {
            return FALSE;
        }
        if (!is_uploaded_file($_FILES["file"]["tmp_name"])) {
            return FALSE;
        }

        file_put_contents($this->upload_path . $filename,
            file_get_contents($_FILES["file"]["tmp_name"]),
            FILE_APPEND);
        return TRUE;


    }

    /***
     * 分段上传文件
     * @param $file $_FILES['file'],
     * @param $post $_POST
     * @param string $upload_dir
     * @return array
     */
    public function uploadfile($file, $post, $upload_dir = './')
    {
        $res = array('ret' => 0);
        if (!isset($file['tmp_name'])) {
            return $res;
        }
        if (!$this->validate_upload_path()) {
            $res = array('ret' => -1);
            // errors will already be set by validate_upload_path() so just return FALSE
            return $res;
        }
        $saved_files = array();
        $remote_name = $file['tmp_name'];
        $chunk_count = intval($post['chunks']);
        $chunk_index = intval($post['chunk']);
        //文件名应有过滤_prep_filename($file['name']);
        //文件类型检测_file_mime_type($file);

        $filename = $file['name'] . '_' . $post['name'];
        $parts_dir = $upload_dir . $filename . '_parts';
        if (!is_dir($parts_dir)) {
            @mkdir($parts_dir);
        }
        // 判断是否为上传的文件
        if (is_uploaded_file($remote_name)) {
            $destination_path = $parts_dir . '/' . $filename . '_' . $chunk_index;
            @copy($remote_name, $destination_path);
            $saved_files[] = $filename;
        } else {
            //非法上传

        }
        if ($chunk_index == ($chunk_count - 1)) {
            $rename_file = substr(md5($post['filename']), 0, 27) . substr(time(), 5, 10) . '.' . substr($post['filename'], strripos($post['filename'], '.') + 1);
            $res = $this->_merge_file($parts_dir, $rename_file);
            return $res;
        } else {
            $res['ret'] = 2;
            return $res;
        }
    }

    /***
     * 合并文件
     * @param $parts_directory
     * @param $filename
     * @return array
     */
    private function _merge_file($parts_directory, $filename)
    {
        $res = array('ret' => 0);
        $base_dir = dirname($parts_directory);
        $parts_name = basename($parts_directory);
        // 正则匹配
        if (!preg_match('/^(.+)_parts$/', $parts_name)) {
            return $res;
        }
        $target_name = $filename;
        $target_path = $base_dir . '/' . $target_name;
        // 获取所有文件
        $old_file_arr = scandir($parts_directory);
        $files_arr = array();
        foreach ($old_file_arr as $key => $value) {
            if ($value == '.' OR $value == '..') {
                unset($old_file_arr[$key]);
                continue;
            }
            $new_key = intval(substr($value, strripos($value, '_') + 1));
            $files_arr[$new_key] = $value;
        }
        $fp = fopen($target_path, 'wb+');
        ksort($files_arr);
        // 循环写入
        foreach ($files_arr as $key => $value) {
            $part_path = $parts_directory . '/' . $value;
            $fpp = fopen($part_path, 'rb+');
            $buf = fread($fpp, 1024 * 1024);
            while ($buf) {
                fwrite($fp, $buf);
                $buf = fread($fpp, 1024 * 1024);
            }
            fclose($fpp);
        }
        fclose($fp);
        $this->_delete_parts_dir($parts_directory);
        $res['ret'] = 1;
        $res['targetName'] = $target_name;
        return $res;
    }

    /***
     * 删除文件夹
     * @param $dir
     * @return bool
     */
    private function _delete_parts_dir($dir)
    {
        if (is_dir($dir)) {
            //先删除目录下的文件：
            $dh = opendir($dir);
            while ($file = readdir($dh)) {
                if ($file != '.' && $file != '..') {
                    $full_path = $dir . '/' . $file;
                    if (!is_dir($full_path)) {
                        @unlink($full_path);
                    } else {
                        // @deldir($fullpath);
                        //此处应是递归
                        $this->_delete_parts_dir($full_path);
                    }
                }
            }
            closedir($dh);
            //删除当前文件夹：
            return rmdir($dir);
        }
        return FALSE;
    }


}