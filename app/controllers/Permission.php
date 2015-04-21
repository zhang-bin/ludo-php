<?php
class Permission extends BaseCtrl {
	const RESOURCE_TYPE_MODULE = 1;
	const RESOURCE_TYPE_MENU = 2;

	const DEFAULT_PASSWORD = '123456';

	const USER_ENABLED = 1;
	const USER_DISABLED = 0;

    public function __construct() {
        parent::__construct('Permission');
    }
    
    public function init() {
    	$dao = new PermissionDao();
    	$dao->truncate();
    	$conf = Load::conf('Permission');
    	foreach ($conf as $resource => $operations) {
    		foreach ($operations['operations'] as $operation => $urls) {
    			$dao->insert(array(
                    'resource' => $resource,
                    'operation' => $operation,
                    'type' => self::RESOURCE_TYPE_MODULE
                ));
    		}
    	}
    }
    
    public function index() {
    	$dao = new RoleDao();
		$pager = pager(array(
	    	'base' => 'permission/index',
	    	'cur' => empty($_GET['id']) ? 1 : intval($_GET['id']),
	    	'cnt' => $dao->count('deleted = 0')
		));
		$roles = $dao->findAll('deleted = 0', $pager['rows'], $pager['start']);
		
    	$this->tpl->setFile('role/index')
    			->assign('roles', $roles)
    			->assign('pager', $pager['html'])
				->display();

    }
    
	public function addRole() {
		if (empty($_POST)) {
			list($modules, $menus, $subMenus) = $this->_transformPermissions();
			
			$modulePermissions = array();
			$conf = Load::conf('Permission');
			foreach ($conf as $resource => $operations) {
				foreach ($operations['operations'] as $operation => $urls) {
					$modulePermissions[$operations['name']][$urls['name']] = array('id' => $modules[$resource][$operation]);
				}
			}
			
			$menuPermissions = $subMenuPermissions = array();
			$conf = Load::conf('Menu');
			foreach ($conf as $topMenuId => $menu) {
				if (empty($menus[$topMenuId])) continue;
				$menuPermissions[$menu['name']] = array('id' => $menus[$topMenuId]); 
				foreach ($menu['children'] as $subMenuId => $v) {
					if (empty($subMenus[$topMenuId][$subMenuId])) continue;
					$subMenuPermissions[$menu['name']][$v['name']] = array('id' => $subMenus[$topMenuId][$subMenuId]);
				}
			}
			
	        $this->tpl->setFile('role/change')
	        		->assign('modulePermissions', $modulePermissions)
	        		->assign('menuPermissions', $menuPermissions)
	        		->assign('subMenuPermissions', $subMenuPermissions)
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
				
				foreach ($_POST['permission'] as $permissionId => $v) {
					$permissions[] = array(
                        'roleId' => $roleId,
                        'permissionId' => $permissionId
					);
				}
				$rolePermissionDao->batchInsert($permissions);
				
				$add['id'] = $roleId;
				Log::log(array(
					'name' => 'add role',
					'new' => json_encode(array('role' => $add, 'permission' => $permissions))
				));
				$dao->commit();
                return array(STATUS => SUCCESS, URL => url('permission'));
			} catch (Exception $e) {
				$dao->rollback();
                return array(STATUS => ALERT, MSG => '添加角色失败');
			}
		}
	}
	
	public function changeRole() {
		if(empty($_POST)) {
			$id = intval($_GET['id']);
			$roleDao = new RoleDao();
			$role = $roleDao->fetch($id);
			list($modules, $menus, $subMenus) = $this->_transformPermissions();
			
			$rolePermissions = Factory::dao('RolePermission')->findAllUnique(array('roleId = ?', $id), 'permissionId');
			
			$modulePermissions = array();
			$conf = Load::conf('Permission');
			foreach ($conf as $resource => $operations) {
				foreach ($operations['operations'] as $operation => $urls) {
					$permissionId = $modules[$resource][$operation];
					$checked = $this->_checkPermission($permissionId, $rolePermissions);
					$modulePermissions[$operations['name']][$urls['name']] = array('id' => $permissionId, 'checked' => $checked);
				}
			}
			
			$menuPermissions = $subMenuPermissions = array();
			$conf = Load::conf('Menu');
			foreach ($conf as $topMenuId => $menu) {
				$permissionId = $menus[$topMenuId];
				if (empty($permissionId)) continue;
				$checked = $this->_checkPermission($permissionId, $rolePermissions);
				$menuPermissions[$menu['name']] = array('id' => $menus[$topMenuId], 'checked' => $checked);
				
				foreach ($menu['children'] as $subMenuId => $v) {
					$permissionId = $subMenus[$topMenuId][$subMenuId];
					if (empty($permissionId)) continue;
					$checked = $this->_checkPermission($permissionId, $rolePermissions);
					$subMenuPermissions[$menu['name']][$v['name']] = array('id' => $subMenus[$topMenuId][$subMenuId], 'checked' => $checked);
				}
			}
			
			$this->tpl->setFile('role/change')
					->assign('modulePermissions', $modulePermissions)
					->assign('menuPermissions', $menuPermissions)
					->assign('subMenuPermissions', $subMenuPermissions)
					->assign('role', $role)
					->display();
		} else {
			$dao = new RoleDao();
			$rolePermissionDao = new RolePermissionDao();
			try {
				$dao->beginTransaction();
				$id = intval($_POST['id']);
				$old = $dao->fetch($id);
				
				$add['role'] = trim($_POST['role']);
				$add['descr'] = trim($_POST['descr']);
				$dao->update($id, $add);
				
				foreach ($_POST['permission'] as $permissionId => $v) {
					$permissions[] = array(
                        'roleId' => $id,
                        'permissionId' => $permissionId
					);
				}
				$oldPermissions = $rolePermissionDao->findAll(array('roleId = ?', $id));
				$rolePermissionDao->deleteWhere('roleId = ?', array($id));
				$rolePermissionDao->batchInsert($permissions);
				
				Log::log(array(
					'name' => 'change role',
					'new' => json_encode(array('role' => $add, 'permission' => $permissions)),
					'old' => json_encode(array('role' => $old, 'permission' => $oldPermissions))
				));
				$dao->commit();
				unset($_SESSION[USER]['formId']);
                return array(STATUS => SUCCESS, URL => url('permission'));
			} catch (Exception $e) {
				$dao->rollback();
                return array(STATUS => ALERT, MSG => '修改角色失败');
			}
		}
	}
		
	public function delRole() {
		$id = intval($_GET['id']);
		$dao = new RoleDao();
		try {
			$dao->beginTransaction();
			$dao->update($id, array('deleted' => 1));
			Log::log(array(
				'name' => 'del role',
				'old' => $id
			));
			$dao->commit();
            return array(STATUS => SUCCESS, URL => url('permission'));
		} catch (Exception $e) {
			$dao->rollback();
            return array(STATUS => ALERT, MSG => '删除角色失败');
		}
	}
	
    public function permissions() {
        $roleId = intval($_GET['id']);
        $role = Factory::dao('role')->fetch($roleId);
        
       	list($modules, $menus, $subMenus) = $this->_transformPermissions();
        
        $rolePermissions = Factory::dao('rolePermission')->findAllUnique(array('roleId = ?', $roleId), 'permissionId');
        	
        $modulePermissions = array();
        $conf = Load::conf('Permission');
        foreach ($conf as $resource => $operations) {
        	foreach ($operations['operations'] as $operation => $urls) {
        		$permissionId = $modules[$resource][$operation];
        		if (!$this->_checkPermission($permissionId, $rolePermissions)) continue;
        		
        		$modulePermissions[$operations['name']][] = $urls['name'];
        	}
        }
        
        $menuPermissions = array();
        $conf = Load::conf('Menu');
        foreach ($conf as $topMenuId => $menu) {
        	$permissionId = $menus[$topMenuId];
        	if (!$this->_checkPermission($permissionId, $rolePermissions)) continue;
        
        	foreach ($menu['children'] as $subMenuId => $v) {
        		$permissionId = $subMenus[$topMenuId][$subMenuId];
        		if (!$this->_checkPermission($permissionId, $rolePermissions)) continue;
        		$menuPermissions[$menu['name']][] = $v['name'];
        	}
        }

        $this->tpl->setFile('role/view')
        		->assign('modulePermissions', $modulePermissions)
				->assign('menuPermissions', $menuPermissions)
        		->assign('role', $role)
        		->display();
    }
    
	/**
	 * 查看用户信息
	 * added by sarah
	 */
    public function user(){
    	$roles = Factory::dao('role')->findAll('deleted = 0');
    	
    	$condition = 'deleted = 0';
    	$params = array();

    	if (empty($_GET['roleId'])) {
    		$dao = new UserDao();
    		$cnt = $dao->count($condition, $params);
    	} else {
    		$condition .= ' and roleId = ?';
    		$params[] = intval($_GET['roleId']);
    		$dao = new UserRoleDao();
    		$cnt = $dao->hasA('User')->count($condition, $params);
    	}

    	$pager = pager(array(
    			'base' => 'permission/userTbl'.$this->resetGet(),
    			'cur'  => empty($_GET['pager']) ? 1 : intval($_GET['pager']),
    			'cnt'  => $cnt
    	));
    	if (empty($_GET['roleId'])) {
    		$users = $dao->findAll(array($condition, $params), $pager['rows'], $pager['start'], 'createTime desc');
    	} else {
	    	$users = $dao->hasA('User', 'User.*')->findAll(array($condition, $params), $pager['rows'], $pager['start'], 'createTime desc');
    	}
        $this->tpl->setFile('user/index')
            ->assign('roles', $roles)
            ->assign('users', $users)
            ->assign('pager', $pager['html'])
            ->assign('roleId', intval($_GET['roleId']))
            ->display();
    }

    public function addUser() {
    	if (empty($_POST)) {
    		$_SESSION[USER]['formId'] = uniqid(time());
    		$roles = Factory::dao('role')->findAll('deleted = 0');
    		$this->tpl->setFile('user/change')
    				->assign('roles', $roles)
    				->assign('userRoles', array())
    				->display();
    	} else {
    		if (empty($_SESSION[USER]['formId']) || $_SESSION[USER]['formId'] != trim($_POST['uniqueFormId'])) die;
    		$dao = new UserDao();
    		$userRoleDao = new UserRoleDao();
    		$add = $_POST;
    		$add['password'] = password_hash(self::DEFAULT_PASSWORD, PASSWORD_DEFAULT);
    		try {
    			$dao->beginTransaction();
    			$add['createTime'] = date(TIME_FORMAT);
    			$add['createUserId'] = $_SESSION[USER]['id'];
    			$userId = $dao->insert($add);
				$userRole = array();
    			if (!empty($_POST['role'])) {
	    			foreach ($_POST['role'] as $roleId) {
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
    			unset($_SESSION[USER]['formId']);
				return array(STATUS => SUCCESS, URL => url('permission/users'));
    		} catch (Exception $e) {
    			$dao->rollback();
				return array(STATUS => ALERT, MSG => '添加用户失败');
    		}
    	}
    }
    
    public function changeUser() {
    	if (empty($_POST)) {
    		$_SESSION[USER]['formId'] = uniqid(time());
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
    		if (empty($_SESSION[USER]['formId']) || $_SESSION[USER]['formId'] != trim($_POST['uniqueFormId'])) die;
    		$dao = new UserDao();
    		$userRoleDao = new UserRoleDao();
    		$add = $_POST;
    		$id = intval($_POST['id']);
    		try {
    			$dao->beginTransaction();
    			$old = $dao->fetch($id);
    			
    			$dao->update($id, $add);
    			
    			$oldRole = $userRoleDao->findAll(array('userId = ?', $id));
    			$userRoleDao->deleteWhere('userId = ?', array($id));
				$userRole = array();
    			if (!empty($_POST['role'])) {
	    			foreach ($_POST['role'] as $roleId) {
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
    			unset($_SESSION[USER]['formId']);
				return array(STATUS => SUCCESS, URL => url('permission/users'));
    		} catch (Exception $e) {
    			$dao->rollback();
				return array(STATUS => ALERT, MSG => '修改用户失败');
    		}
    	}
    }
    
    public function changePassword() {
    	if (empty($_POST)) {
    		$_SESSION[USER]['formId'] = uniqid(time());
    		$id = intval($_GET['id']);
    		$dao = new UserDao();
    		$user = $dao->fetch($id);
    		$this->tpl->setFile('user/changePassword')
    		->assign('user', $user)
    		->display();
    	} else {
    		if (empty($_SESSION[USER]['formId']) || $_SESSION[USER]['formId'] != trim($_POST['uniqueFormId'])) die;
    		$dao = new UserDao();
    		$id = intval($_POST['id']);
    		$new = trim($_POST['newPassword']);
			$password2 = trim($_POST['password2']);
			if ($new != $password2) return array(STATUS => ALERT, MSG => '密码不一致');;
			
			try {
				$dao->beginTransaction();
				$dao->update($id, array('password' => password_hash($new, PASSWORD_DEFAULT)));
				Log::log(array(
					'name' => 'change password',
					'new' => $id
				));
				$dao->commit();
				unset($_SESSION[USER]['formId']);
				return array(STATUS => SUCCESS, URL => url('permission/users'));
			} catch (Exception $e) {
				$dao->rollback();
				return array(STATUS => ALERT, MSG => '修改密码失败');
			}
    	}
    }
    
    public function delUser() {
    	$id = intval($_GET['id']);
    	$dao = new UserDao();
    	try {
    		$dao->beginTransaction();
    		$dao->update($id, array('deleted' => 1));
    		Log::log(array(
    			'name' => 'del user',
    			'old' => $id
    		));
    		$dao->commit();
			return array(STATUS => SUCCESS, URL => url('permission/users'));
    	} catch (Exception $e) {
    		$dao->rollback();
			return array(STATUS => ALERT, MSG => '删除用户失败');
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
    	$id = intval($_GET['id']);
    	$dao = new UserDao();
    	try {
    		$dao->beginTransaction();
    		$dao->update($id, array('enabled' => self::USER_DISABLED));
    		Log::log(array(
	    		'name' => 'disable user',
	    		'old' => $id
    		));
    		$dao->commit();
			return array(STATUS => SUCCESS, URL => url('permission/users'));
    	} catch (Exception $e) {
    		$dao->rollback();
			return array(STATUS => ALERT, MSG => '操作失败');
    	}
    }
    
    public function enableUser() {
    	$id = intval($_GET['id']);
    	$dao = new UserDao();
    	try {
    		$dao->beginTransaction();
    		$dao->update($id, array('enabled' => self::USER_ENABLED));
    		Log::log(array(
	    		'name' => 'enable user',
	    		'old' => $id
    		));
    		$dao->commit();
			return array(STATUS => SUCCESS, URL => url('permission/users'));
    	} catch (Exception $e) {
    		$dao->rollback();
			return array(STATUS => ALERT, MSG => '操作失败');
    	}
    }
    
    private function _transformPermissions() {
    	$permissions = Factory::dao('permission')->fetchAll();
    	$modules = $menus = $subMenus = array();
    	foreach ($permissions as $permission) {
    		switch ($permission['type']) {
    			case self::RESOURCE_TYPE_MENU:
    				if (is_null($permission['operation'])) {//一级菜单
    					$menus[$permission['resource']] = $permission['id'];
    				} else {
    					$subMenus[$permission['resource']][$permission['operation']] = $permission['id'];
    				}
    				break;
    			case self::RESOURCE_TYPE_MODULE:
    				$modules[$permission['resource']][$permission['operation']] = $permission['id'];
    				break;
    			default:
    				break;
    		}
    	}
    	return array($modules, $menus, $subMenus);
    }
    
    private function _checkPermission($permissionId, $permissions) {
    	$checked = false;
    	if (in_array($permissionId, $permissions)) $checked = true;
    	return $checked;
    }
    
    function beforeAction($action) {
        $this->illegalRequest();
        $this->admin();
    }
}