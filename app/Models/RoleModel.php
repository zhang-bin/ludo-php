<?php

namespace App\Models;

/**
 * 用户角色类
 * @author libok
 *
 */
class RoleModel {
	public static function parsePermissions($uid){
		$roles = Factory::dao('UserRole')->hasA('Role')->findAllUnique(array('userId = ? and Role.deleted = 0', $uid), 'roleId');
		if (empty($roles)) {
		    return array();
        }

		$permissionConf = Load::conf('Permission');
		$permissions = array();
		foreach ($roles as $roleId) {
			$permList = Factory::dao('RolePermission')->hasA('Permission', 'Permission.resource, Permission.operation')->findAll(array('roleId = ?', array($roleId)));
			if (empty($permList)) {
			    continue;
            }

			foreach ($permList as $item) {
			    $permissions['operation'][$item['resource']][$item['operation']] = 1;
			    foreach ($permissionConf[$item['resource']]['operations'][$item['operation']]['url'] as $url) {
                    $permissions['url'][$url] = $url;
                }
            }
		}
		return $permissions;
	}
}
