<?php
/**
 * Ludo BillGo Platform
 * @author     going1000 <miaorenjin@gmail.com>
 * @copyright  Copyright (c) 2012 Ludo team (http://www.loongjoy.com)
 * @version    $Id: ServiceManage.php 420 2013-06-21 01:39:47Z zhangbin $
 */
class ServiceManage extends LdBaseCtrl {
	protected $_vendorId = null;
	protected $_stationId = null;
	const MAINBOARD_ID = 24;
	const STATUS_SEND = 1;
	const STATUS_APPLY = 2;
	const STATUS_CLOSED = 3;
	const STATUS_APPLY_SUCCESS = 4;
	const STATUS_PROCESSING = 5;
	const STATUS_REPAIR_DONE = 6;
	const STATUS_WAITING = 7;
	const STATUS_RECEIVED = 8;
	const STATUS_PARTS_AVAILBLE = 9;
    const STATUS_PARTS_AVAILBLE_SELF = 10;
    const STATUS_SEND_CI_RECEIVED = 11;
    const STATUS_SEND_CI_TRANSFER = 12;
	
	public function __construct() {
		parent::__construct('ServiceManage');
		list($this->_stationId, $this->_vendorId) = Permission::getIdentity();
	}
	
	function beforeAction($action) {
    	if (!User::logined()) return User::gotoLogin();
    	if (!User::can()) redirect('error/accessDenied');
    }
}