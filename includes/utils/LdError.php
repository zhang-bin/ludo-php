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
| Error handling utilities
+-------------------------------------------------------------------------------
*/

Class LdError {
	//private
	var $errMSG = '';

	var $msg_tag = 'errorMSG';
	var $cause_tag = 'errorCause';

	var $def_url_tag = 'defaultGotoURL';

	//protected
	var $tpl = null;

	function __construct($MSG, $cause = '', $defaultURL = '', $tpl = null) {
		$cause = str_replace(array ('\n', "\n"), '<br>', $cause);

		if ($tpl) {
			$tpl->setFile('error', 'error.tpl');

			$tpl->set_var($this->msg_tag, $MSG);
			$tpl->set_var($this->cause_tag, $cause);

			if ($defaultURL)
				$tpl->set_var($this->def_url_tag, $defaultURL);

			die($tpl->parse('error'));
		} else
			die($MSG);
	}

	function setTagStyle($msg_tag, $cause_tag, $def_url_tag) {
		$this->msg_tag = $msg_tag ? $msg_tag : 'errorMSG';
		$this->cause_tag = $cause_tag ? $cause_tag : 'errorCause';
		$this->def_url_tag = $def_url_tag ? $def_url_tag : 'defaultGotoURL';
	}
}
?>