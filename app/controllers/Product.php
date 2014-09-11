<?php
/**
 * Ludo BillGo Platform
 *
 * @author     zhangbin <hunter.zhangbin@gmail.com>
 * @copyright  Copyright (c) 2012 Ludo team (http://www.loongjoy.com)
 * @version    $Id$
 */
class Product extends LdBaseCtrl {
	public function __construct() {
		parent::__construct('Product');
	}
	
	public function index() {
		$this->phoneBom();
	}

	public function phoneBom() {
        $models = Api::getModelTypes();
        $countries = Api::getCountries();
        $this->tpl->setFile('product/phoneBom')
                  ->assign('models', $models)
                  ->assign('countries', $countries)
                  ->display();
	}
	
	public function phoneBomList() {
		$dao = new PhoneBomDao();
        list($condition, $params) = $this->_getPhoneBomSearchCondition();
		$cnt = $dao->getSearchCnt($condition, $params);
		$pager = pager(array(
				'base' => 'product/phoneBomList',
				'cur'  => empty($_GET['id']) ? '1':intval($_GET['id']),
				'cnt'  => $cnt
		));
        $list = $dao->getSearchList($condition, $params, $pager);

		$this->tpl->setFile('product/phoneBomList')
		->assign('list', $list)
		->assign('pager', $pager['html'])
		->display();
	}
	
	public function warranty() {
        $models = Api::getModelTypes();
        $countries = Api::getCountries();
        $this->tpl->setFile('product/warranty')
            ->assign('models', $models)
            ->assign('countries', $countries)
            ->display();
	}

    public function warrantyList() {
		$dao = new ModelWarrantyDao();
        $condition = $and = '';
        $params = array();
        if (!empty($_POST['country'])) {
            $countries = $_POST['country'];
            $countries = implode('","', $countries);
            $countries = '"'.$countries.'"';
            $condition .= 'country in ('.$countries.')';
            $and = ' and ';
        }
        if (!empty($_POST['modelName'])) {
            $modelName = trim($_POST['modelName']);
            $condition .= $and.'model = ?';
            $params[] = $modelName;
        }

		$pager = pager(array(
				'base' => 'product/warrantyList',
				'cur'  => empty($_GET['id']) ? 1 : intval($_GET['id']),
				'cnt'  => $dao->count($condition, $params)
		));
        if (empty($condition)) {
            $warranties = $dao->fetchAll($pager['rows'], $pager['start'], 'country,model');
        } else {
            $warranties = $dao->findAll(array($condition, $params), $pager['rows'], $pager['start'], 'country,model');
        }

		$this->tpl->setFile('product/warrantyList')
		->assign('warranties', $warranties)
		->assign('pager', $pager['html'])
		->display();

    }
	
	public function warrantyReport() {
        $dao = new ModelWarrantyDao();
        $condition = $and = '';
        $params = array();
        if (!empty($_POST['country'])) {
            $country = $_POST['country'];
            $country = implode('","', $country);
            $country = '"'.$country.'"';
            $condition .= 'country in ('.$country.')';
            $and = ' and ';
        }
        if (!empty($_POST['modelName'])) {
            $modelName = trim($_POST['modelName']);
            $condition .= $and.'model = ?';
            $params[] = $modelName;
        }
        if (empty($condition)) {
            $warranties = $dao->fetchAll(0, 0, 'country,model');
        } else {
            $warranties = $dao->findAll(array($condition, $params), 0, 0, 'country,model');
        }
        $menu = array(
            'country' => 'Country',
            'modelName' => 'Model Name',
            'model' => 'Model Type',
            'pn' => 'Model PN',
            'number' => 'Number',
            'salesTime' => 'Sales Time',
            'expireTime' => 'Expire Time',
        );

		$excel = new Excel();
        return SUCCESS.'|'.url('product/download/'.base64_encode($excel->write($menu, $warranties)));
	}

    public function download() {
        $name = base64_decode(trim($_GET['id']));
        downloadLink($name, 'Lenovo Mobile Phone Model Warranty('.getCurrentDate(gmdate(DATE_FORMAT)).').csv');
    }

    public function warrantyChart() {
        $this->tpl->setFile('product/warrantyChart')
            ->assign('countries', Api::getCountries())
            ->assign('models', Api::getModelTypes())
            ->display();
    }

    public function warrantyChartData() {
        $dao = new ModelWarrantyDao();
        $condition = 'salesTime != "0000-00-00"';
        $params = array();

        if (!empty($_GET['country'])) {
            $condition .= ' and country = ?';
            $params[] = trim($_GET['country']);
        }

        if (!empty($_GET['model'])) {
            $condition .= ' and model = ?';
            $params[] = trim($_GET['model']);
        }

        $sql = $dao->tbl()->setField("sum(number) as qty, DATE_FORMAT(salesTime, '%Y-%m') as month");
        if (!empty($condition)) $sql->where($condition, $params);
        $data = $sql->where($condition, $params)->groupby('month')->orderby('month')->fetchAll();

        $tmp = array();
        foreach ($data as $v) {
            $tmp[] = array('month' => $v['month'], 'qty' => $v['qty']);
        }

        return json_encode($tmp);
    }
	
	public function autoWarranty() {
		set_time_limit(0);
		ini_set('memory_limit', '1024M');
		$warrantyDao = new WarrantyDao();
		$modelDao = new ModelDao();
		$dao = new ModelWarrantyDao();

		$sql = 'select max(batch) from ModelWarranty';
		$batch = $dao->tbl()->sql($sql)->fetchColumn();
		$batch++;
	
		$warrantyIdDao = new WarrantyIdDao();
		$now = date(TIME_FORMAT);
		$id = $warrantyIdDao->fetchColumn(1, 'warrantyId');
		$sql = 'select max(id) from PhoneWarranty';
		$maxId = $warrantyDao->tbl('s')->sql($sql)->fetchColumn();
		$pns = array();
		if (empty($id)) {//第一次执行
			$warrantyIdDao->insert(array('warrantyId' => $maxId));
            $models = $modelDao->tbl()->innerJoin('Vendor', 'Model.country = Vendor.country', 'Vendor.id as vendorId')->fetchAll();
            foreach ($models as $model) {
				$add = array();
				if (!empty($model['pn']) && !isset($pns[$model['pn']])) {
					$pns[$model['pn']] = $model['pn'];
					$warranties = $warrantyDao->findAll(array('pn = ? group by shipmentDate', $model['pn']), 0, 0, '', 'count(pn) as cnt,PhoneWarranty.*');
					if (!empty($warranties)) {
						foreach ($warranties as $warranty) {
							$add[] = array(
									'pn' => $warranty['pn'],
									'model' => $model['modeltype'],
									'modelName' => $model['name'],
                                    'country' => $model['country'],
                                    'vendorId' => $model['vendorId'],
									'number' => $warranty['cnt'],
									'salesTime' => $warranty['shipmentDate'],
									'expireTime' => date(DATE_FORMAT, strtotime('+15 month', strtotime($warranty['shipmentDate']))),
									'batch' => $batch,
									'createTime' => $now
							);
						}
					}
				}
				if (!empty($model['pn2']) && !isset($pns[$model['pn2']])) {
					$pns[$model['pn2']] = $model['pn2'];
					$warranties = $warrantyDao->findAll(array('pn = ? group by shipmentDate', $model['pn2']), 0, 0, '', 'count(pn) as cnt,PhoneWarranty.*');
					if (!empty($warranties)) {
						foreach ($warranties as $warranty) {
							$add[] = array(
									'pn' => $warranty['pn'],
									'model' => $model['modeltype'],
									'modelName' => $model['name'],
                                    'country' => $model['country'],
                                    'vendorId' => $model['vendorId'],
									'number' => $warranty['cnt'],
									'salesTime' => $warranty['shipmentDate'],
									'expireTime' => date(DATE_FORMAT, strtotime('+15 month', strtotime($warranty['shipmentDate']))),
									'batch' => $batch,
									'createTime' => $now
							);
						}
					}
				}
				if (!empty($model['pn3']) && !isset($pns[$model['pn3']])) {
					$pns[$model['pn3']] = $model['pn3'];
					$warranties = $warrantyDao->findAll(array('pn = ? group by shipmentDate', $model['pn3']), 0, 0, '', 'count(pn) as cnt,PhoneWarranty.*');
					if (!empty($warranties)) {
						foreach ($warranties as $warranty) {
							$add[] = array(
									'pn' => $warranty['pn'],
									'model' => $model['modeltype'],
									'modelName' => $model['name'],
                                    'country' => $model['country'],
                                    'vendorId' => $model['vendorId'],
									'number' => $warranty['cnt'],
									'salesTime' => $warranty['shipmentDate'],
									'expireTime' => date(DATE_FORMAT, strtotime('+15 month', strtotime($warranty['shipmentDate']))),
									'batch' => $batch,
									'createTime' => $now
							);
						}
					}
				}
				if (!empty($model['pn4']) && !isset($pns[$model['pn4']])) {
					$pns[$model['pn4']] = $model['pn4'];
					$warranties = $warrantyDao->findAll(array('pn = ? group by shipmentDate', $model['pn4']), 0, 0, '', 'count(pn) as cnt,PhoneWarranty.*');
					if (!empty($warranties)) {
						foreach ($warranties as $warranty) {
							$add[] = array(
									'pn' => $warranty['pn'],
									'model' => $model['modeltype'],
									'modelName' => $model['name'],
                                    'country' => $model['country'],
                                    'vendorId' => $model['vendorId'],
									'number' => $warranty['cnt'],
									'salesTime' => $warranty['shipmentDate'],
									'expireTime' => date(DATE_FORMAT, strtotime('+15 month', strtotime($warranty['shipmentDate']))),
									'batch' => $batch,
									'createTime' => $now
							);
						}
					}
				}
				if (!empty($add)) $dao->batchInsert($add);
			}
				
		} else {//增量
			$warrantyIdDao->update(1, array('warrantyId' => $maxId));
            $models = $modelDao->tbl()->innerJoin('Vendor', 'Model.country = Vendor.country', 'Vendor.id as vendorId')->fetchAll();
			$pns = array();
			foreach ($models as $model) {
				$add = array();
				if (!empty($model['pn']) && !isset($pns[$model['pn']])) {
					$pns[$model['pn']] = $model['pn'];
					$warranties = $warrantyDao->findAll(array('pn = ? and id > ? group by shipmentDate', array($model['pn'], $id)), 0, 0, '', 'count(pn) as cnt,PhoneWarranty.*');
					if (!empty($warranties)) {
						foreach ($warranties as $warranty) {
							$add[] = array(
									'pn' => $warranty['pn'],
									'model' => $model['modeltype'],
									'modelName' => $model['name'],
                                    'country' => $model['country'],
                                    'vendorId' => $model['vendorId'],
									'number' => $warranty['cnt'],
									'salesTime' => $warranty['shipmentDate'],
									'expireTime' => date(DATE_FORMAT, strtotime('+15 month', strtotime($warranty['shipmentDate']))),
									'batch' => $batch,
									'createTime' => $now
							);
						}
					}
				}
				if (!empty($model['pn2']) && !isset($pns[$model['pn2']])) {
					$pns[$model['pn2']] = $model['pn2'];
					$warranties = $warrantyDao->findAll(array('pn = ? and id > ? group by shipmentDate', array($model['pn2'], $id)), 0, 0, '', 'count(pn) as cnt,PhoneWarranty.*');
					if (!empty($warranties)) {
						foreach ($warranties as $warranty) {
							$add[] = array(
									'pn' => $warranty['pn'],
									'model' => $model['modeltype'],
									'modelName' => $model['name'],
                                    'country' => $model['country'],
                                    'vendorId' => $model['vendorId'],
									'number' => $warranty['cnt'],
									'salesTime' => $warranty['shipmentDate'],
									'expireTime' => date(DATE_FORMAT, strtotime('+15 month', strtotime($warranty['shipmentDate']))),
									'batch' => $batch,
									'createTime' => $now
							);
						}
					}
				}
				if (!empty($model['pn3']) && !isset($pns[$model['pn3']])) {
					$pns[$model['pn3']] = $model['pn3'];
					$warranties = $warrantyDao->findAll(array('pn = ? and id > ? group by shipmentDate', array($model['pn3'], $id)), 0, 0, '', 'count(pn) as cnt,PhoneWarranty.*');
					if (!empty($warranties)) {
						foreach ($warranties as $warranty) {
							$add[] = array(
									'pn' => $warranty['pn'],
									'model' => $model['modeltype'],
									'modelName' => $model['name'],
                                    'country' => $model['country'],
                                    'vendorId' => $model['vendorId'],
									'number' => $warranty['cnt'],
									'salesTime' => $warranty['shipmentDate'],
									'expireTime' => date(DATE_FORMAT, strtotime('+15 month', strtotime($warranty['shipmentDate']))),
									'batch' => $batch,
									'createTime' => $now
							);
						}
					}
				}
				if (!empty($model['pn4']) && !isset($pns[$model['pn4']])) {
					$pns[$model['pn4']] = $model['pn4'];
					$warranties = $warrantyDao->findAll(array('pn = ? and id > ? group by shipmentDate', array($model['pn4'], $id)), 0, 0, '', 'count(pn) as cnt,PhoneWarranty.*');
					if (!empty($warranties)) {
						foreach ($warranties as $warranty) {
							$add[] = array(
									'pn' => $warranty['pn'],
									'model' => $model['modeltype'],
									'modelName' => $model['name'],
                                    'country' => $model['country'],
                                    'vendorId' => $model['vendorId'],
									'number' => $warranty['cnt'],
									'salesTime' => $warranty['shipmentDate'],
									'expireTime' => date(DATE_FORMAT, strtotime('+15 month', strtotime($warranty['shipmentDate']))),
									'batch' => $batch,
									'createTime' => $now
							);
						}
					}
				}
				if (!empty($add)) $dao->batchInsert($add);
			}
		}
		return 1;
	}

    public function forecastWarranty() {
        $batches = LdFactory::dao('ForecastModelWarranty')->fetchAllUnique('batch');
        $this->tpl->setFile('product/forecastWarranty')
            ->assign('models', Api::getModelTypes())
            ->assign('countries', Api::getCountries())
            ->assign('batches', $batches)
            ->display();
    }

    public function forecastWarrantyList() {
        $dao = new ForecastModelWarrantyDao();
        list($condition, $params) = $this->_getForecastSearchCondition();

        $pager = pager(array(
            'base' => 'product/warrantyList',
            'cur'  => empty($_GET['id']) ? 1 : intval($_GET['id']),
            'cnt'  => $dao->count($condition, $params)
        ));
        if (empty($condition)) {
            $warranties = $dao->fetchAll($pager['rows'], $pager['start'], 'batch,country,model');
        } else {
            $warranties = $dao->findAll(array($condition, $params), $pager['rows'], $pager['start'], 'batch,country,model');
        }

        $this->tpl->setFile('product/forecastWarrantyList')
            ->assign('warranties', $warranties)
            ->assign('pager', $pager['html'])
            ->display();
    }

    public function forecastWarrantyReport() {
        $dao = new ForecastModelWarrantyDao();
        list($condition, $params) = $this->_getForecastSearchCondition();
        if (empty($condition)) {
            $warranties = $dao->fetchAll(0, 0, 'batch,country,model');
        } else {
            $warranties = $dao->findAll(array($condition, $params), 0, 0, 'batch,country,model');
        }
        $menu = array(
            'country' => 'Country',
            'modelName' => 'Model Name',
            'model' => 'Model Type',
            'pn' => 'Model PN',
            'number' => 'Number',
            'salesTime' => 'Sales Time',
            'batch' => 'Batch'
        );

        $excel = new Excel();
        $filename = $excel->write($menu, $warranties);
        downloadLink($filename, 'Lenovo Mobile Phone Forecast Model Warranty('.getCurrentDate(gmdate(DATE_FORMAT)).').csv');
    }

    public function importForecastWarranty() {
        if (empty($_POST)) {
            $this->tpl->setFile('product/importForecastWarranty')->display();
        } else {
            $dao = new ForecastModelWarrantyDao();
            $countryDao = new CountryDao();
            try {
                $dao->beginTransaction();

                $excel = $this->_getExcelHandler($_FILES['Filedata']['tmp_name']);
                $maxRow = $excel->getHighestRow();

                $now = gmdate(TIME_FORMAT);
                $add = array();
                for ($i = 2; $i <= $maxRow; $i++) {
                    $country = trim($excel->getCellByColumnAndRow(0, $i)->getValue());
                    $add['country'] = $countryDao->findColumn('country = ? or code = ?', array($country, $country), 'country');
                    $add['modelName'] = trim($excel->getCellByColumnAndRow(1, $i)->getValue());
                    $add['model'] = trim($excel->getCellByColumnAndRow(2, $i)->getValue());
                    $add['pn'] = trim($excel->getCellByColumnAndRow(3, $i)->getValue());
                    $add['number'] = trim($excel->getCellByColumnAndRow(4, $i)->getValue());
                    $day = $excel->getCellByColumnAndRow(5, $i);
                    $add['salesTime'] = PHPExcel_Style_NumberFormat::toFormattedString($day->getCalculatedValue(), 'yyyy-mm-dd');
                    $add['batch'] = trim($excel->getCellByColumnAndRow(6, $i)->getValue());

                    if (empty($add['model']) || empty($add['pn']) || empty($add['country']) || empty($add['number']) || empty($add['salesTime']) || empty($add['batch'])) continue;
                    list($exist, list($id)) = $dao->existsRow('batch = ? and country = ? and modelName = ? and salesTime = ?',
                        array($add['batch'], $add['country'], $add['modelName'], $add['salesTime']), 'id');

                    if ($exist) {
                        $dao->update($id, array('number' => $add['number']));
                    } else {
                        $add['createTime'] = $now;
                        $dao->insert($add);
                    }
                }

                Logger::log(array(
                    'name' => 'import forecast warranty',
                    'new' => ''
                ));
                $dao->commit();
                return SUCCESS.'|'.url('product/forecastWarranty');
            } catch (SqlException $e) {
                $dao->rollback();
                return ALERT.'|'.LG_FORECAST_MODEL_WARRANTY_IMPORT_FAILED;
            }
        }
    }

    public function delForecastWarranty() {
        $dao = new ForecastModelWarrantyDao();
        try {
            $dao->beginTransaction();
            $id = intval($_GET['id']);
            $old = $dao->fetch($id);
            $dao->delete($id);
            Logger::log(array(
                'name' => 'del forecast warranty',
                'old' => print_r($old, true)
            ));
            $dao->commit();
            return SUCCESS.'|'.url('product/forecastWarranty');
        } catch (SqlException $e) {
            $dao->rollback();
            return ALERT.'|'.LG_FORECAST_MODEL_WARRANTY_DELETED_FAILED;
        }
    }

    public function phoneBomReport() {
        $dao = new PhoneBomDao();
        list($condition, $params) = $this->_getPhoneBomSearchCondition();
        $list = $dao->getSearchList($condition, $params);
        $excel = new Excel();
        $menu = array(
            'country' => 'Country',
            'modelName' => 'Model Name',
            'modelPN3' => 'New Model PN',
            'level' => 'BOM Level',
            'maintainlevel' => 'Maintenance Level',
            'pn' => 'Parts PN',
            'bomqty' => 'Quantity',
            'unit' => 'Unit',
            'positionNumber' => 'Position Number',
            'replaceNumber' => 'Replaceable Parts'
        );
        return SUCCESS.'|'.url('product/phoneBomDownload/'.base64_encode($excel->write($menu, $list)));
    }

    public function phoneBomDownload() {
        $name = base64_decode(trim($_GET['id']));
        downloadLink($name, 'Lenovo Mobile Phone Bom('.getCurrentDate(gmdate(DATE_FORMAT)).').csv');
    }

    public function warrantySetting() {
        $this->tpl->setFile('product/warrantySetting')
                  ->assign('countries', Api::getCountries())
                  ->display();
    }

    public function warrantySettingList() {
        $country = trim($_POST['country']);
        $condition = '';
        $params = array();
        if (!empty($country)) {
            $condition .= 'country = ?';
            $params[] = $country;
        }

        $dao = new ModelWarrantySettingDao();
        $pager = pager(array(
            'base' => 'product/warrantySettingList',
            'cur'  => empty($_GET['id']) ? 1 : intval($_GET['id']),
            'cnt'  => $dao->count($condition, $params, 'country')
        ));

        if (empty($condition)) {
            $countries = $dao->fetchAllUnique('country', $pager['rows'], $pager['start'], 'country');
        } else {
            $countries = $dao->findAllUnique(array($condition, $params), 'country', $pager['rows'], $pager['start'], 'country');
        }

        $settings = array();
        foreach ($countries as $country) {
            $vendors = $dao->hasA('Vendor', 'Vendor.name')->findAll(array('ModelWarrantySetting.country = ?', $country));
            $sum = 0;
            foreach ($vendors as $vendor) {
                $sum += $vendor['percentage'];
            }
            $warning = ($sum == 100) ? 'label-success' : 'label-important';
            $settings[] = array('country' => $country, 'vendors' => $vendors, 'warning' => $warning);
        }
        $this->tpl->setFile('product/warrantySettingList')
                  ->assign('settings', $settings)
                  ->assign('pager', $pager['html'])
                  ->display();
    }

    public function addWarrantySetting() {
        if (empty($_POST)) {
            $countries = Api::getCountries();
            $this->tpl->setFile('product/changeWarrantySetting')
                      ->assign('countries', $countries)
                      ->display();
        } else {
            $country = trim($_POST['country']);
            $percentages = $_POST['percentage'];
            $dao = new ModelWarrantySettingDao();
            try {
                $dao->beginTransaction();
                $dao->deleteWhere('country = ?', $country);
                foreach ($percentages as $vendorId => $percentage) {
                    $dao->insert(array(
                        'country' => $country,
                        'vendorId' => $vendorId,
                        'percentage' => $percentage
                    ));
                }
                $dao->commit();
                return SUCCESS.'|'.url('product/warrantySetting');
            } catch (SqlException $e) {
                $dao->rollback();
                return ALERT.'|Add Percentage of Country Sales Volume Failed';
            }
        }
    }

    public function changeWarrantySetting() {
        $dao = new ModelWarrantySettingDao();
        if (empty($_POST)) {
            $countries = Api::getCountries();
            $setting['country'] = trim($_GET['id']);
            $setting['vendors'] = $dao->hasA('Vendor', 'Vendor.name')->findAll(array('ModelWarrantySetting.country = ?', $setting['country']));
            $this->tpl->setFile('product/changeWarrantySetting')
                ->assign('countries', $countries)
                ->assign('setting', $setting)
                ->display();
        } else {
            $country = trim($_POST['country']);
            $percentages = $_POST['percentage'];
            try {
                $dao->beginTransaction();
                $dao->deleteWhere('country = ?', $country);
                foreach ($percentages as $vendorId => $percentage) {
                    $dao->insert(array(
                        'country' => $country,
                        'vendorId' => $vendorId,
                        'percentage' => $percentage
                    ));
                }
                $dao->commit();
                return SUCCESS.'|'.url('product/warrantySetting');
            } catch (SqlException $e) {
                $dao->rollback();
                return ALERT.'|Change Percentage of Country Sales Volume Failed';
            }
        }
    }

    public function delWarrantySetting() {
        $country = trim($_GET['id']);
        $dao = new ModelWarrantySettingDao();
        try {
            $dao->beginTransaction();
            $dao->deleteWhere('country = ?', $country);
            $dao->commit();
            return SUCCESS.'|'.url('product/warrantySetting');
        } catch (SqlException $e) {
            $dao->rollback();
            return ALERT.'|Delete Percentage of Country Sales Volume Failed';
        }
    }

    public function getVendorsByCountry() {
        $country = trim($_REQUEST['country']);
        $vendorDao = new VendorDao();
        $dao = new ModelWarrantySettingDao();

        $vendors = $dao->hasA('Vendor', 'Vendor.name,Vendor.id')->findAll(array('ModelWarrantySetting.country = ?', $country),
            0, 0, '', 'ModelWarrantySetting.percentage', PDO::FETCH_ASSOC);
        if (empty($vendors)) $vendors = $vendorDao->findAll(array('country = ?', $country), 0, 0, '', 'id,name', PDO::FETCH_ASSOC);
        return json_encode($vendors);
    }

    private function _getForecastSearchCondition() {
        $condition = $and = '';
        $params = array();
        if (!empty($_POST['country'])) {
            $country = trim($_POST['country']);
            $condition .= 'country = ?';
            $params[] = $country;
            $and = ' and ';
        }
        if (!empty($_POST['modelName'])) {
            $modelName = trim($_POST['modelName']);
            $condition .= $and.'model = ?';
            $params[] = $modelName;
            $and = ' and ';
        }
        if (!empty($_POST['batch'])) {
            $batch = trim($_POST['batch']);
            $condition .= $and.'batch = ?';
            $params[] = $batch;
        }
        return array($condition, $params);
    }

    private function _getPhoneBomSearchCondition() {
        $condition = $and = '';
        $params = array();
        if (!empty($_POST['country'])) {
            $country = trim($_POST['country']);
            $condition .= 'Model.country = ?';
            $params[] = $country;
            $and = ' and ';
        }
        if (!empty($_POST['modelName'])) {
            $modelName = trim($_POST['modelName']);
            $condition .= $and.'PhoneBom.modelType = ?';
            $params[] = $modelName;
            $and = ' and ';
        }
        if (!empty($_POST['pn'])) {
            $pn = Filter::str($_POST['pn']);
            $condition .= $and.'PhoneBom.pn =?';
            $params[] = $pn;
        }
        return array($condition, $params);
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
	
	
	public function beforeAction($action) {
		if (!User::logined()) return User::gotoLogin();
		if (!User::can()) redirect('error/accessDenied');
	}
}