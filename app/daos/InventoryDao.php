<?php
class InventoryDao extends LdBaseDao {
    function __construct() {
        parent::__construct('Inventory');
    }
    
    function getList($condition, $params) {
    	$sql = $this->tbl('s')
    				->leftJoin('Warehouse', 'Warehouse.id = Inventory.warehouseId', 'Warehouse.name,Warehouse.goodOrBad')
    				->leftJoin('Vendor', 'Vendor.id = Warehouse.vendorId', 'Vendor.countryShortName')
    				->leftJoin('Station', 'Station.id = Warehouse.stationId', 'Station.code')
    				->leftJoin('PartsMaitrox', 'PartsMaitrox.id = Inventory.PartsMaitroxId', 'PartsMaitrox.pn,PartsMaitrox.en');
    	if (!empty($condition)) $sql->where($condition, $params);
    	return $sql->fetchAll();
    }
}