<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: qirupeng
 * Date: 2016/8/5
 * Time: 16:31
 */

/***
 * Class Get_Csv 读写CSV文件
 * $fields = array('field1', 'field2');
 * $data = $this->get_csv->set_file_path('path/to/file.csv')->get_array($fields);
 */
class Get_csv
{
    private $file_path = "";
    private $handle;

    /**
     * set_file_path
     *
     * @param mixed $file_path Description.
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function set_file_path($file_path)
    {
        $this->file_path = $file_path;
        return $this;
    }

    /**
     * get_handle
     *
     * @access private
     *
     * @return mixed Value.
     */
    private function get_handle()
    {
        //$this->handle = fopen($this->file_path, "r");
        $this->handle = $this->utf8_fopen_read($this->file_path);
        return $this;
    }

    /***
     * 编码转换
     * @param $fileName
     * @return resource
     */
    private function utf8_fopen_read($fileName)
    {
        $fc = file_get_contents($fileName);
        $e = mb_detect_encoding($fc, array('UTF-8', 'GBK'));
        switch($e){
            case 'UTF-8' : //如果是utf8编码
                $fc = iconv('UTF-8','GBK//TRANSLIT//IGNORE', $fc);
                break;
            case 'GBK': //如果是gbk编码
                $fc = iconv('GBK','UTF-8//TRANSLIT//IGNORE', $fc);
                break;
        }
        $handle=fopen("php://memory", "rw");
        fwrite($handle, $fc);
        fseek($handle, 0);
        return $handle;
    }

/**
     * close_csv
     *
     * @access private
     *
     * @return mixed Value.
     */
    private function close_csv()
    {
        fclose($this->handle);
        return $this;
    }

    //this is the most current function to use


    /**
     * This function gets the CSV and passes it to an associative array
     * If the $fields parameter is not empty, it gets the names of the csv fields from it
     * In case $fields is empty the names of the csv will be the ones in the first line of the CSV
     *
     * @param string $fields name of the csv fields
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function get_array($fields = array())
    {
        $this->get_handle();
        $row = 0;
        $result = array();
        $title = array();
        while (($data = fgetcsv($this->handle, 0, ",")) !== FALSE)
        {

            if($row == 0)
            {
                if(!empty($fields)){
                    //If the array of fields has different number of columns than the csv, return an empty array
                    if(count($data) != count($fields)){
                        $this->close_csv();
                        return array();
                    }


                    foreach($fields as $key => $value)
                        $title[$key] = mb_convert_encoding(trim($value), "UTF-8", "GBK");

                    //we need to store the first line
                    /*$row++; //Make the first line 0 not -1
                    $new_row = $row - 1; //this is needed so that the returned array starts at 0 instead of 1
                    foreach($title as $key => $value)
                    {
                        $result[$new_row][$value] = mb_convert_encoding(trim($data[$key]), "UTF-8", "GBK");
                    }*/
                }
                else
                {
                    foreach ($data as $key => $value)
                    {
                        //$key =   mb_convert_encoding($key, "UTF-8", "GBK");
                        $value =  mb_convert_encoding($value, "UTF-8", "GBK");
                        $title[$key] = trim($value);
                    }
                }
            }
            else
            {
                $new_row = $row - 1; //this is needed so that the returned array starts at 0 instead of 1
                foreach($title as $key => $value)
                {
                    //$key =   mb_convert_encoding($key, "UTF-8", "GBK");
                    //$value =  mb_convert_encoding($value, "UTF-8", "GBK");
                    $result[$new_row][$value] = mb_convert_encoding(trim($data[$key]),"UTF-8", "GBK");
                    //$result[$new_row][] = $value;
                }
            }
            $row++;
        }
        $this->close_csv();
        return $result;
    }

    /**
     * get_csv_array
     *
     * @access public
     *
     * @return mixed Value.
     */
    function get_csv_array()
    {
        $row = 0;
        $final_array = array();
        if (($handle = fopen($this->file_path, "r")) !== FALSE)
        {
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE)
            {
                $final_array[$row] = $data;
                $row++;
            }
            fclose($handle);
        }
        return $final_array;
    }

    /***
     * 写入CSV文件
     * @param $data
     */
    function rewrite_csv($data)
    {
        if (($handle = fopen($this->file_path, "w")) !== FALSE) {
            foreach ($data as $line) {
                $line = array_map(function ($str){return iconv('UTF-8','GBK//TRANSLIT//IGNORE', $str);},$line);
                fputcsv($handle, $line, ',');
            }
        }
        fclose($handle);
    }




}