<?php
/**
 * Ludo BillGo Platform
 *
 * @author     zhangbin <hunter.zhangbin@gmail.com>
 * @copyright  Copyright (c) 2012 Ludo team (http://www.loongjoy.com)
 * @version    $Id$
 */
class PurchaseOrder extends LdBaseCtrl {
	public function __construct() {
		parent::__construct('PurchaseOrder');
	}
	
	public function index() {
		$suppliers = LdFactory::dao('supplier')->findAll('deleted = 0');
		$this->tpl->setFile('purchaseOrder/index')
				->assign('suppliers', $suppliers)
				->display();
	}
	
	public function tbl() {
		$dao = new PurchaseOrderDao();
		list($condition, $params) = $this->_getSearchCondition();
		$pager = pager(array(
				'base' => 'purchaseOrder/tbl',
				'cur'  => empty($_GET['id']) ? 1 : intval($_GET['id']),
				'cnt'  => $dao->count($condition, $params),
		));
		$list = $dao->hasA('Supplier', 'Supplier.supplier')
				->hasA('Warehouse', 'Warehouse.name as warehouse')
				->hasA('Users', 'Users.nickname', 'createUserId')
				->findAll(array($condition, $params), $pager['rows'], $pager['start'],'PurchaseOrder.id desc');
		$this->tpl->setFile('purchaseOrder/tbl')
		->assign('list', $list)
		->assign('pager', $pager['html'])
		->display();
	}
	
	public function add() {
		if (empty($_POST)) {
			$suppliers = LdFactory::dao('supplier')->findAll('deleted = 0');
			$warehouses = LdFactory::dao('warehouse')->findAll('vendorId = 7 and goodOrBad = 0');
			$this->tpl->setFile('purchaseOrder/change')
					->assign('suppliers', $suppliers)
					->assign('warehouses', $warehouses)
					->display();
		} else {
			$dao = new PurchaseOrderDao();
			$add['supplierId'] = intval($_POST['supplierId']);
			if (empty($add['supplierId'])) return ALERT.'|'.LG_PO_SUPPLIER_EMPTY;
			
			$add['warehouseId'] = intval($_POST['warehouseId']);
			if (empty($add['warehouseId'])) return ALERT.'|'.LG_PO_WAREHOUSE_EMPTY;

			$add['type'] = intval($_POST['type']);
			if (empty($add['type'])) return ALERT.'|'.LG_PO_TYPE_EMPTY;
			
			$add['demandTime'] = trim($_POST['demandTime']);
			if (empty($add['demandTime'])) return ALERT.'|'.LG_PO_DEMAND_TIME_EMPTY;
			
			$add['currency'] = trim($_POST['currency']);
			if (empty($add['currency'])) return ALERT.'|'.LG_PO_CURRENCY_EMPTY;

			$add['warranty'] = intval($_POST['warranty']);
			if (empty($add['warranty'])) return ALERT.'|'.LG_PO_WARRANTY_STATUS_EMPTY;
			
			$add['remark'] = trim($_POST['remark']);
			$add['createUserId'] = $_SESSION[USER]['id'];
			$add['status'] = self::STATUS_COMMIT;
			$add['commitTime'] = date(TIME_FORMAT);
            if (empty($_POST['code'])) {
                $add['code'] = $this->_getCode($add['type']);
            } else {
                $add['code'] = trim($_POST['code']);
            }

			try {
				$dao->beginTransaction();
				if (empty($_POST['id'])) {
					$add['id'] = $dao->insert($add);
				} else {
					$id = intval($_POST['id']);
					$dao->update($id, $add);
				}
				
				$dao->commit();
				return SUCCESS.'|'.url('purchaseOrder');
			} catch (SqlException $e) {
				$dao->rollback();
				return ALERT.'|'.LG_PO_ADD_FAILED;
			}
		}
	}
	
	public function submit() {
		$id = intval($_POST['id']);
		$dao = new PurchaseOrderDao();
		$old = $dao->fetch($id);
		if (!in_array($old['status'], array(self::STATUS_PROCESS, self::STATUS_BACK))) return SUCCESS.'|'.url('purchaseOrder');
		try {
			$dao->beginTransaction();
			$dao->update($id, array(
					'status' => self::STATUS_COMMIT,
					'commitTime' => date(TIME_FORMAT)
			));
			$dao->commit();
			return SUCCESS.'|'.url('purchaseOrder');
		} catch (SqlException $e) {
			$dao->rollback();
			return ALERT.'|'.LG_PO_ADD_FAILED;
		}
	}
	
	public function save() {
		$dao = new PurchaseOrderDao();
		$add['supplierId'] = intval($_POST['supplierId']);
		$add['warehouseId'] = intval($_POST['warehouseId']);
		$add['type'] = intval($_POST['type']);
		if (empty($add['type'])) return ALERT.'|'.LG_PO_TYPE_EMPTY;
		$add['demandTime'] = trim($_POST['demandTime']);
		$add['currency'] = trim($_POST['currency']);
		$add['warranty'] = intval($_POST['warranty']);
		$add['remark'] = trim($_POST['remark']);
		$add['createUserId'] = $_SESSION[USER]['id'];
		$add['status'] = self::STATUS_PROCESS;
        if (empty($_POST['code'])) {
            $add['code'] = $this->_getCode($add['type']);
        } else {
            $add['code'] = trim($_POST['code']);
        }
		try {
			$dao->beginTransaction();
			if (empty($_POST['id'])) {
				$add['createTime'] = date(TIME_FORMAT);
				$add['id'] = $dao->insert($add);
			} else {
				$id = intval($_POST['id']);
				$dao->update($id, $add);
			}
			
			$dao->commit();
			return SUCCESS.'|'.url('purchaseOrder');
		} catch (SqlException $e) {
			$dao->rollback();
			return ALERT.'|'.LG_PO_ADD_FAILED;
		}
	}
	
	public function change() {
		$id = intval($_GET['id']);
		$dao = new PurchaseOrderDao();
		$order = $dao->fetch($id);
		$suppliers = LdFactory::dao('supplier')->findAll('deleted = 0');
		$warehouses = LdFactory::dao('warehouse')->findAll('vendorId = 7 and goodOrBad = 0');
		$this->tpl->setFile('purchaseOrder/change')
				->assign('order', $order)
				->assign('suppliers', $suppliers)
				->assign('warehouses', $warehouses)
				->display();
	}
	
	public function del() {
		$id = intval($_GET['id']);
		$dao = new PurchaseOrderDao();
        $detailDao = new PurchaseOrderDetailDao();
		$old = $dao->fetch($id);
		if (!self::canDel($old)) redirect('purchaseOrder');
		
		try {
			$dao->beginTransaction();
			$dao->update($id, array('deleted' => 1));
            $detailDao->updateWhere(array('deleted' => 1), 'purchaseOrderId = ?', $id);
			$dao->commit();
			return SUCCESS.'|'.url('purchaseOrder');
		} catch (SqlException $e) {
			$dao->rollback();
			return ALERT.'|'.LG_PO_DEL_FAILED;
		}
 	}
 	
 	public function close() {
 		$id = intval($_GET['id']);
 		$dao = new PurchaseOrderDao();
 		$old = $dao->fetch($id);
 		if (!self::canClose($old)) redirect('purchaseOrder');
 	
 		try {
 			$dao->beginTransaction();
 			$dao->update($id, array('status' => self::STATUS_CLOSED));
 			$dao->commit();
 			return SUCCESS.'|'.url('purchaseOrder');
 		} catch (SqlException $e) {
 			$dao->rollback();
 			return ALERT.'|'.LG_PO_CLOSE_FAILED;
 		}
 	}
 	
 	public function view() {
 		$dao = new PurchaseOrderDao();
 		$detailDao = new PurchaseOrderDetailDao();
 		$partsMaitroxDao = new PartsMaitroxDao();
 		$id = intval($_GET['id']);
 		$order = $dao->getInfo($id);
 		$details = $detailDao->findAll(array('purchaseOrderId = ? and deleted = 0', $id));
 		$sum['qty'] =0;
 		$sum['amount']=0;
 		foreach($details as $k=>$v){
 			$details[$k]['en'] = $partsMaitroxDao->findColumn('pn = ?', $v['pn'], 'en');
 			$details[$k]['unitPrice'] = Crypter::decrypt($v['unitPrice']);
 			$details[$k]['amount'] = $v['qty']*$details[$k]['unitPrice'];
 			$sum['qty'] = $sum['qty']+$v['qty'];
 			$sum['amount'] = $sum['amount']+$details[$k]['amount'];
 		}
 		
 		$this->tpl->setFile('purchaseOrder/view')
 				->assign('order', $order)
 				->assign('details', $details)
 				->assign('sum',$sum)
 				->display();
 	}
 	
 	public function approve() {
 		$dao = new PurchaseOrderDao();
 		$id = intval($_GET['id']);
 		$order = $dao->getInfo($id);
 		if (!self::canApprove($order)) redirect('PurchaseOrder');
 		$this->tpl->setFile('purchaseOrder/approve')
 				->assign('order', $order)
 				->display();
 	}
 	
 	public function approveTbl() {
 		$id = intval($_GET['id']);
 		$dao = new PurchaseOrderDetailDao();
 		$condition = 'purchaseOrderId = ? and deleted = 0';
 		$params[] = $id;
 			
 		$pager = pager(array(
 				'base' => 'purchaseOrder/approveTbl/'.$id.'/page/',
 				'cur'  => empty($_GET['page']) ? 1 : intval($_GET['page']),
 				'cnt'  => $dao->count($condition, $params)
 		));
 			
 		$details = $dao->findAll(array($condition, $params), $pager['rows'], $pager['start']);
 		$this->tpl->setFile('purchaseOrder/approveTbl')
 				->assign('details', $details)
 				->assign('pager', $pager['html'])
 				->display(); 		
 	}

 	public function agree() {
 		$id = intval($_POST['id']);
 		$dao = new PurchaseOrderDao();
 		try {
 			$dao->beginTransaction();
 			$old = $dao->fetch($id);
 			if (!self::canApprove($old)) redirect('PurchaseOrder');
 			$dao->update($id, array(
 					'status' => self::STATUS_APPROVE, 
 					'approveTime' => date(TIME_FORMAT), 
 					'approveRemark' => trim($_POST['approveRemark'])
 			));
 			$dao->commit();
 			return SUCCESS.'|'.url('purchaseOrder');
 		} catch (SqlException $e) {
 			$dao->rollback();
 			return ALERT.'|'.LG_PO_APPROVE_FAIL;
 		}
 	}
 	
 	public function reject() {
 		$id = intval($_POST['id']);
 		$dao = new PurchaseOrderDao();
 		try {
 			$dao->beginTransaction();
 			$old = $dao->fetch($id);
 			if (!self::canApprove($old)) redirect('PurchaseOrder');
 			$dao->update($id, array(
 					'status' => self::STATUS_BACK, 
 					'approveTime' => date(TIME_FORMAT),
 					'approveRemark' => trim($_POST['approveRemark'])
 			));
 			$dao->commit();
 			return SUCCESS.'|'.url('purchaseOrder');
 		} catch (SqlException $e) {
 			$dao->rollback();
 			return ALERT.'|'.LG_PO_APPROVE_FAIL;
 		}
 		
 	}
 	
 	public function pn() {
 		$id = intval($_GET['id']);
 		$dao = new PurchaseOrderDao();
 		$order = $dao->getInfo($id);
 		$this->tpl->setFile('purchaseOrder/pn')
 				->assign('order', $order)
 				->display();
 	}
 	
 	public function pnTbl() {
 		$id = intval($_GET['id']);
 		$dao = new PurchaseOrderDetailDao();
 			
 		$condition = 'purchaseOrderId = ? and deleted = 0';
 		$params[] = $id;
 		
 		$pager = pager(array(
 				'base' => 'purchaseOrder/pnTbl/'.$id.'/page/',
 				'cur'  => empty($_GET['page']) ? 1 : intval($_GET['page']),
 				'cnt'  => $dao->count($condition, $params),
 				'size' => 30
 		));
 		
 		$details = $dao->tbl('s')
 					->leftJoin('PartsMaitrox', 'PartsMaitrox.pn = PurchaseOrderDetail.pn', 'PartsMaitrox.en')
 					->where($condition, $params)
 					->limit($pager['rows'], $pager['start'])
 					->orderby('status asc')
 					->fetchAll();
 		$order = LdFactory::dao('purchaseOrder')->fetch($id);
 		$this->tpl->setFile('purchaseOrder/pnTbl')
 		->assign('details', $details)
 		->assign('id', $id)
 		->assign('order', $order)
 		->assign('pager', $pager['html'])
 		->display();
 	}
 	
 	public function addPn() {
 		$orderDao = new PurchaseOrderDao();
 		$dao = new PurchaseOrderDetailDao();
 		$priceDao = new SupplierPriceDao();
 		try {
 			$dao->beginTransaction();
 			$add['pn'] = trim($_POST['pn']);
 			if (empty($add['pn'])) return ALERT.'|'.LG_PO_PN_EMPTY;
 			$add['qty'] = intval($_POST['qty']);
 			if (empty($add['qty'])) return ALERT.'|'.LG_PO_QTY_EMPTY;
 			$add['aog'] = intval($_POST['aog']);
 			$add['remark'] = trim($_POST['remark']);
 			$add['purchaseOrderId'] = intval($_POST['id']);
 			$order = $orderDao->fetch($add['purchaseOrderId']);
 			$add['createTime'] = date(TIME_FORMAT);
 			$add['status'] = self::PN_STATUS_OPEN;
 			$price = $priceDao->find('supplierId = ? and pn = ? and priceType = ? and endTime is null', array($order['supplierId'], $add['pn'], PartsPrice::TYPE_PURCHASE));
 			$add['unitPrice'] = $price[strtolower($order['currency'])];
 			$add['rmb'] = $price['rmb'];
 			$add['usd'] = $price['usd'];
 			$add['leadTime'] = intval(LdFactory::dao('PartsLeadTime')->findColumn('pn = ?', $add['pn'], 'leadTime'));
 			$add['id'] = $dao->insert($add);
 			$amount = Crypter::decrypt($add['unitPrice']) * $add['qty'];
 			$sql = 'update PurchaseOrder set amount = amount + '.$amount.', pnSum = pnSum + '.$add['qty'].' where id = ?';
 			$orderDao->tbl('m')->exec($sql, $add['purchaseOrderId']);
 			$dao->commit();
 			return SUCCESS.'|'.url('purchaseOrder/pnTbl/'.$add['id']);
 		} catch (SqlException $e) {
 			$dao->rollback();
 			return ALERT.'|'.LG_PO_PN_ADD_FAILED;
 		}
 	}
 	
 	public function changePn() {
 		$dao = new PurchaseOrderDetailDao();
 		if (empty($_POST)) {
 			$id = intval($_GET['id']);
 			$detail = $dao->fetch($id);
 			$detail['en'] = LdFactory::dao('partsMaitrox')->findColumn('pn = ?', $detail['pn'], 'en');
 			$this->tpl->setFile('purchaseOrder/changePn')
 					->assign('detail', $detail)
 					->display();
 		} else {
	 		$id = intval($_POST['id']);
	 		$orderDao = new PurchaseOrderDao();
	 		$priceDao = new SupplierPriceDao();
	 		
	 		$old = $dao->fetch($id);
	 		$order = $orderDao->fetch($old['purchaseOrderId']);
	 		
	 		try {
	 			$dao->beginTransaction();
	 			$add['aog'] = intval($_POST['aog']);
	 			$in = in_array($order['status'], array(self::STATUS_PROCESS, self::STATUS_BACK)); 
	 			if ($in) {//可以修改PN和qty
	 				$add['pn'] = trim($_POST['pn']);
	 				$price = $priceDao->find('supplierId = ? and pn = ? and priceType = ? and endTime is null', array($order['supplierId'], $add['pn'], PartsPrice::TYPE_PURCHASE));
	 				$add['unitPrice'] = $price[strtolower($order['currency'])];
	 				$add['rmb'] = $price['rmb'];
	 				$add['usd'] = $price['usd'];
	 				$add['qty'] = intval($_POST['qty']);
                    $add['leadTime'] = intval(LdFactory::dao('PartsLeadTime')->findColumn('pn = ?', $add['pn'], 'leadTime'));
		 			if ($add['aog'] > $add['qty']) return ALERT.'|'.LG_PO_PN_AOG_GREATER;
	 			} else {
		 			if ($add['aog'] > $old['qty']) return ALERT.'|'.LG_PO_PN_AOG_GREATER;
	 			}
	 			$add['status'] = intval($_POST['status']);
	 			$add['closeReason'] = intval($_POST['closeReason']);
	 			$add['closeTime'] = date(TIME_FORMAT);
	 			$add['remark'] = trim($_POST['remark']);
	 			$dao->update($id, $add);
	 			if ($in) {
		 			$sum = $add['qty'] - $old['qty'];
		 			$amount =  Crypter::decrypt($add['unitPrice']) * $add['qty'] - Crypter::decrypt($old['unitPrice']) * $old['qty'];
		 			$sql = 'update PurchaseOrder set amount = amount + '.$amount.', pnSum = pnSum + '.$sum.' where id = ?';
		 			$dao->tbl('m')->exec($sql, $old['purchaseOrderId']);
	 			}
	 			$dao->commit();
	 			return SUCCESS.'|'.url('purchaseOrder/pn/'.$old['purchaseOrderId']);
	 		} catch (SqlException $e) {
	 			$dao->rollback();
	 			return ALERT.'|'.LG_PO_PN_CHANGE_FAILED;
	 		}
 		}
 	}
 	
 	public function delPn() {
 		$id = intval($_GET['id']);
 		$dao = new PurchaseOrderDetailDao();
 		try {
 			$dao->beginTransaction();
 			$old = $dao->fetch($id);
 			$amount =  Crypter::decrypt($old['unitPrice']) * $old['qty'];
 			$sql = 'update PurchaseOrder set amount = amount - '.$amount.', pnSum = pnSum - '.$old['qty'].' where id = ?';
 			$dao->tbl('m')->exec($sql, $old['purchaseOrderId']);
 			$dao->delete($id);
 			$dao->commit();
 			return SUCCESS.'|'.url('purchaseOrder/pn/'.$old['purchaseOrderId']);
 		} catch (SqlException $e) {
 			$dao->rollback();
 			return ALERT.'|'.LG_PO_PN_DEL_FAILED;
 		}
 	}
 	
 	public function cancelPn() {
 		$dao = new PurchaseOrderDetailDao();
 		if (empty($_POST)) {
 			$id = intval($_GET['id']);
 			$detail = $dao->fetch($id);
 			$detail['en'] = LdFactory::dao('partsMaitrox')->findColumn('pn = ?', $detail['pn'], 'en');
 			$this->tpl->setFile('purchaseOrder/cancelPn')
 			->assign('detail', $detail)
 			->display();
 		} else {
 			$id = intval($_POST['id']);
 			$old = $dao->fetch($id);
 			try {
 				$dao->beginTransaction();
 				$add['status'] = self::PN_STATUS_CANCEL;
 				$add['cancelTime'] = date(TIME_FORMAT);
 				$add['remark'] = trim($_POST['remark']);
 				$dao->update($id, $add);
 				
 				$amount = Crypter::decrypt($old['unitPrice']) * $old['qty'];
 				$sql = 'update PurchaseOrder set amount = amount - '.$amount.', pnSum = pnSum - '.$old['qty'].' where id = ?';
 				$dao->tbl('m')->exec($sql, $old['purchaseOrderId']);
 				
 				$dao->commit();
 				return SUCCESS.'|'.url('purchaseOrder/pn/'.$old['purchaseOrderId']);
 			} catch (SqlException $e) {
 				$dao->rollback();
 				return ALERT.'|'.LG_PO_PN_CANCEL_FAILED;
 			}
 		}
 	}
 	
 	public function suggestParts() {
 		$pn = trim($_GET['query']);
 		$supplierId = intval($_GET['supplierId']);
 		$currency = trim($_GET['currency']);
 		$dao = new PartsMaitroxDao();
 		$priceDao = new SupplierPriceDao();
        $ltDao = new PartsLeadTimeDao();
 		$pns = $dao->findAll(array('pn like ? and purchasable = 1', $pn.'%'), 10, 0, '', 'pn,en', PDO::FETCH_ASSOC);
        $tmp = array();
 		foreach ($pns as $pn) {
 			$price = Crypter::decrypt($priceDao->findColumn('supplierId = ? and pn = ? and priceType = ? and endTime is null', array($supplierId, $pn['pn'], PartsPrice::TYPE_PURCHASE), $currency));
 			$price = round($price, 2);
 			$tmp[] = array(
 					'data' => $pn['pn'],
 					'value' => $pn['pn'].'--'.$pn['en'],
 					'price' => $price,
 					'en' => $pn['en'],
                    'lt' => intval($ltDao->findColumn('pn = ?', $pn['pn'], 'leadTime'))
 			);
 		}
 		return json_encode(array('suggestions'=> $tmp));
 	}
 	
 	public function report() {
 		$dao = new PurchaseOrderDao();
 		list($condition, $params) = $this->_getSearchCondition();
 		$list = $dao->hasA('Supplier', 'Supplier.supplier')
 		->hasA('Warehouse', 'Warehouse.name as warehouse')
 		->hasA('Users', 'Users.nickname', 'createUserId')
 		->findAll(array($condition, $params), 0, 0, 'PurchaseOrder.id desc');
 		$menu = array(
 			'code' => LG_PO_CODE,
 			'supplier' => LG_PO_SUPPLIER,
 			'warehouse' => LG_PO_WAREHOUSE,
 			'demandTime' => LG_PO_DEMAND_TIME,
 			'type' => LG_PO_TYPE,
 			'createTime' => LG_PO_CREATE_TIME,
 			'commitTime' => LG_PO_COMMIT_TIME,
 			'nickname' => LG_PO_CREATE_USER,
 			'pnSum' => LG_PO_SUM,
 			'amount' => LG_PO_AMOUNT,
 			'currency' => LG_PO_CURRENCY,
 			'warranty' => LG_PO_WARRANTY,
 			'status' => LG_PO_STATUS,
 			'approve' => LG_PO_APPROVE,
 			'remark' => LG_PO_REMARK
 		);
 		
 		$conf = Load::conf('PurchaseOrder');
 		foreach ($list as $k => $v) {
 			$list[$k]['type'] = $conf['type'][$v['type']];
 			$list[$k]['warranty'] = $conf['warranty'][$v['warranty']];
 			$list[$k]['status'] = $conf['status'][$v['status']];
 			$list[$k]['approve'] = $conf['approve'][$v['approve']];
 		}
 		$excel = new Excel();
 		$filename = $excel->write($menu, $list);
 		return SUCCESS.'|'.url('purchaseOrder/download/'.base64_encode($filename));
 	} 
 	
 	public function download() {
 		$name = base64_decode(trim($_GET['id']));
 		downloadLink($name, 'PO('.getCurrentDate(gmdate(DATE_FORMAT)).').csv');
 	}
 	
 	public function parts() {
 		$suppliers = LdFactory::dao('supplier')->findAll('deleted = 0');
 		$this->tpl->setFile('purchaseOrder/parts')->assign('suppliers', $suppliers)->display();
 	}
 	
 	public function partsTbl() {
 		$dao = new PurchaseOrderDetailDao();
 		list($condition, $params) = $this->_getPartsSearchCondition();
 		$pager = pager(array(
 				'base' => 'purchaseOrder/partsTbl',
 				'cur'  => empty($_GET['id']) ? 1 : intval($_GET['id']),
 				'cnt'  => $dao->getCount($condition, $params),
 		));
 		
 		$list = $dao->getList($condition, $params, $pager);
 		$this->tpl->setFile('purchaseOrder/partsTbl')
 		->assign('list', $list)
 		->assign('pager', $pager['html'])
 		->display();
 	}
 	
 	public function partsReport() {
 		$dao = new PurchaseOrderDetailDao();
 		list($condition, $params) = $this->_getPartsSearchCondition();
 		$list = $dao->getList($condition, $params);
 		$menu = array(
 				'code' => LG_PO_CODE,
 				'supplier' => LG_PO_SUPPLIER,
 				'warehouse' => LG_PO_WAREHOUSE,
 				'pn' => LG_PN,
 				'en' => LG_PO_DESCR,
 				'price' => LG_PO_PRICE,
 				'qty' => LG_PO_QTY,
 				'amount' => LG_PO_PN_AMOUNT,
 				'aog' => LG_PO_AOG,
 				'nonArrival' => LG_PO_NON_ARRIVAL,
 				'status' => LG_PO_STATUS,
 				'closeReason' => LG_PO_CLOSE_REASON,
 				'remark' => LG_PO_PN_REMARK
 		);
 			
 		$conf = Load::conf('PurchaseOrder');
 		foreach ($list as $k => $v) {
 			$list[$k]['price'] = Crypter::decrypt($v['unitPrice']);
 			$list[$k]['amount'] = round($v['qty'] * $list[$k]['price'], 4);
 			$list[$k]['nonArrival'] = $v['qty'] - $v['aog'];
 			$list[$k]['status'] = $conf['pnStatus'][$v['status']];
 			$list[$k]['closeReason'] = $conf['closeReason'][$v['closeReason']];
 		}
 		$excel = new Excel();
 		$filename = $excel->write($menu, $list);
 		return SUCCESS.'|'.url('purchaseOrder/download/'.base64_encode($filename));
 	}
 	
 	public static function canChange($order) {
 		$arr = array(PurchaseOrder::STATUS_PROCESS, PurchaseOrder::STATUS_BACK);
 		return in_array($order['status'], $arr) && ($_SESSION[USER]['isAdmin'] || $order['createUserId'] == $_SESSION[USER]['id']);
 	}
 	
 	public static function canDel($order) {
 		$arr = array(PurchaseOrder::STATUS_PROCESS, PurchaseOrder::STATUS_BACK);
 		return in_array($order['status'], $arr) && ($_SESSION[USER]['isAdmin'] || $order['createUserId'] == $_SESSION[USER]['id']);
 	}
 	
 	public static function canApprove($order) {
 		return $order['status'] == PurchaseOrder::STATUS_COMMIT && $_SESSION[USER]['usergroup'] != 15;
 	}
 	
 	public static function canPN($order) {
 		return $order['status'] != PurchaseOrder::STATUS_COMMIT && ($_SESSION[USER]['isAdmin'] || $order['createUserId'] == $_SESSION[USER]['id']);
 	}
 	
 	public static function canClose($order) {
 		return $order['status'] == PurchaseOrder::STATUS_APPROVE && ($_SESSION[USER]['isAdmin'] || $order['createUserId'] == $_SESSION[USER]['id']);
 	}
	
	private function _getSearchCondition() {
		$condition = 'PurchaseOrder.deleted = 0';
		$params = array();
		if (!empty($_POST['code'])) {
			$condition .= ' and PurchaseOrder.code = ?';
			$params[] = trim($_POST['code']); 
		}
		if (!empty($_POST['from'])) {
			$from = date(TIME_FORMAT, strtotime($_POST['from'].' 00:00:00'));
			$condition .= ' and PurchaseOrder.createTime >= ?';
			$params[] = $from;
		}
		if (!empty($_POST['to'])) {
			$to = date(TIME_FORMAT, strtotime($_POST['to'].' 23:59:59'));
			$condition .= ' and PurchaseOrder.createTime <= ?';
			$params[] = $to;
		}
		if (!empty($_POST['supplier'])) {
			$condition .= ' and PurchaseOrder.supplierId = ?';
			$params[] = intval($_POST['supplier']); 
		}
		
		$conf = Load::conf('PurchaseOrder');
		if (!empty($_POST['type']) && (count($_POST['type']) < count($conf['type']))) {
			$type = implode(',', $_POST['type']);
			$condition .= " and PurchaseOrder.type in ({$type})";
		}
		if (!empty($_POST['status']) && (count($_POST['status']) < count($conf['status']))) {
			$status = implode(',', $_POST['status']);
			$condition .= " and PurchaseOrder.status in ({$status})";
		}
		return array($condition, $params);
	}
	
	private function _getPartsSearchCondition() {
		$condition = 'PurchaseOrder.deleted = 0 and PurchaseOrderDetail.deleted = 0';
		$params = array();
		
		$conf = Load::conf('PurchaseOrder');
		if (!empty($_POST['status']) && (count($_POST['status']) < count($conf['pnStatus']))) {
			$status = implode(',', $_POST['status']);
			$condition .= " and PurchaseOrderDetail.status in ({$status})";
		}
		
		if (!empty($_POST['po'])){
			$condition .= " and PurchaseOrder.code like ?";
			$params[] = LdFilter::str($_POST['po']).'%';
		}
		
		if (!empty($_POST['pn'])){
			$condition .= " and PurchaseOrderDetail.pn like ?";
			$params[] = LdFilter::str($_POST['pn']).'%';
		}
		
		if (!empty($_POST['suppliers'])){
			$supplier = implode(',', $_POST['suppliers']);
			$condition .= " and Supplier.id in ({$supplier})";
		}
		return array($condition, $params);
	}
	
	
	private function _getCode($type) {
		$dao = new PurchaseOrderSequenceDao();
		
		$seq = $dao->find('type = ?', $type);
		$day = date('Ymd');
		if (empty($seq)) {
			$dao->insert(array(
					'type' => $type,
					'sequence' => 1,
					'currentDate' => $day
			));
			$seq = '001';
		} else {
			if ($day != $seq['currentDate']) {//不是同一天
				$dao->update($seq['id'], array(
						'sequence' => 1,
						'currentDate' => $day
				));
				$seq = '001';
			} else {
				$dao->update($seq['id'], array('sequence' => $seq['sequence'] + 1));
				$seq = str_pad($seq['sequence']+1, 3, 0, STR_PAD_LEFT);
			}
		}
		return $type.$day.$seq;
	}

	function beforeAction($action) {
		if (!User::logined()) return User::gotoLogin();
		if (!User::can()) redirect('error/accessDenied');
	}
	
	const TYPE_NPI = 1;
	const TYPE_PSI = 2;
	const TYPE_EOL = 3;
	const TYPE_OMP = 4;
	
	const STATUS_PROCESS = 1;
	const STATUS_COMMIT = 2;
	const STATUS_APPROVE = 3;
	const STATUS_BACK = 4;
	const STATUS_CLOSED = 5;
	
	const WARRANTY_IN = 1;
	const WARRANTY_OUT = 2;
	
	const PN_STATUS_OPEN = 1;
	const PN_STATUS_CLOSE = 2;
	const PN_STATUS_CANCEL = 3;
	
	const PN_CLOSE_VENDOR_NOT_ETD = 1;
	const PN_CLOSE_LOGISTICS_BROKEN = 2;
	const PN_CLOSE_MISOPERATION = 3;
	const PN_CLOSE_PO_MODIFICATION = 4;
	const PN_CLOSE_EXCESS_OF_RECEIPT = 5;
}