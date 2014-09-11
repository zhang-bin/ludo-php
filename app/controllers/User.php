<?php
class User extends LdBaseCtrl {
    function __construct() {
        parent::__construct('User');
    }
	
    function index() {
    	if (!self::logined()) {
    		$this->tpl->setFile('user/login')->assign('jurl', trim($_GET['jurl']))->display();
    	} else {
			redirect('index');
    	}
    }

    function login(){
    	$dao = new UsersDao();
    	if (empty($_POST)){
			$this->tpl->setFile('user/login')->display();
    	} else {
    		$uname = Filter::str($_POST['uname']);
    		if (empty($uname)) return ALERT.'|'.LG_USER_UNAME_EMPTY;

    		$password = Filter::str($_POST['password']);
    		if (empty($password)) return ALERT.'|'.LG_USER_PASSWORD_EMPTY;
    		
    		list($exist, list($userId, $nickname,$vendorId,$stationId,$usergroup,$timezone, $online)) = $dao->existsRow('uname=? and password=?', 
    				array($uname, md5($password)),'id, nickname,vendorId,stationId,usergroupId,timezone, online');
    	//	if ($online == 1) return ALERT.'|'.LG_USER_LOGIN_REPEAT;

    		$dao->update($userId, array('online' => 1, 'sessionId' => session_id()));
    		
    		if (!$exist) return ALERT.'|'.LG_USER_LOGIN_FAILED;	
    		$_SESSION[USER]['id'] = $userId;
    		$_SESSION[USER]['uname'] = $uname;
    		$_SESSION[USER]['nickname'] = $nickname;
    		$_SESSION[USER]['vendorId'] = $vendorId;
    		$_SESSION[USER]['stationId'] = $stationId;
			$_SESSION[USER]['usergroup'] = $usergroup;
			$timezoneOffset = intval($_POST['timezoneOffset']);
			if ($timezoneOffset >= 0) $timezoneOffset = '+'.$timezoneOffset;
			$_SESSION[USER]['timezone'] = 'Etc/GMT'.strval($timezoneOffset);
			$_SESSION[USER]['timezoneOffset'] = trim($timezoneOffset*-1);
			if ($usergroup == 1) $_SESSION[USER]['isAdmin'] = 1;
			Logger::log(array(
				'name' => 'User Login',
			));
			$_SESSION[USER]['station'] = LdFactory::dao('Station')->fetchColumn($stationId, 'name');
			$_SESSION[USER]['timezoneOffsetVendor'] = LdFactory::dao('vendor')->fetchColumn($vendorId, 'timezoneOffset');

    		if (isset($_COOKIE['lang'])) {
    			$language = $_COOKIE['lang'];
    		} else {
    			$language = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
    		}
    		
    		if (false !== strstr($language, 'zh')) {
    			$_SESSION[USER]['isChinese'] = true;
    		} else {
    			$_SESSION[USER]['isChinese'] = false;
    		}
    		return redirect();
    	}
    }
    
    static function logined() {
    	return !empty($_SESSION[USER]);
    }
    
	/**
	 * redirect to login page with jurl
	 *
	 * @param String $jurl innerUrl eg.:user/change_password
	 * @param bool $isOuterJurl whether the jurl is an outter url
	 */
	static function gotoLogin($jurl = '', $isOuterJurl=false) {
		if (empty($jurl)) {
			$jurl = currUrl();
			$isOuterJurl = true;
		}
		$jurl = $isOuterJurl ? '?jurl='.urlencode($jurl) : '?jurl='.urlencode(url($jurl));
		return redirect('user/'.$jurl);
	}
	
	function logout() {
		$dao = new UsersDao();
		$dao->update($_SESSION[USER]['id'], array('online' => 0));
		unset($_SESSION);
		session_destroy();
		redirect();
	}
	
	public static function can($action = CURRENT_ACTION) {
		if ($_SESSION[USER]['isAdmin']) return true;
		$permissions = Load::conf('permission');
		$group = $_SESSION[USER]['usergroup'];
		if (!isset($permissions[$group][lcfirst(CURRENT_CONTROLLER)])) return false;
		if ($permissions[$group][lcfirst(CURRENT_CONTROLLER)] == '*') return true;//表示所有操作都能执行
		if (in_array($action, $permissions[$group][lcfirst(CURRENT_CONTROLLER)])) return true;
		return false;
	}
	
	function beforeAction($action) {
		$needValidate = array('add', 'modifyUser', 'delUser', 'userList');
		if (!in_array($action, $needValidate)) return;
		if (!User::logined()) return User::gotoLogin();
		if (!User::can()) redirect('error/accessDenied');
	}
}