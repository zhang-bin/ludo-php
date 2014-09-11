<?php
/**
 * Ludo BillGo Platform
 *
 * @author     liangsijun <yhshenghuang7@gmail.com>
 * @copyright  Copyright (c) 2012 Ludo team (http://www.loongjoy.com)
 * @version    $Id$
 */
class PartsDeliveryWarehouseDao extends LdBaseDao {
	public function __construct() {
		parent::__construct('PartsDeliveryWarehouse');
	}
	
	
	public function getList($condition, $params, $pager) {
		return $this->hasA('Warehouse a', 'a.name as departureWarehouse', 'departureWarehouseId')
					->hasA('Warehouse b', 'b.name as destinationWarehouse', 'destinationWarehouseId')
					->hasA('Users', 'Users.nickname', 'createUserId')
					->hasA('ShippingOrder', 'ShippingOrder.shippingOrderCode')
					->findAll(array($condition, $params), $pager['rows'], $pager['start'], 'createTime desc');
	}
	
	public function getInfo($id) {
		return $this->hasA('Warehouse a', 'a.name as departureWarehouse', 'departureWarehouseId')
		->hasA('Warehouse b', 'b.name as destinationWarehouse', 'destinationWarehouseId')
		->hasA('PartsShipper', 'PartsShipper.name as shipperName', 'shipperId')
		->hasA('PartsConsignee', 'PartsConsignee.name as consigneeName', 'consigneeId')
		->fetch($id);
	}
}