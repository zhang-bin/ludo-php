<?php
class Npi extends LdBaseCtrl {
    public function __construct() {
        parent::__construct('npi');
    }

    public function index() {
        $setting = parse_ini_file('setting.ini', true);
        $this->tpl->setFile('npi/npi')
                  ->assign('vendors', Api::getVendors())
                  ->assign('productSeries', explode(',', $setting['NPI MFFR']['product.series']))
                  ->display();
    }

    public function tbl() {
        $dao = new NpiDao();
        list($condition, $params) = $this->_getSearchCondition();
        $pager = pager(array(
            'base' => 'npi/tbl',
            'cur'  => empty($_GET['id']) ? 1 : intval($_GET['id']),
            'cnt'  => $dao->count($condition, $params),
        ));
        if (empty($condition)) {
            $list = $dao->hasA('Vendor', 'Vendor.countryShortName')->fetchAll($pager['rows'], $pager['start']);
        } else {
            $list = $dao->hasA('Vendor', 'Vendor.countryShortName')->findAll(array($condition, $params), $pager['rows'], $pager['start']);
        }
        $this->tpl->setFile('npi/tbl')
                  ->assign('list', $list)
                  ->display();
    }

    public function add() {
        if (empty($_POST)) {
            $planningMonths = LdFactory::dao('basicData')->findColumn('name = ?', BasicData::NPI_PLANNING_MONTHS, 'value');
            $setting = parse_ini_file('setting.ini', true);
            $this->tpl->setFile('npi/add')
                      ->assign('vendors', Api::getVendors())
                      ->assign('productSeries', explode(',', $setting['NPI MFFR']['product.series']))
                      ->assign('planningMonths', $planningMonths)
                      ->display();
        } else {
            $dao = new NpiDao();
            $partsDao = new PartsMaitroxDao();
            $pnDao = new NpiPnDao();
            $priceDao = new SupplierPriceDao();
            if (empty($_POST['code'])) {
                $add['code'] = self::getCode(1);
            } else {
                $add['code'] = trim($_POST['code']);
            }
            $add['vendorId'] = intval($_POST['vendorId']);
            $add['planningMonths'] = intval($_POST['planningMonths']);
            $add['createTime'] = date(TIME_FORMAT);
            $add['remark'] = trim($_POST['remark']);
            $add['productSeries'] = trim($_POST['productSeries']);
            $models = $_POST['models'];
            $add['forecastSalesNumber'] = 0;
            foreach ($models as $model) {
                $warranties[$model] = $this->_getForecastSalesNumber($model, $add['vendorId'], $add['planningMonths']);
                foreach ($warranties[$model] as $warranty) {
                    $add['forecastSalesNumber'] += $warranty['number'];
                }
            }
            try {
                $dao->beginTransaction();
                $add['id'] = $dao->insert($add);
                $pns = $_POST['pns'];

                $supplierId = intval(LdFactory::dao('supplier')->findColumn('isDefault = 1', '', 'id'));
                foreach ($pns as $model => $pn) {
                    $addPn['npiId'] = $add['id'];
                    $addPn['model'] = $model;
                    foreach ($pn as $v) {
                        $addPn['pn'] = $v;
                        $addPn['categoryId'] = $partsDao->findColumn('pn = ?', $v, 'partsGroupId');
                        $addPn['unitPrice'] = $priceDao->findColumn('pn = ? and supplierId = ? and priceType = ? and endTime is null', array($v, $supplierId, PartsPrice::TYPE_PURCHASE), PartsPrice::CURRENCY_USD);
                        $addPn['rate'] = $this->_getNpiMffr($add['vendorId'], $model, $addPn['categoryId'], $add['planningMonths']);
                        $addPn['poQty'] = $this->_getPoQty($warranties[$model], $addPn['rate']);
                        $addPn['rate'] = json_encode($addPn['rate']);
                        $pnDao->insert($addPn);
                    }
                }

                $dao->commit();
                return SUCCESS.'|'.url('npi');
            } catch (SqlException $e) {
                $dao->rollback();
                return ALERT.'|Add NPI Plan Failed';
            }
        }
    }

    public function model() {
        $productSeries = trim($_POST['productSeries']);
        $vendorId = intval($_POST['vendorId']);
        $planningMonths = intval($_POST['planningMonths']);
        $dao = new ModelDao();
        $country = LdFactory::dao('vendor')->fetchColumn($vendorId, 'country');
        $models = $dao->findAll(array('name like ? and country = ?', array($productSeries.'%', $country)));
        foreach ($models as $k => $model) {
            $models[$k]['number'] = 0;
            $numbers = $this->_getForecastSalesNumber($model['name'], $vendorId, $planningMonths);
            foreach ($numbers as $number) {
                $models[$k]['number'] += $number['number'];
            }
            if ($models[$k]['number'] == 0) unset($models[$k]);
        }

        $this->tpl->setFile('npi/model')
                  ->assign('models', $models)
                  ->display();
    }

    public function pn() {
        $models = $_POST['models'];
        $bomDao = new PhoneBomDao();
        $modelDao = new ModelDao();
        foreach ($models as $model) {
            $modelId = $modelDao->findColumn('name = ?', $model, 'id');
            $pns[$model] = $bomDao->tbl()
                        ->leftJoin('PartsMaitrox', 'PartsMaitrox.pn = PhoneBom.pn', 'PartsMaitrox.en,PartsMaitrox.partsGroup')
                        ->where('modelId = ?', $modelId)
                        ->fetchAll();
        }
        $this->tpl->setFile('npi/pn')
                  ->assign('pns', $pns)
                  ->display();
    }

    public function del() {
        $dao = new NpiDao();
        $pnDao = new NpiPnDao();
        try {
            $dao->beginTransaction();
            $id = intval($_GET['id']);
            $dao->delete($id);
            $pnDao->deleteWhere('npiId = ?', $id);
            Logger::log(array(
                'name' => 'del npi plan',
                'old' => ''
            ));
            $dao->commit();
            return SUCCESS.'|'.url('npi');
        } catch (SqlException $e) {
            $dao->rollback();
            return ALERT.'|Delete NPI Plan Failed';
        }
    }

    public function view() {
        $dao = new NpiDao();
        $pnDao = new NpiPnDao();
        $id = intval($_GET['id']);
        $npi = $dao->hasA('Vendor', 'Vendor.countryShortName')->fetch($id);
        $pns = $pnDao->tbl()
                    ->leftJoin('PartsMaitrox', 'PartsMaitrox.pn = NpiPn.pn', 'PartsMaitrox.en,PartsMaitrox.partsGroup')
                    ->where('npiId = ?', array($id))
                    ->fetchAll();
        $tmp = array();
        foreach ($pns as $pn) {
            $tmp[$pn['model']][] = $pn;
        }

        $this->tpl->setFile('npi/view')
                  ->assign('npi', $npi)
                  ->assign('pns', $tmp)
                  ->display();
    }

    public function report() {
    }

    private function _getSearchCondition() {
        $condition = $and = '';
        $params = array();

        if (!empty($_POST['productSeries'])) {
            $condition .= 'productSeries = ?';
            $params[] = trim($_POST['productSeries']);
            $and = ' and ';
        }

        if (!empty($_POST['vendor'])) {
            $condition .= $and.'vendorId in ('.implode(',', $_POST['vendor']).')';
            $and = ' and ';
        }

        if (!empty($_POST['from'])) {
            $condition .= $and.'createTime >= ?';
            $params[] = trim($_POST['from']).' 00:00:00';
            $and = ' and ';
        }

        if (!empty($_POST['to'])) {
            $condition .= $and.'createTime <= ?';
            $params[] = trim($_POST['to']).' 23:59:59';
        }
        return array($condition, $params);
    }

    private function _getForecastSalesNumber($model, $vendorId, $month) {
        $dao = new ForecastModelWarrantyDao();
        $from = date(DATE_FORMAT);
        $to = date(DATE_FORMAT, strtotime('+'.$month.' month'));
        $sql = 'select distinct batch from ForecastModelWarranty order by createTime desc limit 1';
        $batch = $dao->tbl()->sql($sql)->fetchColumn();
        return $dao->findAll(array('batch = ? and vendorId = ? and modelName = ? and salesTime >= ? and salesTime <= ?',
            array($batch, $vendorId, $model, $from, $to)));
    }

    private function _getNpiMffr($vendorId, $model, $categoryId, $month) {
        $dao = new FailureRateNpiDao();
        $productSeries = substr($model, 0, 1);
        $from = date('Y-m');
        $to = date('Y-m', strtotime('+'.$month.' month'));
        return $dao->findAll(array('vendorId = ? and productSeries = ? and categoryId = ? and month >= ? and month <= ?',
            array($vendorId, $productSeries, $categoryId, $from, $to)), 0, 0, '', 'month,rate', PDO::FETCH_ASSOC);
    }

    private function _getPoQty($warranties, $rates) {
        $defective = 0;
        foreach ($warranties as $warranty) {
            foreach ($rates as $rate) {
                if (date('Y-m', strtotime($warranty['salesTime'])) == $rate['month']) {
                    $defective += ceil($warranty['number'] * $rate['rate'] / 100);
                }
            }
        }
        return $defective;
    }

    public static function getCode($type) {
        $dao = new PlanSequenceDao();

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



    public function beforeAction($action) {
        if (!User::logined()) return User::gotoLogin();
        if (!User::can()) redirect('error/accessDenied');
    }
}