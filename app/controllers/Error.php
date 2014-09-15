<?php
class Error extends LdBaseCtrl {
	public function __construct() {
		parent::__construct('Error');
	}
	
	public function accessDenied() {
		$this->tpl->setFile('error/access_denied')->display();
	}
	
	function beforeAction($action) {
		if (!User::logined()) return User::gotoLogin();
	}
}