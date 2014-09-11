<?php
/**
 * Ludo BillGo Platform
 *
 * @author     zhangbin <hunter.zhangbin@gmail.com>
 * @copyright  Copyright (c) 2012 Ludo team (http://www.loongjoy.com)
 * @version    $Id$
 */
class PartsDeliveryFactoryDetailDao extends LdBaseDao {
	public function __construct() {
		parent::__construct('PartsDeliveryFactoryDetail');
	}
	
	public function getList($id) {
		return $this->hasA('PurchaseOrderDetail', 'PurchaseOrderDetail.qty,PurchaseOrderDetail.aog,PurchaseOrderDetail.delivery')
					->findAll(array('partsDeliveryFactoryId = ?', $id));
	}
	
	public function getInvoice($id) {
		return $this->tbl['s']
					->leftJoin('PartsMaitrox', 'PartsMaitrox.pn = PartsDeliveryFactoryDetail.pn', 'PartsMaitrox.en')
					->where('PartsDeliveryFactoryDetail.partsDeliveryFactoryId = ?', $id)
					->fetchAll();
	}
}