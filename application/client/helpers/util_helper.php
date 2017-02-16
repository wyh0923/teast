<?php
/**
 * Created by PhpStorm.
 * User: qirupeng
 * Date: 2016/8/5
 * Time: 9:27
 */
if ( ! function_exists('get_unique_code'))
{
    /***
     * 生成唯一Code，16位
     * @return string
     */
    function get_unique_code()
    {
        $code = get_uuid();
        $code = md5($code);
        $code = substr($code, 8, 16);
        return $code;

    }
}

if ( ! function_exists('get_uuid'))
{
    /***
     * 生成UUID
     * @return string
     */
    function get_uuid()
    {
        mt_srand((double)microtime()*10000);//optional for php 4.2.0 and up.
        $charid = strtoupper(md5(uniqid(rand(), true)));
        $hyphen = chr(45);// "-"
        $uuid = substr($charid, 0, 8).$hyphen
            .substr($charid, 8, 4).$hyphen
            .substr($charid,12, 4).$hyphen
            .substr($charid,16, 4).$hyphen
            .substr($charid,20,12);
        return $uuid;
    }
}

if ( ! function_exists('get_pages'))
{
    /***
     * 生成分页链接
     * @param $base_url
     * @param int $total_rows 总记录
     * @param int $per_page 每页记录
     * @param bool $use_page_numbers
     * @param string $first_link
     * @param string $last_link
     * @return mixed
     */
    function get_pages($base_url, $total_rows, $suffix = '', $per_page = 10 ,
                       $use_page_numbers = TRUE, $first_link = '&lsaquo;首页', $last_link = '尾页&rsaquo;')
    {
        $CI =& get_instance();
        $CI->load->library('pagination');
        $parameter  = $CI->input->get(NULL, TRUE);
        $configs = get_config();
        if ($configs['enable_query_strings']) {
            unset($parameter[$CI->router->class.'/'.$CI->router->method]);
            unset($parameter["per_page"]);
            $param = http_build_query($parameter);
            $url = site_url($CI->router->class.'/'.$CI->router->method.'&'.$param);
        } else{
            $parameter  = array_merge($parameter, $CI->uri->uri_to_assoc(3));
            $param = '';
            foreach ($parameter as $key=>$val) {
                if ($val === FALSE OR $val === NULL) {
                    unset($parameter[$key]);
                    continue;
                }
                $param .= $key.'/'.$val.'/';
            }
            $url = site_url($CI->router->class.'/'.$CI->router->method.'/'.$param);
            // 指定包含分页数的uri位置
            $config['uri_segment'] = (count($parameter) * 2) + 3;
        }

        $config['base_url'] = $url;
        $config['total_rows'] = $total_rows;
        $config['per_page'] = $per_page;
        $config['use_page_numbers'] = $use_page_numbers;
        $config['first_link'] = $first_link;
        $config['last_link'] = $last_link;
        $config['prev_link'] = '&lt;上一页';
        $config['next_link'] = '下一页&gt;';
        $config['suffix'] = $suffix;
        //$config['num_links'] = 0;//控制页面显示首页尾页

        //$config['page_query_string'] = TRUE;
        $CI->pagination->initialize($config);
        return $CI->pagination->create_links();
    }

}
if ( ! function_exists('get_url'))
{
    /***
     * 生成参数url
     * @param $url
     * @param $param
     * @param $value
     * @return mixed|string
     */
    function get_url($url,$param,$value = '')
    {
        $url = translate_char($url);
        if ($value == '')return preg_replace("/\/$param\/([^\/]+)/i", '', $url);

        if (strpos($url, $param) === FALSE){
            return $url.$param.'/'.$value.'/';
        }else{
            return preg_replace('/\/'.$param.'\/\d+\/(.*)/i', '/'.$param.'/'.$value.'/'.'$1', $url);
        }

    }

}
if ( ! function_exists('translate_char'))
{
    /***
     * 特殊字符转换
     * @param $str
     * @return mixed
     */

    function translate_char($str)
    {
        $str = str_replace(
            array('+','!','#','$','&',"'",'(',')','*',',',';','=','?','@',"\\",'%','|'),
            array('%2B','%21','%23','%24','%26','%27','%28','%29','%2A','%2C','%3B','%3D','%3F','%40','%255C','%25','%7C'),
            $str);
        return $str;
    }

}