<?php
class User extends BaseCtrl
{
    function __construct()
    {
        parent::__construct('User');
    }
	
    function index()
    {
    	if (!logined()) {
    		$this->tpl->setFile('user/login')->assign('jurl', trim($_GET['jurl']))->display();
    	} else {
			redirect('index');
    	}
    }

    function login()
    {
    	$dao = new UserDao();
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
            $_SESSION[USER]['first'] = $user['first'];
            $_SESSION[USER]['isAdmin'] = $user['isAdmin'] ? true : false;
            $_SESSION[USER]['menu'] = RoleModel::parseMenuPermissionsForUser($user['id']);
            $_SESSION[USER]['permissions'] = RoleModel::parseModulePermissionsForUser($user['id']);
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
    
	public function logout()
    {
		unset($_SESSION);
		session_destroy();
		redirect();
	}

    /**
     * 用户首次登录修改密码
     */
    public function changePassword()
    {
        if (empty($_POST)) {
            $this->tpl->setFile('user/changePassword')
                ->assign('user', (new UserDao())->fetch($_SESSION[USER]['id']))
                ->display();
        } else {
            $dao = new UserDao();
            $id = intval($_POST['id']);
            $newPassword = trim($_POST['newPassword']);
            $confirmPassword = trim($_POST['confirmPassword']);
            if (empty($newPassword)) return array(STATUS => ALERT, MSG => '新密码为空');
            if (empty($confirmPassword)) return array(STATUS => ALERT, MSG => '确认密码为空');
            if ($newPassword != $confirmPassword) return array(STATUS => ALERT, MSG => '两次密码不一致');

            $add['password'] = password_hash($newPassword, PASSWORD_DEFAULT, array('salt' => PASSWORD_SALT));
            $_SESSION[USER]['first'] = $add['first'] = 0;

            try {
                $dao->beginTransaction();
                $dao->update($id, $add);
                Log::log(array(
                    'name' => 'change userPassword',
                    'new' => $id
                ));
                $dao->commit();
                return redirect();
            } catch (Exception $e) {
                $dao->rollback();
                return array(STATUS => ALERT, MSG => '修改用户密码失败');
            }
        }
    }

	public static function can($action = CURRENT_ACTION)
    {
		if ($_SESSION[USER]['isAdmin']) return true;
		$permissions = Load::conf('permission');
		$group = $_SESSION[USER]['usergroup'];
		if (!isset($permissions[$group][lcfirst(CURRENT_CONTROLLER)])) return false;
		if ($permissions[$group][lcfirst(CURRENT_CONTROLLER)] == '*') return true;//表示所有操作都能执行
		if (in_array($action, $permissions[$group][lcfirst(CURRENT_CONTROLLER)])) return true;
		return false;
	}
	
	function beforeAction($action)
    {
        return true;
	}
}