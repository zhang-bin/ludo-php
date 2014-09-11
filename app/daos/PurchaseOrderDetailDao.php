<?php
/**
 * Ludo BillGo Platform
 *
 * @author     zhangbin <hunter.zhangbin@gmail.com>
 * @copyright  Copyright (c) 2012 Ludo team (http://www.loongjoy.com)
 * @version    $Id$
 */
class PurchaseOrderDetailDao extends LdBaseDao {
	public function __construct() {
		parent::__construct('PurchaseOrderDetail');
	}
	
	public function getList($condition, $params, $pager = array()) {
		return $this->tbl['s']
					->leftJoin('PurchaseOrder', 'PurchaseOrder.id = PurchaseOrderDetail.purchaseOrderId', 'PurchaseOrder.code,PurchaseOrder.currency')
					->leftJoin('Supplier', 'PurchaseOrder.supplierId = Supplier.id', 'Supplier.supplier')
					->leftJoin('Warehouse', 'PurchaseOrder.warehouseId = Warehouse.id', 'Warehouse.name as warehouse')
					->leftJoin('PartsMaitrox', 'PurchaseOrderDetail.pn = PartsMaitrox.pn', 'PartsMaitrox.en')
					->where($condition, $params)
					->limit($pager['rows'], $pager['start'])
					->orderby('PurchaseOrder.createTime desc')
					->fetchAll();
	}
	
	public function getCount($condition, $params, $pager = array()) {
		return  $this->tbl['s']
					->leftJoin('PurchaseOrder', 'PurchaseOrder.id = PurchaseOrderDetail.purchaseOrderId')
					->leftJoin('Supplier', 'PurchaseOrder.supplierId = Supplier.id', 'Supplier.supplier')
					->leftJoin('Warehouse', 'PurchaseOrder.warehouseId = Warehouse.id', 'Warehouse.name as warehouse')
					->where($condition, $params)
					->recordsCount();
	}
}