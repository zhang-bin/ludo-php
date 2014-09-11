<?php
class Index extends LdBaseCtrl {
	function __construct() {
		parent::__construct('Index');
	}
	
	function index() {
		$this->tpl->setFile('index/index')->display();
	}
	
    function beforeAction($action) {
    	if (!User::logined()) {
    		return User::gotoLogin();
    	}
    }
}