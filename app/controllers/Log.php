<?php
class Log extends LdBaseCtrl {
	function __construct() {
		parent::__construct('Log');
	}

	/**
	 * log params, it's an array
	 *
	 * @var array
	 */
	static $log = array();

	static function log($arr, $autoWrite=true) {
		if (empty(self::$log)) {
			self::$log = array(
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

				self::$log['proxyIp'] = $cip.$xip.$rip.$srip;
			}
		}
		if (!empty($arr)) {
			self::$log = array_merge(self::$log, $arr);
		}
		if ($autoWrite) self::write();
	}

	static function write() {
		if (!empty(self::$log)) {
			LdFactory::dao('Log')->add(self::$log);
			self::$log = array();
		}
	}

	static function logPostAction($action, $result, $logData=false) {
		if (empty($_POST)) return; //skip any not post action

		$log = array();

		//==log[uid]
		if (empty(Logger::$log['userId'])) {
			if (User::logined()) {
				$log['userId'] = $_SESSION[USER]['id'];
			} else {
				$log['userId'] = isset($_SESSION[PROCESS]['user']) ? $_SESSION[PROCESS]['user']['id'] : 0;
			}
		}

		//==log[count]
		$actionCnt = CURRENT_CONTROLLER.'_'.$action.'_cnt';	    
		$_SESSION[$actionCnt] = isset($_SESSION[$actionCnt]) ? $_SESSION[$actionCnt]+1 : 1;
		$log['count'] = $_SESSION[$actionCnt];

		//==log[success]
		if (strpos($result, SUCCESS) === 0) {
			unset($_SESSION[$actionCnt]);
			$log['success'] = 1;
		} else {
    		if ($action == 'login') {
    		    $_SESSION['User_last_login_time'] = time();    
    		}
			$log['success'] = 0;
		}

		//==log[uname] and log[data]. Record more information for [not_logined_action]
		if (!User::logined()) {
			$log['uname'] = $_POST['uname'] ? $_POST['uname'] : $_SESSION[PROCESS]['user']['uname'];
		} else {
			$log['uname'] = $_SESSION[USER]['uname'];
		}

		//==log[data]
		if ($logData) {
			$log['desc'] = 'POST:'.print_r($_POST, true).', SESSION:'.print_r($_SESSION, true).', COOKIE:'.print_r($_COOKIE, true);
		}

		//==save log
		Logger::log($log);
	}
	
	function beforeAction($action) {
		if (!User::logined()) return User::gotoLogin();
		if (!User::can()) redirect('error/accessDenied');
	}
}