<?php

/**
 * 数据验证类
 * User: qirupeng
 * Date: 2016/8/9
 * Time: 9:33
 */
class Data_validate
{
    /***
     * 是否为空，此处可能有坑，NULL
     * @param $str
     * @return bool
     */
    public  function is_empty($str)
    {
        $str = trim($str);
        return ! empty($str) ? TRUE : FALSE;
    }

    /**
     * 数字验证
     * @param $flag : int是否是整数，float是否是浮点型
     */
    public function is_num($str, $flag = 'float')
    {
        if (!self::is_empty($str)) return FALSE;
        if (strtolower($flag) == 'int') {
            return ((string)(int)$str === (string)$str) ? TRUE : FALSE;
        } else {
            return ((string)(float)$str === (string)$str) ? TRUE : FALSE;
        }
    }

    /**
     * 姓名匹配
     * @param:string $str 要匹配的字符串
     *
     */
    public function is_name($str)
    {
        if (empty($str))
            return FALSE;
        $match = '/^[\x{4e00}-\x{9fa5}A-Za-z]{2,12}$/iu';
        return preg_match($match, $str)? TRUE : FALSE;
    }

    /**
     * 邮箱验证
     */
    public function is_email($str)
    {
        if (!self::is_empty($str)) return FALSE;
        return preg_match("/([a-z0-9]*[-_\.]?[a-z0-9]+)*@([a-z0-9]*[-_]?[a-z0-9]+)+[\.][a-z]{2,3}([\.][a-z]{2})?/i", $str) ? TRUE : FALSE;
    }

    /***
     * 是否是固定电话或手机
     * @param $str
     * @return bool
     */
    public function is_mobile_or_tel($str)
    {
        return $this->is_mobile($str) XOR $this->is_tel($str);
    }

    /***
     * 手机号验证
     * @param $str
     * @return bool
     */
    public function is_mobile($str)
    {
        $exp = "/^[1][3-9][0-9]{9}$/";
        if (preg_match($exp, $str)) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /***
     * 固定电话验证
     * @param $str
     * @return bool
     */
    public function is_tel($str)
    {
        $exp = "/^([0-9]{3,4}-)?[0-9]{7,8}$/";
        if (preg_match($exp, $str)) {
            return TRUE;
        } else {
            return FALSE;
        }

    }

    /**
     * 验证中文
     * @param:string $str 要匹配的字符串
     * @param:$charset 编码（默认utf-8,支持gb2312）
     */
    public function is_chinese($str, $charset = 'utf-8')
    {
        if (!self::is_empty($str)) return FALSE;
        $match = (strtolower($charset) == 'gb2312') ? "/^[" . chr(0xa1) . "-" . chr(0xff) . "]+$/"
            : "/^[\x{4e00}-\x{9fa5}]+$/u";
        return preg_match($match, $str) ? TRUE : FALSE;
    }

    /**
     * UTF-8验证
     */
    public function is_utf8($str)
    {
        if (!self::is_empty($str)) return FALSE;
        return (preg_match("/^([" . chr(228) . "-" . chr(233) . "]{1}[" . chr(128) . "-" . chr(191) . "]{1}[" . chr(128) . "-" . chr(191) . "]{1}){1}/", $str)
            == TRUE || preg_match("/([" . chr(228) . "-" . chr(233) . "]{1}[" . chr(128) . "-" . chr(191) . "]{1}[" . chr(128) . "-" . chr(191) . "]{1}){1}$/", $str)
            == TRUE || preg_match("/([" . chr(228) . "-" . chr(233) . "]{1}[" . chr(128) . "-" . chr(191) . "]{1}[" . chr(128) . "-" . chr(191) . "]{1}){2,}/", $str)
            == TRUE) ? TRUE : FALSE;
    }

    /**
     * 验证长度
     * @param: string $str
     * @param: int $type(方式，默认min <= $str <= max)
     * @param: int $min,最小值;$max,最大值;
     * @param: string $charset 字符
     */
    public function length($str, $type = 3, $min = 0, $max = 0, $charset = 'utf-8')
    {
        if (!self::is_empty($str)) return FALSE;
        $len = mb_strlen($str, $charset);
        switch ($type) {
            case 1: //只匹配最小值
                return ($len >= $min) ? TRUE : FALSE;
                break;
            case 2: //只匹配最大值
                return ($max >= $len) ? TRUE : FALSE;
                break;
            default: //min <= $str <= max
                return (($min <= $len) && ($len <= $max)) ? TRUE : FALSE;
        }
    }

    /**
     * 验证密码
     * @param string $value
     * @param int $length
     * @return boolean
     */
    public function is_password($value, $minLen = 6, $maxLen = 16)
    {
        $match = '/^[\\~!@#$%^&*()-_=+|{}
    ,.?\/:;\'\"\d\w]{' . $minLen . ',' . $maxLen . '}$/';
        $v = trim($value);
        if (empty($v))
            return FALSE;
        return preg_match($match, $v);
    }

    /**
     * 验证用户名
     * @param string $value
     * @param int $length
     * @return boolean
     */
    public function is_username($value, $minLen = 2, $maxLen = 16, $charset = 'ALL')
    {
        if (empty($value))
            return FALSE;
        switch ($charset) {
            case 'EN':
                $match = '/^[_\w\d]{' . $minLen . ',' . $maxLen . '}$/iu';
                break;
            case 'CN':
                $match = '/^[_\x{4e00}-\x{9fa5}\d]{' . $minLen . ',' . $maxLen . '}$/iu';
                break;
            default:
                $match = '/^[\x{4e00}-\x{9fa5}A-Za-z]{' . $minLen . ',' . $maxLen . '}$/iu';
        }
        return preg_match($match, $value);
    }


    /**
     * 匹配日期
     * @param string $value
     */
    public function is_date($str)
    {
        $dateArr = explode("-", $str);
        if (is_numeric($dateArr[0]) && is_numeric($dateArr[1]) && is_numeric($dateArr[2])) {
            if (($dateArr[0] >= 1000 && $dateArr[0] <= 10000) && ($dateArr[1] >= 0 && $dateArr[1] <= 12) && ($dateArr[2] >= 0 && $dateArr[2] <= 31))
                return TRUE;
            else
                return FALSE;
        }
        return FALSE;
    }

    /**
     * 匹配时间
     * @param string $value
     */
    public function is_time($str)
    {
        $timeArr = explode(":", $str);
        if (is_numeric($timeArr[0]) && is_numeric($timeArr[1]) && is_numeric($timeArr[2])) {
            if (($timeArr[0] >= 0 && $timeArr[0] <= 23) && ($timeArr[1] >= 0 && $timeArr[1] <= 59) && ($timeArr[2] >= 0 && $timeArr[2] <= 59))
                return TRUE;
            else
                return FALSE;
        }
        return FALSE;
    }

    /***
     * 验证IP地址
     * @param $ip
     * @return bool
     */
    public function is_ip($ip)
    {
        $exp = "/^[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}$/";
        if (preg_match($exp, $ip)) {
            return TRUE;
        } else {
            return FALSE;
        }

    }

    /***
     * 验证是否由字母数字组成
     * @param $str
     * @param int $minLen
     * @param int $maxLen
     * @return bool
     */
    public function is_num_word($str,$minLen = 2, $maxLen = 16)
    {
        $exp = '/^[a-zA-Z0-9]{' . $minLen . ',' . $maxLen . '}$/';
        if (preg_match($exp, $str)) {
            return TRUE;
        } else {
            return FALSE;
        }

    }

    /***
     * 验证IP是否为一个内网IP 172.16.1[0-9].[0-254]
     * @param $ip
     * @return bool
     */
    public function is_intranet_ip($ip)
    {
        $exp = "/^(172)\.(16)\.(1[0-9]|1)\.(\d{1,3})$/";
        if (preg_match($exp, $ip)) {
            return TRUE;
        } else {
            return FALSE;
        }


    }
    /***
     * 工作单位验证
     * @param $str
     * @return bool
     */
    public function is_department($str)
    {
        if (empty($str))
            return FALSE;
        $match = '/^[\x{4e00}-\x{9fa5}A-Za-z]+$/iu';
        return preg_match($match, $str)? TRUE : FALSE;
    }
}  