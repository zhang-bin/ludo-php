<?php

namespace App\Controllers;

use App\Daos\UserDao;
use App\Models\LogModel;
use Ludo\Database\QueryException;
use Ludo\Support\Facades\Lang;
use Ludo\Support\Filter;
use App\Models\RoleModel;

class User extends BaseCtrl
{
    public function __construct()
    {
        parent::__construct('User');
    }

    public function login()
    {
        $dao = new UserDao();
        if (empty($_POST)) {
            $this->tpl->setFile('user/login')->display();
        } else {
            $username = Filter::str($_POST['username']);
            $password = Filter::str($_POST['password']);
            $err = Lang::get('base.username_or_password_wrong');
            [$exist, $user] = $dao->existsRow('username = ? and deleted = 0 and enabled = 1', [$username]);
            if (!$exist) {
                return $this->alert($err);
            }

            if (!password_verify($password, $user['password'])) {
                return $this->alert($err);
            }

            $_SESSION[USER]['id'] = $user['id'];
            $_SESSION[USER]['username'] = $user['username'];
            $_SESSION[USER]['nickname'] = $user['nickname'];
            $_SESSION[USER]['isAdmin'] = $user['isAdmin'] ? true : false;
            [$_SESSION[USER]['permission'], $_SESSION[USER]['menu']] = RoleModel::parsePermissions($user['id']);
            unset($_POST['password']);

            LogModel::log('user login');

            if (isset($_POST['callback'])) {
                redirectOut($_POST['callback']);
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
                ->assign('user', (new UserDao())->fetch($_SESSION[USER]['id']))
                ->display();
        } else {
            $dao = new UserDao();
            $id = intval($_POST['id']);
            $newPassword = trim($_POST['newPassword']);
            $confirmPassword = trim($_POST['confirmPassword']);
            if (empty($newPassword)) {
                return $this->alert(Lang::get('user.password_empty'));
            }
            if ($newPassword != $confirmPassword) {
                $this->alert(Lang::get('user.password_not_equal'));
            }

            $add['password'] = password_hash($newPassword, PASSWORD_DEFAULT);
            try {
                $dao->beginTransaction();
                $dao->update($id, $add);

                LogModel::log('user change password');
                $dao->commit();
                return $this->alert(Lang::get('user.change_password_successful'));
            } catch (QueryException $e) {
                $dao->rollback();
                return $this->alert(Lang::get('user.change_password_failed'));
            }
        }
    }

    public function logout()
    {
        unset($_SESSION);
        session_destroy();
        $this->tpl->setFile('user/logout')->display();
    }

    function beforeAction($action)
    {
        return;
    }
}