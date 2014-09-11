<?php
/**
 * Ludo BillGo Platform
 *
 * @author     zhangbin <hunter.zhangbin@gmail.com>
 * @copyright  Copyright (c) 2012 Ludo team (http://www.loongjoy.com)
 * @version    $Id$
 */
class PurchaseOrderDao extends LdBaseDao {
	public function __construct() {
		parent::__construct('PurchaseOrder');
	}
	
	public function getInfo($id) {
		return $this->hasA('Supplier', 'Supplier.supplier')
					->hasA('Warehouse', 'Warehouse.name as warehouse')
					->hasA('Users', 'Users.nickname', 'createUserId')
					->fetch($id);
	}
}