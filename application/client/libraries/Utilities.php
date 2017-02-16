<?php
/**
 *
 * @author kyx
 *
 */
class Utilities {

    public $CI;
    private $iv = "Ecq@12!Byad`^#.1"; /* 必须16位哦 */

    function __construct() {

        $this->CI =& get_instance();
    }

    //marked 格式清除
    public function clearMarkdown($stra) {
        $stra = str_replace("\\", "\\\\", $stra);
        $stra = str_replace("\n", "\\n", $stra);
        $stra = str_replace("\r", "\\r", $stra);
        $stra = str_replace("\'", "\\\'", $stra);
        $stra = str_replace("\"", "\\\"", $stra);

        $strb = "";
        $strArray = explode("```", $stra);
        $strArrayLen = count($strArray);
        for ($i=0;$i<$strArrayLen;$i++) {
            if ($i % 2 == 0) {
                $strc = "";
                $strArrayInner = explode("`", $strArray[$i]);
                for ($j=0;$j<count($strArrayInner);$j++) {
                    if ($j % 2 == 0) {
                        $strc .= str_replace("&gt;", ">", htmlspecialchars($strArrayInner[$j]));
                    } else {
                        $strc .= "`" . str_replace("/", "\\/", $strArrayInner[$j]) . "`";
                    }
                }
                $strb .= str_replace("&gt;", ">", $strc);
            } else {
            	//$strb .= "```" . str_replace("/", "\\/", $strArray[$i]) . "```";
            	//将尖括号替换 保证代码块可以输出
            	$tmp=str_replace("/", "\\/", $strArray[$i]);
            	$tmp=str_replace("&lt;", "<", $tmp);
            	$tmp=str_replace("&gt;", ">", $tmp);
            	$strb .= "```" . $tmp . "```";
            }
        }
        return $strb;
    }

    public function getArchRootCode(){
        return "----------------";
    }

    public function getPackageRootCode(){
        return "0";
    }

    public function getWebServiceIp(){

        try{
            $this->CI->db->select("InfoValue");
            $this->CI->db->from('p_system_info');
            $this->CI->db->where("InfoName", "DCenterIP");

            $res = $this->CI->db->get()->result_array();

            $this->CI->db->select("InfoValue");
            $this->CI->db->from('p_system_info');
            $this->CI->db->where("InfoName", "DCenterPort");
            $resport = $this->CI->db->get()->result_array();

            if($this->CI->db->affected_rows()>0){
                return $res[0]["InfoValue"].":".$resport[0]["InfoValue"].'/';
            }
        }catch(Exception $exp){
            return "127.0.0.1".":5001/";
        }
        return "127.0.0.1".":5001/";
    }

    public function getversion_upgrade_IP(){
        $restPacket = new ResPacket();
        try{
            $this->CI->db->select("InfoValue");
            $this->CI->db->from('p_system_info');
            $this->CI->db->where("InfoName", "DCenterIP");

            $res = $this->CI->db->get()->result_array();
            if($this->CI->db->affected_rows()>0){
                if ($res[0]["InfoValue"] == "127.0.0.1") {
                    $res[0]["InfoValue"] =$_SERVER["SERVER_ADDR"] ;
                }
                return $res[0]["InfoValue"].":".'5000/';
            }
        }catch(Exception $exp){
//             return "127.0.0.1:5000"."/";
            return $_SERVER["SERVER_ADDR"].":5000/";
        }
        return $_SERVER["SERVER_ADDR"].":5000/";
    }

    public function getWebServiceIpnotport(){
        $restPacket = new ResPacket();
        try{
            $this->CI->db->select("InfoValue");
            $this->CI->db->from('p_system_info');
            $this->CI->db->where("InfoName", "DCenterIP");

            $res = $this->CI->db->get()->result_array();

//             $this->CI->db->select("InfoValue");
//             $this->CI->db->from('p_system_info');
//             $this->CI->db->where("InfoName", "DCenterPort");
//             $resport = $this->CI->db->get()->result_array();

            if($this->CI->db->affected_rows()>0){
                if ($res[0]["InfoValue"] == "127.0.0.1") {
                    $res[0]["InfoValue"] =$_SERVER["SERVER_ADDR"] ;
                }
                return $res[0]["InfoValue"];
            }
        }catch(Exception $exp){
            return $_SERVER["SERVER_ADDR"];
        }
        return $_SERVER["SERVER_ADDR"];
    }

    /* 采用128位加密，密钥也必须是16位 */
    private function aes_encode($sourcestr, $key)
    {
        global $iv;
        return base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key, $sourcestr, MCRYPT_MODE_CBC, $this->iv));
    }

    private function aes_decode($crypttext, $key)
    {
        global $iv;
        return rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key, base64_decode($crypttext), MCRYPT_MODE_CBC, $this->iv), "\0");
    }

    public function getUUID(){
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

    public function getUnityCode(){
        $code = $this->getUUID();
        $code = md5($code);
        // echo $code.'<br>';
        $code = substr($code, 8, 16);
        //echo $code;
        return $code;

        //$code = time().rand(0,99);
        //return $code;
    }

    public function generateBaseUrl($targetKeyNames){
        $args = $this->CI->input->get(NULL,TRUE);
        $targetKeyNameArray = explode('|', $targetKeyNames);
        $baseUrl = site_url() . $this->CI->uri->uri_string();
        $baseUrl = $baseUrl."?";
        foreach($args as $key=>$value){
            //echo "+++++++|";
            $flag = false;
            foreach($targetKeyNameArray as $targetKeyName) {
                if ($key == $targetKeyName){
                    $flag = true;
                    //echo '<br />'.$key.'<br />';
                    //break;
                }
            }
            if (!$flag)
                $baseUrl = $baseUrl . $key . "=" . htmlspecialchars( $value ) . "&";
        }
        //echo ""
        //var_dump($targetKeyNameArray);
        //echo $targetKeyNames.$baseUrl."<br>";
        return $baseUrl;
    }

    /**
     * $hostip 指定要上传到哪个服务器 $hostname 服务器用户名 $hostpwd服务器密码
     */
    public function huploadifyfunc($filename, $keys, $uploaddir, $hostip='', $hostname='', $hostpwd=''){
        $fileTypes = array('jpg', 'jpeg', 'gif', 'png', 'bmp','mp4','flv','rm','rmvb',
            'qcow2', 'rar', 'tar', 'gz', 'zip', 'gzip',
            'doc', 'docx', 'xls', 'xlsx','sql');

        $res = array();
        if (count($keys) < 2)
        {
            $res['success'] = false;
            $res['fileurl'] = null;
            $res['msg'] = 'keynotfound';
            return $res;
        }
        if (empty($filename)){
            $res['success'] = false;
            $res['fileurl'] = null;
            $res['msg'] = 'emptypathorname';
            return $res;
        }

        if (empty($uploaddir)){
            $res['success'] = false;
            $res['fileurl'] = null;
            $res['msg'] = 'emptypathorname_dir';
            return $res;
        }

        //获取文件后缀
        $name = explode('.',$filename);
        $suffix = $name[count($name)-1];
        //是否符合上传格式
        if ( in_array( strtolower($suffix),  $fileTypes ) ){

            $resname = $keys['key2'].$keys['key1'].'.'.$suffix;

            if ($resname){
                if (!empty($hostname)){
                    $connection = ssh2_connect($hostip, 22);
                    $cmd = "mkdir -p ".$uploaddir." && cat ".$_FILES["file"]["tmp_name"] ." >> ".$uploaddir.$resname;
                    ssh2_auth_password($connection,$hostname,$hostpwd);
                    if (!$connection){
                        $res['success'] = false;
                        $res['fileurl'] = null;
                        $res['msg'] = "connecthosterror";
                        return $res;
                    }
                    $result = ssh2_scp_send($connection, $_FILES["file"]["tmp_name"], $_FILES["file"]["tmp_name"]);
                    $result = ssh2_exec($connection,$cmd);
                }else{
                    if (!empty($keys['key3pic'])) {
                        $uploadDir = getcwd().$uploaddir.$keys['key1'].'/';

                        if(!is_dir($uploadDir)) {
                            @mkdir($uploadDir);
                        }
                        $respicname = $keys['key2'].'.'.$suffix;
                        $res['respicname'] = $respicname;
                        file_put_contents($uploadDir.$respicname,file_get_contents($_FILES["file"]["tmp_name"]),FILE_APPEND);;
                    }elseif (!empty($keys['key3admin'])){

                        $uploadDir = getcwd().$uploaddir.'/';;
                        //var_dump($uploadDir);die;
                        if(!is_dir($uploadDir)) {
                            // mkdir($uploadDir);
                            mkdir($uploadDir,0777,true);
                        }
                        //$respicname = $keys['key2'].'.'.$suffix;
                        $res['resname'] = $resname;
                        file_put_contents($uploadDir.$resname,file_get_contents($_FILES["file"]["tmp_name"]),FILE_APPEND);;

                    } else{
                        if(!is_dir($uploaddir)) {
                            @mkdir($uploaddir);
                        }
                        file_put_contents($uploaddir.$resname,
                            file_get_contents($_FILES["file"]["tmp_name"]),
                            FILE_APPEND);
                    }


                }

                $res['success'] = true;
                $res['fileurl'] = $resname;
            }
        }
        else
        {
            $res['success'] = false;
            $res['fileurl'] = null;
            $res['msg']  = 'notallowedtype';
        }

        return $res;
    }

    /**
     * 对象转数组
     */
    public function objectToArray($e){
        $e=(array)$e;
        foreach($e as $k=>$v){
            if( gettype($v)=='resource' ) return;
            if( gettype($v)=='object' || gettype($v)=='array' )
                $e[$k]=(array)$this->objectToArray($v);
        }
        return $e;
    }

    /**
     * 向url 发送put请求 $fields=>内容 by monk
     */
    private function delStatus($url,$fields=array()){
        if( $ch = curl_init($url) ){
            $fields = (is_array($fields)) ? http_build_query($fields) : $fields;
            //$now = date ( "Y-m-d=H:i", time());
            //$fields = $this->aes_encode($fields, $now);
            curl_setopt($ch, CURLOPT_URL, $url );
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Length: ' . strlen($fields)));
            curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
            $file_contents = curl_exec($ch);
            $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ((int) $status != 0)
            {
                $obj = NULL;
                $now = 'ecq@13!Back`^#.1';
                $file_contents = $this->aes_decode($file_contents, $now);
                $obj = json_decode($file_contents);
                if( gettype($obj) == "string" ){
                    $obj = json_decode($obj);
                }
                return $obj;
            }

        } else {
            return NULL;
        }
    }

    /**
     * 向url 发送put请求 $fields=>内容 by monk
     */
    private function putStatus($url,$fields=array()){
        if( $ch = curl_init($url) ){
            $fields = (is_array($fields)) ? http_build_query($fields) : $fields;
            //$now = date ( "Y-m-d=H:i", time());
            //$fields = $this->aes_encode($fields, $now);
            curl_setopt($ch, CURLOPT_URL, $url );
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Length: ' . strlen($fields)));
            curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
            $file_contents = curl_exec($ch);
            $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ((int) $status != 0)
            {
                $obj = NULL;
                $now = 'ecq@13!Back`^#.1';
                $file_contents = $this->aes_decode($file_contents, $now);
                $obj = json_decode($file_contents);
                if( gettype($obj) == "string" ){
                    $obj = json_decode($obj);
                }
                return $obj;
            }

        } else {
            return NULL;
        }
    }

    /**
     * 向url 发送get请求 edit by monk
     */
    private function getStatus($url){
        $ch = curl_init();

        curl_setopt ($ch, CURLOPT_URL, $url );
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        $file_contents = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ((int) $status != 0)
        {
            $obj = NULL;
            //$now = date ( "Y-m-d=H:i", time());
            $now = 'ecq@13!Back`^#.1';
            $file_contents = $this->aes_decode($file_contents, $now);
            //var_dump($file_contents);
            $obj = json_decode($file_contents);
            if( gettype($obj) == "string" ){
                $obj = json_decode($obj);
            }
            return $obj;
        }

        return NULL;
    }

    /**
     * 向url发送post请求 $fields=>发送的内容 by monk
     */
    private function postStatus($url,$fields=array()){
        if( $ch = curl_init($url) ){
            $fields = (is_array($fields)) ? http_build_query($fields) : $fields;
            //$now = date ( "Y-m-d=H:i", time());
            //$fields = $this->aes_encode($fields, $now);
            curl_setopt($ch, CURLOPT_URL, $url );
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Length: ' . strlen($fields)));
            curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
            $file_contents = curl_exec($ch);
            $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            //var_dump($status.":".$file_contents);die();
            //执行结果
            if ((int) $status != 0)
            {
                $obj = NULL;
                $now = 'ecq@13!Back`^#.1';
                $file_contents = $this->aes_decode($file_contents, $now);
                $obj = json_decode($file_contents);
                if( gettype($obj) == "string" ){
                    $obj = json_decode($obj);
                }
                return $obj;
            }
        } else {
            return NULL;
        }
    }

    /**
     * 使用curl 发送get/post请求 $fields=>内容 $url=>httpurl $method=>get/post/put
     */
    public function request_interface($fields=array(), $url, $method){
        $restPacket = array();
        try
        {
            $result = array();
            if (strtolower($method) == "post")
            {
                $result = $this->postStatus($url, $fields);
            }
            else if (strtolower($method) == "get"){
                $result = $this->getStatus($url, $fields);
            }
            else if (strtolower($method) == "put")
            {
                $result = $this->putStatus($url, $fields);
            }
            else if (strtolower($method) == "delete")
            {
                $result = $this->delStatus($url, $fields);
            }

            if (empty($result))
            {
                //接口请求失败 日志记录
                $data['url'] = $url;
                $data['message'] = '请求接口失败';
                $data['CreateTime'] = date('Y-m-d H:i:s',time());
                $this->CI->db->insert('p_syserror_log',$data);

                $restPacket['RespHead']=array(
                    'ErrorCode'=>404,
                    'ID'=>$this->CI->db->insert_id(),
                    'Message'=>'请求接口失败，请检查网络是否畅通!'
                );
                return $restPacket;

            }else if(isset($result)){
                $info = $this->objectToArray($result);

                //返回错误日志记录
                if($info['RespHead']['ErrorCode'] != 0){
                    $data['url'] = $url;
                    $data['message'] = $info['RespHead']['Message'];
                    $data['CreateTime'] = date('Y-m-d H:i:s',time());
                    $this->CI->db->insert('p_syserror_log',$data);

                    //返回错误 paython给出的错误信息
                    return $info;
                }
            }

            $restPacket = $this->objectToArray($result);
        }catch(Exception $exp)
        {
            $restPacket['RespHead']=array(
                'ErrorCode'=>11,
                'Message'=>$exp->getMessage()
            );
        }
        return $restPacket;
    }

    /**
     * 发送http请求(method = get/post) eidt by monk 该请求方式容易出现400 error
     */
    public function request_interface_old($data,$url,$method)
    {
        $restPacket = array();
        try{

            $params = $data;
            $options = array (
                'http' => array (
                    'method' => $method,
                    'header' => 'Content-type:application/json',
                    'timeout' => 90,
                    'content' => json_encode($params)
                )
            );
            // $options =json_encode($options);

            $stream_context = stream_context_create ( $options ); // 创建流

            $result = @file_get_contents ( $url, false, $stream_context );  //读取

            if( empty($result) )
            {
                $restPacket['RespHead']=array(
                    'ErrorCode'=>404,
                    'Message'=>'请求接口失败，请检查网络是否畅通!'
                );
                return $restPacket;
            }
            $result = json_decode(trim($result));
            $result = $this->objectToArray( json_decode( $result, true ) );
            $restPacket = $result;
        }catch(Exception $exp){
            $restPacket['RespHead']=array(
                'ErrorCode'=>11,
                'Message'=>$exp->getMessage()
            );
        }
        return $restPacket;
    }

    public function getBestVideoServer() {
        $url = 'api/v1.0/function_node/?server_type=7';
        $method = "GET";
        //$url=$this->utilities->getWebServiceIp().$url;
        $url=$this->getWebServiceIp().$url;
        $nodeInfoRes = $this->request_interface(null,$url,$method);
        //var_dump($nodeInfoRes);
        $videoServerIp = '';
        if ($nodeInfoRes["RespHead"]["ErrorCode"] == 0) {
            $nodeList = $nodeInfoRes["RespBody"]["Result"]["host_list"];
            foreach($nodeList as $nodeItem) {
                if (isset($nodeItem["best_host"])) {
                    //if ($nodeItem["best_host"] == true) {
                    foreach($nodeItem["nat_list"] as $natItem) {
                        if ($natItem["int_port"] == config_item ('videoIntPort')){
                            $videoServerIp = "http://" . $natItem["root_router_ip"] . ":" . $natItem["ext_port"] . "/train";
                            break;
                        }
                    }
                    break;
                    //}
                } else {
                    foreach($nodeItem["nat_list"] as $natItem) {
                        if ($natItem["int_port"] == config_item ('videoIntPort')){
                            $videoServerIp = "http://" . $natItem["root_router_ip"] . ":" . $natItem["ext_port"] . "/train";
                            break;
                        }
                    }
                    break;
                }
            }
        }
        return $videoServerIp;
    }

    //linxiaobing   工具库数据库文件配置参数化
    public function gettoooldbconfig() {
        $url = 'api/v1.0/function_node/?server_type=6';
        $method = "GET";
        $url=$this->getWebServiceIp().$url;
        //$url ="192.168.199.82:5001/api/v1.0/function_node/?server_type=6";
        $nodeInfoRes = $this->request_interface(array(),$url,$method);
        //var_dump($nodeInfoRes);die;
        $configtooldb = array();
        $configtooldb['hostname'] = '192.168.9.181';
        if ($nodeInfoRes["RespHead"]["ErrorCode"] == 0) {
//             if (!empty($nodeInfoRes["RespBody"]["Result"]["host_info"])) {
//                 $configtooldb['hostname'] = $nodeInfoRes["RespBody"]["Result"]["host_info"]["root_router_ip"];
//             }

            $nodeList = $nodeInfoRes["RespBody"]["Result"]["host_list"];
            foreach($nodeList as $nodeItem) {

                foreach($nodeItem["nat_list"] as $natItem) {
                    if ($natItem["int_port"] == 3306){
                        $configtooldb['hostname'] =  $natItem["root_router_ip"] ;
                        $configtooldb['port']  = $natItem["ext_port"]  ;
                        break;
                    }
                }
                break;

            }


        }
        // echo $configtooldb['hostname'] .":".$configtooldb['port'] ;
        // var_dump($nodeInfoRes);die;
        $configtooldb['username'] = 'root';
        $configtooldb['password'] = 'bachangV3.0@mysql';
        $configtooldb['database'] = 'tooldb';
        $configtooldb['dbdriver'] = 'mysqli';
        $configtooldb['dbprefix'] = '';
        $configtooldb['pconnect'] = FALSE;
        $configtooldb['db_debug'] = FALSE;
        $configtooldb['cache_on'] = FALSE;
        $configtooldb['cachedir'] = '';
        $configtooldb['char_set'] = 'utf8';
        $configtooldb['dbcollat'] = 'utf8_general_ci';
        $configtooldb['swap_pre'] = '';
        $configtooldb['autoinit'] = FALSE;
        $configtooldb['stricton'] = FALSE;
        // $configtooldb['port'] = 3306;
        return $configtooldb;
    }
    public function getBestCtfServer($ctfServerId, $ctfServerPort) {
        $url = 'api/v1.0/function_node/?server_type=5';
        $method = "GET";
        // $url=$this->utilities->getWebServiceIp().$url;
        $url=$this->getWebServiceIp().$url;
        $nodeInfoRes = $this->request_interface(null,$url,$method);
        //var_dump($nodeInfoRes);var_dump($ctfServerId);var_dump($ctfServerPort);
        $ctfUrl = "";
        if ($nodeInfoRes["RespHead"]["ErrorCode"] == 0) {
            $nodeList = $nodeInfoRes["RespBody"]["Result"]["host_list"];
            foreach($nodeList as $nodeItem) {
                if ($nodeItem["id"] == $ctfServerId) {
                    foreach($nodeItem["nat_list"] as $natItem) {
                        if ($natItem["int_port"] == $ctfServerPort){
                            $ctfUrl = "http://" . $natItem["root_router_ip"] . ":" . $natItem["ext_port"];
                            break;
                        }
                    }
                    break;
                }
            }
        }
        return $ctfUrl;
    }



}