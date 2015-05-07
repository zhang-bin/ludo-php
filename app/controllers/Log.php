<?php
class Log extends BaseCtrl {
	public function __construct() {
		parent::__construct('Log');
	}

	/**
	 * log params, it's an array
	 *
	 * @var array
	 */
	public static $logData = array();

	public static function log($arr, $autoWrite=true) {
		if (empty(self::$logData)) {
			self::$logData = array(
				'userId' => $_SESSION[USER]['id'] ? $_SESSION[USER]['id'] : 0,
				'uname' => $_SESSION[USER]['uname'],
				'createTime' => date(TIME_FORMAT),
				'ip' => realIp(),
				'ctrl' => CURRENT_CONTROLLER,
				'act' => CURRENT_ACTION,
				'url' => currUrl(),
				'httpReferer' => $_SERVER['HTTP_REFERER'],
				'userAgent' => $_SERVER['HTTP_USER_AGENT'],
				'post'  	=> json_encode($_POST),
                'get'       => json_encode($_GET),
				'session'	=> json_encode($_SESSION),
				'cookie'	=> json_encode($_COOKIE),
                'success'   => 1
			);
			if (realIp() != $_SERVER['REMOTE_ADDR']) {
				$cip = getenv('HTTP_CLIENT_IP') ? 'HTTP_CLIENT_IP:'.getenv('HTTP_CLIENT_IP') : '';
				$xip = getenv('HTTP_X_FORWARDED_FOR') ? ', HTTP_X_FORWARDED_FOR:'.getenv('HTTP_X_FORWARDED_FOR') : '';
				$rip = getenv('REMOTE_ADDR') ? ', getenv(REMOTE_ADDR):'.getenv('REMOTE_ADDR') : '';
				$srip = $_SERVER['REMOTE_ADDR'] ? ', SERVER[REMOTE_ADDR]'.$_SERVER['REMOTE_ADDR'] : '';

				self::$logData['proxyIp'] = $cip.$xip.$rip.$srip;
			}
		}
		if (!empty($arr)) {
			self::$logData = array_merge(self::$logData, $arr);
		}
		if ($autoWrite) self::write();
	}

	static function write() {
		if (!empty(self::$logData)) {
			Factory::dao('log')->add(self::$logData);
			self::$logData = array();
		}
	}
}