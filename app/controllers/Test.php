<?php
class Test extends LdBaseCtrl {
	function __construct() {
		parent::__construct('Test');
	}

	function index() {
    }

	function beforeAction($action) {
		return true;
	}
}