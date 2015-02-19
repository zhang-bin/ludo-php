<?php
class User extends BaseCtrl {
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
    	$dao = new AdminDao();
    	if (empty($_POST)){
			$this->tpl->setFile('user/login')->display();
    	} else {
            $username = Filter::str($_POST['username']);
    		$password = Filter::str($_POST['password']);
			$err = '用户名或密码错误';
            list ($exist, $user) = $dao->existsRow('username = ? and deleted = 0 and enabled = 1', array($username));
    		if (!$exist) return array(STATUS => ALERT, MSG => $err);
			if (!password_verify($password, $user['password'])) return array(STATUS => ALERT, MSG => $err);

    		$_SESSION[USER]['id'] = $user['id'];
    		$_SESSION[USER]['username'] = $user['username'];
    		$_SESSION[USER]['nickname'] = $user['nickname'];
            $_SESSION[USER]['isAdmin'] = $user['isAdmin'] ? true : false;
			unset($_POST['password']);
			Log::log(array(
				'name' => 'User Login',
			));
			if (isset($_POST['jurl'])) {
				return redirectOut($_POST['jurl']);
			}
    		return redirect();
    	}
    }
    
    static function logined() {
    	return !empty($_SESSION[USER]);
    }

	public function logout() {
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