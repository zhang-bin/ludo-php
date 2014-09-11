<?php
/**
 * Ludo BillGo Platform
 *
 * @author     zhangbin <hunter.zhangbin@gmail.com>
 * @copyright  Copyright (c) 2012 Ludo team (http://www.loongjoy.com)
 * @version    $Id$
 */
class FailureRate extends LdBaseCtrl {
	private $_pager = array('rows' => 0, 'start' => 0);
	
	public function __construct() {
		parent::__construct('FailureRate');
	}
	
	public function index() {
		$this->tpl->setFile('failureRate/index')
				->assign('countries', Api::psiPlan())
				->display();
	}
	
	public function failureRateList() {
		$type = intval($_POST['type']);
        $date = '';
		if (!empty($_POST['month'])) $date = date('Y-m', strtotime($_POST['month']));
		$country = trim($_POST['country']);
		
		//这里的逻辑：先从db查数据，如果没有的话，直接计算结果，然后放入db，再查询出来
		switch ($type) {
			case 1:
				$pns = $this->getFailureRateByParts($date, $country);
				$dao = new PartsMaitroxPN2Dao();
				foreach ($pns as $k => $v) {
					$pns[$k]['en'] = $dao->findColumn('pn = ?', $k, 'en');
				}
				
				$this->tpl->setFile('failureRate/pn')->assign('pns', $pns);
				break;
			case 2:
				$clusters = $this->getFailureRateByPartsReplacement($date, $country);
				$dao = new PartsClusterDao();
				foreach ($clusters as $k => $v) {
                    $cluster = $dao->find('cluster = ?', $k);
                    $pn = array_merge(array($cluster['masterPn']), json_decode($cluster['slavePn'], true));
					$clusters[$k]['pn'] = implode(',', $pn);
				}
				$this->tpl->setFile('failureRate/replacement')->assign('replacements', $clusters);
				break;
			case 3:
				$models = $this->getFailureRateByModelNormal($date, $country);
				$this->tpl->setFile('failureRate/model')->assign('models', $models);
				break;
			case 4:
				$this->tpl->setFile('failureRate/shipment');
				if ($country) {
					$shipments = $this->getFailureRateByShipment($date, $country);
					$this->tpl->assign('shipments', $shipments);
				}
				break;
			default:
				break;
		}
		$this->tpl->assign('pager', $this->_pager['html'])->assign('params', json_encode($_POST))->display();
	}
	
	public function failureRateReport() {
		$type = intval($_POST['type']);
        $date = '';
		if (!empty($_POST['month'])) $date = date('Y-m', strtotime($_POST['month']));
        $country = trim($_POST['country']);
        $data = array();
		switch ($type) {
			case 1:
				$pns = $this->getFailureRateByParts($date, $country, true);
				$dao = new PartsMaitroxPN2Dao();
				foreach ($pns as $k => $v) {
					$pns[$k]['en'] = $dao->findColumn('pn = ?', $k, 'en');
				}
				$data[] = array(LG_FAILURE_RATE_PN, LG_FAILURE_RATE_PN_EN, LG_FAILURE_RATE_QTY, LG_FAILURE_RATE_WARRANTY, LG_FAILURE_RATE_RATE);
				foreach ($pns as $pn => $qty) {
					$data[] = array($pn, $qty['en'], $qty['qty'], $qty['warranty'], $qty['rate']);
				}
				break;
			case 2:
				$clusters = $this->getFailureRateByPartsReplacement($date, $country, true);
                $dao = new PartsClusterDao();
                foreach ($clusters as $k => $v) {
                    $cluster = $dao->find('cluster = ?', $k);
                    $pn = array_merge(array($cluster['masterPn']), json_decode($cluster['slavePn'], true));
                    $clusters[$k]['pn'] = implode(',', $pn);
                }
				$data[] = array(LG_FAILURE_RATE_GROUP, LG_FAILURE_RATE_PN, LG_FAILURE_RATE_QTY, LG_FAILURE_RATE_WARRANTY, LG_FAILURE_RATE_RATE);
				foreach ($clusters as $group => $qty) {
					$data[] = array($group, $qty['pn'], $qty['qty'], $qty['warranty'], $qty['rate']);
				}
				break;
			case 3:
				list($models, $modelTh) = $this->getFailureRateByModel($date, $country, true);
				$tmp[] = '';
				if (!empty($modelTh)) {
					foreach ($modelTh as $th) {
						$tmp[] = $th;
						$tmp[] = '';
						$tmp[] = '';
					}
				}
				$data[] = $tmp;
				
				$tmp = array();
				if (!empty($modelTh)) {
					$tmp[] = LG_FAILURE_RATE_CATEGORY;
					foreach ($modelTh as $th) {
						$tmp[] = LG_FAILURE_RATE_QTY;
						$tmp[] = LG_FAILURE_RATE_WARRANTY;
						$tmp[] = LG_FAILURE_RATE_RATE;
					}
				}
				$data[] = $tmp;
				
				if (!empty($models)) {
					foreach ($models as $category => $model) {
						$tmp = array();
						$tmp[] = $category;
						foreach ($modelTh as $th) {
							$tmp[] = $model[$th]['qty']; 
							$tmp[] = $model[$th]['warranty']; 
							$tmp[] = $model[$th]['rate']; 
						}
						$data[] = $tmp;
					}
				}
				break;
			case 4:
				$data[] = array(LG_FAILURE_RATE_MODEL, LG_FAILURE_RATE_CATEGORY, LG_FAILURE_RATE_MODEL, LG_FAILURE_RATE_SALESTIME, LG_FAILURE_RATE_QTY, LG_FAILURE_RATE_WARRANTY, LG_FAILURE_RATE_RATE);
				if ($country) {
					$shipments = $this->getFailureRateByShipment($date, $country, true);
					foreach ($shipments as $shipment) {
						$row = array();
						$row[] = $shipment['model'];							
						$row[] = $shipment['category'];							
						$row[] = $shipment['month'];							
						$row[] = $shipment['salesTime'];							
						$row[] = $shipment['qty'];							
						$row[] = $shipment['warranty'];							
						$row[] = $shipment['rate'];
						$data[] = $row;							
					}
				}
				break;
			default:
				break;
		}
		
		return SUCCESS.'|'.url('failureRate/failureRateDownload/'.base64_encode($this->_writeExcel($data)));
	}
	
	/**
	 * 重置不良率
	 * 
	 * @return number
	 */
	public function resetFailureRate() {
		$type = intval($_POST['type']);
		$date = date('Y-m', strtotime($_POST['month']));
        $country = trim($_POST['country']);
        $rtn = false;
		switch ($type) {
			case 1:
				$rtn = $this->setFailureRateByParts($date, $country);
				break;
			case 2:
				$rtn = $this->setFailureRateByPartsReplacement($date, $country);
				break;
			case 3:
				$rtn = $this->setFailureRateByModel($date, $country);
				break;
			case 4:
				if ($country) {
					$rtn = $this->setFailureRateByShipment($date, $country);
				}
				break;
			default:
				break;
		}
		if ($rtn) {
			return 1;
		} else {
			return 0;
		}
	}
		
	public function failureRateDownload() {
		$name = base64_decode(trim($_GET['id']));
		downloadLink($name, 'Lenovo Mobile Phone MFFR('.getCurrentDate(gmdate(DATE_FORMAT)).').csv');
	}
	
	/**
	 * 得到by pn的不良率
	 * 
	 * @param string $date
	 * @param string $country
	 * @param boolean $all
	 * @return array
	 */
	public function getFailureRateByParts($date, $country, $all = false) {
		$condition = 'month = ?';
		$params[] = $date;
		
		if ($country) {
			$condition .= ' and country = ?';
			$params[] = $country;
		}
		
		$dao = new FailureRatePNDao();
		if (!$dao->exists($condition, $params)) {
			if (false === $this->setFailureRateByParts($date)) return null;
		}
		 
		if (!$all) {
			$this->_pager = pager(array(
					'base' => 'failureRate/failureRateList',
					'cur' => isset($_GET['id']) ? intval($_GET['id']) : 1,
					'cnt' => $dao->count($condition, $params, 'pn')
			));
		}
		if ($country) {
			$field = 'pn, qty, warranty, rate';
		} else {
			$condition .= ' group by pn';
			$field = 'pn, sum(qty) as qty, sum(warranty) as warranty';
		}
		
		$pns = $dao->findAll(array($condition, $params), $this->_pager['rows'], $this->_pager['start'], 'pn', $field, PDO::FETCH_ASSOC);
		if (!empty($pns)) {
			$tmp = array();
			foreach ($pns as $pn) {
				if (!isset($pn['rate'])) $pn['rate'] = ($pn['warranty'] == 0) ? 0 : round($pn['qty'] / $pn['warranty'] * 100, 2); 
				$tmp[$pn['pn']] = array(
					'qty' => $pn['qty'],
					'warranty' => $pn['warranty'],
					'rate' => $pn['rate']
				);
			}
			$pns = $tmp;
		}
		return $pns;
	}

	/**
	 * 计算by pn的不良率
	 *
	 * @param string $date
	 * @param string $country
	 * @return array()
	 */
	public function calFailureRateByParts($date, $country = '') {
		$pns = $this->_getDefectivePN($date, $country);
        $models = $this->_getInWarrantyModelSum($date, $country);

        $distinctModels = array();
        $warranties = array();
        $modelCountries = array();
        foreach ($models as $model) {
            $distinctModels[$model['modelName']] = $model['modelName'];
            $warranties[$model['modelName']][$model['country']] = $model['number'];
            $modelCountries[$model['modelName']] = $model['country'];
        }

        $allPns = $this->_getPns($distinctModels);
        foreach ($allPns as $pn => $models) {
            foreach ($models as $model) {
                $country = $modelCountries[$model];
                if (!isset($pns[$pn][$country])) {
                    $pns[$pn][$country]['qty'] = 0;
                }
            }
        }

		foreach ($pns as $pn => $countries) {
			foreach ($countries as $country => $qty) {
                $models = $allPns[$pn];
                if (!empty($models)) {
                    foreach ($models as $model) {
                        $pns[$pn][$country]['warranty'] += $warranties[$model][$country];
                    }
                } else {
                    $pns[$pn][$country]['warranty'] = 0;
                }
			}
		}

		return $pns;
	}
	
	/**
	 * 设置by pn的不良率
	 * 
	 * @param string $date
	 * @param string $country
	 * @return boolean
	 */
	public function setFailureRateByParts($date, $country = '') {
		$pns = $this->calFailureRateByParts($date, $country);
		$dao = new FailureRatePNDao();
		$add = array();
		try {
			$dao->beginTransaction();
			$condition = 'month = ?';
			$params[] = $date;
			if ($country) {
				$condition .= ' and country = ?';
				$params[] = $country;
			}
			$dao->deleteWhere($condition, $params);
			foreach ($pns as $pn => $countries) {
				foreach ($countries as $country => $qty) {
					$add[] = array(
							'pn' => $pn,
							'country' => $country,
							'month' => $date,
							'qty' => $qty['qty'],
							'warranty' => $qty['warranty'],
							'rate' => ($qty['warranty'] == 0) ? 0 : round($qty['qty'] / $qty['warranty'] * 100, 2)
					);

                    if (count($add) == 200) {
                        $dao->batchInsert($add);
                        $add = array();
                    }
				}
			}
			$dao->batchInsert($add);
			$dao->commit();
			return true;
		} catch (PDOException $e) {
			$dao->rollback();
			return false;
		}
	}
	
	/**
	 * 得到by pn replacement的不良率
	 * 
	 * @param string $date
	 * @param string $country
	 * @param boolean $all
	 * @return array()
	 */
	public function getFailureRateByPartsReplacement($date, $country, $all = false) {
		$condition = 'month = ?';
		$params[] = $date;
		
		if ($country) {
			$condition .= ' and country = ?';
			$params[] = $country;
		}
		
		$dao = new FailureRatePNReplacementDao();
		if (!$dao->exists($condition, $params)) {
			if (false === $this->setFailureRateByPartsReplacement($date)) return null;
		}
		
		if (!$all) {
			$this->_pager = pager(array(
					'base' => 'failureRate/failureRateList',
					'cur' => isset($_GET['id']) ? intval($_GET['id']) : 1,
					'cnt' => $dao->count($condition, $params, 'partsGroup')
			));
		}
		if ($country) {
			$field = 'partsGroup, qty, warranty, rate';
		} else {
			$condition .= ' group by partsGroup';
			$field = 'partsGroup, sum(qty) as qty, sum(warranty) as warranty';
		}
		
		$groups = $dao->findAll(array($condition, $params), $this->_pager['rows'], $this->_pager['start'], 'partsGroup', $field, PDO::FETCH_ASSOC);

        $replacements = array();
		if (!empty($groups)) {
			foreach ($groups as $group) {
				if (!isset($group['rate'])) $group['rate'] = ($group['warranty'] == 0) ? 0 : round($group['qty'] / $group['warranty'] * 100, 2); 
				$replacements[$group['partsGroup']] = array(
					'qty' => $group['qty'],
					'warranty' => $group['warranty'],
					'rate' => $group['rate']
				);
			}
		}
		return $replacements;
	}
	
	/**
	 * 计算by pn replacement的不良率
	 * 
	 * @param string $date
	 * @param string $country
	 * @return array
	 */
	public function calFailureRateByPartsReplacement($date, $country = '') {
		$pns = $this->_getDefectivePN($date, $country);
        $models = $this->_getInWarrantyModelSum($date, $country);

        $distinctModels = array();
        $warranties = array();
        $modelCountries = array();
        foreach ($models as $model) {
            $distinctModels[$model['modelName']] = $model['modelName'];
            $warranties[$model['modelName']][$model['country']] = $model['number'];
            $modelCountries[$model['modelName']] = $model['country'];
        }


        $clusters = LdFactory::dao('PartsCluster')->fetchAll();
        $tmp = array();
        foreach ($clusters as $cluster) {
            $tmp[$cluster['cluster']][$cluster['masterPn']] = $cluster['masterPn'];
            $slaves = json_decode($cluster['slavePn'], true);
            foreach ($slaves as $slave) {
                $tmp[$cluster['cluster']][$slave] = $slave;
            }
        }
        $clusters = $tmp;

        //先找到cluster里面pn所属model的并集
        $tmp = array();
        $allPns = $this->_getPns($distinctModels);
        foreach ($clusters as $cluster => $members) {
            $pnModels = array();
            foreach ($members as $pn) {
                if (empty($allPns[$pn])) continue;
                $pnModels = array_merge($pnModels, $allPns[$pn]);
            }
            $pnModels = array_unique($pnModels);
            $tmp[$cluster] = $pnModels;
        }
        $clusterModels = $tmp;

        //获取qty,warranty数据
		$tmp = array();
        foreach ($clusterModels as $cluster => $models) {
            foreach ($models as $model) {
                $country = $modelCountries[$model];
                $tmp[$cluster][$country]['warranty'] += $warranties[$model][$country];
            }
            foreach ($clusters[$cluster] as $pn) {
                $tmp[$cluster][$country]['qty'] += $pns[$pn][$country]['qty'];
            }
        }
		return $tmp;
	}
	
	/**
	 * 设置by pn replacement的不良率
	 * 
	 * @param string $date
	 * @param string $country
	 * @return boolean
	 */
	public function setFailureRateByPartsReplacement($date, $country = '') {
		$clusters = $this->calFailureRateByPartsReplacement($date,  $country);
		$dao = new FailureRatePNReplacementDao();
		$add = array();
		try{
			$dao->beginTransaction();
			$condition = 'month = ?';
			$params[] = $date;
			if ($country) {
				$condition .= ' and country = ?';
				$params[] = $country;
			}
			$dao->deleteWhere($condition, $params);
			foreach ($clusters as $group => $countries) {
				foreach ($countries as $country => $qty) {
					$add[] = array(
							'partsGroup' => $group,
							'country' => $country,
							'month' => $date,
							'qty' => $qty['qty'],
							'warranty' => $qty['warranty'],
							'rate' => ($qty['warranty'] == 0) ? 0 : round($qty['qty'] / $qty['warranty'] * 100, 2)
					);
				}
			}
			$dao->batchInsert($add);
			$dao->commit();
			return true;
		} catch (PDOException $e) {
			$dao->rollback();
			return false;
		}
	}
	
	/**
	 * 得到by model的不良率
	 * 
	 * @param string $date
	 * @param string $country
	 * @param bool $all
	 * @return array
	 */
	public function getFailureRateByModel($date, $country, $all = false) {
		$condition = 'month = ?';
		$params[] = $date;
		
		if ($country) {
			$condition .= ' and country = ?';
			$params[] = $country;
		}
		$dao = new FailureRateModelDao();
		if (!$dao->exists($condition, $params)) {
			if (false === $this->setFailureRateByModel($date)) return null;
		}
		
		if (!$all) {
			$this->_pager = pager(array(
					'base' => 'failureRate/failureRateList',
					'cur' => isset($_GET['id']) ? intval($_GET['id']) : 1,
					'cnt' => $dao->count($condition, $params, 'category')
			));
		}

		$categories = $dao->findAllUnique(array($condition, $params), 'distinct category', $this->_pager['rows'], $this->_pager['start']);
		$categories = implode("','", $categories);
		$categories = "'{$categories}'";
		$condition .= ' and category in ('.$categories.')';
			
		$models = $dao->findAll(array($condition, $params), 0, 0, 'category,model');
		$tmp = $modelTh = array();
		if ($country) {
			foreach ($models as $model) {
				$modelTh[$model['model']] = $model['model'];
				$tmp[$model['category']][$model['model']] = array(
						'qty' => $model['qty'],
						'warranty' => $model['warranty'],
						'rate' => $model['rate']
				);
			}
			$models = $tmp;
		} else {
			foreach ($models as $model) {
				$modelTh[$model['model']] = $model['model'];
				$tmp[$model['category']][$model['model']]['qty'] += $model['qty'];
				$tmp[$model['category']][$model['model']]['warranty'] += $model['warranty'];
			}
			$models = $tmp;
			foreach ($models as $category => $model) {
				foreach ($model as $modelName => $qty) {
					$models[$category][$modelName]['rate'] = ($qty['warranty'] == 0) ? 0 : round($qty['qty'] / $qty['warranty'] * 100, 2);
				}
			}
		}
		
		return array($models, $modelTh);
	}

    public function getFailureRateByModelNormal($date, $country) {
        $condition = 'month = ?';
        $params[] = $date;

        if ($country) {
            $condition .= ' and country = ?';
            $params[] = $country;
        }
        $dao = new FailureRateModelDao();
        if (!$dao->exists($condition, $params)) {
            if (false === $this->setFailureRateByModel($date)) return null;
        }
        $this->_pager = pager(array(
            'base' => 'failureRate/failureRateList',
            'cur' => isset($_GET['id']) ? intval($_GET['id']) : 1,
            'cnt' => $dao->count($condition, $params)
        ));
        return $dao->findAll(array($condition, $params), $this->_pager['rows'], $this->_pager['start']);
    }
	
	/**
	 * 计算by model的不良率
	 * 
	 * @param string $date
	 * @param string $country
	 * @return array
	 */
	public function calFailureRateByModel($date, $country = '') {
        //统计坏件数据
        $srs = $this->_getSRs($date, $country);
		$parts = LdFactory::dao('partsMaitrox')->fetchAll();
		$tmp = array();
		foreach ($parts as $part) {
			$tmp[$part['pn']] = $part['partsGroupId'];
		}
        $parts = $tmp;

        $pns = array();
		foreach ($srs as $sr) {
			if (empty($sr['model'])) continue;
			if (empty($sr['country'])) continue;
			if (!empty($sr['oldPN1'])) $pns[$parts[$sr['oldPN1']]][$sr['model']][$sr['country']]['qty']++;
			if (!empty($sr['oldPN2'])) $pns[$parts[$sr['oldPN2']]][$sr['model']][$sr['country']]['qty']++;
			if (!empty($sr['oldPN3'])) $pns[$parts[$sr['oldPN3']]][$sr['model']][$sr['country']]['qty']++;
		}
        unset($srs);

        //获取在保量
        $models = $this->_getInWarrantyModelSum($date, $country);
        $modelTypes = array();
        $warranties = array();
        $modelCountries = array();
        foreach ($models as $model) {
            $modelTypes[$model['modelName']] = $model['model'];
            $warranties[$model['modelName']][$model['country']] = $model['number'];
            $modelCountries[$model['modelName']] = $model['country'];
        }

        $partsCategories = Api::getPartsCategories();
        $tmp = array();
        foreach ($partsCategories as $partsCategory) {
            foreach ($models as $model) {
                $modelName = $model['modelName'];
                $modelType = $modelTypes[$modelName];
                $country = $modelCountries[$modelName];
                $tmp[$partsCategory['id']][$modelType][$country]['warranty'] += $warranties[$modelName][$country];
                $tmp[$partsCategory['id']][$modelType][$country]['qty'] += $pns[$partsCategory['id']][$modelName][$country]['qty'];
            }
        }
		return $tmp;
	}
	
	/**
	 * 设置by model的不良率
	 * 
	 * @param string $date
	 * @param string $country
	 * @return boolean
	 */
	public function setFailureRateByModel($date, $country = '') {
		$models = $this->calFailureRateByModel($date, $country);
		$dao = new FailureRateModelDao();
		$add = array();
		$groupDao = new PartsGroupDao();
		try{
			$dao->beginTransaction();
			$condition = 'month = ?';
			$params[] = $date;
			if ($country) {
				$condition .= ' and country = ?';
				$params[] = $country;
			}
			$dao->deleteWhere($condition, $params);
			foreach ($models as $categoryId => $model) {
				foreach ($model as $modelName => $countries) {
					foreach ($countries as $country => $qty) {
						$add[] = array(
								'model' => $modelName,
								'categoryId' => $categoryId,
								'category' => $groupDao->fetchColumn($categoryId, 'partsGroupName'), 
								'country' => $country,
								'month' => $date,
								'qty' => $qty['qty'],
								'warranty' => $qty['warranty'],
								'rate' => ($qty['warranty'] == 0) ? 0 : round($qty['qty'] / $qty['warranty'] * 100, 2)
						);
					}
				}
			}
			$dao->batchInsert($add);
			$dao->commit();
			return true;
		} catch (PDOException $e) {
			$dao->rollback();
			return false;
		}
	}
	
	/**
	 * 得到by shipment的不良率
	 * 
	 * @param string $date
	 * @param string $country
	 * @param boolean $all
	 * @return array
	 */
	public function getFailureRateByShipment($date, $country, $all = false) {
		$condition = 'country = ?';
		$params = array($country);
		if (!empty($date)) {
			$condition .= ' and month = ?';
			$params[] = $date;
		}
		
		$dao = new FailureRateShipmentDao();
		if (!$dao->exists($condition, $params)) {
			if (false === $this->setFailureRateByShipment($date, $country)) return null;
		}
		
		if (!$all) {
			$this->_pager = pager(array(
					'base' => 'failureRate/failureRateList',
					'cur' => isset($_GET['id']) ? intval($_GET['id']) : 1,
					'cnt' => $dao->count($condition, $params),
			));
		}
		return $dao->findAll(array($condition, $params), $this->_pager['rows'], $this->_pager['start'], 'model,category,salesTime');
	}
	
	
	/**
	 * 设置by shipment的不良率
	 * 
	 * @param string $date
	 * @param string $country
	 * @return boolean
	 */
	public function setFailureRateByShipment($date, $country) {
		if (empty($date)) $date = date('Y-m'); 
		$shipments = $this->calFailureRateByShipment($date, $country);
		$dao = new FailureRateShipmentDao();
		$add = array();
		$groupDao = new PartsGroupDao();
		try{
			$dao->beginTransaction();
			$condition = 'month = ? and country = ?';
			$params = array($date, $country);
			$dao->deleteWhere($condition, $params);
			
			foreach ($shipments as $model => $categories) {
				foreach ($categories as $categoryId => $batch) {
					foreach ($batch as $salesTime => $qty) {
						$add[] = array(
								'model' => $model,
                                'categoryId' => $categoryId,
                                'category' => $groupDao->fetchColumn($categoryId, 'partsGroupName'),
								'country' => $country,
								'month' => $date,
								'qty' => $qty['qty'],
								'warranty' => $qty['warranty'],
								'salesTime' => $salesTime,
								'rate' => ($qty['warranty'] == 0) ? 0 : round($qty['qty'] / $qty['warranty'] * 100, 2)
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
		} catch (PDOException $e) {
			$dao->rollback();
			return false;
		}
	}
	
	/**
	 * 计算by shipment的不良率
	 * 
	 * @param string $date
	 * @param string $country
	 * @return array
	 */
	public function calFailureRateByShipment($date, $country) {
        //统计坏件数量
        $srs = $this->_getSRs($date, $country);
        $parts = LdFactory::dao('partsMaitrox')->fetchAll();
        $tmp = array();
        foreach ($parts as $part) {
            $tmp[$part['pn']] = $part['partsGroupId'];
        }
        $parts = $tmp;
		$pns = array();
		$warrantyDao = new WarrantyDao();
		foreach ($srs as $sr) {
			if (empty($sr['imei'])) continue;
			if (empty($sr['country'])) continue;
            $shipmentDate = $warrantyDao->findColumn('imei = ?', $sr['imei'], 'shipmentDate');

			if (!empty($sr['oldPN1'])) $pns[$sr['model']][$parts[$sr['oldPN1']]][$shipmentDate]['qty']++;
			if (!empty($sr['oldPN2'])) $pns[$sr['model']][$parts[$sr['oldPN2']]][$shipmentDate]['qty']++;
			if (!empty($sr['oldPN3'])) $pns[$sr['model']][$parts[$sr['oldPN3']]][$shipmentDate]['qty']++;
		}
        unset($srs);

        //获取在保量
        $models = $this->_getInWarrantyModel($date, $country);
        $warranties = array();
        foreach ($models as $model) {
            $warranties[$model['modelName']][$model['salesTime']] = $model['number'];
        }

        $partsCategories = Api::getPartsCategories();
        $tmp = array();
        foreach ($partsCategories as $partsCategory) {
            foreach ($models as $model) {
                $modelName = $model['modelName'];
                $shipmentDate = $model['salesTime'];
                $tmp[$modelName][$partsCategory['id']][$shipmentDate]['warranty'] += $warranties[$modelName][$shipmentDate];
                $tmp[$modelName][$partsCategory['id']][$shipmentDate]['qty'] += $pns[$modelName][$partsCategory['id']][$shipmentDate]['qty'];
            }
        }
		return $tmp;
	}

    public function chartByShipment() {
        $this->tpl->setFile('failureRate/chart')
                  ->assign('models', LdFactory::dao('ModelWarranty')->fetchAllUnique('distinct modelName'))
                  ->assign('partsCategories', Api::getPartsCategories())
                  ->display();
    }

    public function chartByShipmentData() {
        $model = trim($_GET['model']);
        $partsCategoryId = intval($_GET['partsCategory']);
        $dao = new FailureRateShipmentDao();

        $condition = 'model = ? and categoryId = ?';
        $params = array($model, $partsCategoryId);

        $sql = $dao->tbl()->setField('rate, month, salesTime');
        if (!empty($condition)) $sql->where($condition, $params);
        $data = $sql->orderby('month')->fetchAll();

        $tmp = array();
        $title = array();
        foreach ($data as $v) {
            $column = 'rate_'.$v['salesTime'];
            $title[$v['salesTime']] = $column;
            $tmp[$v['month']]['month'] = $v['month'];
            $tmp[$v['month']][$column] = $v['rate'];
        }

        $data = array();
        $data['title'] = $title;
        foreach ($tmp as $v) {
            $data['data'][] = $v;
        }

        return json_encode($data);
    }

	/**
	 * 得到坏件根据国家统计的数量
	 * 
	 * @param string $date
	 * @param string $country
	 * @return array(
	 * 				'pn1' => array('country1' => 1, 'country2' => 2),
	 * 				'pn2' => array('country1' => 3, 'country2' => 2),
	 * 				...
	 * 			)
	 */
	private function _getDefectivePN($date, $country = '') {
		$srs = $this->_getSRs($date, $country);

        $pns = array();
		foreach ($srs as $sr) {
			if (empty($sr['country'])) continue;
			if (!empty($sr['oldPN1'])) $pns[$sr['oldPN1']][$sr['country']]['qty']++;
			if (!empty($sr['oldPN2'])) $pns[$sr['oldPN2']][$sr['country']]['qty']++;
			if (!empty($sr['oldPN3'])) $pns[$sr['oldPN3']][$sr['country']]['qty']++;
		}
		return $pns;
	}

    private function _getSRs($date, $country = '') {
        //一个月之内产生的坏件数量
        $dao = new ServiceOrderDao();
        $date = strtotime($date);
        $from = date('Y-m-01 00:00:00', $date);
        $end = date('Y-m-t 23:59:59', $date);
        $condition = 'deleted = 0 and createTime >= ? and createTime <= ? and (oldPN1 != "" or oldPN2 != "" or oldPN3 != "") and recoverMethod != 5 and recoverMethod != 6';
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

        $srs = $dao->findAll(array($condition, $params), 0, 0, '', 'oldPN1,oldPN2,oldPN3,vendorId,model,imei', PDO::FETCH_ASSOC);
        if (!empty($replace)) {
            foreach ($srs as $k => $sr) {
                $srs[$k]['country'] = $replace[$sr['vendorId']];
            }
        }
        return $srs;
    }
	
    private function _getInWarrantyModel($month, $country = '') {
        $dao = new ModelWarrantyDao();
        if (empty($month)) {
            $month = date('Y-m-t');
        } else {
            $month = date('Y-m-t', strtotime($month));
        }
        $condition = 'expireTime > ? and salesTime < ?';
        $params = array($month, $month);
        if ($country) {
            $condition .= ' and country = ?';
            $params[] = $country;
        } else {
            $countries = Api::psiPlan();
            $tmp = array();
            foreach ($countries as $country) {
                $tmp[] = $country['country'];
            }
            $tmp = implode('","', $tmp);
            $tmp = '"'.$tmp.'"';
            $condition .= 'and country in ('.$tmp.')';
        }
        $condition .= ' group by modelName,salesTime';
        return $dao->findAll(array($condition, $params), 0, 0, '', 'modelName,country,sum(number) as number,model,salesTime', PDO::FETCH_ASSOC);
    }

    private function _getInWarrantyModelSum($month, $country = '') {
        $dao = new ModelWarrantyDao();
        if (empty($month)) {
            $month = date('Y-m-t');
        } else {
            $month = date('Y-m-t', strtotime($month));
        }
        $condition = 'expireTime > ? and salesTime < ?';
        $params = array($month, $month);
        if ($country) {
            $condition .= ' and country = ?';
            $params[] = $country;
        } else {
            $countries = Api::psiPlan();
            $tmp = array();
            foreach ($countries as $country) {
                $tmp[] = $country['country'];
            }
            $tmp = implode('","', $tmp);
            $tmp = '"'.$tmp.'"';
            $condition .= 'and country in ('.$tmp.')';
        }
        $condition .= ' group by modelName';
        return $dao->findAll(array($condition, $params), 0, 0, '', 'modelName,country,sum(number) as number,model', PDO::FETCH_ASSOC);
    }

    private function _getPns($models) {
        $dao = new PhoneBomDao();
        $tmp = array();
        foreach ($models as $model) {
            $pns = $dao->hasA('Model')->findAllUnique(array('Model.name = ?', array($model)), 'distinct PhoneBom.pn');
            foreach ($pns as $pn) {
                $tmp[$pn][$model] = $model;
            }
        }
        return $tmp;
    }
	
	private function _writeExcel($data) {
		$dir = SITE_ROOT.'/uploads/'.gmdate(DATE_FORMAT).'/';
		if (!is_dir($dir)) mkdir($dir);
		$filename = $dir.uniqid(time()).'.csv';
	
		$sep  = "\t";
		$eol  = "\n";
	
		$csv = '';
	
		foreach ($data as $v) {
			if (is_array($v)) {
				$csv .= '"'.implode('"'.$sep.'"', $v).'"'.$eol;
			} else {
				$csv .= $v.$eol;
			}
		}
		$csv = chr(255).chr(254).mb_convert_encoding($csv, 'UTF-16LE', 'UTF-8');
		file_put_contents($filename, $csv);
		return $filename;
	}
	
	function beforeAction($action) {
		if (!User::logined()) return User::gotoLogin();
		if (!User::can()) redirect('error/accessDenied');
	}
}