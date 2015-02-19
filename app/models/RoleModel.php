<?php
/**
 * 用户角色类
 * @author libok
 *
 */
class RoleModel {
	const ROLE_BUSINESS_MANAGER = 1;
	const ROLE_BUSINESS_SUPERVISOR = 2;
	const ROLE_IT_SPECIALIST = 3;
	const ROLE_SERVICE = 4;
	const ROLE_PURCHASE = 5;
	const ROLE_SALES = 6;
	const ROLE_SALES_MANAGER = 7;
	const ROLE_TECHNICAL_SUPPORT = 8;
	const ROLE_BUSINESS_MARKETING = 9;
	
	
	static function parseModulePermissionsForUser($uid) {
		$conf = Load::conf('Permission');
		$roles = LdFactory::dao('UserRole')->hasA('Role')->findAllUnique(array('userId = ? and Role.deleted = 0', $uid), 'roleId');
		if (empty($roles)) return;
		
		$permissions = array();
		foreach ($roles as $role) {
			$permList = LdFactory::dao('RolePermission')->hasA('Permission', 'Permission.resource,Permission.operation')->findAll(array('roleId = ? and Permission.type = ?', array($role, Permission::RESOURCE_TYPE_MODULE)));
			if (empty($permList)) continue;
			foreach($permList as $perm) {
				$urls = $conf[$perm['resource']]['operations'][$perm['operation']]['url'];
				if (!empty($urls)) {
					foreach ($urls as $controller => $actions) {
						if ($actions == '*') {
							$permissions[$controller] = true;
						} else {
							if (is_array($actions)) {
								foreach ($actions as $action) {
									$permissions[$controller.'/'.$action] = true;
								}
							} else {
								$permissions[$controller.'/'.$actions] = true;
							}
						}
					}
				}
			}
		}
		return $permissions;
	}
	
	static function parseMenuPermissionsForUser($uid) {
		$roles = LdFactory::dao('UserRole')->hasA('Role')->findAllUnique(array('userId = ? and Role.deleted = 0', $uid), 'roleId');
		if (empty($roles)) return;
		
		$permissions = array();
		foreach ($roles as $role) {
			$permList = LdFactory::dao('RolePermission')->hasA('Permission', 'Permission.resource,Permission.operation')->findAll(array('roleId = ? and Permission.type = ?', array($role, Permission::RESOURCE_TYPE_MENU)));
			if (empty($permList)) continue;
			foreach($permList as $perm) {
				if (is_null($perm['operation'])) {
					$permissions['top'][$perm['resource']] = true;
				} else {
					$permissions['sub'][$perm['resource']][$perm['operation']] = true;
				}
			}
		}
		return $permissions;
		
	}
}