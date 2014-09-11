<?php
class Inventory extends LdBaseCtrl {
    protected $_vendorId = null;
	protected $_stationId = null;
    
    function __construct() {
        parent::__construct('Inventory');
    }
    
    public function index() {
    	$this->tpl->setFile('inventory/index')
    	->assign('vendors', Api::getVendors())
        ->assign('warehouses', LdFactory::dao('warehouse')->fetchAll())
    	->display();
	}
	
	function inventoryList(){
		$dao = new InventoryDao();
    	list($condition, $params) = $this->_getSearchCondition();
		if (!empty($condition)) {
			$cnt = $dao->hasA('Warehouse', 'vendorId')->count($condition, $params);
			$pager = pager(array(
					'base' => 'inventory/inventoryList',
					'cur' => empty($_GET['id']) ? 1 : intval($_GET['id']),
					'cnt' =>$cnt,
			));
			$list = $dao->hasA('Warehouse','name, vendorId')->hasA('PartsMaitrox','PartsMaitrox.pn,PartsMaitrox.en')->findAll(array($condition, $params),$pager['rows'],$pager['start']," Inventory.id desc ");
		} else {
			$cnt = $dao->count();
			$pager = pager(array(
					'base' => 'inventory/inventoryList',
					'cur' => empty($_GET['id']) ? 1 : intval($_GET['id']),
					'cnt' => $cnt,
			));
			$list = $dao->hasA('Warehouse','name, vendorId')->hasA('PartsMaitrox','PartsMaitrox.pn,PartsMaitrox.en')->fetchAll($pager['rows'],$pager['start']," Inventory.id desc ");
		}
		$dao = new PartsMaitroxPN2Dao();
		$shippingDetailDao = new ShippingDetailsDao();
		foreach ($list as $k=>$v) {
			if (empty($v['pn'])) continue;
			$pn2 = $dao->findAllUnique(array('pn = ?', $v['pn']), 'pn2');
			if (!empty($pn2)) {
				$list[$k]['pn2'] = implode('/', $pn2); 
			}
			$pn3 = $dao->findAllUnique(array('pn = ?', $v['pn']), 'pn3');
			if (!empty($pn3)) {
				$list[$k]['pn3'] = implode('/', $pn3); 
			}
			$list[$k]['inTransit'] = $shippingDetailDao->hasA('ShippingOrder')->findColumn('destinationWarehouseId = ? and ShippingDetails.partsPN = ? and status = 1', array($v['warehouseId'], $v['pn']), 'sum(ShippingDetails.qty)');
			$list[$k]['inTransit'] = round($list[$k]['inTransit']);
		}
		$this->tpl->setFile('inventory/list')
                  ->assign('list', $list)                
                  ->assign('pager', $pager['html'])
                  ->display();               
	}
	
    function inventoryExport() {
    	$dao = new InventoryDao();
    	list($condition, $params) = $this->_getSearchCondition();
    	$list = $dao->getList($condition, $params);
    	$dao = new PartsMaitroxPN2Dao();
    	$shippingDetailDao = new ShippingDetailsDao();
    	$parts = array();
    	foreach ($list as $k=>$v) {
    		$list[$k]['goodOrBad'] = Warehouse::$_types[$v['goodOrBad']];
    		if (!isset($parts[$v['pn']])) {
    			$tmp = $dao->findAll(array('pn = ?', $v['pn']), 0, 0, '', 'pn2,pn3');
    			foreach ($tmp as $tmpV) {
    				if (!empty($tmpV['pn2'])) $parts[$v['pn']]['pn2'][] = $tmpV['pn2'];
    				if (!empty($tmpV['pn3'])) $parts[$v['pn']]['pn3'][] = $tmpV['pn3'];
    			}
    			
    			if (!empty($parts[$v['pn']]['pn2'])) $parts[$v['pn']]['pn2'] = implode('/', $parts[$v['pn']]['pn2']);
    			if (!empty($parts[$v['pn']]['pn3'])) $parts[$v['pn']]['pn3'] = implode('/', $parts[$v['pn']]['pn3']);
    		}
    		
    		$list[$k]['pn2'] = $parts[$v['pn']]['pn2'];
    		$list[$k]['pn3'] = $parts[$v['pn']]['pn3'];
    		
    		$list[$k]['inTransit'] = $shippingDetailDao->hasA('ShippingOrder')->findColumn('destinationWarehouseId = ? and ShippingDetails.partsPN = ? and status = 1', array($v['warehouseId'], $v['pn']), 'sum(ShippingDetails.qty)');
    		$list[$k]['inTransit'] = round($list[$k]['inTransit']);
    	}
    	$menu = array(
    			'name' => 'Warehouse Name',
    			'code' => 'Site',
    			'countryShortName' => 'Country',
    			'goodOrBad' => 'Warehouse Type',
    			'pn' => 'New PN',
    			'pn2' => 'Order PN',
    			'pn3' => 'Old PN',
    			'en' => 'Part Description',
    			'qty' => 'Quantity',
    			'inTransit' => 'In Transit',
    			'doaQty' => 'DOA Qty'
    	);
    	
    	$excel = new Excel();
    	$filename = $excel->write($menu, $list);
    	return SUCCESS.'|'.url('inventory/download/'.base64_encode($filename));
    }
    
    function download() {
    	$name = base64_decode(trim($_GET['id']));
    	downloadLink($name, 'Lenovo Mobile Phone Inventory('.getCurrentDate(gmdate(DATE_FORMAT)).').csv');
    }
    
    
    private function _getSearchCondition() {
    	if (!empty($_REQUEST['vendor'])) {
    		$vendorIds = $_REQUEST['vendor'];
            $condition = 'Warehouse.vendorId in ('.implode(',', $vendorIds).')';
    		$and = ' and ';
    	}
    	if (isset($_REQUEST['warehouseType']) && $_REQUEST['warehouseType'] != '-1') {
    		$goodOrBad = intval($_REQUEST['warehouseType']);
    		$condition .= $and.'goodOrBad = ?';
    		$params[] = $goodOrBad;
    		$and = ' and ';
    	}
    	
    	if (!empty($_REQUEST['warehouseId'])) {
    		$warehouseId = intval($_REQUEST['warehouseId']);
    		$condition .= $and.'warehouseId = ?';
    		$params[] = $warehouseId;
    		$and = ' and ';
    	}
    	if (!empty($_REQUEST['pn'])) {
    		$pn2 = trim($_REQUEST['pn']);
    		$dao = new PartsMaitroxPN2Dao();
    		list($exist, list($pn)) = $dao->existsRow('pn2 = ? or pn3 = ?', array($pn2, $pn2), 'pn');
    		if (!$exist) {
    			$pn = $pn2;
    		}
    		$condition .= $and.'Inventory.pn like ?';
    		$params[] = $pn.'%';
    		$and = ' and ';
    	}
    	return array($condition, $params);
    }
    
    function suggestParts() {
    	$shipping = new ShippingOrder();
    	return $shipping->suggestParts();
    }
    
    public function historyPN() {
    	$pn = trim($_POST['pn']);
    	$tmp = Api::getPNs($pn);
    	$pn = $tmp['pn'];
    	if (!empty($pn)) {
    		$dao = new InventorySerialDetailsDao();
    		$pager = pager(array(
    				'base' => 'inventory/historyPN',
    				'cur' => isset($_GET['id']) ? intval($_GET['id']) : 1,
    				'cnt' => $dao->getCntByPn($pn)
    		));
    			
    		$inventories = $dao->getListByPn($pn, $pager);
    	}
    	$this->tpl->setFile('parts/historyInventory')
    	->assign('inventories', $inventories)
    	->assign('pager', $pager['html'])
    	->display();
    }
    
    function beforeAction($action) {
    	if (!User::logined()) return User::gotoLogin();
    	if (!User::can()) redirect('error/accessDenied');
    }
}