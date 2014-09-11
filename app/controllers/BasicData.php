<?php
/**
 * Ludo BillGo Platform
 *
 * @author     zhangbin <hunter.zhangbin@gmail.com>
 * @copyright  Copyright (c) 2012 Ludo team (http://www.loongjoy.com)
 * @version    $Id$
 */
class BasicData extends LdBaseCtrl {
	const EXPIRE_TIME = 'expireTime';
	const PSI_WEIGHT1 = 'weight1';
	const PSI_WEIGHT2 = 'weight2';
	const PSI_WEIGHT3 = 'weight3';
	const PSI_SAFTY_STOCK = 'saftyStock';	
	const ABC_A_T = 'at';
	const ABC_B_T = 'bt';
	const ABC_C_T = 'ct';
	const CR = 'cr';
	const SLOW_TO_LIMIT = 'slowToLimit';
	const SLOW_MONTH_LIMIT = 'slowMonthLimit';
	const OBSOLETE = 'obsolete';
	const NPI_PLANNING_MONTHS = 'npi';

	const TAT_TRANS_WAY_AIR = 1;
	const TAT_TRANS_WAY_SHIP = 2;
	const TAT_TRANS_WAY_CAR = 3;
	
	const LT_PSI = 1;
	const LT_NPI = 2;
	const LT_EOL = 3;
	
	const TAT_TYPE_IN_HOUSE = 1;
	const TAT_TYPE_OUTSIDE = 2;

	public function __construct() {
		parent::__construct('BasicData');
	}
	
	public function index() {
		$this->basic();
	}
	
	public function changeBasic() {
		$dao = new BasicDataDao();
		if (empty($_POST)) {
			$data = $dao->fetchAll();
			$tmp = array();
			foreach ($data as $v) {
				$tmp[$v['name']] = $v['value'];
			}
            $countries = Api::psiPlan();
			$this->tpl
                 ->setFile('basicData/changeBasic')
                 ->assign('countries', $countries)
                 ->assign('data', $tmp)
                 ->display();
		} else {
			foreach ($_POST as $k => $v) {
                //Safty Stock Parameter
                if (strstr($k, self::PSI_SAFTY_STOCK) !== false) {
                    if ($dao->exists('name = ?', self::PSI_SAFTY_STOCK)) {
                        $dao->updateWhere(array('value' => json_encode($v)), 'name = ?', self::PSI_SAFTY_STOCK);
                    } else {
                        $dao->insert(array('name' => self::PSI_SAFTY_STOCK, 'value' => json_encode($v)));
                    }
                } else {
                    if ($dao->exists('name = ?', $k)) {
                        $dao->updateWhere(array('value' => $v), 'name = ?', $k);
                    } else {
                        $dao->insert(array('name' => $k, 'value' => $v));
                    }
                }
			}
			return SUCCESS.'|'.url('basicData/basic');
		}
	}
	
	public function basic() {
		$dao = new BasicDataDao();
		$data = $dao->fetchAll();
		$tmp = array();
		foreach ($data as $v) {
			$tmp[$v['name']] = $v['value'];
		}
        $countries = Api::psiPlan();
		$this->tpl
            ->setFile('basicData/basic')
            ->assign('countries', $countries)
            ->assign('data', $tmp)
            ->display();
	}
	
	public function tat() {
		$points = Api::getScPoints();
		$this->tpl->setFile('basicData/tat')
				->assign('points', $points)
				->display();	
	}
	
	public function tatTbl() {
		$condition = 'LogisticsRoutesInfo.deleted = 0';
		$params = array();
		
		if (!empty($_POST['from'])) {
			$from = intval($_POST['from']);
			$condition .= ' and LogisticsRoutes.from = ?';
			$params[] = $from;
		}
		if (!empty($_POST['to'])) {
			$to = intval($_POST['to']);
			$condition .= ' and LogisticsRoutes.to = ?';
			$params[] = $to;
		}
		
		$dao = new LogisticsRoutesInfoDao();
		$pager = pager(array(
				'base' => 'basicData/tatTbl',
				'cur'  => empty($_GET['id']) ? 1 : intval($_GET['id']),
				'cnt'  => $dao->hasA('LogisticsRoutes')->count($condition, $params),
		));
		
		$tats = $dao->getList($condition, $params, $pager);
		$this->tpl->setFile('basicData/tatTbl')
		->assign('tats', $tats)
		->assign('pager', $pager['html'])
		->display();
	}
	
	public function addTat() {
		if (empty($_POST)) {
			 $points = Api::getScPoints();
			 $this->tpl->setFile('basicData/changeTat')
			 		->assign('points', $points)
			 		->display();
		} else {
			$dao = new LogisticsRoutesInfoDao();
			$routesDao = new LogisticsRoutesDao();
			
			try {
				$dao->beginTransaction();
				$from = intval($_POST['from']);
				$to = intval($_POST['to']);
				list($exist, list($routeId)) = $routesDao->existsRow('`from` = ? and `to` = ?', array($from, $to), 'id');
				if (!$exist) $routeId = $routesDao->insert(array('from' => $from, 'to' => $to));
				
				$add['logisticsRoutesId'] = $routeId;
				$add['transportWay'] = intval($_POST['transportWay']);
				$add['consumeDays'] = intval($_POST['consumeDays']);
				$add['fee'] = floatval($_POST['fee']);
				$add['type'] = intval($_POST['type']);
				$add['remark'] = trim($_POST['remark']);
				$add['createTime'] = gmdate(TIME_FORMAT);
				$add['id'] = $dao->insert($add);
				Logger::log(array(
					'name' => 'add tat',
					'new' => print_r($add, true)
				));
				$dao->commit();
				return SUCCESS.'|'.url('basicData/tat');
			} catch (SqlException $e) {
				$dao->rollback();
				return ALERT.'|'.LG_TAT_ADD_FAILED;
			}
		}
	}
	
	public function changeTat() {
		$dao = new LogisticsRoutesInfoDao();
		if (empty($_POST)) {
			$id = intval($_GET['id']);
			$points = Api::getScPoints();
			$tat = $dao->hasA('LogisticsRoutes', 'LogisticsRoutes.from, LogisticsRoutes.to')->fetch($id);
			$this->tpl->setFile('basicData/changeTat')
			->assign('tat', $tat)
			->assign('points', $points)
			->display();
		} else {
			$add['id'] = intval($_POST['id']);
			$routesDao = new LogisticsRoutesDao();
			$old = $dao->hasA('LogisticsRoutes', 'LogisticsRoutes.from, LogisticsRoutes.to')->fetch($add['id']);
			
			try {
				$dao->beginTransaction();
				$from = intval($_POST['from']);
				$to = intval($_POST['to']);
				list($exist, list($routeId)) = $routesDao->existsRow('`from` = ? and `to` = ?', array($from, $to), 'id');
				if (!$exist) $routeId = $routesDao->insert(array('from' => $from, 'to' => $to));
				
				$add['logisticsRoutesId'] = $routeId;
				$add['transportWay'] = intval($_POST['transportWay']);
				$add['consumeDays'] = intval($_POST['consumeDays']);
				$add['fee'] = floatval($_POST['fee']);
				$add['type'] = intval($_POST['type']);
				$add['remark'] = trim($_POST['remark']);
				$dao->update($add['id'], $add);

				Logger::log(array(
					'name' => 'change tat',
					'new' => print_r($add, true),
					'old' => print_r($old, true)
				));
				$dao->commit();
                $scRoute = new ScRoute();
                $scRoute->reset();
				return SUCCESS.'|'.url('basicData/tat');
			} catch (SqlException $e) {
				$dao->rollback();
				return ALERT.'|'.LG_TAT_CHANGE_FAILED;
			}
		}
	}
	
	public function delTat() {
		$dao = new LogisticsRoutesInfoDao();
		try {
			$dao->beginTransaction();
			$id = intval($_GET['id']);
			$old = $dao->fetch($id);
			$dao->update($id, array('deleted' => 1));
			Logger::log(array(
				'name' => 'del tat',
				'old' => print_r($old, true)
			));
			$dao->commit();
            $scRoute = new ScRoute();
            $scRoute->reset();
			return SUCCESS.'|'.url('basicData/tat');
		} catch (SqlException $e) {
			$dao->rollback();
			return ALERT.'|'.LG_TAT_DELETE_FAILED;
		}
	}
	
	public function lt() {
		$suppliers = LdFactory::dao('supplier')->findAll('deleted = 0');
		$this->tpl->setFile('basicData/lt')
				->assign('suppliers', $suppliers)
				->display();
	}
	
	public function ltTbl() {
		$condition = '';
		$params = array();
		
		if (!empty($_POST['supplier'])) {
			$supplierId = intval($_POST['supplier']);
			$condition .= ' supplierId = ?';
			$params[] = $supplierId;
		}
		
		$dao = new PurchaseLeadTimeDao();
		$pager = pager(array(
				'base' => 'basicData/ltTbl',
				'cur'  => empty($_GET['id']) ? 1 : intval($_GET['id']),
				'cnt'  => $dao->hasA('Supplier')->count($condition, $params),
		));
		if (empty($condition)) {
			$lts = $dao->hasA('Supplier', 'Supplier.supplier')->fetchAll($pager['rows'], $pager['start']);
		} else {
			$lts = $dao->hasA('Supplier', 'Supplier.supplier')->findAll(array($condition, $params), $pager['rows'], $pager['start']);
		}
		$this->tpl->setFile('basicData/ltTbl')
		->assign('lts', $lts)
		->assign('pager', $pager['html'])
		->display();
	}
	
	public function addLt() {
		if (empty($_POST)) {
			$suppliers = LdFactory::dao('supplier')->findAll('deleted = 0');
			$this->tpl->setFile('basicData/changeLt')
			->assign('suppliers', $suppliers)
			->display();
		} else {
			$dao = new PurchaseLeadTimeDao();
			try {
				$dao->beginTransaction();
				$add['supplierId'] = intval($_POST['supplier']);
				$add['poType'] = intval($_POST['poType']);
				if ($dao->exists('supplierId = ? and poType = ?', array($add['supplierId'], $add['poType']))) return ALERT.'|'.LG_LT_EXIST;
				$add['leadTime'] = intval($_POST['leadTime']);
				$add['createTime'] = gmdate(TIME_FORMAT);
				$add['id'] = $dao->insert($add);
				
				Logger::log(array(
					'name' => 'add lt',
					'new' => print_r($add, true)
				));
				$dao->commit();
				return SUCCESS.'|'.url('basicData/lt');
			} catch (SqlException $e) {
				$dao->rollback();
				return ALERT.'|'.LG_LT_ADD_FAILED;
			}
		}
	}
	
	public function changeLt() {
		$dao = new PurchaseLeadTimeDao();
		if (empty($_POST)) {
			$suppliers = LdFactory::dao('supplier')->findAll('deleted = 0');
			$id = intval($_GET['id']);
			$lt = $dao->fetch($id);
			$this->tpl->setFile('basicData/changeLt')
			->assign('suppliers', $suppliers)
			->assign('lt', $lt)
			->display();
		} else {
			try {
				$dao->beginTransaction();
				$add['supplierId'] = intval($_POST['supplier']);
				$add['poType'] = intval($_POST['poType']);
				$add['id'] = intval($_POST['id']);
				if ($dao->exists('supplierId = ? and poType = ? and id != ?', array($add['supplierId'], $add['poType'], $add['id']))) return ALERT.'|'.LG_LT_EXIST;
				
				$old = $dao->fetch($add['id']);
				$add['leadTime'] = intval($_POST['leadTime']);
				$dao->update($add['id'], $add);
		
				Logger::log(array(
					'name' => 'change lt',
					'new' => print_r($add, true),
					'old' => print_r($old, true)
				));
				$dao->commit();
				return SUCCESS.'|'.url('basicData/lt');
			} catch (SqlException $e) {
				$dao->rollback();
				return ALERT.'|'.LG_LT_CHANGE_FAILED;
			}
		}	
	}
	
	public function delLt() {
		$dao = new PurchaseLeadTimeDao();
		try {
			$dao->beginTransaction();
			$id = intval($_GET['id']);
			$old = $dao->fetch($id);
			$dao->delete($id);
			Logger::log(array(
				'name' => 'del lt',
				'old' => print_r($old, true)
			));
			$dao->commit();
			return SUCCESS.'|'.url('basicData/lt');
		} catch (SqlException $e) {
			$dao->rollback();
			return ALERT.'|'.LG_LT_DELETE_FAILED;
		}
	}
	
	public function mffr() {
		$this->tpl->setFile('basicData/mffr')
				->assign('countries', Api::getCountries())
				->assign('categories', Api::getPartsCategories())
				->assign('models', Api::getModelTypes())
				->display();
	}
	
	public function mffrTbl() {
        $condition = $and = '';
        $params = array();
		if (!empty($_POST['month'])) {
			$month = date('Y-m', strtotime(trim($_POST['month'])));
			$condition = 'month = ?';
			$params[] = $month;
			$and = ' and ';
		}
		
		if (!empty($_POST['model'])) {
			$model = trim($_POST['model']);
			$condition .= $and.'model = ?';
			$params[] = $model;
			$and = ' and ';
		}
		
		if (!empty($_POST['category'])) {
			$categoryId = intval($_POST['category']);
			$condition .= $and.'categoryId = ?';
			$params[] = $categoryId;
			$and = ' and ';
		}
		
		if (!empty($_POST['country'])) {
            $country = trim($_POST['country']);
			$condition .= $and.'country = ?';
			$params[] = $country;
		}
		
		$dao = new FailureRateModelDao();
		$pager = pager(array(
				'base' => 'basicData/mffrTbl',
				'cur'  => empty($_GET['id']) ? 1 : intval($_GET['id']),
				'cnt'  => $dao->count($condition, $params),
		));
		if (empty($condition)) {
			$mffrs = $dao->fetchAll($pager['rows'], $pager['start'], 'model');
		} else {
			$mffrs = $dao->findAll(array($condition, $params), $pager['rows'], $pager['start']);
		}
		$this->tpl->setFile('basicData/mffrTbl')
		->assign('mffrs', $mffrs)
		->assign('pager', $pager['html'])
		->display();
	}
	
	public function changeMffr() {
		$dao = new FailureRateModelDao();
		$id = intval($_POST['pk']);	
		$rate = floatval($_POST['value']);
		$old = $dao->fetch($id);
		try {
			$dao->beginTransaction();
			$dao->update($id, array('rate' => $rate));
			$dao->commit();
			return json_encode(array('newValue' => $rate));
		} catch (SqlException $e) {
			$dao->rollback();
			return json_encode(array('newValue' => $old['rate']));
		}
	}
	
	public function syncMffr() {
		$month = trim($_POST['month']);
		$models = LdFactory::dao('model')->fetchAllUnique('modeltype');
		$categories = LdFactory::dao('partsGroup')->findAll('deleted = 0');
		$tmp = array();
		foreach ($models as $model) {
			$tmp[] = strtoupper($model);
		}
		$models = array_filter($tmp);
		$countries = Api::psiPlan();
		
		$dao = new FailureRateModelDao();
		
		$add = array();
		try {
			$dao->beginTransaction();
			foreach ($models as $model) {
				foreach ($categories as $category) {
					foreach ($countries as $country) {
						//if ($dao->exists('model = ? and categoryId = ? and vendorId = ? and month = ?', array($model, $category['id'], $vendor['id'], $month))) continue;
						$add[] = array(
								'model' => $model,
								'categoryId' => $category['id'],
								'category' => $category['partsGroupName'],
								'country' => $country['country'],
								'month' => $month,
								'rate' => 0,
								'qty' => 0,
								'warranty' => 0
						);
						
						if (count($add) == 200) {
							$dao->batchInsert($add, array(), true);
							$add = array();
						}
					}
				}
			}
			$dao->commit();
			return 1;
		} catch (SqlException $e) {
			$dao->rollback();
			return 0;
		}
	}
	
	public function uploadMffr() {
		if (empty($_POST)) {
			$this->tpl->setFile('basicData/uploadMffr')->display();
		} else {
			$month = trim($_POST['month']);
			
			$excel = $this->_getExcelHandler($_FILES['Filedata']['tmp_name']);
				
			$maxRow = $excel->getHighestRow();
			
			$dao = new FailureRateModelDao();
			$categoryDao = new PartsGroupDao();
            $countryDao = new CountryDao();

			$categories = $categoryDao->fetchAll();
			$tmp = array();
			foreach ($categories as $category) {
				$tmp[$category['partsGroupName']] = $category['id'];
			}
			$categories = $tmp;
			
			$m = $n = 0;
			try {
				$dao->beginTransaction();
                $add = $old = array();
				for ($i = 2; $i <= $maxRow; $i++) {
					$model = trim($excel->getCellByColumnAndRow(0, $i)->getValue());
					$category = trim($excel->getCellByColumnAndRow(1, $i)->getValue());
                    $country = trim($excel->getCellByColumnAndRow(2, $i)->getValue());
                    $country = $countryDao->findColumn('country = ? or code = ?', array($country, $country), 'country');
					$rate = trim($excel->getCellByColumnAndRow(3, $i)->getValue());
					
					if (empty($model) || empty($category) || empty($country)) {
						continue;
					}
					list($exist, list($id)) = $dao->existsRow('model = ? and categoryId = ? and country = ? and month = ?',
						array($model, $categories[$category], $country, $month), 'id');
					if ($exist) {
                        $add[$id] = $rate;
                        $old[$id] = $dao->fetch($id, 'rate');
						$dao->update($id, array('rate' => $rate));						
						$n++;
					} else {
						$m++;
					}
				}
				
				$dao->commit();
				return 'alert2go|success:'.$n.'; failed:'.$m.'|'.url('basicData/mffr');
			} catch (SqlException $e) {
				$dao->rollback();
				return ALERT.'|failed';
			}
		} 
	}
	
	public function partsGroup() {
		$condition = 'deleted = 0';
		$dao = new PartsGroupDao();
		$pager = pager(array(
				'base' => 'basicData/partsGroup',
				'cur'  => empty($_GET['id']) ? 1 : intval($_GET['id']),
				'cnt'  => $dao->count($condition),
		));
		
		$groups = $dao->findAll($condition, $pager['rows'], $pager['start'], 'partsGroupName');
		$this->tpl->setFile('basicData/partsGroup')
		->assign('groups', $groups)
		->assign('pager', $pager['html'])
		->display();
	}
	
	public function addPartsGroup() {
		if (empty($_POST)) {
			$this->tpl->setFile('basicData/changePartsGroup')->display();
		} else {
			$dao = new PartsGroupDao();
				
			try {
				$dao->beginTransaction();
		
				$add['partsGroupName'] = trim($_POST['partsGroupName']);
				$add['id'] = $dao->insert($add);
				Logger::log(array(
					'name' => 'add parts group',
					'new' => print_r($add, true)
				));
				$dao->commit();
				return SUCCESS.'|'.url('basicData/partsGroup');
			} catch (SqlException $e) {
				$dao->rollback();
				return ALERT.'|'.LG_PARTS_GROUP_ADD_FAILED;
			}
		}
	}
	
	public function changePartsGroup() {
		$dao = new PartsGroupDao();
		$id = intval($_GET['id']);
		if (empty($_POST)) {
			$partsGroup = $dao->fetch($id);
			$this->tpl->setFile('basicData/changePartsGroup')
					->assign('partsGroup', $partsGroup)
					->display();
		} else {
			$partsDao = new PartsMaitroxDao();
			try {
				$dao->beginTransaction();
				$add['id'] = intval($_POST['id']);
				$old = $dao->fetch($add['id']);
				$add['partsGroupName'] = trim($_POST['partsGroupName']);
				$dao->update($add['id'], $add);
				$partsDao->updateWhere(array('partsGroup' => $add['partsGroupName']), 'partsGroupId = ?', $add['id']);
				Logger::log(array(
					'name' => 'change parts group',
					'old' => print_r($old, true),
					'new' => print_r($add, true)
				));
				$dao->commit();
				return SUCCESS.'|'.url('basicData/partsGroup');
			} catch (SqlException $e) {
				$dao->rollback();
				return ALERT.'|'.LG_PARTS_GROUP_CHANGE_FAILED;
			}
		}
	}
	
	public function delPartsGroup() {
		$dao = new PartsGroupDao();
		try {
			$dao->beginTransaction();
			$id = intval($_GET['id']);
			$old = $dao->fetch($id);
			$dao->update($id, array('deleted' => 1));
			Logger::log(array(
				'name' => 'del parts group',
				'old' => print_r($old, true)
			));
			$dao->commit();
			return SUCCESS.'|'.url('basicData/partsGroup');
		} catch (SqlException $e) {
			$dao->rollback();
			return ALERT.'|'.LG_PARTS_GROUP_DELETE_FAILED;
		}
	}
	
	public function pnLt() {
		$this->tpl->setFile('basicData/pnLt')->display();
	}
	
	public function pnLtTbl() {
		$condition = '';
		$params = array();
		
		if (!empty($_POST['pn'])) {
			$pn = trim($_POST['pn']);
			$condition .= ' pn like ?';
			$params[] = "{$pn}%";
		}
		
		$dao = new PartsLeadTimeDao();
		$pager = pager(array(
				'base' => 'basicData/pnLtTbl',
				'cur'  => empty($_GET['id']) ? 1 : intval($_GET['id']),
				'cnt'  => $dao->count($condition, $params),
		));
		if (empty($condition)) {
			$lts = $dao->fetchAll($pager['rows'], $pager['start']);
		} else {
			$lts = $dao->findAll(array($condition, $params), $pager['rows'], $pager['start']);
		}
		$this->tpl->setFile('basicData/pnLtTbl')
		->assign('lts', $lts)
		->assign('pager', $pager['html'])
		->display();
	}
	
	public function addPnLt() {
		if (empty($_POST)) {
			$this->tpl->setFile('basicData/changePnLt')->display();
		} else {
			$dao = new PartsLeadTimeDao();
			try {
				$dao->beginTransaction();
				$add['pn'] = trim($_POST['pn']);
				$add['leadTime'] = intval($_POST['leadTime']);
				if ($dao->exists('pn = ?', $add['pn'])) {
					$dao->updateWhere($add, 'pn = ?', $add['pn']);					
				} else {
					$add['createTime'] = gmdate(TIME_FORMAT);
					$add['id'] = $dao->insert($add);
				}
		
				Logger::log(array(
					'name' => 'add pn lt',
					'new' => print_r($add, true)
				));
				$dao->commit();
				return SUCCESS.'|'.url('basicData/pnLt');
			} catch (SqlException $e) {
				$dao->rollback();
				return ALERT.'|'.LG_PN_LT_ADD_FAILED;
			}
		}
	}
	
	public function importPnLt() {
		if (empty($_POST)) {
			$this->tpl->setFile('basicData/importPnLt')->display();
		} else {
			$dao = new PartsLeadTimeDao();
			try {
				$dao->beginTransaction();
				
				$excel = $this->_getExcelHandler($_FILES['Filedata']['tmp_name']);
				$maxRow = $excel->getHighestRow();
				
				$now = gmdate(TIME_FORMAT);
                $add = array();
				for ($i = 2; $i <= $maxRow; $i++) {
					$pn = trim($excel->getCellByColumnAndRow(0, $i)->getValue());
					$lt = trim($excel->getCellByColumnAndRow(1, $i)->getValue());
						
					if (empty($pn) || empty($lt)) continue;
						
					$add[] = array(
						'pn' => $pn,
						'leadTime' => $lt,
						'createTime' => $now
					);
				}
				
				$dao->truncate();
				$dao->batchInsert($add);
				
				Logger::log(array(
					'name' => 'import pn lt',
					'new' => ''
				));
				$dao->commit();
				return SUCCESS.'|'.url('basicData/pnLt');
			} catch (SqlException $e) {
				$dao->rollback();
				return ALERT.'|'.LG_PN_LT_IMPORT_FAILED;
			}
		}
	}
	
	public function changePnLt() {
		$dao = new PartsLeadTimeDao();
		if (empty($_POST)) {
			$id = intval($_GET['id']);
			$lt = $dao->fetch($id);
			$this->tpl->setFile('basicData/changePnLt')->assign('lt', $lt)->display();
		} else {
			try {
				$dao->beginTransaction();
				
				$add['id'] = intval($_POST['id']);
				$add['pn'] = trim($_POST['pn']);
				$add['leadTime'] = intval($_POST['leadTime']);
				$old = $dao->fetch($add['id']);
				
				$dao->update($add['id'], $add);
		
				Logger::log(array(
					'name' => 'change pn lt',
					'new' => print_r($add, true),
					'old' => print_r($old, true)
				));
				$dao->commit();
				return SUCCESS.'|'.url('basicData/pnLt');
			} catch (SqlException $e) {
				$dao->rollback();
				return ALERT.'|'.LG_PN_LT_CHANGE_FAILED;
			}
		}
	}
	
	public function delPnLt() {
		$dao = new PartsLeadTimeDao();
		try {
			$dao->beginTransaction();
			$id = intval($_GET['id']);
			$old = $dao->fetch($id);
			$dao->delete($id);
			Logger::log(array(
				'name' => 'del pn lt',
				'old' => print_r($old, true)
			));
			$dao->commit();
			return SUCCESS.'|'.url('basicData/pnLt');
		} catch (SqlException $e) {
			$dao->rollback();
			return ALERT.'|'.LG_PN_LT_DELETE_FAILED;
		}
	}

    public function npiMffr() {
        $setting = parse_ini_file('setting.ini', true);
        $this->tpl->setFile('basicData/npiMffr')
                  ->assign('vendors', Report::getVendors())
                  ->assign('partsCategories', Api::getPartsCategories())
                  ->assign('productSeries', explode(',', $setting['NPI MFFR']['product.series']))
                  ->display();
    }

    public function npiMffrTbl() {
        $condition = $and = '';
        $params = array();
        if (!empty($_POST['month'])) {
            $month = date('Y-m', strtotime(trim($_POST['month'])));
            $condition = 'month = ?';
            $params[] = $month;
            $and = ' and ';
        }

        if (!empty($_POST['productSeries'])) {
            $productSeries = trim($_POST['productSeries']);
            $condition .= $and.'productSeries = ?';
            $params[] = $productSeries;
            $and = ' and ';
        }

        if (!empty($_POST['category'])) {
            $categoryId = intval($_POST['category']);
            $condition .= $and.'categoryId = ?';
            $params[] = $categoryId;
            $and = ' and ';
        }

        if (!empty($_POST['vendor'])) {
            $vendorId = intval($_POST['vendor']);
            $condition .= $and.'vendorId = ?';
            $params[] = $vendorId;
        }

        $dao = new FailureRateNpiDao();
        $pager = pager(array(
            'base' => 'basicData/npiMffrTbl',
            'cur'  => empty($_GET['id']) ? 1 : intval($_GET['id']),
            'cnt'  => $dao->count($condition, $params)
        ));
        if (empty($condition)) {
            $mffrs = $dao->hasA('Vendor', 'Vendor.countryShortName')->fetchAll($pager['rows'], $pager['start']);
        } else {
            $mffrs = $dao->hasA('Vendor', 'Vendor.countryShortName')->findAll(array($condition, $params), $pager['rows'], $pager['start']);
        }
        $this->tpl->setFile('basicData/npiMffrTbl')
            ->assign('mffrs', $mffrs)
            ->assign('pager', $pager['html'])
            ->display();
    }

    public function addNpiMffr() {
        if (empty($_POST)) {
            $setting = parse_ini_file('setting.ini', true);
            $this->tpl->setFile('basicData/changeNpiMffr')
                      ->assign('vendors', Report::getVendors())
                      ->assign('partsCategories', Api::getPartsCategories())
                      ->assign('productSeries', explode(',', $setting['NPI MFFR']['product.series']))
                      ->display();
        } else {
            $dao = new FailureRateNpiDao();
            $add['productSeries'] = trim($_POST['productSeries']);
            $add['vendorId'] = intval($_POST['vendor']);
            $add['categoryId'] = intval($_POST['partsCategory']);
            $add['month'] = trim($_POST['month']);
            list($exist, list($id)) = $dao->existsRow('productSeries = ? and vendorId = ? and categoryId = ? and month = ?',
                array($add['productSeries'], $add['vendorId'], $add['categoryId'], $add['month']), 'id');
            $add['rate'] = floatval($_POST['rate']);
            $add['createTime'] = date(TIME_FORMAT);
            try {
                $dao->beginTransaction();
                if ($exist) {
                    $dao->update($id, $add);
                    $add['id'] = $id;
                } else {
                    $add['category'] = LdFactory::dao('partsGroup')->fetchColumn($add['categoryId'], 'partsGroupName');
                    $add['id'] = $dao->insert($add);
                }
                Logger::log(array(
                    'name' => 'add npi failure rate',
                    'new' => json_encode($add)
                ));
                $dao->commit();
                return SUCCESS.'|'.url('basicData/npiMffr');
            } catch (SqlException $e) {
                $dao->rollback();
                return ALERT.'|'.LG_FAILURE_RATE_NPI_ADD_FAILED;
            }
        }
    }

    public function changeNpiMffr() {
        $dao = new FailureRateNpiDao();
        if (empty($_POST)) {
            $id = intval($_GET['id']);
            $setting = parse_ini_file('setting.ini', true);
            $this->tpl->setFile('basicData/changeNpiMffr')
                ->assign('vendors', Report::getVendors())
                ->assign('partsCategories', Api::getPartsCategories())
                ->assign('productSeries', explode(',', $setting['NPI MFFR']['product.series']))
                ->assign('mffr', $dao->fetch($id))
                ->display();
        } else {
            $id = intval($_POST['id']);
            $add['productSeries'] = trim($_POST['productSeries']);
            $add['vendorId'] = intval($_POST['vendor']);
            $add['categoryId'] = intval($_POST['partsCategory']);
            $add['month'] = trim($_POST['month']);
            $add['rate'] = floatval($_POST['rate']);
            $add['category'] = LdFactory::dao('partsGroup')->fetchColumn($add['categoryId'], 'partsGroupName');
            try {
                $dao->beginTransaction();
                $old = $dao->fetch($id);
                $dao->update($id, $add);
                Logger::log(array(
                    'name' => 'change npi failure rate',
                    'old' => json_encode($old),
                    'new' => json_encode($add)
                ));
                $dao->commit();
                return SUCCESS.'|'.url('basicData/npiMffr');
            } catch (SqlException $e) {
                $dao->rollback();
                return ALERT.'|'.LG_FAILURE_RATE_NPI_CHANGE_FAILED;
            }
        }
    }

    public function delNpiMffr() {
        $dao = new FailureRateNpiDao();
        try {
            $dao->beginTransaction();
            $id = intval($_GET['id']);
            $old = $dao->fetch($id);
            $dao->delete($id);
            Logger::log(array(
                'name' => 'del npi failure rate',
                'old' => json_encode($old)
            ));
            $dao->commit();
            return SUCCESS.'|'.url('basicData/npiMffr');
        } catch (SqlException $e) {
            $dao->rollback();
            return ALERT.'|'.LG_FAILURE_RATE_NPI_DELETE_FAILED;
        }
    }

    public function importNpiMffr() {
        if (empty($_POST)) {
            $this->tpl->setFile('basicData/importNpiMffr')->display();
        } else {
            $dao = new FailureRateNpiDao();
            $categoryDao = new PartsGroupDao();
            $vendorDao = new VendorDao();
            try {
                $dao->beginTransaction();

                $excel = $this->_getExcelHandler($_FILES['Filedata']['tmp_name']);
                $maxRow = $excel->getHighestRow();

                $now = gmdate(TIME_FORMAT);
                $add = array();
                for ($i = 2; $i <= $maxRow; $i++) {
                    $add['productSeries'] = trim($excel->getCellByColumnAndRow(0, $i)->getValue());
                    $add['category'] = trim($excel->getCellByColumnAndRow(1, $i)->getValue());
                    $vendor = trim($excel->getCellByColumnAndRow(2, $i)->getValue());
                    $add['month'] = trim($excel->getCellByColumnAndRow(3, $i)->getValue());
                    $add['rate'] = trim($excel->getCellByColumnAndRow(4, $i)->getValue());

                    $add['categoryId'] = $categoryDao->findColumn('partsGroupName = ?',  $add['category'], 'id');
                    $add['vendorId'] = $vendorDao->findColumn('countryShortName = ?', $vendor, 'id');

                    if (empty($add['productSeries']) || empty($add['categoryId']) || empty($add['vendorId']) || empty($add['month']) || empty($add['rate'])) continue;
                    list($exist, list($id)) = $dao->existsRow('productSeries = ? and vendorId = ? and categoryId = ? and month = ?',
                        array($add['productSeries'], $add['vendorId'], $add['categoryId'], $add['month']), 'id');

                    if ($exist) {
                        $dao->update($id, array('rate' => $add['rate']));
                    } else {
                        $add['createTime'] = $now;
                        $dao->insert($add);
                    }
                }

                Logger::log(array(
                    'name' => 'import npi mffr',
                    'new' => ''
                ));
                $dao->commit();
                return SUCCESS.'|'.url('basicData/npiMffr');
            } catch (SqlException $e) {
                $dao->rollback();
                return ALERT.'|'.LG_FAILURE_RATE_NPI_IMPORT_FAILED;
            }
        }
    }

	private function _getExcelHandler($filename) {
		ini_set('memory_limit', '1000M');
		Load::helper('excel/PHPExcel');
		$reader = new PHPExcel_Reader_Excel5();
		if (!$reader->canRead($filename)) {
			$reader = new PHPExcel_Reader_Excel2007();
		}
		$excel = $reader->load($filename);
		spl_autoload_register('__autoload');
		return $excel->getSheet(0);
	}
	

	function beforeAction($action) {
		if ($action == 'uploadMffr') return null;
		if ($action == 'importPnLt') return null;
		if (!User::logined()) return User::gotoLogin();
		if (!User::can()) redirect('error/accessDenied');
	}
}