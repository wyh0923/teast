<?php
/**
 * Created by PhpStorm.
 * User: WKF
 * Date: 16-8-7
 * Time: 下午5:06
 */


/**
 * Class Crypt_3Des_Ecb
 * 加密算法：3DES
 * 工作模式：ECB
 * 填充模式：PKCS5
 *
 * @package Core
 */
Class Crypt_3des_ecb
{
    /**
     * @var string
     */
    var $key = '';

    /**
     * @param $key
     */
    function __construct($data)
    {
        $this->key = $data['key'];
    }

    /**
     * 加密数据
     *
     * @param $data
     * @return string
     */
    function encrypt($data)
    {
        $size = mcrypt_get_block_size(MCRYPT_3DES, 'ecb');
        $data = $this->pkcs5_pad($data, $size);
        $key = str_pad($this->key, 24, '0');
        $key = substr($key, 0, 24);
        $td = mcrypt_module_open(MCRYPT_3DES, '', 'ecb', '');
        $iv = mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
        mcrypt_generic_init($td, $key, $iv);
        $encrypted = mcrypt_generic($td, $data);
        mcrypt_generic_deinit($td);
        mcrypt_module_close($td);
        return base64_encode($encrypted);
    }

    /**
     * 解密数据
     *
     * @param $data
     * @return bool|string
     */
    function decrypt($data)
    {
        $data = base64_decode($data);
        $key = str_pad($this->key, 24, '0');
        $key = substr($key, 0, 24);
        $td = mcrypt_module_open(MCRYPT_3DES, '', 'ecb', '');
        $iv = mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
        mcrypt_generic_init($td, $key, $iv);
        $decrypted = mdecrypt_generic($td, $data);
        mcrypt_generic_deinit($td);
        mcrypt_module_close($td);
        $result = $this->pkcs5_unpad($decrypted);
        return $result;
    }

    /**
     * 填充加密后数据
     *
     * @param $text
     * @param $blocksize
     * @return string
     */
    function pkcs5_pad($text, $blocksize)
    {
        $pad = $blocksize - (strlen($text) % $blocksize);
        return $text . str_repeat(chr($pad), $pad);
    }

    /**
     * 去除填充字符
     *
     * @param $text
     * @return bool|string
     */
    function pkcs5_unpad($text)
    {
        $pad = ord($text{strlen($text) - 1});
        if ($pad > strlen($text)) {
            return FALSE;
        }
        if (strspn($text, chr($pad), strlen($text) - $pad) != $pad) {
            return FALSE;
        }
        return substr($text, 0, -1 * $pad);
    }

}