<?php
class ShippingDetailsDao extends LdBaseDao {
    function __construct() {
        parent::__construct('ShippingDetails');
    }
    
    function getCntByPn($pn) {
    	return $this->count('partsPN = ?', $pn, 'shippingOrderId');
    }
    
    function getListByPn($pn, $pager) {
    	return $this->tbl['s']
    				->setField('sum(ShippingDetails.qty) as pnQty')
    				->innerJoin('ShippingOrder', 'ShippingOrder.id = ShippingDetails.shippingOrderId', 'ShippingOrder.*')
					->leftJoin('Warehouse a', 'a.id = ShippingOrder.destinationWarehouseId', 'a.name as destName')
					->leftJoin('Warehouse b', 'b.id = ShippingOrder.departureWarehouseId', 'b.name as departureName')
					->innerJoin('Users', 'ShippingOrder.userId = Users.id', 'Users.nickname')
					->where('ShippingDetails.partsPN = ?', $pn)
					->limit($pager['rows'], $pager['start'])
					->groupby('ShippingOrder.id')
					->fetchAll();
    }
}