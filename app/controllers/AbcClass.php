<?php
/**
 * Ludo BillGo Platform
 *
 * @author     zhangbin <hunter.zhangbin@gmail.com>
 * @copyright  Copyright (c) 2012 Ludo team (http://www.loongjoy.com)
 * @version    $Id$
 */
class AbcClass extends LdBaseCtrl {
	private static $_prices = array();
	public function __construct() {
		parent::__construct('AbcClass');
	}
	
	/**
	 * 1 计算每一种材料的金额
	 * 2 按照金额由大到小排序并列成表格
	 * 3 计算每一种材料金额占库存总金额的比率
	 * 4 计算累计比率
	 * 5 分类
	 */
	public function index() {
		$this->tpl->setFile('abcClass/index')
		->assign('vendors', Api::psiPlanVendors())
		->display();
	}
	
	public function tbl() {
		$dao = new AbcClassDao();
		
		$date = date('Y-m', strtotime($_POST['month']));
		$condition = 'month = ?';
		$params[] = $date;
		
		$vendorId = intval($_POST['vendor']);
		$condition .= ' and vendorId = ?';
		$params[] = $vendorId;
		
		if (!empty($_POST['pn'])) {
			$condition .= ' and pn = ?';
			$params[] = trim($_POST['pn']);
		}
		
		$cnt = $dao->count($condition, $params);
		if ($cnt == 0) {
			$this->setAbcClass($date, $vendorId);
			$cnt = $dao->count($condition, $params);
		}
		$pager = pager(array(
				'base' => 'abcClass/tbl',
				'cur'  => empty($_GET['id']) ? 1 : intval($_GET['id']),
				'cnt'  => $cnt
		));
		
		$classes = $dao->findAll(array($condition, $params), $pager['rows'], $pager['start'], 'abcClass', '*', PDO::FETCH_ASSOC);
			
		$this->tpl->setFile('abcClass/tbl')
		->assign('classes', $classes)
		->assign('pager', $pager['html'])
		->display();
	}
	
	public function reset() {
		$vendorId = intval($_POST['vendor']);
		$date = date('Y-m', strtotime($_POST['month']));
		$rtn = $this->setAbcClass($date, $vendorId);
		if ($rtn) {
			return 1;
		} else {
			return 0;
		}
	}
	
	public function report() {
		$dao = new AbcClassDao();
		$vendorId = intval($_POST['vendor']);
		$date = date('Y-m', strtotime($_POST['month']));
		$classes = $dao->hasA('Vendor', 'Vendor.countryShortName')->findAll(array('month = ? and vendorId = ?', array($date, $vendorId)), 0, 0, 'abcClass', '*', PDO::FETCH_ASSOC);
			
		$menu = array(LG_ABC_CLASS_PN, LG_ABC_CLASS_MONTH, LG_ABC_CLASS_VENDOR, LG_ABC_CLASS_CLASS, LG_ABC_CLASS_MODEL, LG_ABC_CLASS_GROUP,
					LG_ABC_CLASS_PRICE, LG_ABC_CLASS_CATEGORY, LG_ABC_CLASS_DESCR);
        $data = array();
		foreach ($classes as $class) {
			$data[] = array($class['pn'], $class['month'], $class['countryShortName'], $class['abcClass'], $class['model'], $class['group'], 
					Crypter::decrypt($class['price']), $class['category'], $class['descr']);
		}
		$excel = new Excel();
		return SUCCESS.'|'.url('abcClass/download/'.base64_encode($excel->write($menu, $data)));
	}
	
	public function download() {
		$name = base64_decode(trim($_GET['id']));
		downloadLink($name, 'Lenovo Mobile Phone Parts ABC Classify('.getCurrentDate(gmdate(DATE_FORMAT)).').csv');
	}
	
	public function setAbcClass($date, $vendorId = 0) {
		$pns = $this->_getPNs($date, $vendorId);
		$rates = $this->_getRate($pns);
		$dao = new AbcClassDao();
		$basicDao = new BasicDataDao();
		$basic = $basicDao->fetchAll();
		$tmp = array();
		foreach ($basic as $v) {
			$tmp[$v['name']] = $v['value'];
		}
		$basic = $tmp;
		$add = array();
		$substitutionDao = new PartsSubstitutionDao();
		$partsDao = new PartsMaitroxDao();

		if (!empty($rates)) {
			foreach ($rates as $pn => $rate) {
				if ($rate <= $basic[BasicData::ABC_A_T]) {
					$class = 'A';
				} else if ($rate <= $basic[BasicData::ABC_B_T]) {
					$class = 'B';
				} else {
					$class = 'C';
				}
				$parts = $partsDao->find('pn = ?', $pn);
				$models = Parts::getModel($pn);
				if (!empty($models)) $models = implode(',', $models);
				$add[] = array(
					'month' => $date,
					'vendorId' => $vendorId,
					'pn' => $pn,
					'abcClass' => $class,
					'model' => $models,
					'group' => implode(',', array_filter($substitutionDao->findAllUnique(array('pn1 = ?', $pn), 'groupNo'))),
					'price' => Crypter::encrypt(self::$_prices[$pn]),
					'category' => $parts['partsGroup'],
					'descr' => $parts['en']
				);
			}
		}
		try {
			$dao->beginTransaction();
			if (!empty($add)) {
				$dao->deleteWhere('month = ? and vendorId = ?', array($date, $vendorId));
				$dao->batchInsert($add);
			}
			$dao->commit();
			return true;
		} catch (SqlException $e) {
			$dao->rollback();
			return false;
		}
	}
	
	private function _getPNs($date, $vendorId) {
		$pns = array();
		$dao = new ServiceOrderDao();
		$condition = '(newPN1 != "" or newPN2 != "" or newPN3 != "") and deleted = 0 and createTime >= ? and createTime <= ?';
		$date = strtotime($date);
		$from = date('Y-m-01 00:00:00', $date);
		$end = date('Y-m-t 23:59:59', $date);
		$params = array($from, $end);
		if ($vendorId != 0) {
			$condition .= ' and vendorId = ?';
			$params[] = $vendorId;
		}
		$srs = $dao->findAll(array($condition, $params), 0, 0, '', 'newPN1,newPN2,newPN3');
		if (empty($srs)) return null;
		
		foreach ($srs as $sr) {
			if (!empty($sr['newPN1'])) {
				$pns[$sr['newPN1']]++;
			}
			if (!empty($sr['newPN2'])) {
				$pns[$sr['newPN2']]++;
			}
			if (!empty($sr['newPN3'])) {
				$pns[$sr['newPN3']]++;
			}
		}
		
		$supplierId = LdFactory::dao('supplier')->findColumn('isDefault = ?', 1, 'id');
		$priceDao = new SupplierPriceDao();
		$prices = array();
		foreach ($pns as $pn => $qty) {
			$price = $priceDao->findColumn('pn = ? and supplierId = ? and priceType = ? and endTime is null', array($pn, $supplierId, PartsPrice::TYPE_PURCHASE), 'usd');
			self::$_prices[$pn] = Crypter::decrypt($price);
			$prices[$pn] = round($qty * self::$_prices[$pn], 2);
		}
		arsort($prices);
		return $prices;
	}
	
	private function _getRate($pns) {
		if (empty($pns)) return null;
		$rates = array();
		$sum = array_sum($pns);
		foreach ($pns as $pn => $price) {
			$rates[$pn] = ($sum == 0) ? 0 : $price / $sum * 100;
		}
				
		$ratesTotal = array();
		$cur = 0;
		foreach ($rates as $pn => $rate) {
			if (empty($ratesTotal)) {
				$ratesTotal[$pn] = $rate;
			} else {
				$ratesTotal[$pn] = $cur + $rate;
			}
			$cur = $ratesTotal[$pn]; 
		}
		
		foreach ($rates as $pn => $rate) {
			$rates[$pn] = round($rate, 2);
		}
		foreach ($ratesTotal as $pn => $rate) {
			$ratesTotal[$pn] = round($rate, 2);
		}
		return $ratesTotal;
	}
	
	function beforeAction($action) {
		if (!User::logined()) return User::gotoLogin();
		if (!User::can()) redirect('error/accessDenied');
	}
}