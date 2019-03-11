<?php
class User extends BaseCtrl
{
    public function __construct()
    {
        parent::__construct('User');
    }

    public function index()
    {
    	if (!logined()) {
    		$this->tpl->setFile('user/login')->assign('jurl', trim($_GET['jurl']))->display();
    	} else {
			redirect('index');
    	}
    }

    public function login()
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
            $_SESSION[USER]['permissions'] = RoleModel::parsePermissions($user['id']);
            csrf_token();//生成token
			unset($_POST['password']);
			Log::log(array(
				'name' => 'User Login',
			));
			if (!empty($_POST['jurl']) ) {
                redirectOut($_POST['jurl']);
			}
    		redirect();
    	}
    }

    /**
     * 用户首次登录修改密码
     */
    public function changePassword()
    {
        if (empty($_POST)) {
            $this->tpl->setFile('user/changePassword')
                ->assign('url', url('user/changePassword'))
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

            $add['password'] = password_hash($newPassword, PASSWORD_DEFAULT);
            $_SESSION[USER]['first'] = $add['first'] = 0;

            try {
                $dao->beginTransaction();
                $dao->update($id, $add);
                Log::log(array(
                    'name' => 'change userPassword',
                    'new' => $id
                ));
                $dao->commit();
                redirect('index/home');
            } catch (Exception $e) {
                $dao->rollback();
                return $this->alert('修改用户密码失败');
            }
        }
    }

    public function logout()
    {
        unset($_SESSION);
        session_destroy();
        redirect();
    }

    public function beforeAction($action)
    {
        return true;
	}
}