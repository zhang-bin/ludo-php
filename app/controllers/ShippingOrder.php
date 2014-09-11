<?php
/**
 * Ludo BillGo Platform
 * @author     going1000 <miaorenjin@gmail.com>
 * @copyright  Copyright (c) 2012 Ludo team (http://www.loongjoy.com)
 * @version    $Id: ShippingOrder.php 421 2013-06-21 01:44:13Z zhangbin $
 */
class ShippingOrder extends LdBaseCtrl {
	protected $_vendorId = null;
	protected $_stationId = null;
	public function __construct() {
		parent::__construct('ShippingOrder');
		list($this->_stationId, $this->_vendorId) = Permission::getIdentity();
	}
    
    function view() {
    	$dao = new ShippingOrderDao();
    	$id = intval($_REQUEST['id']);
    	$shippingDetailsDao = new ShippingDetailsDao();
    	$order = $dao->hasA('Warehouse a', 'a.name as destName', 'destinationWarehouseId')
					->hasA('Warehouse b', 'b.name as departureName', 'departureWarehouseId')
					->hasA('Users', 'Users.nickname', 'userId')
					->fetch($id);
    	if(!empty($this->_stationId) && $order['stationId'] !== $this->_stationId){
    		redirect('error/accessDenied');
    	}
    	
    	$orderDetail = $shippingDetailsDao->findAll(array('shippingOrderId = ?', $id));
    	$discrepancyDao = new ShippingDiscrepancyDao();
    	foreach ($orderDetail as $k=>$detail) {
    		list($orderDetail[$k]['num'], $orderDetail[$k]['status']) = $discrepancyDao->find('shippingDetailsId = ?', $detail['id'], 'num,status');
    	}
    	$this->tpl->setFile('shippingOrder/detail')
    		->assign('order', $order)
    		->assign('orderDetail', $orderDetail)
    		->assign('param', Load::conf('ShippingOrderParam'))
    		->display();
    }
    
    function suggestParts() {
    	$model = trim($_GET['model']);
    	$part = trim($_GET['query']);
    	$where = '(pn like ? or pn2 like ? or pn3 like ?)';
    	$condition = array($part.'%', $part.'%', $part.'%'); 
    	if (!empty($model)) {
    		$modelId = LdFactory::dao('model')->findColumn('name = ?', $model, 'id');
    		$pns = LdFactory::dao('PhoneBom')->findAllUnique(array('modelId = ? and level in (\'T1\', \'T2\')', $modelId), 'pn');
    		$pns = array_filter($pns);
    		if (!empty($pns)) {
	    		$pns = implode("','", $pns);
	    		$pns = "'$pns'";
	    		$where .= ' and pn in ('.$pns.')';
    		}
    	}

    	$dao = new PartsMaitroxPN2Dao();
    	
    	$parts = $dao->findAll(array($where, $condition), 0, 0, '', 'distinct pn, pn2, pn3, en');
    	$tmp = array();
    	foreach ($parts as $part) {
    		$label = $part['pn'];
    		if (!empty($part['pn2'])) $label .= '--'.$part['pn2'];
    		if (!empty($part['pn3'])) $label .= '--'.$part['pn3'];
    		$label .= '--'.$part['en'];
    		$tmp[] = array(
    				'data' => $part['pn'],
    				'value' => $label,
    				'en' => $part['en']
    		);
    	} 
    	return json_encode(array('suggestions' => $tmp));
    }
    public static function addSO($order, $details) {
    	$order['createTime'] = gmdate(TIME_FORMAT);
    	$vendorId = LdFactory::dao('warehouse')->fetchColumn($order['departureWarehouseId'], 'vendorId');
    	$vendorCode = LdFactory::dao('vendor')->fetchColumn($vendorId, 'countryShortName');
    	$order['shippingOrderCode'] = 'SO'.$vendorCode.gmdate('ymd').Sequence::getSequence('ShippingSequence');
    	$order['userId'] = $_SESSION[USER]['id'];
    	$order['stationId'] = $_SESSION[USER]['stationId'];
    	$order['vendorId'] = $_SESSION[USER]['vendorId'];
    	$order['status'] = 1;
    	
    	$dao = new ShippingOrderDao();
    	$detailDao = new ShippingDetailsDao();
    	$inventoryDao = new InventoryDao();
    	
	    $order['id'] = $dao->insert($order);
	    foreach ($details as $detail) {
	   		$tmp = Api::getPNs($detail['pn']);
	   		if (empty($tmp)) return false;
	   		
	   		list($inventoryId, $actualQty) = $inventoryDao->find('warehouseId = ? and pn = ?', array($order['departureWarehouseId'], $tmp['pn']), 'id,qty');
	   		if (empty($actualQty) || ($detail['qty'] > $actualQty)) return false;
	   		$inventoryDao->update($inventoryId, array('qty'=>$actualQty - $detail['qty']));
	   		$detailDao->insert(array(
	   				'shippingOrderId' => $order['id'],
	   				'partsPN' => $detail['pn'],
	   				'qty' => $detail['qty'],
	   				'partsPN2'=>$tmp['pn2'],
	   				'partsPN3'=>$tmp['pn3']
	   		));
	   	}
    	return $order['id'];
    }
    
    public static function delSO($id) {
    	$dao = new ShippingOrderDao();
    	$detailDao = new ShippingDetailsDao();
    	
	    $order = $dao->fetch($id);
	    $orderDetail = $detailDao->findAll(array('shippingOrderId = ?', $id));
    	
	    if (!empty($orderDetail)) {
	    	$inventoryDao = new InventoryDao();
	    	foreach ($orderDetail as $detail) {
	    		list($inventoryId, $actualQty) = $inventoryDao->find('warehouseId = ? and pn = ?', array($order['departureWarehouseId'], $detail['partsPN']), 'id,qty');
	    		$inventoryDao->update($inventoryId, array('qty'=>$actualQty+$detail['qty']));
	    	}
	    }
	   	$dao->delete($id);
	   	$detailDao->deleteWhere('shippingOrderId = ?', $id);
    	return true;
    }
    

	function beforeAction($action) {
    	if (!User::logined()) return User::gotoLogin();
    	if (!User::can()) redirect('error/accessDenied');
    	$arr = array('index', 'add', 'modify', 'del');
    	if (in_array($action, $arr) && $_SESSION[USER]['usergroup'] == 4) {
	    	$stationType = LdFactory::dao('station')->fetchColumn($_SESSION[USER]['stationId'], 'type');
	    	if ($stationType == 2) redirect('error/accessDenied');
    	}
    }
}