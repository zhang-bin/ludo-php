<?php
/*
+-------------------------------------------------------------------------------
| LudoPHP 1.0
| =====================================================
| Author: Libok.Zhou <libkhorse@gmail.com>
| Home  : http://libk.8800.org
| Copyright (C)2004 - 2005 LdFirm All Rights Reserved.
| License: {LdLicense}
+-------------------------------------------------------------------------------
| 项目相关的校验
+-------------------------------------------------------------------------------
*/
class Validator extends LdValidator {
	public static function uname($data) {
		return !empty($data) && preg_match('/^[a-zA-Z][\w\.]{6,16}$/', $data);
	}
	public static function password($data) {
		return !empty($data) && preg_match('/^.{6,16}$/', $data);
	}
	public static function nickname($data) {
		return self::range($data, 3, 20);
	}
	public static function location($data) {
		return self::chinese($data);
	}
	public static function type($data) {
		return !empty($data) && preg_match('/^[a-zA-Z-]+$/', $data);
	}
	public static function hostname($data) {
		return !empty($data) && preg_match('/^[a-zA-Z0-9\-]+$/', $data);
	}
	public static function hostname_wildcard($data) {
		return !empty($data) && preg_match('/^[a-zA-Z0-9\-\?\*]+$/', $data);
	}
	public static function recordFqdn($data) {
		if (empty($data)) return false;
		if (strpos($data, '.') === false) {
			return ctype_alnum($data);
		} else {
			if (!preg_match('/[a-z0-9-\.]+/i', $data)) return false;
			if (substr($data, -1) != '.') return false;
		}
		return true;
	}

	public static function captcha($data) {
		include_once LD_UTIL_PATH.'/captcha/Captcha'.php;
		$image = new Captcha();
		return $image->check($data);
	}
	public static function realName($data) {
		return self::range($data, 4, 20) && self::chinese($data);
	}
	public static function realId($data) {
		return self::idCard($data);
	}
	public static function msn($data) {
		return parent::email($data);
	}
	public static function phone($data) {
		return !empty($data) && preg_match('/^(13|15|18)[0-9]{9}$/', $data);
	}
	public static function address($data) {
		return self::minlength($data, 10) &&  preg_match('/^[\x80-\xff]{10}[\x80-\xff\(\).-]+$/', $data);
	}
}
?>