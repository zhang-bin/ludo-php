<?php
/**
 * Ludo BillGo Platform
 * @author     going1000 <miaorenjin@gmail.com>
 * @copyright  Copyright (c) 2012 Ludo team (http://www.loongjoy.com)
 * @version    $Id: Warehouse.php 153 2013-02-20 06:35:57Z zhangbin $
 */
class Warehouse extends LdBaseCtrl {
    const TYPE_GOOD = 0;
    const TYPE_BAD = 1;
    const TYPE_SCRAP = 2;
    const TYPE_PROBLEM = 3;

    public static $_types = array(
        self::TYPE_GOOD => 'Good',
        self::TYPE_BAD => 'Defect',
        self::TYPE_SCRAP => 'Scrap',
        self::TYPE_PROBLEM => 'Problem'
    );

	public function __construct() {
		parent::__construct('Warehouse');
	}
	
	public function index() {
		$vendors = Api::getVendors('name');
		$this->tpl->setFile('warehouse/list')
				->assign('vendors', $vendors)
				->display();
		
	}
	
	public function tbl() {
        $condition = $and = '';
        $params = array();
		if (!empty($_REQUEST['vendor'])) {
			$condition = 'Warehouse.vendorId = ?';
			$params[] = intval($_REQUEST['vendor']);
			$and = ' and ';
		}
		if (!empty($_REQUEST['station'])) {
			$condition .= $and.'Warehouse.stationId = ?';
			$params[] = intval($_REQUEST['station']);
			$and = ' and ';
		}
		if (isset($_REQUEST['goodOrBad']) && $_REQUEST['goodOrBad'] != '-1') {
			$condition .= $and.'Warehouse.goodOrBad = ?';
			$params[] = intval($_REQUEST['goodOrBad']);
		}
		$dao = new WarehouseDao();
		$pager = pager(array(
				'base'	=> 'warehouse/tbl',
				'cur'	=> intval($_GET['id']),
				'cnt'	=> $dao->count($condition, $params),
		));
		if (empty($condition)) {
			$list = $dao->hasA('Vendor', 'Vendor.name as vendorName')->hasA('Station', 'Station.name as stationName')->fetchAll($pager['rows'], $pager['start'],'name');
		} else {
			$list = $dao->hasA('Vendor', 'Vendor.name as vendorName')->hasA('Station', 'Station.name as stationName')->findAll(array($condition, $params), $pager['rows'], $pager['start'],'name');
		}
		$this->tpl->setFile('warehouse/tbl')
				->assign('list', $list)             
				->assign('pager', $pager['html'])
				->display();
		
	}
	
	function beforeAction($action) {
    	if (!User::logined()) return User::gotoLogin();
    }
}