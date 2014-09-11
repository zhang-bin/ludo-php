<?php
/**
 * Ludo BillGo Platform
 *
 * @author     liangsijun <yhshenghuang7@gmail.com>
 * @copyright  Copyright (c) 2012 Ludo team (http://www.loongjoy.com)
 * @version    $Id$
 */
class PartsDeliveryWarehouseDetailDao extends LdBaseDao {
	public function __construct() {
		parent::__construct('PartsDeliveryWarehouseDetail');
	}
	
	public function getInvoice($id) {
		return $this->tbl['s']
		->leftJoin('PartsMaitrox', 'PartsMaitrox.pn = PartsDeliveryWarehouseDetail.pn', 'PartsMaitrox.en')
		->where('PartsDeliveryWarehouseDetail.partsDeliveryWarehouseId = ?', $id)
		->fetchAll();
	}
	
}