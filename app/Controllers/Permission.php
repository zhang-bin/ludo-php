<?php

namespace App\Controllers;

use App\Daos\RoleDao;
use App\Daos\RolePermissionDao;
use App\Daos\UserDao;
use App\Daos\UserRoleDao;
use App\Helpers\HtmlWidget;
use App\Helpers\Load;
use App\Models\LogModel;
use Ludo\Database\QueryException;
use Ludo\Support\Facades\Lang;

class Permission extends BaseCtrl
{
    public function __construct()
    {
        parent::__construct('Permission');
    }

    public function index()
    {
        $dao = new RoleDao();
        $total = $dao->count('deleted = 0');
        [$start, $rows, $page] = HtmlWidget::page('permission/index' . $this->resetGet(), $this->currentPage, $total);

        $roles = $dao->findAll('deleted = 0', $rows, $start);

        $this->tpl->setFile('role/index')
            ->assign('roles', $roles)
            ->assign('page', $page)
            ->display();
    }

    public function addRole()
    {
        if (empty($_POST)) {
            $permissions = Load::conf('Permission');

            $this->tpl->setFile('role/change')
                ->assign('permissions', $permissions)
                ->display();
        } else {
            $dao = new RoleDao();
            $rolePermissionDao = new RolePermissionDao();
            try {
                $dao->beginTransaction();
                $add['role'] = trim($_POST['role']);
                $add['descr'] = trim($_POST['descr']);
                $add['createTime'] = date(TIME_FORMAT);
                $add['id'] = $dao->insert($add);

                if (!empty($_POST['permission'])) {
                    $permissions = [];
                    foreach ($_POST['permission'] as $permissionPolicy) {
                        $permissions[] = array(
                            'roleId' => $add['id'],
                            'permissionPolicy' => $permissionPolicy
                        );
                    }
                    $rolePermissionDao->batchInsert($permissions);
                }

                LogModel::log('add role', [
                    'new' => $add['id'],
                ]);

                $dao->commit();
                return $this->success(url('permission'));
            } catch (QueryException $e) {
                $dao->rollback();
                return $this->alert(Lang::get('user.role_add_failed'));
            }
        }
    }

    public function changeRole()
    {
        if (empty($_POST)) {
            $id = intval($_GET['id']);
            $roleDao = new RoleDao();
            $role = $roleDao->fetch($id);
            $role['permissionPolicy'] = (new RolePermissionDao())->findAllUnique(['roleId = ?', [$role['id']]], 'permissionPolicy');

            $permissions = Load::conf('Permission');

            $this->tpl->setFile('role/change')
                ->assign('permissions', $permissions)
                ->assign('role', $role)
                ->display();
        } else {
            $dao = new RoleDao();
            $rolePermissionDao = new RolePermissionDao();
            try {
                $dao->beginTransaction();
                $id = intval($_POST['id']);
                $add['role'] = trim($_POST['role']);
                $add['descr'] = trim($_POST['descr']);
                $dao->update($id, $add);

                if (!empty($_POST['permission'])) {
                    $permissions = [];
                    foreach ($_POST['permission'] as $permissionPolicy) {
                        $permissions[] = array(
                            'roleId' => $id,
                            'permissionPolicy' => $permissionPolicy
                        );
                    }
                    $rolePermissionDao->deleteWhere('roleId = ?', array($id));
                    $rolePermissionDao->batchInsert($permissions);
                }

                LogModel::log('change role', [
                    'new' => $id,
                ]);

                $dao->commit();
                return $this->success(url('permission'));
            } catch (QueryException $e) {
                $dao->rollback();
                return $this->alert(Lang::get('user.role_change_failed'));
            }
        }
    }

    public function delRole()
    {
        $id = intval($_GET['id']);
        $dao = new RoleDao();
        try {
            $dao->beginTransaction();
            $dao->update($id, array('deleted' => 1));

            LogModel::log('delete role', [
                'new' => $id,
            ]);

            $dao->commit();
            return $this->success(url('permission'));
        } catch (QueryException $e) {
            $dao->rollback();
            return $this->alert(Lang::get('user.role_delete_failed'));
        }
    }

    public function viewRole()
    {
        $id = intval($_GET['id']);
        $roleDao = new RoleDao();
        $role = $roleDao->fetch($id);
        $role['permissionPolicy'] = (new RolePermissionDao())->findAllUnique(['roleId = ?', [$role['id']]], 'permissionPolicy');

        $permissions = Load::conf('Permission');

        $this->tpl->setFile('role/view')
            ->assign('permissions', $permissions)
            ->assign('role', $role)
            ->display();
    }

    public function user()
    {
        $dao = new UserDao();
        $total = $dao->count('deleted = 0');
        [$start, $rows, $page] = HtmlWidget::page('permission/user' . $this->resetGet(), $this->currentPage, $total);

        $users = $dao->findAll('deleted = 0', $rows, $start);

        $this->tpl->setFile('user/index')
            ->assign('users', $users)
            ->assign('page', $page)
            ->display();
    }

    public function addUser()
    {
        if (empty($_POST)) {
            $roles = (new RoleDao())->findAll('deleted = 0');

            $this->tpl->setFile('user/change')
                ->assign('roles', $roles)
                ->display();
        } else {
            $dao = new UserDao();
            $userRoleDao = new UserRoleDao();
            try {
                $dao->beginTransaction();
                $add['username'] = trim($_POST['username']);
                $add['nickname'] = trim($_POST['nickname']);
                $add['password'] = password_hash('123456', PASSWORD_DEFAULT);
                $add['createTime'] = date(TIME_FORMAT);
                $add['id'] = $dao->insert($add);

                if (!empty($_POST['role'])) {
                    $roles = [];
                    foreach ($_POST['role'] as $roleId) {
                        $roles[] = array(
                            'userId' =>  $add['id'],
                            'roleId' => $roleId
                        );
                    }
                    $userRoleDao->batchInsert($roles);
                }

                LogModel::log('add user', [
                    'new' => $add['id'],
                ]);

                $dao->commit();
                return $this->success(url('permission/user'));
            } catch (QueryException $e) {
                $dao->rollback();
                return $this->alert(Lang::get('user.user_add_failed'));
            }
        }
    }

    public function changeUser()
    {
        if (empty($_POST)) {
            $id = intval($_GET['id']);
            $dao = new UserDao();
            $user = $dao->fetch($id);
            $user['roles'] = (new UserRoleDao())->findAllUnique(array('userId = ?', $id), 'roleId');
            $roles = (new RoleDao())->findAll('deleted = 0');

            $this->tpl->setFile('user/change')
                ->assign('user', $user)
                ->assign('roles', $roles)
                ->display();
        } else {
            $dao = new UserDao();
            $userRoleDao = new UserRoleDao();
            $add['nickname'] = trim($_POST['nickname']);
            $id = intval($_POST['id']);
            try {
                $dao->beginTransaction();
                $dao->update($id, $add);
                $userRoleDao->deleteWhere('userId = ?', array($id));
                if (!empty($_POST['role'])) {
                    $roles = array();
                    foreach ($_POST['role'] as $roleId) {
                        $roles[] = array(
                            'userId' => $id,
                            'roleId' => $roleId
                        );
                    }
                    $userRoleDao->batchInsert($roles);
                }

                LogModel::log('change user', [
                    'new' => $id,
                ]);

                $dao->commit();
                return $this->success(url('permission/user'));
            } catch (QueryException $e) {
                $dao->rollback();
                return $this->alert(Lang::get('user.user_change_failed'));
            }
        }
    }

    public function delUser()
    {
        $id = intval($_GET['id']);
        $dao = new UserDao();
        try {
            $dao->beginTransaction();
            $dao->update($id, array('deleted' => 1));

            LogModel::log('delete user', [
                'new' => $id,
            ]);

            $dao->commit();
            return $this->success(url('permission/user'));
        } catch (QueryException $e) {
            $dao->rollback();
            return $this->alert(Lang::get('user.user_delete_failed'));
        }
    }

    public function viewUser()
    {
        $id = intval($_GET['id']);
        $user = (new UserDao())->fetch($id);
        $userRoles = (new UserRoleDao())->hasA('Role', 'Role.role')->findAll(array('userId = ?', $id));
        $this->tpl->setFile('user/view')
            ->assign('user', $user)
            ->assign('userRoles', $userRoles)
            ->display();
    }
}