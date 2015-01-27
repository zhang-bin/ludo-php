<?php
/*
+-------------------------------------------------------------------------------
| {ProgName}
| =====================================================
| Author: Libok.Zhou <libkhorse@gmail.com>
| Home  : http://libk.8800.org
| Copyright (C)2004 - 2005 LdFirm All Rights Reserved.
| License: {LdLicense}
+-------------------------------------------------------------------------------
|
+-------------------------------------------------------------------------------
*/

/**
 * Factory to create instance for all object.
 */
final class LdFactory {
	/**
	 * create Dao instance for passed $daoName.
	 *
	 * @param String $daoName name of dao to be created. (note: no trailing Dao needed)<br>
	 * 	you can using / for package separator, eg. LdFactory::dao('user/UserState') will refer to DAO_PATH/user/UserStateDao.php
	 * @return LdBaseDao instance of child class of LdBaseDao
	 */
	static function dao($daoName) {
		$tmp = $daoName;
		$daoName = trim($daoName, '/').'Dao';

		if ( ($pos = strrpos($daoName, '/')) === false ) {
			$daoName = ucfirst($daoName);
			$file = $daoName;
		} else {
			$path = substr($daoName, 0, $pos+1);
			$daoName = ucfirst(substr($daoName, $pos+1));
			$file = $path.$daoName;
		}
		$file = LD_DAO_PATH.DIRECTORY_SEPARATOR.$file.php;
		if (file_exists($file)) {
			include_once $file;
			return new $daoName;
		} else {
			include_once LD_DAO_PATH.DIRECTORY_SEPARATOR.'UserDefinedDao'.php;
			return new UserDefinedDao($tmp);
		}
	}
	/**
	 * create Ctrl instance for passed $ctrlName.
	 *
	 * @param String $ctrlName name of ctrl to be created. (note: no trailing Ctrl needed)
	 * @return LdBaseCtrl instance of child class of LdBaseCtrl
	 */
	static function ctrl($ctrlName) {
		 return new $ctrlName;
	}
}