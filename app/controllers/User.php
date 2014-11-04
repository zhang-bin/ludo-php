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
    	$dao = new UserDao();
    	if (empty($_POST)){
			$this->tpl->setFile('user/login')->display();
    	} else {
            $username = Filter::str($_POST['username']);
    		$password = Filter::str($_POST['password']);
            list ($exist, $user) = $dao->existsRow('username = ? and password = ?', array($username, md5($password)));
    		if (!$exist) return array(STATUS => ALERT, MSG => '用户名或密码错误');
    		$_SESSION[USER]['id'] = $user['id'];
    		$_SESSION[USER]['username'] = $user['username'];
    		$_SESSION[USER]['nickname'] = $user['nickname'];
            $_SESSION[USER]['isAdmin'] = $user['isAdmin'] ? true : false;
			Logger::log(array(
				'name' => 'User Login',
			));
    		return redirect();
    	}
    }
    
    static function logined() {
    	return !empty($_SESSION[USER]);
    }
    
    /**
     * redirect to login page with jurl
     *
     * @param string $jurl
     * @param bool $isOuterJurl
     * @return string
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
        return true;
	}
}