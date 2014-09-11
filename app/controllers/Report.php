<?php
/**
 * Ludo BillGo Platform
 *
 * @author     zhangbin <hunter.zhangbin@gmail.com>
 * @copyright  Copyright (c) 2012 Ludo team (http://www.loongjoy.com)
 * @version    $Id$
 */
class Report extends LdBaseCtrl {
	private static $_exceptVendor = array('CN');
	private static $_maxBatch = array();
    private $_pager = array();

	public function __construct() {
		parent::__construct('Report');
	}
	
	public function index() {
		$this->useNumber();
	}
	
	public function useNumber() {
		$this->tpl->setFile('report/useNumber')
				->assign('vendors', Api::psiPlanVendors())
                ->assign('partsCategories', Api::getPartsCategories())
				->display();
	}
	
	public function useNumberList() {
		$month = trim($_POST['month']);
		$vendorId = intval($_POST['vendorId']);
		$pn = trim($_POST['pn']);
        $categoryId = intval($_POST['partsCategory']);
		$usages = $this->getUseNumber($month, $vendorId, $categoryId, $pn);
		$this->tpl->setFile('report/useNumberList')
				->assign('usages', $usages)
				->assign('pager', $this->_pager['html'])
				->display();
	}
	
	public function useNumberReport() {
		$month = trim($_POST['month']);
		if (empty($month)) $month = date('Y-m');
		$vendorId = intval($_POST['vendorId']);
        $categoryId = intval($_POST['partsCategory']);
		$usages = $this->getUseNumber($month, $vendorId, $categoryId, '', true);
		$data = array();
		$data[] = array('PN', 'Parts Name', 'Parts Category', 'Service Vendor', 'Month', 'Qty');
		if (!empty($usages)) {
			foreach ($usages as $usage) {
				$data[] = array($usage['pn'], $usage['en'], $usage['partsGroupName'], $usage['countryShortName'], $usage['month'], $usage['qty']);
			}
		}
        $excel = new Excel();
		return SUCCESS.'|'.url('report/useNumberDownload/'.base64_encode($excel->writeData($data)));
	}

    public function useNumberChart() {
        $this->tpl->setFile('report/useNumberChart')
            ->assign('vendors', Api::psiPlanVendors())
            ->assign('partsCategories', Api::getPartsCategories())
            ->display();
    }

    public function useNumberChartData() {
        $vendorId = intval($_GET['vendorId']);
        $partsCategoryId = intval($_GET['partsCategory']);
        $dao = new PartsUseNumberDao();

        $condition = $and = '';
        $params = array();
        if (!empty($vendorId)) {
            $condition .= 'vendorId = ?';
            $params[] = $vendorId;
            $and = ' and ';
        }
        if (!empty($partsCategoryId)) {
            $condition .= $and.'partsCategoryId = ?';
            $params[] = $partsCategoryId;
        }

        $sql = $dao->tbl()->setField('sum(qty) as qty, month');
        if (!empty($condition)) $sql->where($condition, $params);
        $data = $sql->groupby('month')->orderby('month')->fetchAll();

        $tmp = array();
        foreach ($data as $v) {
            $tmp[] = array('month' => $v['month'], 'qty' => $v['qty']);
        }
        return json_encode($tmp);
    }
	
	public function useNumberDownload() {
		$name = base64_decode(trim($_GET['id']));
		downloadLink($name, 'Lenovo Mobile Phone Monthly Usage('.getCurrentDate(gmdate(DATE_FORMAT)).').csv');
	}
	
	public function getUseNumber($month, $vendorId, $partsCategoryId, $pn = '', $all = false) {
		$condition = $and = '';
		$params = array();
		if (!empty($month)) {
			$condition .= 'PartsUseNumber.month = ?';
			$params[] = $month;
			$and = ' and ';			
		}
		if ($vendorId != 0) {
			$condition .= $and.'PartsUseNumber.vendorId = ?';
			$params[] = $vendorId;
			$and = ' and ';			
		}
        if (!empty($partsCategoryId)) {
			$condition .= $and.'PartsUseNumber.partsCategoryId = ?';
			$params[] = $partsCategoryId;
			$and = ' and ';
		}
		if (!empty($pn)) {
			$condition .= $and.'PartsUseNumber.pn = ?';
			$params[] = $pn;
		}

		$dao = new PartsUseNumberDao();
		$cnt = $dao->count($condition, $params);
		if ($cnt == 0) {
			if (empty($month)) $month = date('Y-m');
			$this->setUseNumber($month, $vendorId);
			$cnt = $dao->count($condition, $params);
		}
		if (!$all) {
			$this->_pager = pager(array(
					'base' => 'report/useNumberList',
					'cur' => isset($_GET['id']) ? intval($_GET['id']) : 1,
					'cnt' => $cnt
			));
		}
		return $dao->tbl()
					->leftJoin('Vendor', 'PartsUseNumber.vendorId = Vendor.id', 'Vendor.countryShortName')
					->leftJoin('PartsMaitrox', 'PartsUseNumber.pn = PartsMaitrox.pn', 'PartsMaitrox.en')
                    ->leftJoin('PartsGroup', 'PartsGroup.id = PartsUseNumber.partsCategoryId', 'PartsGroup.partsGroupName')
					->where($condition, $params)
					->limit($this->_pager['rows'], $this->_pager['start'])
					->orderby('month,vendorId,pn')
					->fetchAll();
	}
	
	public function resetUseNumber() {
		$vendorId = intval($_POST['vendorId']);
		$month = trim($_POST['month']);
		if (empty($month)) $month = date('Y-m');
		$rtn = $this->setUseNumber($month, $vendorId);
		if ($rtn) {
			return 1;
		} else {
			return 0;
		}
	}
	
	public function setUseNumber($month, $vendorId) {
		$dao = new PartsUseNumberDao();
		try {
			$dao->beginTransaction();
			$usages = $this->calUseNumber($month, $vendorId);
			$condition = 'month = ?';
			$params[] = $month;
			if ($vendorId != 0) {
				$condition .= ' and vendorId = ?';
				$params[] = $vendorId;
			}
            $vendors = Api::getVendors();
            $tmp = array();
            foreach ($vendors as $vendor) {
                $tmp[$vendor['id']] = $vendor['country'];
            }
            $vendors = $tmp;

			$dao->deleteWhere($condition, $params);
			$add = array();
            $partsDao = new PartsMaitroxDao();
			foreach ($usages as $vendorId => $pns) {
				foreach ($pns as $pn => $qty) {
					$add[] = array(
						'pn' => $pn,
						'vendorId' => $vendorId,
                        'country' => $vendors[$vendorId],
						'month' => $month,
						'qty' => $qty,
                        'partsCategoryId' => $partsDao->findColumn('pn = ?', $pn, 'partsGroupId')
					);
				}
				$dao->batchInsert($add);
				$add = array();
			}
			$dao->commit();
			return true;
		} catch (SqlException $e) {
			$dao->rollback();
			return false;
		}
	}
	
	public function calUseNumber($month, $vendorId) {
		$dao = new ServiceOrderDao();
		$month = strtotime($month);
		$from = date('Y-m-01 00:00:00', $month);
		$end = date('Y-m-t 23:59:59', $month);
		$condition = '(newPN1 != "" or newPN2 != "" or newPN3 != "") and deleted = 0 and createTime >= ? and createTime <= ?
		              and recoverMethod != 5 and recoverMethod != 6';
		$params = array($from, $end);
		
		if ($vendorId != 0) {
			$condition .= ' and vendorId = ?';
			$params[] = $vendorId;
		}
		$vendors = Api::psiPlanVendors();
        $tmp = array();
		foreach ($vendors as $vendor) {
			$tmp[] = $vendor['id'];
		}
		$vendors = $tmp;
		$srs = $dao->findAll(array($condition, $params), 0, 0, '', 'id,vendorId,newPN1,newPN2,newPN3');
		$usages = array();
		foreach ($srs as $sr) {
			if (!in_array($sr['vendorId'], $vendors)) continue;
			if (!empty($sr['newPN1'])) {
				$usages[$sr['vendorId']][$sr['newPN1']]++;
			}
			if (!empty($sr['newPN2'])) {
				$usages[$sr['vendorId']][$sr['newPN2']]++;
			}
			if (!empty($sr['newPN3'])) {
				$usages[$sr['vendorId']][$sr['newPN3']]++;
			}
		}
		return $usages;
	}
	
	public function inventory() {
		$this->tpl->setFile('report/inventory')
				->assign('vendors', self::getGoodInventoryVendors())
                ->assign('partsCategories', Api::getPartsCategories())
				->display();
	}
	
	public function inventoryList() {
		$vendorId = intval($_POST['vendorId']);
		$pn = trim($_POST['pn']);
        $month = trim($_POST['month']);
        $categoryId = intval($_POST['partsCategory']);
        $goodOrBad = isset($_POST['goodOrBad']) ? intval($_POST['goodOrBad']) : '-1';
		$inventories = $this->getInventory($month, $vendorId, $goodOrBad, $categoryId, $pn);
		$this->tpl->setFile('report/inventoryList')
				->assign('inventories', $inventories)
				->assign('pager', $this->_pager['html'])
				->display();
	}
	
	public function inventoryReport() {
		$vendorId = intval($_POST['vendorId']);
        $month = trim($_POST['month']);
        $categoryId = intval($_POST['partsCategory']);
        $goodOrBad = isset($_POST['goodOrBad']) ? intval($_POST['goodOrBad']) : '-1';
		$inventories = $this->getInventory($month, $vendorId, $goodOrBad, $categoryId, '', true);
		$data = array();
		$data[] = array('PN', 'Parts Name', 'Parts Category', 'Service Vendor', 'Warehouse', 'Good/Defect', 'Month', 'Qty');
		if (!empty($inventories)) {
			foreach ($inventories as $inventory) {
                $goodOrBad = Warehouse::$_types[$inventory['goodOrBad']];
				$data[] = array($inventory['pn'], $inventory['en'], $inventory['partsGroupName'], $inventory['countryShortName'],
                    $inventory['name'], $goodOrBad, $inventory['month'], $inventory['qty']);
			}
		}
        $excel = new Excel();
		return SUCCESS.'|'.url('report/inventoryDownload/'.base64_encode($excel->writeData($data)));
	}
	
	public function inventoryDownload() {
		$name = base64_decode(trim($_GET['id']));
		downloadLink($name, 'Lenovo Mobile Phone Parts Inventory('.getCurrentDate(gmdate(DATE_FORMAT)).').csv');
	}
	
	public function getInventory($month, $vendorId, $goodOrBad, $partsCategoryId, $pn = '', $all = false) {
		$condition = $and = '';
		$params = array();
        if (!empty($month)) {
            $condition .= 'PartsInventory.month = ?';
            $params[] = $month;
            $and = ' and ';
        }
        if ($vendorId != 0) {
            $condition .= $and.'PartsInventory.vendorId = ?';
            $params[] = $vendorId;
            $and = ' and ';
        }
        if ($goodOrBad != '-1') {
            $condition .= $and.'Warehouse.goodOrBad = ?';
            $params[] = $goodOrBad;
            $and = ' and ';
        }
        if (!empty($partsCategoryId)) {
            $condition .= $and.'PartsInventory.partsCategoryId = ?';
            $params[] = $partsCategoryId;
            $and = ' and ';
        }
        if (!empty($pn)) {
            $condition .= $and.'PartsInventory.pn = ?';
            $params[] = $pn;
        }

		$dao = new PartsInventoryDao();
		$cnt = $dao->hasA('Warehouse')->count($condition, $params);
		if ($cnt == 0) {
			$this->setInventory($vendorId);
			$cnt = $dao->hasA('Warehouse')->count($condition, $params);
		}
		if (!$all) {
			$this->_pager = pager(array(
					'base' => 'report/inventoryList',
					'cur' => isset($_GET['id']) ? intval($_GET['id']) : 1,
					'cnt' => $cnt
			));
		}
		return $dao->tbl()
					->leftJoin('Vendor', 'PartsInventory.vendorId = Vendor.id', 'Vendor.countryShortName')
					->leftJoin('Warehouse', 'PartsInventory.warehouseId = Warehouse.id', 'Warehouse.name, Warehouse.goodOrBad')
					->leftJoin('PartsMaitrox', 'PartsInventory.pn = PartsMaitrox.pn', 'PartsMaitrox.en')
                    ->leftJoin('PartsGroup', 'PartsGroup.id = PartsInventory.partsCategoryId', 'PartsGroup.partsGroupName')
					->where($condition, $params)
					->limit($this->_pager['rows'], $this->_pager['start'])
					->orderby('vendorId,warehouseId,pn')
					->fetchAll();
	}
	
	public function resetInventory() {
        ini_set('memory_limit', '1000M');
		$vendorId = intval($_POST['vendorId']);
		$rtn = $this->setInventory($vendorId);
		if ($rtn) {
			return 1;
		} else {
			return 0;
		}
	} 
	
	public function calInventory($vendorId) {
		if ($vendorId == 0) {
			$vendors = self::getGoodInventoryVendors();
		} else {
			$vendors[] = LdFactory::dao('vendor')->fetch($vendorId);
		}
		
		$dao = new InventoryDao();
		$data = array();
		foreach ($vendors as $vendor) {
			$warehouses = self::getWarehouses($vendor['id']);
			if (empty($warehouses)) continue;
			foreach ($warehouses as $warehouse) {
				if (empty($warehouse)) continue;
				$inventories = $dao->findAll(array('warehouseId = ?', $warehouse['id']), 0, 0, '', 'pn,qty');
				if (empty($inventories)) continue;
				foreach ($inventories as $inventory) {
					if (empty($inventory['pn']) || $inventory['qty'] < 0) continue;
					$data[$vendor['id']][$warehouse['id']][$inventory['pn']] += $inventory['qty'];
				}
			}
		}
		
		return $data;
	}
	
	public function setInventory($vendorId) {
		$dao = new PartsInventoryDao();
		try {
			$dao->beginTransaction();
			$inventories = $this->calInventory($vendorId);
            $month = date('Y-m');
            $condition = 'month = ?';
            $params[] = $month;
			if ($vendorId != 0) {
				$condition .= ' and vendorId = ?';
				$params[] = $vendorId;
			}
            $vendors = Api::getVendors();
            $tmp = array();
            foreach ($vendors as $vendor) {
                $tmp[$vendor['id']] = $vendor['country'];
            }
            $vendors = $tmp;

			$dao->deleteWhere($condition, $params);
            $partsDao = new PartsMaitroxDao();
			$add = array();
			foreach ($inventories as $vendorId => $warehouses) {
				foreach ($warehouses as $warehouseId => $pns) {
					foreach ($pns as $pn => $qty) {
						$add[] = array(
								'pn' => $pn,
								'vendorId' => $vendorId,
                                'country' => $vendors[$vendorId],
								'warehouseId' => $warehouseId,
								'qty' => $qty,
                                'month' => $month,
                                'partsCategoryId' => $partsDao->findColumn('pn = ?', $pn, 'partsGroupId')
						);
                        if (count($add) == 200) {
                            $dao->batchInsert($add);
                            $add = array();
                        }
					}
				}
			}
            $dao->batchInsert($add);
			$dao->commit();
			return true;
		} catch (SqlException $e) {
			$dao->rollback();
			return false;
		}
	}


    public function inventoryChart() {
        $this->tpl->setFile('report/inventoryChart')
            ->assign('vendors', Api::psiPlanVendors())
            ->assign('partsCategories', Api::getPartsCategories())
            ->display();
    }

    public function inventoryChartData() {
        $vendorId = intval($_GET['vendorId']);
        $partsCategoryId = intval($_GET['partsCategory']);
        $goodOrBad = intval($_GET['goodOrBad']);
        $dao = new PartsInventoryDao();

        $condition = $and = '';
        $params = array();
        if (!empty($vendorId)) {
            $condition .= 'PartsInventory.vendorId = ?';
            $params[] = $vendorId;
            $and = ' and ';
        }
        if (!empty($partsCategoryId)) {
            $condition .= $and.'PartsInventory.partsCategoryId = ?';
            $params[] = $partsCategoryId;
            $and = ' and ';
        }
        if ($goodOrBad != '-1') {
            $condition .= $and.'Warehouse.goodOrBad = ?';
            $params[] = $goodOrBad;
        }

        $sql = $dao->tbl()->setField('sum(qty) as qty, month')->leftJoin('Warehouse', 'Warehouse.id = PartsInventory.warehouseId');
        if (!empty($condition)) $sql->where($condition, $params);
        $data = $sql->groupby('month')->orderby('month')->fetchAll();

        $tmp = array();
        foreach ($data as $v) {
            $tmp[] = array('month' => $v['month'], 'qty' => $v['qty']);
        }
        return json_encode($tmp);
    }
	
	public function shipping() {
		$this->tpl->setFile('report/shipping')
				->assign('vendors', Api::psiPlanVendors())
                ->assign('partsCategories', Api::getPartsCategories())
				->display();
	}
	
	public function shippingList() {
		$vendorId = intval($_POST['vendorId']);
        $categoryId = intval($_POST['partsCategory']);
		$pn = trim($_POST['pn']);
		$shippings = $this->getShipping($vendorId, $categoryId, $pn);
		$this->tpl->setFile('report/shippingList')
		->assign('shippings', $shippings)
		->assign('pager', $this->_pager['html'])
		->display();
	}
	
	public function shippingReport() {
		$vendorId = intval($_POST['vendorId']);
        $categoryId = intval($_POST['partsCategory']);
		$shippings = $this->getShipping($vendorId, $categoryId, '', true);
		$data = array();
		$data[] = array('PN', 'Parts Name', 'Parts Category', 'Destination Depot', 'Good Parts Qty');
		if (!empty($shippings)) {
			foreach ($shippings as $shipping) {
				$data[] = array($shipping['pn'], $shipping['en'], $shipping['partsGroupName'], $shipping['countryShortName'], $shipping['qty']);
			}
		}
        $excel = new Excel();
		return SUCCESS.'|'.url('report/shippingDownload/'.base64_encode($excel->writeData($data)));
	}
	
	public function shippingDownload() {
		$name = base64_decode(trim($_GET['id']));
		downloadLink($name, 'Lenovo Mobile Phone Shipping On Way('.getCurrentDate(gmdate(DATE_FORMAT)).').csv');
	}
	
	public function resetShipping() {
		$vendorId = intval($_POST['vendorId']);
		$rtn = $this->setShipping($vendorId);
		if ($rtn) {
			return 1;
		} else {
			return 0;
		}
	}
	
	public function setShipping($vendorId) {
		$dao = new PartsShippingDao();
		try {
			$dao->beginTransaction();
			$shippingOrders = $this->calShipping($vendorId);
            $condition = '';
            $params = array();
			if ($vendorId != 0) {
				$condition .= ' vendorId = ?';
				$params[] = $vendorId;
			}
            $vendors = Api::getVendors();
            $tmp = array();
            foreach ($vendors as $vendor) {
                $tmp[$vendor['id']] = $vendor['country'];
            }
            $vendors = $tmp;

			$dao->deleteWhere($condition, $params);
			$add = array();
            $partsDao = new PartsMaitroxDao();
			foreach ($shippingOrders as $vendorId => $pns) {
				foreach ($pns as $pn => $qty) {
					$add[] = array(
							'pn' => $pn,
							'vendorId' => $vendorId,
                            'country' => $vendors[$vendorId],
							'qty' => $qty,
                            'partsCategoryId' => $partsDao->findColumn('pn = ?', $pn, 'partsGroupId')
					);
				}
				$dao->batchInsert($add);
				$add = array();
			}

			$dao->commit();
			return true;
		} catch (SqlException $e) {
			$dao->rollback();
			return false;
		}
	}
	
	public function calShipping($vendorId) {
		if ($vendorId == 0) {
			$vendors = Api::psiPlanVendors();
		} else {
			$vendors[] = LdFactory::dao('vendor')->fetch($vendorId);
		}
		
		$dao = new ShippingOrderDao();
		$detailDao = new ShippingDetailsDao();
		$data = array();
		foreach ($vendors as $vendor) {
			if (in_array($vendor['countryShortName'], self::$_exceptVendor)) continue;
			$warehouses = self::getGoodWarehouses($vendor['id']);
			if (empty($warehouses)) continue;
			$tmp = array();
			foreach ($warehouses as $warehouse) {
				$tmp[] = $warehouse['id'];
			}
			$warehouses = implode(',', $tmp);
			$shippings = $dao->findAll('status = 1 and destinationWarehouseId in ('.$warehouses.')');
			foreach ($shippings as $shipping) {
				if (empty($shipping))  continue;
				$details = $detailDao->findAll(array('shippingOrderId = ?', $shipping['id']));
				foreach ($details as $detail) {
					if (empty($detail))  continue;
					$data[$vendor['id']][$detail['partsPN']] += $detail['qty'];
				}
			}
		}
		return $data;
	}
	
	public function getShipping($vendorId, $partsCategoryId, $pn = '', $all = false) {
		$condition = $and = '';
		$params = array();
		if ($vendorId != 0) {
			$condition .= 'PartsShipping.vendorId = ?';
			$params[] = $vendorId;
			$and = ' and ';
		}
		if (!empty($partsCategoryId)) {
			$condition .= $and.'PartsShipping.partsCategoryId = ?';
			$params[] = $partsCategoryId;
			$and = ' and ';
		}
		if (!empty($pn)) {
			$condition .= $and.'PartsShipping.pn = ?';
			$params[] = $pn;
		}
		
		$dao = new PartsShippingDao();
		$cnt = $dao->count($condition, $params);
		if ($cnt == 0) {
			$this->setShipping($vendorId);
			$cnt = $dao->count($condition, $params);
		}
		if (!$all) {
			$this->_pager = pager(array(
					'base' => 'report/shippingList',
					'cur' => isset($_GET['id']) ? intval($_GET['id']) : 1,
					'cnt' => $cnt
			));
		}
		return $dao->tbl()
					->leftJoin('Vendor', 'PartsShipping.vendorId = Vendor.id', 'Vendor.countryShortName')
					->leftJoin('PartsMaitrox', 'PartsShipping.pn = PartsMaitrox.pn', 'PartsMaitrox.en')
                    ->leftJoin('PartsGroup', 'PartsGroup.id = PartsShipping.partsCategoryId', 'PartsGroup.partsGroupName')
					->where($condition, $params)
					->limit($this->_pager['rows'], $this->_pager['start'])
					->orderby('vendorId,pn')
					->fetchAll();
	}

	public function suggestParts() {
		$shipping = new ShippingOrder();
		return $shipping->suggestParts();
	}
	
	public static function useNumberData() {
		$header = array();
		$body = array();
		$total = array();
		$allTotal = array();
		$lastMonth = date('Y-m',  strtotime('-1 month'));
		$last6Month = date('Y-m',  strtotime('-6 month'));
		$dao = new PartsUseNumberDao();
		$usages = $dao->findAll(array('month <= ? and month >= ?', array($lastMonth, $last6Month)));
		$vendors = Api::psiPlanVendors();
		$tmp = array();
		foreach ($vendors as $vendor) {
			if ($vendor['countryShortName'] == 'CN') continue;
			$tmp[$vendor['id']] = $vendor['countryShortName'];
			for ($month = $last6Month; $month <= $lastMonth; $month = date('Y-m', strtotime('+1 month', strtotime($month)))) {
				$header[$vendor['countryShortName']][$month] = $month;
			}
		}
		ksort($header);
		$vendors = $tmp;
		
		foreach ($usages as $usage) {
			$name = $vendors[$usage['vendorId']];
			if (empty($name)) continue;
			$body[$usage['pn']][$name][$usage['month']] += $usage['qty'];
			$total[$usage['pn']][$name] += $usage['qty'];
			$allTotal[$usage['pn']] += $usage['qty'];
		}
		
		foreach ($body as $pn=>$number) {
			foreach ($header as $vendor=>$months) {
				foreach ($months as $month=>$v) {
					if (empty($number[$vendor][$month])) $body[$pn][$vendor][$month] = '';
				}
				if (empty($total[$pn][$vendor])) $total[$pn][$vendor] = '';
			}
		}
		return array($header, $body, $total, $allTotal);
	}
	
	public static function inventoryData() {
		$dao = new PartsInventoryDao();
        $condition = 'PartsInventory.month = ? and Warehouse.goodOrBad = 0';
        $params[] = date('Y-m');

		$inventories = $dao->hasA('Vendor', 'Vendor.countryShortName')
						->hasA('Warehouse', 'Warehouse.name')
						->findAll(array($condition, $params));
		
		$header = array();
		$body = array();
		$total = array();
		$allTotal = array();
		foreach ($inventories as $inventory) {
			$name = trim($inventory['countryShortName']);
			$warehouseName = trim(str_replace('Good Warehouse', '', $inventory['name']));
			$header[$name][$warehouseName] = $warehouseName;
			$body[$inventory['pn']][$name][$warehouseName] += $inventory['qty'];
			$total[$inventory['pn']][$name] += $inventory['qty'];
			$allTotal[$inventory['pn']] += $inventory['qty'];
		}
		
		ksort($header);
		foreach ($body as $pn=>$number) {
			foreach ($header as $vendor=>$warehouses) {
				foreach ($warehouses as $warehouse) {
					if (empty($number[$vendor][$warehouse])) $body[$pn][$vendor][$warehouse] = '';
				}
				if (empty($total[$pn][$vendor])) $total[$pn][$vendor] = '';
			}
		}
		return array($header, $body, $total, $allTotal);
	}
	
	public static function shippingData() {
		$dao = new PartsShippingDao();
		$shippings = $dao->hasA('Vendor', 'Vendor.countryShortName')->fetchAll();
		
		$header = array();
		$body = array();
		$total = array();
		foreach ($shippings as $shipping) {
			if (in_array($shipping['countryShortName'], self::$_exceptVendor)) continue;
			$name = trim($shipping['countryShortName']);
			$header[$name] = $name;
			$body[$shipping['pn']][$name] += $shipping['qty'];
			$total[$shipping['pn']] += $shipping['qty'];
		}
		sort($header);
	
		foreach ($body as $pn => $number) {
			foreach ($header as $vendor) {
				if (empty($number[$vendor])) $body[$pn][$vendor] = '';
			}
		}
	
		return array($header, $body, $total);
	}


    /**
     * @param LdBaseDao $dao
     * @return mixed
     */
    public static function getMaxBatch($dao) {
		$tblName = $dao->tblName();
		if (!isset(self::$_maxBatch[$tblName])) {
			$sql = 'select max(batch) from '.$dao->tblName();
			self::$_maxBatch[$tblName] = $dao->tbl()->sql($sql)->fetchColumn();
		}
		return self::$_maxBatch[$tblName];
	}
	
	/**
	 * 得到参与PSI计划的vendor列表
	 * 
	 * @return array
	 */
	public static function getVendors() {
		return LdFactory::dao('vendor')->findAll('forPSI = 1 and countryShortName is not null', 0, 0, 'countryShortName', '*', PDO::FETCH_ASSOC);
	}

	public static function getGoodInventoryVendors() {
		$dao = new VendorDao();
		$vendors = Api::psiPlanVendors();
		$vendors[] = $dao->fetch(7);
		return $vendors;
	}
	
	public static function getWarehouses($vendorId) {
		return LdFactory::dao('warehouse')->findAll(array('vendorId = ? and id != 911', $vendorId), 0, 0, '', 'id,name');
	}

    public static function getGoodWarehouses($vendorId) {
        return LdFactory::dao('warehouse')->findAll(array('vendorId = ? and id != 911 and goodOrBad = 0', $vendorId), 0, 0, '', 'id,name');
    }

    public function model() {
        $this->tpl->setFile('report/model')
                  ->assign('models', Api::getModelTypes())
                  ->assign('countries', Api::getCountries())
                  ->assign('categories', Api::getPartsCategories())
                  ->display();
    }

    public function modelList() {
        $dao = new ModelUseNumberDao();
        $condition = '';
        $params = array();

        $model = trim($_POST['model']);
        if (!empty($model)) {
            $condition .= 'model = ?';
            $params[] = $model;
            $and = ' and ';
        }

        $categoryId = intval($_POST['category']);
        if (!empty($categoryId)) {
            $condition .= $and.' categoryId = ?';
            $params[] = $categoryId;
            $and = ' and ';
        }

        $month = trim($_POST['month']);
        if (!empty($month)) {
            $condition .= $and.'month = ?';
            $params[] = $month;
            $and = ' and ';
        }

        $country = trim($_POST['country']);
        if (!empty($country)) {
            $condition .= $and.'country = ?';
            $params[] = $country;
            $and = ' and ';
        }

        $pager = pager(array(
            'base' => 'report/modelList',
            'cur' => isset($_GET['id']) ? intval($_GET['id']) : 1,
            'cnt' => $dao->count($condition, $params)
        ));

        if (empty($condition)) {
            $list = $dao->fetchAll($pager['rows'], $pager['start']);
        } else {
            $list = $dao->findAll(array($condition, $params), $pager['rows'], $pager['start']);
        }
        $this->tpl->setFile('report/modelList')
                  ->assign('list', $list)
                  ->assign('pager', $pager['html'])
                  ->display();
    }

    public function modelChart() {
        $this->tpl->setFile('report/modelChart')
            ->assign('countries', Api::getCountries())
            ->assign('models', Api::getModelTypes())
            ->display();
    }

    public function modelChartData() {
        $countries = ($_GET['country']);
        $model = trim($_GET['model']);
        $dao = new ModelUseNumberDao();

        $condition = $and = '';
        $params = array();
        if (!empty($model)) {
            $condition .= $and.'model = ?';
            $params[] = $model;
            $and = ' and ';
        }
        if (!empty($countries)) {
            $country = implode('","', $countries);
            $country = '"'.$country.'"';
            $condition .= $and.'country in ('.$country.')';
            $and = ' and ';
        }

        $sql = $dao->tbl()->setField('sum(qty) as qty, month');
        if (!empty($condition)) $sql->where($condition, $params);
        $data = $sql->groupby('month')->orderby('month')->fetchAll();

        $tmp = array();
        foreach ($data as $v) {
            $tmp[] = array('month' => $v['month'], 'qty' => $v['qty']);
        }
        return json_encode($tmp);
    }

    public function modelChartTbl() {
        $year = intval($_REQUEST['year']);
        $month = intval($_REQUEST['month']);
        $month = $month+1;
        $month = str_pad($month, 2, 0, STR_PAD_LEFT);
        $date = $year.'-'.$month;
        $condition = 'month = ?';
        $params = array($date);

        $model = trim($_REQUEST['model']);
        if (!empty($model)) {
            $condition .= ' and model = ?';
            $params[] = $model;
        }

        $countries = ($_REQUEST['country']);
        if (!empty($countries)) {
            $country = implode('","', $countries);
            $country = '"'.$country.'"';
            $condition .= ' and country in ('.$country.')';
        }
        $dao = new ModelUseNumberDao();
        $totalQty = $dao->findColumn($condition, $params, 'sum(qty)');
        $pager = pager(array(
            'base' => 'report/modelChartTbl',
            'cur' => isset($_GET['id']) ? intval($_GET['id']) : 1,
            'cnt' => $dao->count($condition, $params, 'categoryId')
        ));
        $condition .= ' group by categoryId';
        $models = $dao->findAll(array($condition, $params), $pager['rows'], $pager['start'], 'qty desc', 'sum(qty) as qty,category', PDO::FETCH_ASSOC);
        $this->tpl->setFile('report/modelChartTbl')
                  ->assign('models', $models)
                  ->assign('totalQty', $totalQty)
                  ->assign('pager', $pager['html'])
                  ->assign('params', json_encode($_REQUEST))
                  ->display();
    }

    public function modelPieChartDate() {
        $primary = array(10, 1, 20, 7, 2, 5, 18, 45, 21, 24, 15, 13, 16, 14, 12, 22, 11, 17, 8);
        $year = intval($_REQUEST['year']);
        $month = intval($_REQUEST['month']);
        $month = $month+1;
        $month = str_pad($month, 2, 0, STR_PAD_LEFT);
        $date = $year.'-'.$month;
        $condition = 'month = ?';
        $params = array($date);

        $model = trim($_REQUEST['model']);
        if (!empty($model)) {
            $condition .= ' and model = ?';
            $params[] = $model;
        }

        $countries = ($_REQUEST['country']);
        if (!empty($countries)) {
            $country = implode('","', $countries);
            $country = '"'.$country.'"';
            $condition .= ' and country in ('.$country.')';
        }
        $dao = new ModelUseNumberDao();
        $condition .= ' group by categoryId';
        $models = $dao->findAll(array($condition, $params), 0, 0, 'qty desc', 'sum(qty) as qty, category, categoryId', PDO::FETCH_ASSOC);
        $tmp = array();
        $others = 0;
        foreach ($models as $v) {
            if (in_array($v['categoryId'], $primary)) {
                $tmp[] = array('category' => $v['category'], 'value' => $v['qty']);
            } else {
                $others += $v['qty'];
            }
        }
        $tmp[] = array('category' => 'Others', 'value' => $others);
        return json_encode($tmp);
    }

    public function resetModel() {
        $country = trim($_POST['country']);
        $month = trim($_POST['month']);
        if (empty($month)) $month = date('Y-m');
        $rtn = $this->setModel($month, $country);
        if ($rtn) {
            return 1;
        } else {
            return 0;
        }
    }

    public function setModel($month, $country) {
        $dao = new ModelUseNumberDao();
        $groupDao = new PartsGroupDao();
        try {
            $dao->beginTransaction();
            $usages = $this->calModel($month, $country);
            $condition = 'month = ?';
            $params[] = $month;
            if ($country != 0) {
                $condition .= ' and country = ?';
                $params[] = $country;
            }
            $dao->deleteWhere($condition, $params);
            $add = array();
            foreach ($usages as $country => $models) {
                foreach ($models as $model => $categories) {
                    foreach ($categories as $categoryId => $qty) {
                        $add[] = array(
                            'model' => $model,
                            'country' => $country,
                            'month' => $month,
                            'qty' => $qty,
                            'categoryId' => $categoryId,
                            'category' => $groupDao->fetchColumn($categoryId, 'partsGroupName')
                        );
                    }
                }
                $dao->batchInsert($add);
                $add = array();
            }
            $dao->commit();
            return true;
        } catch (SqlException $e) {
            $dao->rollback();
            return false;
        }
    }

    public function calModel($month, $country) {
        $dao = new ServiceOrderDao();
        $month = strtotime($month);
        $from = date('Y-m-01 00:00:00', $month);
        $end = date('Y-m-t 23:59:59', $month);
        $condition = '(newPN1 != "" or newPN2 != "" or newPN3 != "") and deleted = 0 and createTime >= ? and createTime <= ?
		              and recoverMethod != 5 and recoverMethod != 6';
        $params = array($from, $end);

        if ($country) {
            $vendors = LdFactory::dao('vendor')->findAll(array('country = ?', $country));
        } else {
            $vendors = Api::psiPlanVendors();
        }
        $tmp = array();
        $replace = array();
        foreach ($vendors as $vendor) {
            $tmp[] = $vendor['id'];
            $replace[$vendor['id']] = $vendor['country'];
        }
        $condition .= ' and vendorId in ('.implode(',', $tmp).')';
        $srs = $dao->findAll(array($condition, $params), 0, 0, '', 'id,model,vendorId,newPN1,newPN2,newPN3');

        $models = LdFactory::dao('model')->fetchAll();
        $tmp = array();
        foreach ($models as $model) {
            $tmp[$model['name']] = $model['modeltype'];
        }
        $models = $tmp;

        $parts = LdFactory::dao('partsMaitrox')->fetchAll();
        $tmp = array();
        foreach ($parts as $part) {
            $tmp[$part['pn']] = $part['partsGroupId'];
        }
        $parts = $tmp;

        $usages = array();
        foreach ($srs as $sr) {
            $vendorId = $sr['vendorId'];
            $country = $replace[$vendorId];
            $model = $models[$sr['model']];
            if (!empty($sr['newPN1'])) {
                $categoryId = $parts[$sr['newPN1']];
                $usages[$country][$model][$categoryId]++;
            }
            if (!empty($sr['newPN2'])) {
                $categoryId = $parts[$sr['newPN2']];
                $usages[$country][$model][$categoryId]++;
            }
            if (!empty($sr['newPN3'])) {
                $categoryId = $parts[$sr['newPN3']];
                $usages[$country][$model][$categoryId]++;
            }
        }
        return $usages;
    }

	function beforeAction($action) {
		if (!User::logined()) return User::gotoLogin();
		if (!User::can()) redirect('error/accessDenied');
	}
}