<?php
/*
+-------------------------------------------------------------------------------
| {ProgName}
| =====================================================
| Author: Libok.Zhou <libkhorse@gmail.com>
| Home  : http://libk.8800.org
| Copyright (C)2004 - 2005 LdFirm All Rights Reserved.
| License: {LdLicense}
+-------------------------------------------------------------------------------
|
+-------------------------------------------------------------------------------
*/
class LdValidator {
	public static function email($data) {
		if (empty($data)) return false;
//		return preg_match('/^\w+(?:(?:-\w+)|(?:\.\w+))*@[A-Za-z0-9]+(?:(?:\.|-)[A-Za-z0-9]+)*\.[A-Za-z0-9]+$/', $data);
		return filter_var($data, FILTER_VALIDATE_EMAIL) !== false;
	}
	/**
	 * validate if length of data is between the range( including the min and max value);
	 *
	 * @param String $data
	 * @param int $min
	 * @param int $max
	 * @return bool true if valid
	 */
	public static function range($data, $min, $max) {
		$len = strlen($data);
		return $len >= $min && $len <= $max;
	}
	public static function len($data, $lenth) {
		$len = strlen($data);
		return $len == $lenth;
	}
	public static function minlength($data, $min) {
		return strlen($data) >= $min;
	}
	public static function maxlength($data, $max) {
		return strlen($data) <= $max;
	}
	public static function idCard($data) {
		return IdCard::verify($data);
	}
	public static function chinese($data) {
		if (strtolower(PROGRAM_CHARSET) == 'utf-8') {
			return preg_match('/^[\x{4e00}-\x{9fa5}]+$/u', $data);
		} else {
			return preg_match('/^[\x80-\xff]+$/', $data); //gb2312 Chinese character
		}
	}
	
	public static function postcode($data) {
		return !empty($data) &&  preg_match('/^\d{6}$/', $data);
	}
	
	/**
	 * whether data is an valid ip format
	 * @param String $data ip string
	 * @return bool true for well formated ip, vise versa.
	 */
	public static function ip($data) {
//		return !empty($data) && preg_match('/^(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[1-9])\.(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[1-9]|0)\.(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[1-9]|0)\.(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[0-9])$/', $data);
		if (empty($data)) return false;
		return filter_var($data, FILTER_VALIDATE_IP) !== false;
	}
	
	/**
	 * whether data is an valid private ip
	 * 10.0.0.0 through 10.255.255.255 (10/8) 
	 * 172.16.0.0 through 172.31.255.255 (172.16/12) 
	 * 192.168.0.0 through 192.168.255.255 (192.168/16)
	 * 
	 * default the reserved range 169.254.0.0 through 169.254.255.255 will also include. 
	 * 
	 * @param String $data ip string
	 * @param bool $includeReserved whether to include reserved ip range (169.254.0.0/16), default is true.
	 * @return bool true for private ip, vise versa.
	 */
	public static function privateIp($data, $includeReserved=true) {
		if (!self::ip($data)) return false; //kick non-ip off.
		
		if ($includeReserved) { //private ip + reserved ip.
			return !self::publicIp($data);
		} else { //just private ip. no reserved ip.
			return !self::publicIp($data, false);
		}
	}
	
	/**
	 * whether data is an valid public ip
	 * 
	 * @param String $data ip string
	 * @param bool $noReserved whether to exclude reserved ip range (169.254/16), default is true.
	 * @return bool true for public ip, vise versa.
	 */
	public static function publicIp($data, $noReserved=true) {
		if (!self::ip($data)) return false; //kick non-ip off.
		
		$flag = $noReserved ? FILTER_FLAG_NO_PRIV_RANGE|FILTER_FLAG_NO_RES_RANGE : FILTER_FLAG_NO_PRIV_RANGE;
		return filter_var($data, FILTER_VALIDATE_IP, $flag) !== false ? true : false;
	}
	public static function url($data, $schemeRequired=false, $hostRequired=true, $pathRequired=false, $queryStringRequired=flase) {
		if (empty($data)) return false;
		$flags = 0;
		if ($schemeRequired) $flags |= FILTER_FLAG_SCHEME_REQUIRED; 
		if ($pathRequired) $flags |= FILTER_FLAG_PATH_REQUIRED;
		if ($queryStringRequired) $flags |= FILTER_FLAG_QUERY_REQUIRED;
		if ($hostRequired) $flags |= FILTER_FLAG_HOST_REQUIRED;
		
		return filter_var($data, FILTER_VALIDATE_URL, $flags) !== false;
	}
}
?>
<?
class IdCard {
	private static $province=array(11=>"北京",12=>"天津",13=>"河北",14=>"山西",15=>"内蒙古",21=>"辽宁",22=>"吉林",23=>"黑龙江",31=>"上海",32=>"江苏",33=>"浙江",34=>"安徽",35=>"福建",36=>"江西",37=>"山东",41=>"河南",42=>"湖北",43=>"湖南",44=>"广东",45=>"广西",46=>"海南",50=>"重庆",51=>"四川",52=>"贵州",53=>"云南",54=>"西藏",61=>"陕西",62=>"甘肃",63=>"青海",64=>"宁夏",65=>"新疆",71=>"台湾",81=>"香港",82=>"澳门",91=>"国外");
	
	// 计算身份证校验码，根据国家标准GB 11643-1999
	private static function idcard_verify_number($idcard_base){
		if (strlen($idcard_base) != 17){ return false; }
		// 加权因子
		$factor = array(7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2);
		// 校验码对应值
		$verify_number_list = array('1', '0', 'X', '9', '8', '7', '6', '5', '4', '3', '2');
		$checksum = 0;
		for ($i = 0; $i < strlen($idcard_base); $i++){
			$checksum += substr($idcard_base, $i, 1) * $factor[$i];
		}
		$mod = $checksum % 11;
		$verify_number = $verify_number_list[$mod];
		return $verify_number;
	}
	
	// 将15位身份证升级到18位
	static function idcard_15to18($idcard){
		if (strlen($idcard) != 15){
			return false;
		}else{
			// 如果身份证顺序码是996 997 998 999，这些是为百岁以上老人的特殊编码
			if (array_search(substr($idcard, 12, 3), array('996', '997', '998', '999')) !== false){
				$idcard = substr($idcard, 0, 6) . '18'. substr($idcard, 6, 9);
			}else{
				$idcard = substr($idcard, 0, 6) . '19'. substr($idcard, 6, 9);
			}
		}
		$idcard = $idcard . self::idcard_verify_number($idcard);
		return $idcard;
	}
	// 18位身份证校验码有效性检查
	static function idcard_checksum18($idcard){
		if (strlen($idcard) != 18){ return false; }
		$idcard_base = substr($idcard, 0, 17);
		if (self::idcard_verify_number($idcard_base) != strtoupper(substr($idcard, 17, 1))){
			return false;
		}else{
			return true;
		}
	}
	
	static function getProvince($idcard) {
		$province =  self::$province[substr($idcard, 0, 2)];
		return $province ? $province : false;
	}
	static function getBirthday($idcard) {
		return substr($idcard, 6, 8);
	}
	static function isAdult($idcard) {
		//here pretend idcard is 18 and the format have been validated, or you should call LdValidator::verify first.
		return intval(self::getBirthday($idcard)) <= date('Ymd', strtotime('-18 year')) ? true : false;
	}
	static function verify($idcard) {
		$len = strlen($idcard);
		//校验位数是否为15和18位,是否为全部是数字(+X)的组合
		if ($len == 15) {
			if (!ctype_digit($idcard)) return false;
			$idcard = self::idcard_15to18($idcard);
		} else if($len == 18) {
			if (!preg_match('/\d{17}[0-9x]/i', $idcard)) return false;
		} else {
			return false;
		}
		//校验省份是否合法
		if (!self::getProvince($idcard)) return false;
		
		//校验出生日期是否合法
		$year = substr($idcard, 6, 4);
		$month = substr($idcard, 10, 2);
		$day = substr($idcard, 12, 2);
		if (intval($year) < 1870) return false; //139岁的老人应该没有了吧,如果有也不会上网的 :)
		if (!checkdate($month, $day, $year)) return false;
		
		return self::idcard_checksum18($idcard);
	}
}
?>