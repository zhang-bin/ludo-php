<?php
/**
 * Ludo BillGo Platform
 *
 * @author     zhangbin <hunter.zhangbin@gmail.com>
 * @copyright  Copyright (c) 2012 Ludo team (http://www.loongjoy.com)
 * @version    $Id$
 */
class PartsDelivery extends LdBaseCtrl {
	public function __construct() {
		parent::__construct('PartsDelivery');
	}
	
	public function index() {
		$this->compare();
	}
	public function compare() {
		if (empty($_FILES)) {
			$this->tpl->setFile('partsDelivery/compare')->display();
		} else {
			ini_set('memory_limit', '1000M');
			Load::helper('excel/PHPExcel');
			$reader = new PHPExcel_Reader_Excel5();
			if (!$reader->canRead($_FILES['parts']['tmp_name'])) {
				$reader = new PHPExcel_Reader_Excel2007();
			}
			$excel = $reader->load($_FILES['parts']['tmp_name']);
			spl_autoload_register('__autoload');
			$sheet = $excel->getSheet(0);
			$maxRow = $sheet->getHighestRow();
	
			$dao = new PurchaseOrderDao();
			$detailDao = new PurchaseOrderDetailDao();
			$pos = array();
			$result = array();
			for ($i = 2; $i <= $maxRow; $i++) {
				$code = trim($sheet->getCellByColumnAndRow(0, $i)->getValue());
				if (empty($code)) break;
				$pn = trim($sheet->getCellByColumnAndRow(1, $i)->getValue());
				$qty = trim($sheet->getCellByColumnAndRow(2, $i)->getValue());
					
				do {
					if (!isset($pos[$code])) {
						$po = $dao->find('code = ?', $code, '*', PDO::FETCH_ASSOC);
						if (empty($po)) break;
							
						$pns = $detailDao->findAll(array('purchaseOrderId = ? and status = ? and deleted = 0', array($po['id'], PurchaseOrder::PN_STATUS_OPEN)), 0, 0, '', 'id,pn,qty,aog', PDO::FETCH_ASSOC);
						if (empty($pns)) break;

						$pos[$code] = $po;
						foreach ($pns as $v) {
							$pos[$code][$v['pn']]['cfm'] += $v['qty'] - $v['aog'];
						}
					}
				} while(0);
				$result[] = array(
						'id' => $po['id'],
						'code' => $code,
						'pn' => $pn,
						'qty' => $qty,
						'cfm' => intval($pos[$code][$pn]['cfm'])
				);
			}
			$_SESSION['po'] = $result;
			$this->tpl->setFile('partsDelivery/compareResult')->display();
		}
	}
	
	public function compareReport() {
		$menu = array(
				'code' => LG_PO_CODE,
				'pn' => LG_PN,
				'qty' => LG_PN_QTY,
				'cfm' => LG_PN_CFM_QTY
		);
			
		$excel = new Excel();
		$filename = $excel->write($menu, $_SESSION['po']);
		downloadLink($filename, 'PO('.getCurrentDate(gmdate(DATE_FORMAT)).').csv');
	}
	
	public function compareConfirm() {
		$tmp = array();
		foreach ($_SESSION['po'] as $po) {
			$tmp[$po['id']][$po['pn']] += $po['qty'];
		}
		$dao = new PurchaseOrderDetailDao();
		try {
			$dao->beginTransaction();
			foreach ($tmp as $poId => $pns) {
				foreach ($pns as $pn => $qty) {
					$details = $dao->findAll(array('purchaseOrderId = ? and pn = ? and status = ? and deleted = 0', array($poId, $pn, PurchaseOrder::PN_STATUS_OPEN)));
					if (empty($details)) continue;
					foreach ($details as $detail) {
						$cfm = $detail['qty'] - $detail['aog'];
						if ($qty >= $cfm) {
							$dao->update($detail['id'], array('status' => PurchaseOrder::PN_STATUS_CLOSE, 'aog' => $detail['qty'], 'closeTime' => date(TIME_FORMAT)));
							$qty -= $cfm;
						} else {
							$dao->update($detail['id'], array('aog' => $detail['aog'] + $qty));
							break;
						}
					}
				}
			}
			$dao->commit();
			return 'alert2go|success|'.url('purchaseOrder');
		} catch (SqlException $e) {
			$dao->rollback();
			return ALERT.'|failed';
		}
	}
	
	public function factory() {
		$this->tpl->setFile('partsDelivery/factory')->display();
	}
	
	public function factoryTbl() {
		$dao = new PartsDeliveryFactoryDao();
		list($condition, $params) = $this->_getFactoryCondition();
		$pager = pager(array(
				'base' => 'partsDelivery/factoryTbl',
				'cur'  => empty($_GET['id']) ? 1 : intval($_GET['id']),
				'cnt'  => $dao->count($condition, $params),
		));
		$list = $dao->getList($condition, $params, $pager);
		$this->tpl->setFile('partsDelivery/factoryTbl')
		->assign('list', $list)
		->assign('pager', $pager['html'])
		->display();
	}
	
	public function factoryAdd() {
		if (empty($_POST)) {
			$shippers = LdFactory::dao('partsShipper')->findAll('deleted = 0');
			$consignees = LdFactory::dao('partsConsignee')->findAll('deleted = 0');
			$warehouses = LdFactory::dao('warehouse')->findAll('vendorId = 7 and goodOrBad = 0');
			$this->tpl->setFile('partsDelivery/factoryChange')
					->assign('shippers', $shippers)
					->assign('consignees', $consignees)
					->assign('warehouses', $warehouses)
					->display();
		} else {
			$dao = new PartsDeliveryFactoryDao();
			if (!empty($_POST['id'])) {
				$old = $dao->fetch($_POST['id']);
				if ($old['status'] == self::STATUS_FACTORY_SUBMIT) return SUCCESS.'|'.url('partsDelivery/factory');
			}
			
			$detailDao = new PartsDeliveryFactoryDetailDao();
			$poDetailDao = new PurchaseOrderDetailDao();
			$so['departureWarehouseId'] = $add['departureWarehouseId'] = intval($_POST['departureWarehouseId']);
			if (empty($add['departureWarehouseId'])) return ALERT.'|'.LG_DEPARTURE_WAREHOUSE_EMPTY;
			$so['destinationWarehouseId'] = $add['destinationWarehouseId'] = intval($_POST['destinationWarehouseId']);
			if (empty($add['destinationWarehouseId'])) return ALERT.'|'.LG_DESTINATION_WAREHOUSE_EMPTY;
			
			$add['shipperId'] = intval($_POST['shipperId']);
			$add['consigneeId'] = intval($_POST['consigneeId']);
			$so['shipper'] = $add['shipper'] = trim($_POST['shipper']);
			$so['trackingNum'] = $add['trackingNum'] = trim($_POST['trackingNum']);
			$add['status'] = self::STATUS_FACTORY_SUBMIT;
			$add['submitTime'] = date(TIME_FORMAT);
			$add['createUserId'] = $_SESSION[USER]['id'];
			
			try {
				$dao->beginTransaction();
				if (empty($_POST['id'])) {
					$add['createTime'] = $add['submitTime'];
					$add['code'] = self::_getCode(self::FROM_FACTORY, $add['departureWarehouseId']);
					$add['id'] = $dao->insert($add);
				} else {
					$add['id'] = intval($_POST['id']);
					$dao->update($add['id'], $add);
				}
					
				if (!empty($_POST['delivery'])) {
					$details = $soDetails = array();
					foreach ($_POST['delivery'] as $detailId => $qty) {
						$poDetail = $poDetailDao->fetch($detailId);
						if ($poDetail['aog'] - $poDetail['delivery'] < $qty) return ALERT.'|'.LG_PARTS_DELIVERY_FACTORY_QTY_ERROR;
						if (empty($poDetail)) continue;
						$details[] = array(
								'partsDeliveryFactoryId' => $add['id'],
								'purchaseOrderId' => $poDetail['purchaseOrderId'],
								'purchaseOrderDetailId' => $detailId,
								'pn' => $poDetail['pn'],
								'deliveryQty' => $qty,
								'unitPrice' => $poDetail['unitPrice']
						);
						$poDetailDao->update($poDetail['id'], array('delivery' => $poDetail['delivery'] + $qty));
						$soDetails[] = array(
								'pn' => $poDetail['pn'],
								'qty' => $qty
						);
					}
					$detailDao->deleteWhere('partsDeliveryFactoryId = ?', $add['id']);
					$detailDao->batchInsert($details);
					$soId = ShippingOrder::addSO($so, $soDetails);
					
					if (false === $soId) return ALERT.'|'.LG_PARTS_DELIVERY_FACTORY_SUBMIT_FAILED;
					$dao->update($add['id'], array('shippingOrderId' => $soId));
				}
				$dao->commit();
				return SUCCESS.'|'.url('partsDelivery/factory');
			} catch (SqlException $e) {
				$dao->rollback();
				return ALERT.'|'.LG_PARTS_DELIVERY_FACTORY_SUBMIT_FAILED;
			}
		}
	}
	
	public function factorySave() {
		$dao = new PartsDeliveryFactoryDao();
		$detailDao = new PartsDeliveryFactoryDetailDao();
		$poDetailDao = new PurchaseOrderDetailDao();
		$add['departureWarehouseId'] = intval($_POST['departureWarehouseId']);
		if (empty($add['departureWarehouseId'])) return ALERT.'|'.LG_DEPARTURE_WAREHOUSE_EMPTY;
		$add['destinationWarehouseId'] = intval($_POST['destinationWarehouseId']);
		if (empty($add['destinationWarehouseId'])) return ALERT.'|'.LG_DESTINATION_WAREHOUSE_EMPTY;
		
		$add['shipperId'] = intval($_POST['shipperId']);
		$add['consigneeId'] = intval($_POST['consigneeId']);
		$add['shipper'] = trim($_POST['shipper']);
		$add['trackingNum'] = trim($_POST['trackingNum']);
		
		try {
			$dao->beginTransaction();
			if (empty($_POST['id'])) {
				$add['code'] = self::_getCode(self::FROM_FACTORY, $add['departureWarehouseId']);
				$add['createTime'] = date(TIME_FORMAT);
				$add['createUserId'] = $_SESSION[USER]['id'];
				$add['status'] = self::STATUS_FACTORY_PROCESS;
				$add['id'] = $dao->insert($add);
			} else {
				$add['id'] = intval($_POST['id']);
				$dao->update($add['id'], $add);
			}
			
			if (!empty($_POST['delivery'])) {
				$details = array();
				foreach ($_POST['delivery'] as $detailId => $qty) {
					$poDetail = $poDetailDao->fetch($detailId);
					if ($poDetail['aog'] - $poDetail['delivery'] < $qty) return ALERT.'|'.LG_PARTS_DELIVERY_FACTORY_QTY_ERROR;
					if (empty($poDetail)) continue;
					$details[] = array(
							'partsDeliveryFactoryId' => $add['id'],
							'purchaseOrderId' => $poDetail['purchaseOrderId'],
							'purchaseOrderDetailId' => $detailId,
							'pn' => $poDetail['pn'],
							'deliveryQty' => $qty
					);	
				}
				$detailDao->deleteWhere('partsDeliveryFactoryId = ?', $add['id']);
				$detailDao->batchInsert($details);
			}
			$dao->commit();
			return SUCCESS.'|'.url('partsDelivery/factory');
		} catch (SqlException $e) {
			$dao->rollback();
			return ALERT.'|'.LG_PARTS_DELIVERY_FACTORY_SAVE_FAILED;	
		}
	}
	
	public function factoryChange() {
		$id = intval($_GET['id']);
		$dao = new PartsDeliveryFactoryDao();
		$detailDao = new PartsDeliveryFactoryDetailDao();
		$order = $dao->getInfo($id);
		$details = $detailDao->getList($id);
		$shippers = LdFactory::dao('partsShipper')->findAll('deleted = 0');
		$consignees = LdFactory::dao('partsConsignee')->findAll('deleted = 0');
		$this->tpl->setFile('partsDelivery/factoryChange')
					->assign('shippers', $shippers)
					->assign('consignees', $consignees)
					->assign('order', $order)
					->assign('details', $details)
					->display();
	}
	
	public function factoryDel() {
		$id = intval($_GET['id']);
		$dao = new PartsDeliveryFactoryDao();
		try {
			$dao->beginTransaction();
			$dao->update($id, array('deleted' => 1));
			$dao->commit();
			return SUCCESS.'|'.url('partsDelivery/factory');
		} catch (SqlException $e) {
			$dao->rollback();
			return ALERT.'|'.LG_PARTS_DELIVERY_FACTORY_DEL_FAILED;
		}
	}
	
	public function factoryCancel() {
		$dao = new PartsDeliveryFactoryDao();
		$detailDao = new PartsDeliveryFactoryDetailDao();
		if (empty($_POST)) {
			$id = intval($_GET['id']);
			$order = $dao->getInfo($id);
			$details = $detailDao->getList($id);
			$this->tpl->setFile('partsDelivery/factoryCancel')
			->assign('order', $order)
			->assign('details', $details)
			->display();
		} else {
			$id = intval($_POST['id']);
			$poDetailDao = new PurchaseOrderDetailDao();
			try {
				$dao->beginTransaction();
				$old = $dao->fetch($id);
				if ($old['status'] != self::STATUS_FACTORY_SUBMIT) return ALERT.'|'.LG_PARTS_DELIVERY_FACTORY_CANCEL_FAILED;
				
				$details = $detailDao->getList($id);
				foreach ($details as $detail) {
					$poDetailDao->update($detail['purchaseOrderDetailId'], array('delivery' => $detail['delivery'] - $detail['deliveryQty']));
				}
				
				$dao->update($id, array(
						'status' => self::STATUS_FACTORY_CANCEL, 
						'cancelReason' => trim($_POST['cancelReason']),
						'cancelTime' => date(TIME_FORMAT)
				));
				ShippingOrder::delSO($old['shippingOrderId']);
				$dao->commit();
				return SUCCESS.'|'.url('partsDelivery/factory');
			} catch (SqlException $e) {
				$dao->rollback();
				return ALERT.'|'.LG_PARTS_DELIVERY_FACTORY_CANCEL_FAILED;
			}
		}
	}
	
	public function factoryClose() {
		$dao = new PartsDeliveryFactoryDao();
		if (empty($_POST)) {
			$id = intval($_GET['id']);
			$detailDao = new PartsDeliveryFactoryDetailDao();
			$order = $dao->getInfo($id);
			$details = $detailDao->getList($id);
			$this->tpl->setFile('partsDelivery/factoryClose')
			->assign('order', $order)
			->assign('details', $details)
			->display();
		} else {
			$id = intval($_POST['id']);
			try {
				$dao->beginTransaction();
				$old = $dao->fetch($id);
				if ($old['status'] != self::STATUS_FACTORY_SUBMIT) return ALERT.'|'.LG_PARTS_DELIVERY_FACTORY_CLOSE_FAILED;
				
				$dao->update($id, array(
						'status' => self::STATUS_FACTORY_CLOSE, 
						'closeTime' => date(TIME_FORMAT)
				));
				$dao->commit();
				return SUCCESS.'|'.url('partsDelivery/factory');
			} catch (SqlException $e) {
				$dao->rollback();
				return ALERT.'|'.LG_PARTS_DELIVERY_FACTORY_CLOSE_FAILED;
			}
		}
	}
	
	public function factoryDuplicate() {
		$id = intval($_GET['id']);
		$dao = new PartsDeliveryFactoryDao();
		$detailDao = new PartsDeliveryFactoryDetailDao();
		$order = $dao->getInfo($id);
		$details = $detailDao->getList($id);
		$shippers = LdFactory::dao('partsShipper')->findAll('deleted = 0');
		$consignees = LdFactory::dao('partsConsignee')->findAll('deleted = 0');
		$warehouses = LdFactory::dao('warehouse')->findAll('vendorId = 7 and goodOrBad = 0');
		unset($order['id']);
		$this->tpl->setFile('partsDelivery/factoryChange')
		->assign('shippers', $shippers)
		->assign('consignees', $consignees)
		->assign('order', $order)
		->assign('details', $details)
		->assign('warehouses', $warehouses)
		->assign('duplicate', true)
		->display();
	}
	
	public function factoryInvoice() {
		$id = intval($_GET['id']);
		$dao = new PartsDeliveryFactoryDao();
		$detailDao = new PartsDeliveryFactoryDetailDao();
		$order = $dao->fetch($id);
		$order['consigneeInfo'] = LdFactory::dao('partsConsignee')->fetch($order['consigneeId']);
		$order['shipperInfo'] = LdFactory::dao('partsShipper')->fetch($order['shipperId']);
		$details = $detailDao->getInvoice($id);
		$this->_invoice($order, $details);
	}
	
	public function searchPN() {
		$poCode = trim($_POST['poCode']);
		$poId = LdFactory::dao('purchaseOrder')->findColumn('code = ?', $poCode, 'id');
		if (empty($poId)) return;
		$condition = 'purchaseOrderId = ?';
		$params[] = $poId;
		
		$dao = new PurchaseOrderDetailDao();
		$pager = pager(array(
				'base' => 'partsDelivery/searchPN',
				'cur'  => empty($_GET['id']) ? 1 : intval($_GET['id']),
				'cnt'  => $dao->count($condition, $params)
		));
		
		$details = $dao->findAll(array($condition, $params), $pager['rows'], $pager['start'], '', 'id,pn,qty,aog,delivery', PDO::FETCH_ASSOC);
		$this->tpl->setFile('partsDelivery/pnResult')
				->assign('details', $details)
				->assign('pager', $pager['html'])
				->display();
	}
	
	public function warehouse() {
		$this->tpl->setFile('partsDelivery/warehouse')
				->display();
	}
	
	public function warehouseTbl() {
		$dao = new PartsDeliveryWarehouseDao();
		list($condition, $params) = $this->_getWarehouseCondition();
		$pager = pager(array(
				'base' => 'partsDelivery/warehouseTbl',
				'cur'  => empty($_GET['id']) ? 1 : intval($_GET['id']),
				'cnt'  => $dao->count($condition, $params),
		));
		
		$list = $dao->getList($condition, $params, $pager);
		$this->tpl->setFile('partsDelivery/warehouseTbl')
					->assign('list', $list)
					->assign('pager', $pager['html'])
					->display();
	}
	
	public function warehouseAdd() {
		if(empty($_POST)) {
			$fromWarehouses = LdFactory::dao('warehouse')->findAll('vendorId = 7', 0, 0, 'name');
			$toWarehouses = LdFactory::dao('warehouse')->findAll('isPrimary = 1', 0, 0, 'name');
			$consignees = LdFactory::dao('PartsConsignee')->findAll('deleted = 0');
			$shippers = LdFactory::dao('PartsShipper')->findAll('deleted = 0');
			$this->tpl->setFile('partsDelivery/warehouseChange')
				->assign('fromWarehouses', $fromWarehouses)
				->assign('toWarehouses', $toWarehouses)
				->assign('consignees', $consignees)
				->assign('shippers', $shippers)
				->display();
		} else {
			$dao = new PartsDeliveryWarehouseDao();
			if (!empty($_POST['id'])) {
				$old = $dao->fetch($_POST['id']);
				if ($old['status'] == self::STATUS_WAREHOUSE_SUBMIT) return SUCCESS.'|'.url('partsDelivery/warehouse');
			}
			
			$detailDao = new PartsDeliveryWarehouseDetailDao();
			$so['departureWarehouseId'] = $add['departureWarehouseId'] = intval($_POST['departureWarehouseId']);
			if (empty($add['departureWarehouseId'])) return ALERT.'|'.LG_DEPARTURE_WAREHOUSE_EMPTY;
			
			$so['destinationWarehouseId'] = $add['destinationWarehouseId'] = intval($_POST['destinationWarehouseId']);
			if (empty($add['destinationWarehouseId'])) return ALERT.'|'.LG_DESTINATION_WAREHOUSE_EMPTY;
			
			$so['shipper'] = $add['shipper'] = trim($_POST['shipper']);
			if (empty($add['shipper'])) return ALERT.'|'.LG_DESTINATION_SHIPPER_EMPTY;
			
			$so['trackingNum'] = $add['trackingNum'] = trim($_POST['trackingNum']);
			if (empty($add['trackingNum'])) return ALERT.'|'.LG_DESTINATION_AWB_EMPTY;
			
			$add['consigneeId'] = intval($_POST['consigneeId']);
			if (empty($add['consigneeId'])) return ALERT.'|'.LG_DESTINATION_INVOICE_CONSIGNEE_EMPTY;
			
			$add['shipperId'] = intval($_POST['shipperId']);
			if (empty($add['shipperId'])) return ALERT.'|'.LG_DESTINATION_INVOICE_SHIPPER_EMPTY;
			
			$add['status'] = self::STATUS_WAREHOUSE_SUBMIT;
			$add['submitTime'] = date(TIME_FORMAT);
			$add['createUserId'] = $_SESSION[USER]['id'];
			$pns = array_filter($_POST['pn']);
			$qty = array_filter($_POST['qty']);
			
			try {
				$dao->beginTransaction();
				if (empty($_POST['id'])) {
					$add['createTime'] = $add['submitTime'];
					$add['code'] = self::_getCode(self::FROM_WAREHOUSE, $add['destinationWarehouseId']);
					$add['id'] = $dao->insert($add);
				} else {
					$add['id'] = intval($_POST['id']);
					$dao->update($add['id'], $add);
				}
					
				$details = $soDetails = array();
				foreach ($pns as $k=>$v){
					if (empty($v)) continue;
					$details[] = array(
							'pn' => $v,
							'deliveryQty' => $qty[$k],
							'unitPrice' => $this->_getPnPrice($v),
							'partsDeliveryWarehouseId' => $add['id']
					);
						
					$soDetails[] = array(
							'pn' => $v,
							'qty' => $qty[$k]
					);
				}
				
				$detailDao->deleteWhere('partsDeliveryWarehouseId = ?', $add['id']);
				$detailDao->batchInsert($details);
					
				$soId = ShippingOrder::addSO($so, $soDetails);
				if (false === $soId) return ALERT.'|'.LG_PARTS_DELIVERY_WAREHOUSE_SUBMIT_FAILED;
				$dao->update($add['id'], array('shippingOrderId' => $soId));
				
				$dao->commit();
				return SUCCESS.'|'.url('partsDelivery/warehouse');
			}catch (SqlException $e){
				$dao->rollback();
				return ALERT.'|'.LG_PARTS_DELIVERY_WAREHOUSE_SUBMIT_FAILED;
			}
		}
	}
	
	public function warehouseSave() {
		$dao = new PartsDeliveryWarehouseDao();
		$detailDao = new PartsDeliveryWarehouseDetailDao();
		$so['departureWarehouseId'] = $add['departureWarehouseId'] = intval($_POST['departureWarehouseId']);
		if (empty($add['departureWarehouseId'])) return ALERT.'|'.LG_DEPARTURE_WAREHOUSE_EMPTY;
			
		$so['destinationWarehouseId'] = $add['destinationWarehouseId'] = intval($_POST['destinationWarehouseId']);
		if (empty($add['destinationWarehouseId'])) return ALERT.'|'.LG_DESTINATION_WAREHOUSE_EMPTY;
			
		$add['shipper'] = trim($_POST['shipper']);
		$add['trackingNum'] = trim($_POST['trackingNum']);
		$add['consigneeId'] = intval($_POST['consigneeId']);
		$add['shipperId'] = intval($_POST['shipperId']);
			
		$add['status'] = self::STATUS_WAREHOUSE_PROCESS;
		$pns = array_filter($_POST['pn']);
		$qty = array_filter($_POST['qty']);
			
		try {
			$dao->beginTransaction();
			if (empty($_POST['id'])) {
				$add['code'] = self::_getCode(self::FROM_WAREHOUSE, $add['destinationWarehouseId']);
				$add['createTime'] = date(TIME_FORMAT);
				$add['createUserId'] = $_SESSION[USER]['id'];
				$add['id'] = $dao->insert($add);
			} else {
				$add['id'] = intval($_POST['id']);
				$dao->update($add['id'], $add);
			}
			
			$details = array();				
			foreach ($pns as $k=>$v){
				if (empty($v)) continue;
				$details[] = array(
						'pn' => $v,
						'deliveryQty' => $qty[$k],
						'partsDeliveryWarehouseId' => $add['id']
				);
			}
			$detailDao->deleteWhere('partsDeliveryWarehouseId = ?', $add['id']);
			$detailDao->batchInsert($details);
			$dao->commit();
			return SUCCESS.'|'.url('partsDelivery/warehouse');
		}catch (SqlException $e){
			$dao->rollback();
			return ALERT.'|'.LG_PARTS_DELIVERY_WAREHOUSE_SAVE_FAILED;
		}
	}
	
	public function warehouseChange() {
		$id = intval($_GET['id']);
		$dao = new PartsDeliveryWarehouseDao();
		$detailDao = new PartsDeliveryWarehouseDetailDao();
		$order = $dao->getInfo($id);
		$details = $detailDao->findAll(array('partsDeliveryWarehouseId = ?', $id));
		
		$shippers = LdFactory::dao('partsShipper')->findAll('deleted = 0');
		$consignees = LdFactory::dao('partsConsignee')->findAll('deleted = 0');
		
		$this->tpl->setFile('partsDelivery/warehouseChange')
		->assign('shippers', $shippers)
		->assign('consignees', $consignees)
		->assign('order', $order)
		->assign('details', $details)
		->display();
	}
	
	
	public function warehouseClose() {
		$dao = new PartsDeliveryWarehouseDao();
		if (empty($_POST)) {
			$id = intval($_GET['id']);
			$detailDao = new PartsDeliveryWarehouseDetailDao();
			$order = $dao->getInfo($id);
			$details = $detailDao->findAll(array('partsDeliveryWarehouseId = ?', $id));
			$this->tpl->setFile('partsDelivery/warehouseClose')
			->assign('order', $order)
			->assign('details', $details)
			->display();
		} else {
			$id = intval($_POST['id']);
			try {
				$dao->beginTransaction();
				$old = $dao->fetch($id);
				if ($old['status'] != self::STATUS_WAREHOUSE_SUBMIT) return ALERT.'|'.LG_PARTS_DELIVERY_WAREHOUSE_CLOSE_FAILED;
	
				$dao->update($id, array(
						'status' => self::STATUS_WAREHOUSE_CLOSE,
						'closeTime' => date(TIME_FORMAT)
				));
				$dao->commit();
				return SUCCESS.'|'.url('partsDelivery/warehouse');
			} catch (SqlException $e) {
				$dao->rollback();
				return ALERT.'|'.LG_PARTS_DELIVERY_WAREHOUSE_CLOSE_FAILED;
			}
		}
	}
	
	
	public function warehouseCancel() {
		$dao = new PartsDeliveryWarehouseDao();
		$detailDao = new PartsDeliveryWarehouseDetailDao();
		if (empty($_POST)) {
			$id = intval($_GET['id']);
			$order = $dao->getInfo($id);
			$details = $detailDao->findAll(array('partsDeliveryWarehouseId = ?', $id));
			$this->tpl->setFile('partsDelivery/warehouseCancel')
			->assign('order', $order)
			->assign('details', $details)
			->display();
		} else {
			$id = intval($_POST['id']);
			try {
				$dao->beginTransaction();
				$old = $dao->fetch($id);
				if ($old['status'] != self::STATUS_WAREHOUSE_SUBMIT) return ALERT.'|'.LG_PARTS_DELIVERY_WAREHOUSE_CANCEL_FAILED;
	
				$dao->update($id, array(
						'status' => self::STATUS_WAREHOUSE_CANCEL,
						'cancelReason' => trim($_POST['cancelReason']),
						'cancelTime' => date(TIME_FORMAT)
				));
				ShippingOrder::delSO($old['shippingOrderId']);
				$dao->commit();
				return SUCCESS.'|'.url('partsDelivery/warehouse');
			} catch (SqlException $e) {
				$dao->rollback();
				return ALERT.'|'.LG_PARTS_DELIVERY_WAREHOUSE_CANCEL_FAILED;
			}
		}
	}
	
	public function warehouseDuplicate() {
		$id = intval($_GET['id']);
		$dao = new PartsDeliveryWarehouseDao();
		$detailDao = new PartsDeliveryWarehouseDetailDao();
		$order = $dao->getInfo($id);
		$consignees = LdFactory::dao('PartsConsignee')->findAll('deleted = 0');
		$shippers = LdFactory::dao('PartsShipper')->findAll('deleted = 0');
		$fromWarehouses = LdFactory::dao('warehouse')->findAll('vendorId = 7', 0, 0, 'name');
		$toWarehouses = LdFactory::dao('warehouse')->findAll('isPrimary = 1', 0, 0, 'name');
		$details = $detailDao->findAll(array('partsDeliveryWarehouseId = ?', $id));
		unset($order['id']);
		$this->tpl->setFile('partsDelivery/warehouseChange')
		->assign('consignees', $consignees)
		->assign('shippers', $shippers)
		->assign('order', $order)
		->assign('fromWarehouses', $fromWarehouses)
		->assign('toWarehouses', $toWarehouses)
		->assign('details', $details)
		->assign('duplicate', true)
		->display();
	}
	
	public function warehouseDel() {
		$id = intval($_GET['id']);
		$dao = new PartsDeliveryWarehouseDao();
		try {
			$dao->beginTransaction();
			$dao->update($id, array('deleted' => 1));
			$dao->commit();
			return SUCCESS.'|'.url('partsDelivery/warehouse');
		} catch (SqlException $e) {
			$dao->rollback();
			return ALERT.'|'.LG_PARTS_DELIVERY_WAREHOUSE_DEL_FAILED;
		}
	}
	
	public function warehouseInvoice() {
		$id = intval($_GET['id']);
		$dao = new PartsDeliveryWarehouseDao();
		$detailDao = new PartsDeliveryWarehouseDetailDao();
		$order = $dao->fetch($id);
		$order['consigneeInfo'] = LdFactory::dao('partsConsignee')->fetch($order['consigneeId']);
		$order['shipperInfo'] = LdFactory::dao('partsShipper')->fetch($order['shipperId']);
		$details = $detailDao->getInvoice($id);
		$this->_invoice($order, $details);
	}
	
	private function _getPnPrice($pn){
		$dao = new SupplierPriceDao();
		$encryptPrices = $dao->findAll(array('pn = ? and priceType = ? ', array($pn, PartsPrice::TYPE_PURCHASE)) ,0 ,0 ,'' ,'usd');
		foreach($encryptPrices as $v){
			$decryptPrices[] = Crypter::decrypt($v['usd']);
		}
		return Crypter::encrypt(min($decryptPrices));
	}
	
	private function _getCode($from, $uid) {
		$condition = '`from` = ? and ';
		$params[] = $from;
		if ($from == self::FROM_WAREHOUSE) {
			$uid = LdFactory::dao('warehouse')->fetchColumn($uid, 'vendorId');
			$column = 'vendorId';
			$condition .= 'vendorId = ?';
			$prefix = LdFactory::dao('vendor')->fetchColumn($uid, 'countryShortName');
		} else {
			$column = 'warehouseId';
			$condition .= 'warehouseId = ?';
			$prefix = LdFactory::dao('warehouse')->fetchColumn($uid, 'prefix');
		}
		$params[] = $uid;
		
		$dao = new PartsDeliverySequenceDao();
		
		$seq = $dao->find($condition, $params);
		$day = date('Ymd');
		if (empty($seq)) {
			$dao->insert(array(
					'from' => $from,
					$column => $uid, 
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
		return $prefix.$day.$seq;
	}
	
	private function _invoice($order, $details) {
		$this->tpl->setFile('partsDelivery/invoice')
		->assign('order', $order)
		->assign('details', $details)
		->display();
	}
	
	private function _getFactoryCondition() {
		$condition = 'PartsDeliveryFactory.deleted = 0';
		$params = array();
		if (!empty($_POST['code'])) {
			$condition .= ' and PartsDeliveryFactory.code = ?';
			$params[] = trim($_POST['code']);
		}
		if (!empty($_POST['status'])) {
			$condition .= ' and PartsDeliveryFactory.status = ?';
			$params[] = trim($_POST['status']);
		}
		return array($condition, $params);
	}
	
	private function _getWarehouseCondition() {
		$condition = 'PartsDeliveryWarehouse.deleted = 0';
		$params = array();
		if (!empty($_POST['code'])) {
			$condition .= ' and PartsDeliveryWarehouse.code = ?';
			$params[] = trim($_POST['code']);
		}
		if (!empty($_POST['status'])) {
			$condition .= ' and PartsDeliveryWarehouse.status = ?';
			$params[] = trim($_POST['status']);
		}
		return array($condition, $params);
	}
	
	public function shipper(){
		$this->tpl->setFile('partsDelivery/shipper')->display();
	}
	
	public function shipperList(){
		$dao = new PartsShipperDao();
		$list = $dao->findAll(array('deleted = ?', 0));
	
		$pager = pager(array(
				'base' => 'partsDelivery/shipperList',
				'cur'  => empty($_GET['id']) ? 1 : intval($_GET['id']),
				'cnt'  => $dao->count(),
		));
	
		$this->tpl->setFile('partsDelivery/shipperList')
		->assign('list',$list)
		->assign('pager', $pager['html'])
		->display();
	}
	
	public function addShipper(){
		if(empty($_POST)){
			$this->tpl->setFile('partsDelivery/addShipper')
			->display();
		}else{
			$dao = new PartsShipperDao();
			$add['name'] = trim($_POST['name']);
			$add['companyShortName'] = trim($_POST['shortName']);
			$add['companyName'] = trim($_POST['companyName']);
			$add['address'] = trim($_POST['address']);
			$add['telphone'] = trim($_POST['tel']);
			$add['fax'] = trim($_POST['fax']);
			$add['createTime'] = date(TIME_FORMAT);
				
			try {
				$dao->beginTransaction();
				$dao->insert($add);
				$dao->commit();
				return SUCCESS.'|'.url('PartsDelivery/shipper');
			}catch (Exception $e ){
				$dao->rollback();
				return ALERT.'|'.LG_ADD_ERRORINFO;
			}
		}
	}
	
	public function delShipper(){
		$id = $_GET['id'];
		$dao = new PartsShipperDao();
		try {
			$dao->beginTransaction();
 			$dao->update($id, array('deleted'=>1));
			$dao->commit();
			return SUCCESS.'|'.url('PartsDelivery/shipper');
		} catch (SqlException $e) {
			$dao->rollback();
			return ALERT.'|'.LG_DEL_ERRORINFO;
		}
	}
	
	public function consignee(){
		$this->tpl->setFile('partsDelivery/consignee')->display();
	}
	
	public function consigneeList(){
		$dao = new PartsConsigneeDao();
		$list = $dao->findAll(array('deleted = ?', 0));
		
		$pager = pager(array(
				'base' => 'partsDelivery/consigneeList',
				'cur'  => empty($_GET['id']) ? 1 : intval($_GET['id']),
				'cnt'  => $dao->count(),
		));
		
		$this->tpl->setFile('partsDelivery/consigneeList')
					->assign('list',$list)
					->assign('pager', $pager['html'])
					->display();
	}
	
	public function addConsignee(){
		if(empty($_POST)){
			$this->tpl->setFile('partsDelivery/addConsignee')
			->display();
		}else{
			$dao = new PartsConsigneeDao();
			$add['name'] = trim($_POST['name']);
			$add['company'] = trim($_POST['company']);
			$add['address'] = trim($_POST['address']);
			$add['contact'] = trim($_POST['contact']);
			$add['tel'] = trim($_POST['tel']);
			$add['fax'] = trim($_POST['fax']);
			$add['shipmentFrom'] = trim($_POST['shipmentfrom']);
			$add['transTo'] = trim($_POST['transto']);
			$add['priceTerm'] = trim($_POST['priceterm']);
			$add['createTime'] = date(TIME_FORMAT);
			
			try {
				$dao->beginTransaction();
				$dao->insert($add);
				$dao->commit();
				return SUCCESS.'|'.url('PartsDelivery/consignee');
			}catch (Exception $e ){
				$dao->rollback();
				return ALERT.'|'.LG_ADD_ERRORINFO;
			}
		}
	}
	
	public function delConsignee(){
		$id = $_GET['id'];
		$dao = new PartsConsigneeDao();
		try {
			$dao->beginTransaction();
			$dao->update($id, array('deleted'=>1));
			$dao->commit();
			return SUCCESS.'|'.url('partsDelivery/consignee');
		} catch (SqlException $e) {
			$dao->rollback();
			return ALERT.'|'.LG_DEL_ERRORINFO;
		}
	}
	
	const FROM_FACTORY = 1;
	const FROM_WAREHOUSE = 2;
	
	const STATUS_FACTORY_PROCESS = 1;
	const STATUS_FACTORY_SUBMIT = 2;
	const STATUS_FACTORY_CANCEL = 3;
	const STATUS_FACTORY_CLOSE = 4;
	
	const STATUS_WAREHOUSE_PROCESS = 1;
	const STATUS_WAREHOUSE_SUBMIT = 2;
	const STATUS_WAREHOUSE_CANCEL = 3;
	const STATUS_WAREHOUSE_CLOSE = 4;
	
	function beforeAction($action) {
		if (!User::logined()) return User::gotoLogin();
		if (!User::can()) redirect('error/accessDenied');
	}
}