<?php
/**
 *
 * @author kyx
 * 
 */
class Filter {

    public $CI;

    function __construct() {
        $this->CI =& get_instance();
    }
    /***
     * 获取路径
     * $targetKeyNames 参数名 $targetKeyValue 参数值 $linkKeys 课程体系参数 $ParentKeyName 父类名称 $ParentKeyValue 父类值
     */
    private function generateUrl($targetKeyName, $targetKeyValue, $linkKeys="",$ParentKeyName="",$ParentKeyValue=""){
        $delKeyName = $targetKeyName.'|per_page|Search';
        if ($linkKeys != "")
            $delKeyName .= '|'.$linkKeys;

        $baseUrl = $this->generateBaseUrl($delKeyName);
        //目前只支持单选筛选，如果要支持多选筛选更换下面的语句

        if ($targetKeyValue != "") {
            $baseUrl .= $targetKeyName."=".$targetKeyValue;
        } else {
            if ($targetKeyName != "Time"){
                $baseUrl = substr($baseUrl,0,strlen($baseUrl)-1);
            } else {
                $baseUrl .= $targetKeyName."=";
            }
        }
        if($ParentKeyValue != ""){
            $baseUrl .= "&".$ParentKeyName."=".$ParentKeyValue;
        }
        return $baseUrl;
    }
    /***
     * 路径参数的叠加
     * $targetKeyNames 参数名
     */
    public function generateBaseUrl($targetKeyNames){
        $args = $this->CI->input->get(NULL,TRUE);
        $targetKeyNameArray = explode('|', $targetKeyNames);
        $baseUrl = site_url() . $this->CI->uri->uri_string();
        $baseUrl = $baseUrl."?";
        foreach($args as $key=>$value){
            $flag = false;
            foreach($targetKeyNameArray as $targetKeyName) {
                if ($key == $targetKeyName){
                    $flag = true;
                }
            }
            //去掉分页参数
            if($key != 'per_page' && !$flag){
               $baseUrl = $baseUrl . $key . "=" . htmlspecialchars( $value ) . "&";

            }
        }
        return $baseUrl;
    }
    private function decideActive($keyValue,$keyValues){
        if (count($keyValues) == 0 && $keyValue == "") {
            return true;
        }
        foreach($keyValues as $item){
            if ($item == $keyValue) {
                return true;
            }
        }
        return false;
    }

    /***
     * 获取培训方案
     */
    private function getFirstArchList(){
        $targetKeyName = "archid";
        $sceondArchKey = "sonid";
        $firstArchList = array();
        $firstArchArgs = explode("|", $this->CI->input->get($targetKeyName));
        
        $result = $this->get_arch(array('ArchitectureParent'=> 0));

        array_push($firstArchList, array("value"=>"全部","key"=>"","active"=>$this->decideActive("", $firstArchArgs),"url"=>$this->generateUrl($targetKeyName, "", $sceondArchKey)));

        foreach($result as $item) {
            $firstArchItem = array(
                "value" => $item["ArchitectureName"],
                "key" => $item["ArchitectureID"],
                "active" => $this->decideActive($item["ArchitectureID"], $firstArchArgs),
                "url" => $this->generateUrl($targetKeyName, $item["ArchitectureID"], $sceondArchKey)
            );
            array_push($firstArchList, $firstArchItem);
        }
        return $firstArchList;
    }

    /***
     * 获取课程体系
     */
    private function getSecondArchList(){
        $firstArchKeyName = "archid";
        $targetKeyName = "sonid";
        $secondArchList = array();

        $firstArchCode = $this->CI->input->get($firstArchKeyName);

        $secondArchArgs = explode("|", $this->CI->input->get($targetKeyName));

        array_push($secondArchList, array("value"=>"全部","key"=>"","active"=>$this->decideActive("", $secondArchArgs),"url"=>$this->generateUrl($targetKeyName, "")));

        if ($firstArchCode != "") {
            $result = $this->get_arch(array('ArchitectureParent'=>$firstArchCode));
            
            foreach($result as $item) {
                $secondArchItem = array(
                    "value" => $item["ArchitectureName"],
                    "key" => $item["ArchitectureID"],
                    "active" => $this->decideActive($item["ArchitectureID"], $secondArchArgs),
                    "url" => $this->generateUrl($targetKeyName, $item["ArchitectureID"])
                );
                array_push($secondArchList, $secondArchItem);
            }

        } else {
            $result = $this->get_arch(array('ArchitectureParent !='=> 0));
            
            foreach($result as $item) {
                $secondArchItem = array(
                    "value" => $item["ArchitectureName"],
                    "key" => $item["ArchitectureID"],
                    "active" => $this->decideActive($item["ArchitectureID"], $secondArchArgs),
                    "url" => $this->generateUrl($targetKeyName, $item["ArchitectureID"],null,$firstArchKeyName,$item["ArchitectureParent"])
                );
                array_push($secondArchList, $secondArchItem);
            }

        }
        return $secondArchList;
    }
    /***
     * 获取课程难度
     */
    private function getDiffList(){
        $targetKeyName = "diff";
        $diffArgs = explode("|", $this->CI->input->get($targetKeyName));
        $diffList = array(
            array("value"=>"全部","key"=>"","active"=>$this->decideActive("", $diffArgs),"url"=>$this->generateUrl($targetKeyName, "")),
            array("value"=>"初级","key"=>"0","active"=>$this->decideActive("0", $diffArgs),"url"=>$this->generateUrl($targetKeyName, "0")),
            array("value"=>"中级","key"=>"1","active"=>$this->decideActive("1", $diffArgs),"url"=>$this->generateUrl($targetKeyName, "1")),
            array("value"=>"高级","key"=>"2","active"=>$this->decideActive("2", $diffArgs),"url"=>$this->generateUrl($targetKeyName, "2"))
        );
        return $diffList;
    }

    /***
     * 获取课程类型
     */
    private function getExpList(){
        $targetKeyName = "exp";
        $expArgs = explode("|", $this->CI->input->get($targetKeyName));
        $expList = array(
            array("value"=>"全部","key"=>"","active"=>$this->decideActive("", $expArgs),"url"=>$this->generateUrl($targetKeyName, "")),
            array("value"=>"理论","key"=>"0","active"=>$this->decideActive("0", $expArgs),"url"=>$this->generateUrl($targetKeyName, "0")),
            array("value"=>"单机实验","key"=>"1","active"=>$this->decideActive("1", $expArgs),"url"=>$this->generateUrl($targetKeyName, "1")),
            array("value"=>"网络实验","key"=>"2","active"=>$this->decideActive("2", $expArgs),"url"=>$this->generateUrl($targetKeyName, "2"))
        );
        return $expList;
    }

    /***
     * 获取体系搜索列表
     * ArchitectureID 体系编号 或 ArchitectureParent 父类编号
     */
    public function get_arch($where){
        $this->CI->db->select('ArchitectureID,ArchitectureName,ArchitectureParent');
        $this->CI->db->from('p_architecture');
        $this->CI->db->where($where);
        $this->CI->db->	order_by('ArchitectureID','asc');
        $result=$this->CI->db->get()->result_array();
        return $result;
    }

    /***
     * 获取搜索项
     * $funMask 二进制
     */
    public function getFilterData($funMask){
        $filterData = array();
        $firstArch = 0b01;
        $secondArch = 0b0100;
        $diff = 0b010000;
        $exp = 0b01000000;
        $time = 0b0100000000;
        $scene = 0b01000000000000000000;

        if ($funMask & $firstArch){
            array_push($filterData, array("subFilterName"=>"培训方案", "subFilterData"=>$this->getFirstArchList()));
        }

        if ($funMask & $secondArch){
            array_push($filterData, array("subFilterName"=>"课程体系", "subFilterData"=>$this->getSecondArchList()));
        }

        if ($funMask & $exp){
            array_push($filterData, array("subFilterName"=>"类　　型", "subFilterData"=>$this->getExpList()));
        }

        if ($funMask & $scene){
            array_push($filterData, array("subFilterName"=>"区域个数", "subFilterData"=>$this->getSceneList()));
        }

        if ($funMask & $time){
            array_push($filterData, array("subFilterName"=>"时间范围", "subFilterData"=>$this->getTimeList()));
        }

        if ($funMask & $diff){
            array_push($filterData, array("subFilterName"=>"难度等级", "subFilterData"=>$this->getDiffList()));
        }

        return $filterData;
    }
    
}