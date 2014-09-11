<?php
class Crypter {
	/**
	 * Encrypt the given String with a private key.
	 * 
	 * @param String $str string to be encoded
	 * @param String $key the private key
	 */
    public static function encrypt($str, $mode='ecb', $algorithm='rijndael-128') {
    	if (empty($str)) return;
        /* 开启加密算法/ */
        $td = mcrypt_module_open($algorithm, '', $mode, '');
        /* 建立 IV，并检测 key 的长度 */
        $iv = mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
        $ks = mcrypt_enc_get_key_size($td);
        /* 生成 key */
        $keystr = substr(md5(API_SALT), 0, $ks);
        /* 初始化加密程序 */
        mcrypt_generic_init($td, $keystr, $iv);
        /* 加密, $encrypted 保存的是已经加密后的数据 */
        $encrypted = mcrypt_generic($td, $str);
        /* 关闭解密句柄，并关闭模块 */
        mcrypt_generic_deinit($td);
        mcrypt_module_close($td);
        /* 转化为16进制 */
        $hexdata = bin2hex($encrypted);
        //返回
        return $hexdata;
    }

	/**
	 * Decrypt the given String with a private key.
	 * 
	 * @param String $str string to be encoded
	 * @param String $key the private key
	 */
    public static function decrypt($str, $mode='ecb', $algorithm='rijndael-128') {
    	if (empty($str)) return;
        /* 开启加密算法/ */
        $td = mcrypt_module_open($algorithm, '', $mode, '');
        /* 建立 IV，并检测 key 的长度 */
        $iv = mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
        $ks = mcrypt_enc_get_key_size($td);
        /* 生成 key */
        $keystr = substr(md5(API_SALT), 0, $ks);
        /* 初始化加密模块，用以解密 */
        mcrypt_generic_init($td, $keystr, $iv);
        /* 解密 */
        $encrypted = pack( "H*", $str);
        $decrypted = mdecrypt_generic($td, $encrypted);
        /* 关闭解密句柄，并关闭模块 */
        mcrypt_generic_deinit($td);
        mcrypt_module_close($td);
        /* 返回原始字符串 */
        return trim($decrypted);
    }
}
?>