<?php
class Index extends BaseCtrl {
	function __construct() {
		parent::__construct('Index');
	}
	
	function index() {
		$this->tpl->setFile('index/index')->display();
	}
}