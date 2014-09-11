<?php
/**
 * Ludo BillGo Platform
 *
 * @author     zhangbin <hunter.zhangbin@gmail.com>
 * @copyright  Copyright (c) 2012 Ludo team (http://www.loongjoy.com)
 * @version    $Id$
 */
class Warning extends LdBaseCtrl {
	private static $_basicData = array();
	private static $_substitutions = array();
	private static $_purchase = array();
	private static $_inventory = array();
	private static $_shipping = array();
	private static $_apply = array();
	private static $_usage = array();
	private static $_months = array();
	
	public function __construct() {
        $thisMonth = strtotime(date('F 1'));
		self::$_months['lastMonth'] = date('Y-m', strtotime('-1 month', $thisMonth));
		self::$_months['last2Month'] = date('Y-m', strtotime('-2 month', $thisMonth));
		self::$_months['last3Month'] = date('Y-m', strtotime('-3 month', $thisMonth));
		parent::__construct('Warning');
	}

	public function index() {
		$this->tpl->setFile('warning/index')
			->assign('vendors', Api::psiPlanVendors())
			->display();
	}
	
	public function tbl() {
		$vendorId = intval($_POST['vendor']);
		$dao = new WarningDao();
		$condition = 'vendorId = ?';
		$params[] = $vendorId;
		
		if (!empty($_POST['pn'])) {
			$pn = trim($_POST['pn']);
			$condition .= ' and pn = ?';
			$params[] = $pn;
		}
		
		$cnt = $dao->count($condition, $params);
		if ($cnt == 0) {
			$this->setWarning($vendorId);
			$cnt = $dao->count($condition, $params);
		}
		$pager = pager(array(
				'base' => 'warning/tbl',
				'cur'  => empty($_GET['id']) ? 1 : intval($_GET['id']),
				'cnt'  => $cnt
		));
		
		$warnings = $dao->findAll(array($condition, $params), $pager['rows'], $pager['start'], 'pn', '*', PDO::FETCH_ASSOC);
			
		$this->tpl->setFile('warning/tbl')
		->assign('warnings', $warnings)
		->assign('pager', $pager['html'])
		->display();
	}
	
	public function reset() {
		$vendorId = intval($_POST['vendor']);
		$rtn = $this->setWarning($vendorId);
		if ($rtn) {
			return 1;
		} else {
			return 0;
		}
	}
	
	public function setWarning($vendorId) {
		$dao = new WarningDao();
		try {
			$dao->beginTransaction();
			$warnings = $this->getWarning($vendorId);
			$dao->deleteWhere('vendorId = ?', $vendorId);
			$abcClassDao = new AbcClassDao(); 
			foreach ($warnings as $pn => $warning) {
				$warning['pn'] = $pn;
				$warning['vendorId'] = $vendorId;
				$warning['abcClass'] = $abcClassDao->findColumn('pn = ? and vendorId = ? order by month desc', array($pn, $vendorId), 'abcClass');
				$dao->insert($warning);
			}
			$dao->commit();
			return true;
		} catch (SqlException $e) {
			$dao->rollback();
			return false;
		}
	}
	
	public function report() {
		$dao = new WarningDao();
		$vendorId = intval($_POST['vendor']);
		$warnings = $dao->findAll(array('vendorId = ?', $vendorId), 0, 0, 'pn', '*', PDO::FETCH_ASSOC);
			
		$menu = array('PN',  'ABC Class', 'Group', 'Category', 'Model', 'Remain Warranty Month', 'Begin Warranty Time', 'End Warranty Time',
				'Fcst Demand', 'Inventory', 'Shipping Order On Way', 'Parts Apply', 'TO(not Include Purchase)', 'TO(Include Purchase)', 
				'On Way', 'On Order');
        $data = array();
		foreach ($warnings as $warning) {
			$data[] = array($warning['pn'], $warning['abcClass'], $warning['group'], $warning['category'], $warning['model'], $warning['warranty'], $warning['beginTime'], $warning['endTime'],
					$warning['fcst'], $warning['inventory'], $warning['shipping'], $warning['apply'], $warning['notInclude'], $warning['include'], $warning['onWay'], $warning['onOrder']);
		}
		$excel = new Excel();
		return SUCCESS.'|'.url('warning/download/'.base64_encode($excel->write($menu, $data)));
	}
	
	public function download() {
		$name = base64_decode(trim($_GET['id']));
		downloadLink($name, 'Lenovo Mobile Phone TO Warining('.getCurrentDate(gmdate(DATE_FORMAT)).').csv');
	}
	
	public function getWarning($vendorId) {
		$warnings = array();
		$tmp = LdFactory::dao('BasicData')->fetchAll();
		foreach ($tmp as $v) {
			self::$_basicData[$v['name']] = $v['value'];
		}
		
		self::$_substitutions = LdFactory::dao('partsSubstitution')->fetchAll();
		$substitutions = $tmp = array();
		foreach (self::$_substitutions as $substitution) {
			$substitutions[$substitution['pn1']][] = $substitution['groupNo'];
			for ($i = 2; $i <= 10; $i++) {
				$tmp[$substitution['pn1']][] = $substitution['pn'.$i];
			}
		}
		self::$_substitutions = array();
		foreach ($tmp as $pn1 => $pns) {
			$tmp2 = array();
			$pns = array_unique(array_filter($pns));
			$tmp2['pn1'] = $pn1;
			$i = 2;
			foreach ($pns as $pn) {
				$tmp2['pn'.$i] = $pn;
				$i++;
			}
			self::$_substitutions[] = $tmp2;
		}
		foreach ($substitutions as $pn => $substitution) {
			$substitutions[$pn] = implode(',', array_unique($substitution));
		}
		
		list($usages, $applies) = $this->getUsage($vendorId);
		$inventories = $this->getInventory($vendorId);
		$purchases = $this->getPurchase($vendorId);
		$warranties = $this->_getWarranty($vendorId);
		$shippings = $this->getShipping($vendorId);
		$partsDao = new PartsMaitroxDao();
		foreach ($usages as $pn => $usage) {
			$demand = $this->getForecastDemand($usage);
			$warnings[$pn]['group'] = $substitutions[$pn];
			$warnings[$pn]['category'] = $partsDao->findColumn('pn = ?', $pn, 'partsGroup');
			$warnings[$pn]['model'] = Parts::getModel($pn);
			if (!empty($warnings[$pn]['model'])) {
				$models = array();
				$beginTime = array();
				$endTime = array();
				foreach ($warnings[$pn]['model'] as $model) {
					if (empty($warranties[$model])) continue;
					$models[] = $warranties[$model]['diff'];
					$beginTime[] = 	$warranties[$model]['beginTime'];
					$endTime[] = $warranties[$model]['endTime'];
				}
				$warnings[$pn]['model'] = implode(',', $warnings[$pn]['model']);
				if (!empty($models)) {
					$warnings[$pn]['warranty'] = max($models);
					$warnings[$pn]['beginTime'] = min($beginTime);
					$warnings[$pn]['endTime'] = max($endTime);
				}
			}
			$warnings[$pn]['fcst'] = $this->getForecastDemand(self::$_usage[$pn]);

			$use = $inventories[$pn] + $purchases[$pn] + $shippings[$pn] - $applies[$pn];
			$warnings[$pn]['include'] = $this->_getTO($demand, $use);
			$use = $inventories[$pn] + $shippings[$pn] - $applies[$pn];
			$warnings[$pn]['notInclude'] = $this->_getTO($demand, $use);
			$warnings[$pn]['shipping'] = self::$_shipping[$pn];
			$warnings[$pn]['inventory'] = self::$_inventory[$pn];
			$warnings[$pn]['apply'] = self::$_apply[$pn];
			$warnings[$pn]['onWay'] = self::$_purchase[$pn][2];  
			$warnings[$pn]['onOrder'] = self::$_purchase[$pn][1];  
		}
		
		return $warnings;
	}
	
	public function getUsage($vendorId) {
		$dao = new PartsUseNumberDao();
        $usages = array();
		foreach (self::$_months as $month) {
			$condition = 'month = ?';
			$params = array($month);
			if ($vendorId != 0) {
				$condition .= ' and vendorId = ?';
				$params[] = $vendorId;			
			}
			$condition .= ' group by pn';
			$usages[$month] = $dao->findAll(array($condition, $params), 0, 0, '', 'pn,sum(qty) as qty');
		}
		
		foreach ($usages as $month => $pns) {
			foreach ($pns as $pn) {
				self::$_usage[$pn['pn']][$month] += $pn['qty'];
			}
		}
		
		$dao = new ServiceOrderDao();
		$condition = 'status in (?,?,?) and deleted = 0';
		$params = array(ServiceManage::STATUS_APPLY, ServiceManage::STATUS_PARTS_AVAILBLE, ServiceManage::STATUS_PARTS_AVAILBLE_SELF);
		if ($vendorId != 0) {
			$condition .= ' and vendorId = ?';
			$params[] = $vendorId;
		}
		$srs = $dao->findAll(array($condition, $params));
		foreach ($srs as $sr) {
			if (!empty($sr['newPN1'])) {
				self::$_apply[$sr['newPN1']]++;
			}
			if (!empty($sr['newPN2'])) {
				self::$_apply[$sr['newPN2']]++;
			}
			if (!empty($sr['newPN3'])) {
				self::$_apply[$sr['newPN3']]++;
			}	
		}
		
		$inventoryDao = new InventoryDao();
		$inventories = $inventoryDao->fetchAllUnique('pn');
		foreach ($inventories as $inventory) {
			if (!isset(self::$_usage[$inventory])) {
				foreach (self::$_months as $month) {
					self::$_usage[$inventory][$month] = 0;
				}
			}
		}
		$dao = new PurchaseDao();
		$purchases = $dao->fetchAllUnique('pn');
		if (!empty($purchases)) {
			foreach ($purchases as $purchase) {
				if (empty($purchase)) continue;
				if (!isset(self::$_usage[$purchase])) {
					foreach (self::$_months as $month) {
						self::$_usage[$purchase][$month] = 0;
					}
				}
			}
		}
		
		$pns = self::$_usage;
		$applies = self::$_apply;
		foreach (self::$_substitutions as $substitution) {
			for ($i = 2; $i <= 10; $i++) {
				foreach (self::$_months as $month) {
					$pns[$substitution['pn1']][$month] += self::$_usage[$substitution['pn'.$i]][$month];
				}
				$applies[$substitution['pn1']] += self::$_apply[$substitution['pn'.$i]];
			}
		}
		ksort($pns);
		return array($pns, $applies);
	}
	
	public function getForecastDemand($usage) {
		if (empty($usage)) return 0;
		
		$firstMonth = $usage[self::$_months['last3Month']];
		$secondMonth = $usage[self::$_months['last2Month']];
		$thirdMonth = $usage[self::$_months['lastMonth']];
	
		$qty[1] = $this->_getQtyByWeight($firstMonth, $secondMonth, $thirdMonth);
		$qty[2] = $this->_getQtyByWeight($secondMonth, $thirdMonth, $qty[1]);
		$qty[3] = $this->_getQtyByWeight($thirdMonth, $qty[1], $qty[2]);
		
		return ceil(($qty[1] + $qty[2] + $qty[3]) / 3);
	}
	
	public function getInventory($vendorId) {
		$params = array();
        $month = date('Y-m');
        $condition = 'PartsInventory.month = ? and Warehouse.goodOrBad = 0';
        $params[] = $month;
		if ($vendorId != 0) {
			$condition .= ' and PartsInventory.vendorId = ?';
			$params[] = $vendorId; 
		}
		$condition .= ' group by PartsInventory.pn';
		$dao = new PartsInventoryDao();
		$inventories = $dao->hasA('Warehouse', 'Warehouse.name')->findAll(array($condition, $params), 0, 0, '', 'pn,sum(qty) as qty');
		$tmp = array();
		foreach ($inventories as $inventory) {
			self::$_inventory[$inventory['pn']] = $tmp[$inventory['pn']] = $inventory['qty'];
		}
		
		foreach (self::$_substitutions as $substitution) {
			for ($i = 2; $i <= 10; $i++) {
				if (empty($substitution['pn'.$i]) || empty($tmp[$substitution['pn'.$i]])) continue;
				$tmp[$substitution['pn1']] += self::$_inventory[$substitution['pn'.$i]];
			}
		}
		return $tmp;
	}
	
	public function getPurchase($vendorId) {
		$dao = new PurchaseDetailDao();
		$condition = '1';
        $params = array();
		if ($vendorId != 0) {
			$condition .= ' and vendorId = ?';
			$params[] = $vendorId;
		}
		$condition .= ' group by pn,status';
		$purchases = $dao->hasA('Purchase')->findAll(array($condition, $params), 0, 0, '', 'pn, status, sum(number) as qty');
		$tmp = array();
		foreach ($purchases as $purchase) {
			$tmp[$purchase['pn']] += $purchase['qty'];
			self::$_purchase[$purchase['pn']][$purchase['status']] = $purchase['qty'];
		}
		
		$tmp2 = $tmp;
		foreach (self::$_substitutions as $substitution) {
			for ($i = 2; $i <= 10; $i++) {
				if (empty($substitution['pn'.$i]) || empty($tmp[$substitution['pn'.$i]])) continue;
				$tmp2[$substitution['pn1']] += $tmp[$substitution['pn'.$i]];
			}
		}
		return $tmp2;
	}
	
	public function getShipping($vendorId) {
		$condition = '1';
		$params = array();
		
		if ($vendorId != 0) {
			$condition .= ' and vendorId = ?';
			$params[] = $vendorId;
		}
		$condition .= ' group by pn';
		$dao = new PartsShippingDao();
		$shippings = $dao->findAll(array($condition, $params), 0, 0, '', 'pn,sum(qty) as qty');
		$tmp = array();
		foreach ($shippings as $shipping) {
			self::$_shipping[$shipping['pn']] = $tmp[$shipping['pn']] = $shipping['qty'];
		}
		
		foreach (self::$_substitutions as $substitution) {
			for ($i = 2; $i <= 10; $i++) {
				if (empty($substitution['pn'.$i]) || empty($tmp[$substitution['pn'.$i]])) continue;
				$tmp[$substitution['pn1']] += self::$_shipping[$substitution['pn'.$i]];
			}
		}
		return $tmp;
	} 
	
	private function _getWarranty($vendorId) {
		$dao = new ModelWarrantyDao();
		$condition = '1';
		$params = array();
		if ($vendorId != 0) {
            $vendorDao = new VendorDao();
            $country = $vendorDao->fetchColumn($vendorId, 'countryShortName');
			$condition = 'country = ?';
			$params[] = $country;
		}
		$condition .= ' group by model';
		$warranties = $dao->findAll(array($condition, $params), 0, 0, '', 'model, max(expireTime) as expireTime, min(salesTime) as salesTime');
		
		$tmp = array();
		$now = time();
		foreach ($warranties as $warranty) {
			$diff = round((strtotime($warranty['expireTime']) - $now) / 86400 / 30, 1);
			$model = strtoupper($warranty['model']);
			$tmp[$model] = array('diff' => $diff, 'beginTime' => $warranty['salesTime'], 'endTime' => $warranty['expireTime']);	
		} 
		return $tmp;
	}
	
	private function _getQtyByWeight($firstMonth, $secondMonth, $thirdMonth) {
		$qty = $firstMonth * (self::$_basicData[BasicData::PSI_WEIGHT1] / 100) +
		$secondMonth * (self::$_basicData[BasicData::PSI_WEIGHT2] / 100) +
		$thirdMonth * (self::$_basicData[BasicData::PSI_WEIGHT3] / 100);
		return ceil($qty);
	}
	
	private function _getTO($demand, $use) {
// 		if ($use < 0) $use = 0;
		if ($demand == 0 && $use == 0) {
			$to = 'N/A';
		} else if ($demand == 0 && $use != 0) {
			$to = '9999';
		} else if ($use == 0) {
			$to = 0;
		} else {
			$to = round($use / $demand, 2);
		}
		return $to;
	}
	
	function beforeAction($action) {
		if (!User::logined()) return User::gotoLogin();
		if (!User::can()) redirect('error/accessDenied');
	}
}