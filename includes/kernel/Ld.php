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
| All Errors code
+-------------------------------------------------------------------------------
*/

/**
 * separate the real error message with vivid message (which is comprehensive for the end user)
 */
class Ld {
	static $err = array(
		0 => '',
		1 => '',
		2 => 'Connect to DB failed: %s',
		3 => 'Ctrl file: [%s] does not exist',
		4 => 'Action [%s] does not exist',
		5 => 'static method [%s] cannot be accessed by User directly',
		6 => 'Tpl file: [%s] does not exist',
	);
}