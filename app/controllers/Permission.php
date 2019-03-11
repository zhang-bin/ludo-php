<?php
class Permission extends BaseCtrl {
	const DEFAULT_PASSWORD = '123456';

	const USER_ENABLED = 1;
	const USER_DISABLED = 0;

    public function __construct() {
        parent::__construct('Permission');
    }
    
    public function init() {
        $dao = new PermissionDao();
        $permissions = $dao->fetchAll();
        $conf = Load::conf('Permission');

        //检查是否有删除的权限定义
        $formatPermissions = array();
        foreach ($permissions as $permission) {
            if (isset($conf[$permission['resource']]['operations'][$permission['operation']])) {
                $formatPermissions[$permission['resource']][$permission['operation']] = 1;
                continue;
            }

            $dao->delete($permission['id']);
        }

        //检查是否有未添加的权限定义
        foreach ($conf as $resource => $operations) {
            foreach ($operations['operations'] as $operation => $urls) {
                if (isset($formatPermissions[$resource][$operation])) {
                    continue;
                }
                $dao->insert(array('resource' => $resource, 'operation' => $operation));
            }
        }
    }
    
    public function index() {
        $this->init();
    	$this->tpl->setFile('role/index')->display();
    }

    public function roleList() {
        $rows = empty($_REQUEST['limit']) ? 0 : intval($_REQUEST['limit']);
        $start = empty($_REQUEST['page']) ? 0 : ($_REQUEST['page'] - 1) * $_REQUEST['limit'];

        $dao = new RoleDao();
        $data = $dao->findAll('deleted = 0', $rows, $start);
        $count = $dao->count('deleted = 0');
        return $this->response($data, $count);
    }
    
	public function addRole() {
		if (empty($_POST)) {
			$permissionConf = Load::conf('Permission');
            $permissions = $this->transformPermissions();

	        $this->tpl->setFile('role/change')
	        		->assign('permissions', $permissions)
	        		->assign('permissionConf', $permissionConf)
	        		->display();
		} else {
			$dao = new RoleDao();
			$rolePermissionDao = new RolePermissionDao();
			try {
				$dao->beginTransaction();
				$add['role'] = trim($_POST['role']);
				$add['descr'] = trim($_POST['descr']);
				$add['createTime'] = date(TIME_FORMAT);
				$roleId = $dao->insert($add);

                $permissions = array();
                if (!empty($_POST['permission'])) {
                    foreach ($_POST['permission'] as $permissionId => $v) {
                        $permissions[] = array(
                            'roleId' => $roleId,
                            'permissionId' => $permissionId
                        );
                    }
                    $rolePermissionDao->batchInsert($permissions);
                }
				
				$add['id'] = $roleId;
				Log::log(array(
					'name' => 'add role',
					'new' => json_encode(array('role' => $add, 'permission' => $permissions))
				));
				$dao->commit();
                return $this->success(url('permission'));
			} catch (QueryException $e) {
				$dao->rollback();
                return $this->alert('添加角色失败');
			}
		}
	}
	
	public function changeRole() {
		if(empty($_POST)) {
			$id = intval($_GET['id']);
			$roleDao = new RoleDao();
			$role = $roleDao->fetch($id);

            $permissionConf = Load::conf('Permission');
            $permissions = $this->transformPermissions();
			$rolePermissions = Factory::dao('RolePermission')->findAllUnique(array('roleId = ?', $id), 'permissionId');
			foreach ($permissions as $resource => $operations) {
			    foreach ($operations as $operationId) {
                    if (in_array($operationId, $rolePermissions)) {
                        $permissionConf[$resource]['checked'] = 1;
                    }
                }
            }

			$this->tpl->setFile('role/change')
                    ->assign('permissions', $permissions)
                    ->assign('permissionConf', $permissionConf)
                    ->assign('rolePermissions', $rolePermissions)
					->assign('role', $role)
					->display();
		} else {
			$dao = new RoleDao();
			$rolePermissionDao = new RolePermissionDao();
			try {
				$dao->beginTransaction();
				$id = intval($_GET['id']);
				$old = $dao->fetch($id);
				
				$add['role'] = trim($_POST['role']);
				$add['descr'] = trim($_POST['descr']);
				$dao->update($id, $add);

                $permissions = array();
                $oldPermissions = $rolePermissionDao->findAll(array('roleId = ?', $id));
                if (!empty($_POST['permission'])) {
                    foreach ($_POST['permission'] as $permissionId => $v) {
                        $permissions[] = array(
                            'roleId' => $id,
                            'permissionId' => $permissionId
                        );
                    }
                    $rolePermissionDao->deleteWhere('roleId = ?', array($id));
                    $rolePermissionDao->batchInsert($permissions);
                }
				
				Log::log(array(
					'name' => 'change role',
					'new' => json_encode(array('role' => $add, 'permission' => $permissions)),
					'old' => json_encode(array('role' => $old, 'permission' => $oldPermissions))
				));
				$dao->commit();
                return $this->success(url('permission'));
			} catch (QueryException $e) {
				$dao->rollback();
                return $this->alert('修改角色失败');
			}
		}
	}
		
	public function delRole() {
		$id = intval($_POST['id']);
		$dao = new RoleDao();
		try {
			$dao->beginTransaction();
			$dao->update($id, array('deleted' => 1));
			Log::log(array(
				'name' => 'del role',
				'old' => $id
			));
			$dao->commit();
            return $this->success(url('permission'));
		} catch (QueryException $e) {
			$dao->rollback();
            return $this->alert('删除角色失败');
		}
	}
	
    public function viewRole() {
        $roleId = intval($_GET['id']);
        $role = Factory::dao('role')->fetch($roleId);

        $permissionConf = Load::conf('Permission');
        $permissions = $this->transformPermissions();
        $rolePermissions = Factory::dao('RolePermission')->findAllUnique(array('roleId = ?', $roleId), 'permissionId');
        foreach ($permissions as $resource => $operations) {
            foreach ($operations as $operationId) {
                if (in_array($operationId, $rolePermissions)) {
                    $permissionConf[$resource]['checked'] = 1;
                }
            }
        }

        $this->tpl->setFile('role/view')
                ->assign('permissions', $permissions)
                ->assign('permissionConf', $permissionConf)
                ->assign('rolePermissions', $rolePermissions)
                ->assign('role', $role)
        		->display();
    }

    public function userIndex() {
        $this->tpl->setFile('user/index')->display();
    }

    public function userList() {
        $rows = empty($_REQUEST['limit']) ? 0 : intval($_REQUEST['limit']);
        $start = empty($_REQUEST['page']) ? 0 : ($_REQUEST['page'] - 1) * $_REQUEST['limit'];

        $dao = new UserDao();
        $data = $dao->findAll('deleted = 0', $rows, $start);
        $count = $dao->count('deleted = 0');
        return $this->response($data, $count);
    }

    public function addUser() {
    	if (empty($_POST)) {
    		$roles = Factory::dao('role')->findAll('deleted = 0');
    		$this->tpl->setFile('user/change')
    				->assign('roles', $roles)
    				->assign('userRoles', array())
    				->display();
    	} else {
    		$dao = new UserDao();
    		$userRoleDao = new UserRoleDao();
    		$add['username'] = trim($_POST['username']);
    		$add['nickname'] = trim($_POST['nickname']);
            $add['password'] = password_hash(self::DEFAULT_PASSWORD, PASSWORD_DEFAULT);
    		try {
    			$dao->beginTransaction();
    			$add['createTime'] = date(TIME_FORMAT);
    			$add['createUserId'] = $_SESSION[USER]['id'];
    			$userId = $dao->insert($add);
				$userRole = array();
    			if (!empty($_POST['role'])) {
	    			foreach (explodeSafe($_POST['role']) as $roleId) {
	    				$userRole[] = array('userId' => $userId, 'roleId' => $roleId);
	    			}
    				$userRoleDao->batchInsert($userRole);
    			}

    			$add['id'] = $userId;
    			Log::log(array(
    				'name' => 'add user',
    				'new' => json_encode(array('user' => $add, 'role' => $userRole)),
    			));
    			$dao->commit();
				return $this->success(url('permission/userIndex'));
    		} catch (QueryException $e) {
    			$dao->rollback();
				return $this->alert('添加用户失败');
    		}
    	}
    }
    
    public function changeUser() {
    	if (empty($_POST)) {
    		$id = intval($_GET['id']);
    		$dao = new UserDao();
    		$user = $dao->fetch($id);
    		$userRoles = Factory::dao('userRole')->findAllUnique(array('userId = ?', $id), 'roleId');
    		$roles = Factory::dao('role')->findAll('deleted = 0');
    		$this->tpl->setFile('user/change')
                    ->assign('user', $user)
                    ->assign('userRoles', $userRoles)
                    ->assign('roles', $roles)
                    ->display();
    	} else {
    		$dao = new UserDao();
    		$userRoleDao = new UserRoleDao();
            $add['nickname'] = trim($_POST['nickname']);
    		$id = intval($_POST['id']);
    		try {
    			$dao->beginTransaction();
    			$old = $dao->fetch($id);
    			
    			$dao->update($id, $add);
    			
    			$oldRole = $userRoleDao->findAll(array('userId = ?', $id));
    			$userRoleDao->deleteWhere('userId = ?', array($id));
				$userRole = array();
    			if (!empty($_POST['role'])) {
	    			foreach (explodeSafe($_POST['role']) as $roleId) {
	    				$userRole[] = array('userId' => $id, 'roleId' => $roleId);
	    			}
	    			$userRoleDao->batchInsert($userRole);
    			}
    			
    			Log::log(array(
	    			'name' => 'change user',
	    			'new' => json_encode(array('user' => $add, 'role' => $userRole)),
	    			'old' => json_encode(array('old' => $old, 'role' => $oldRole))
    			));
    			$dao->commit();
				return $this->success(url('permission/userIndex'));
    		} catch (QueryException $e) {
    			$dao->rollback();
				return $this->alert('修改用户失败');
    		}
    	}
    }
    
    public function changePassword() {
    	if (empty($_POST)) {
    		$id = intval($_GET['id']);
    		$dao = new UserDao();
    		$user = $dao->fetch($id);
    		$this->tpl->setFile('user/changePassword')
    		        ->assign('user', $user)
                    ->assign('url', url('permission/changePassword'))
    		        ->display();
    	} else {
    		$dao = new UserDao();
    		$id = intval($_POST['id']);
    		$new = trim($_POST['newPassword']);
			$password2 = trim($_POST['confirmPassword']);
			if ($new != $password2) return array(STATUS => ALERT, MSG => '密码不一致');;
			
			try {
				$dao->beginTransaction();
				$dao->update($id, array('password' => password_hash($new, PASSWORD_DEFAULT)));
				Log::log(array(
					'name' => 'change password',
					'new' => $id
				));
				$dao->commit();
				return $this->success(url('permission/userIndex'));
			} catch (QueryException $e) {
				$dao->rollback();
				return $this->alert('修改密码失败');
			}
    	}
    }
    
    public function delUser() {
    	$id = intval($_POST['id']);
    	$dao = new UserDao();
    	try {
    		$dao->beginTransaction();
    		$dao->update($id, array('deleted' => 1));
    		Log::log(array(
    			'name' => 'del user',
    			'old' => $id
    		));
    		$dao->commit();
			return $this->success(url('permission/userIndex'));
    	} catch (QueryException $e) {
    		$dao->rollback();
			return $this->alert('删除用户失败');
    	}
    }
    
    public function viewUser() {
    	$id = intval($_GET['id']);
    	$user = Factory::dao('user')->fetch($id);
    	$userRoles = Factory::dao('userRole')->hasA('Role', 'Role.role')->findAll(array('userId = ?', $id));
    	$this->tpl->setFile('user/view')
    			->assign('user', $user)
    			->assign('userRoles', $userRoles)
    			->display();
    }
    
    public function disabledUser() {
    	$id = intval($_POST['id']);
    	$dao = new UserDao();
    	try {
    		$dao->beginTransaction();
    		$dao->update($id, array('enabled' => self::USER_DISABLED));
    		Log::log(array(
	    		'name' => 'disable user',
	    		'old' => $id
    		));
    		$dao->commit();
			return $this->success(url('permission/userIndex'));
    	} catch (QueryException $e) {
    		$dao->rollback();
			return $this->alert('操作失败');
    	}
    }
    
    public function enableUser() {
    	$id = intval($_POST['id']);
    	$dao = new UserDao();
    	try {
    		$dao->beginTransaction();
    		$dao->update($id, array('enabled' => self::USER_ENABLED));
    		Log::log(array(
	    		'name' => 'enable user',
	    		'old' => $id
    		));
    		$dao->commit();
			return $this->success(url('permission/userIndex'));
    	} catch (QueryException $e) {
    		$dao->rollback();
			return $this->alert('操作失败');
    	}
    }
    
    private function transformPermissions() {
    	$permissions = Factory::dao('permission')->fetchAll();
    	$modules = array();
    	foreach ($permissions as $permission) {
            $modules[$permission['resource']][$permission['operation']] = $permission['id'];
    	}
    	return $modules;
    }

    public function beforeAction($action)
    {
        $this->login();
        $this->admin();
    }
}
