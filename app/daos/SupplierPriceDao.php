<?php
/**
 * Ludo BillGo Platform
 *
 * @author     zhangbin <hunter.zhangbin@gmail.com>
 * @copyright  Copyright (c) 2012 Ludo team (http://www.loongjoy.com)
 * @version    $Id$
 */
class SupplierPriceDao extends LdBaseDao {
	public function __construct() {
		parent::__construct('SupplierPrice');
	}
	
	public function getList($condition) {
		return $this->tbl['s']
					->leftJoin('Supplier', 'Supplier.id = SupplierPrice.supplierId', 'Supplier.supplier')
					->leftJoin('PartsMaitrox', 'PartsMaitrox.pn = SupplierPrice.pn', 'PartsMaitrox.partsGroup, PartsMaitrox.en')
					->where($condition)
					->fetchAll();
	}
}