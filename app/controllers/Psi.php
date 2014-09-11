<?php
class Psi extends LdBaseCtrl {
    private $_pn;
    private static $_basicData = array();
    private static $_months = array();
    private static $_failureRate = array();
    private static $_modelWarranty = array();
    private static $_apply = array();

    public function __construct() {
        $thisMonth = strtotime(date('F 1'));
        self::$_months['lastMonth'] = date('Y-m', strtotime('-1 month', $thisMonth));
        self::$_months['last2Month'] = date('Y-m', strtotime('-2 month', $thisMonth));
        self::$_months['last3Month'] = date('Y-m', strtotime('-3 month', $thisMonth));
        self::$_months['thisMonth'] = date('Y-m');
        self::$_months['nextMonth'] = date('Y-m', strtotime('+1 month', $thisMonth));
        self::$_months['next2Month'] = date('Y-m', strtotime('+2 month', $thisMonth));
        parent::__construct('psi');
    }

    public function index() {
        $this->tpl->setFile('psi/psi')->display();
    }

    public function psi() {
//        ini_set('memory_limit', '1000M');
        $pns = $this->pn();

        $vendors = Report::getVendors();

        $data[] = $this->_getHeader1($vendors);
        $data[] = $this->_getHeader2($vendors);
        $data[] = $this->_getHeader3($vendors);

        $tmp = LdFactory::dao('BasicData')->fetchAll();
        foreach ($tmp as $v) {
            self::$_basicData[$v['name']] = $v['value'];
        }

        $routeDao = new ScRouteDao();
        $routes = $routeDao->findAll(array('deleted = 0 and poType = ?', BasicData::LT_PSI));
        foreach ($routes as $route) {
            self::$_basicData['lt'][$route['vendorId']] = $route['totalDays'];
        }

        self::$_basicData[BasicData::PSI_SAFTY_STOCK] = json_decode(self::$_basicData[BasicData::PSI_SAFTY_STOCK], true);

        $partsDao = new PartsMaitroxDao();
        $warningDao = new WarningDao();
        $abcClassDao = new AbcClassDao();
        $inventoryDao = new InventoryDao();
        $shippingOrderDao = new ShippingDetailsDao();
        $now = time();
        foreach ($pns as $pn => $slave) {
            $this->_pn = $pn;
            $planning = array();
            //PN,Parts Description,Group,Category,Model信息
            $models = $this->_getModel($pn);
            if (empty($models)) continue;

            $beginTime = $endTime = $slaveParts = array();
            $pnTmp = array($pn);
            $part = $partsDao->find('pn = ?', $pn, '*', PDO::FETCH_ASSOC);
            list($beginTime[], $endTime[]) = $warningDao->find('pn = ? and vendorId = 0', array($pn), 'beginTime, endTime', PDO::FETCH_NUM);
            if (!empty($slave['slave'])) {
                foreach ($slave['slave'] as $slavePn) {
                    list($beginTime[], $endTime[]) = $warningDao->find('pn = ? and vendorId = 0', array($slavePn), 'beginTime, endTime', PDO::FETCH_NUM);
                    $pnTmp[] = $slavePn;
                    $slaveParts[] = $partsDao->find('pn = ?', $slavePn, '*', PDO::FETCH_ASSOC);
                }
            }

            $npiFlag = false;
            if ($part['npiLog']) {
                $npiFlag = true;
            } else {
                foreach ($slaveParts as $slavePart) {
                    if ($slavePart['npiLog']) {
                        $npiFlag = true;
                        break;
                    }
                }
            }

            $eolFlag = true;
            if ($part['EOL']) {
                foreach ($slaveParts as $slavePart) {
                    if ($slavePart['EOL']) continue;
                    $eolFlag = false;
                    break;
                }
            } else {
                $eolFlag = false;
            }

            $planning[] = $pn;
            $planning[] = $abcClassDao->findColumn('pn = ? and vendorId = 0 order by month', $pn, 'abcClass');
            $planning[] = $part['en'];
            $planning[] = isset($slave['group']) ? $slave['group'] : '';
            $planning[] = $part['partsGroup'];
            $planning[] = implode(',', $models);
            $minBeginTime = min($beginTime);
            $maxEndTime = max($endTime);
            $planning[] = round((strtotime($maxEndTime) - $now) / 86400 / 30, 1);
            $planning[] = $minBeginTime;
            $planning[] = $maxEndTime;
            $planning[] = $npiFlag ? 'Y' : 'N';
            $planning[] = $eolFlag ? 'Y' : 'N';

            //FCST Next Month Demand
            list($qtyByWeight, $qtyByRA, $qtyByActual) = $this->_getDemand($planning, $pnTmp, $models, $vendors, $part['partsGroupId'], $npiFlag);

            //Forecast Inventory(I)
            $forecastInventories = $this->_getForecastInventory($planning, $vendors, $qtyByActual);

            //Good Inventory
            $inventories = $this->_mergeInventory($planning, $pnTmp, $vendors);

            //Shipping Order
            $shippings = $this->_mergeShipping($planning, $pnTmp, $vendors);

            //Parts Apply
            $applies = $this->_mergePartsApply($planning, $pnTmp, $vendors);

            //Inventory Discrepancy
            $this->_getInventoryDiscrepancy($planning, $vendors, $forecastInventories, $inventories, $shippings, $applies);

            $usages = $demands = array();
            foreach ($vendors as $vendor) {
                $usages[$vendor['id']] = $inventories[$vendor['id']] + $shippings[$vendor['id']] - $applies[$vendor['id']];
                $demands[$vendor['id']]['f'] = $qtyByRA[$vendor['id']];
                $demands[$vendor['id']]['w'] = $qtyByWeight[$vendor['id']];
                $demands[$vendor['id']]['r'] = $qtyByActual[$vendor['id']];

            }
            $this->_getTO($planning, $vendors, $usages, $demands);

            //Maitrox HK Good Inventory
            $qty = 0;
            foreach ($pnTmp as $v) {
                $qty += $inventoryDao->findColumn('warehouseId = ? and pn = ?', array(509, $v), 'sum(qty)');
            }
            $planning[] = $qty;

            //Maitrox Shipping Order
            $qty = 0;
            foreach ($pnTmp as $v) {
                $qty += $shippingOrderDao->hasA('ShippingOrder')->findColumn('partsPN = ? and destinationWarehouseId = ? and status = ?', array($v, 509, 1), 'sum(ShippingDetails.qty)');
            }
            $planning[] = $qty;

            $data[] = $planning;
        }
        return $data;
    }

    public function report() {
        $filename = SITE_ROOT.'/static/psi.txt';
        if (!file_exists($filename)) touch($filename);
        $data = trim(file_get_contents($filename));

        if (empty($data)) {
            $this->reset();
        } else {
            $data = json_decode(gzuncompress(base64_decode($data)));
        }
        $excel = new Excel();
        downloadLink($excel->writeData($data), 'Lenovo Mobile Phone Parts Planning List('.getCurrentDate(gmdate(DATE_FORMAT)).').csv');
    }

    public function reset() {
        $filename = SITE_ROOT.'/static/psi.txt';
        file_exists($filename) && unlink($filename);
        $script = SITE_ROOT.'/bin/psi.py';
        $cmd = "/usr/bin/python {$script} {$filename}";
        exec($cmd);
        if (file_exists($filename)) {
            return 1;
        } else {
            return 0;
        }
//        ini_set('memory_limit', '2000M');
//        if (!file_exists($filename)) touch($filename);
//        $data = $this->psi();
//        file_put_contents($filename, base64_encode(gzcompress(json_encode($data))));
//        return 1;
    }

    public function pn() {
        $dao = new ServiceOrderDao();
        $pns = array();
        $srs = $dao->findAll('(newPN1 != "" or newPN2 != "" or newPN3 != "") and deleted = 0', 0, 0, '', 'newPN1,newPN2,newPN3');
        foreach ($srs as $sr) {
            if (!empty($sr['newPN1'])) {
                $pns[$sr['newPN1']] = $sr['newPN1'];
            }
            if (!empty($sr['newPN2'])) {
                $pns[$sr['newPN2']] = $sr['newPN2'];
            }
            if (!empty($sr['newPN3'])) {
                $pns[$sr['newPN3']] = $sr['newPN3'];
            }
        }
        unset($srs);

        $dao = new InventoryDao();
        $inventories = $dao->fetchAllUnique('distinct pn');
        foreach ($inventories as $inventory) {
            $pns[$inventory] = $inventory;
        }
        unset($inventories);

        $dao = new PurchaseDao();
        $purchases = $dao->fetchAllUnique('distinct pn');
        if (!empty($purchases)) {
            foreach ($purchases as $purchase) {
                $pns[$purchase] = $purchase;
            }
        }
        unset($purchases);

        $clusterDao = new PartsClusterDao();
        $clusters = $clusterDao->fetchAll();
        foreach ($clusters as $cluster) {
            $slaves = json_decode($cluster['slavePn'], true);
            $pns[$cluster['masterPn']] = array('slave' => $slaves, 'group' => $cluster['cluster']);
            foreach ($slaves as $slave) {
                unset($pns[$slave]);
            }
        }
        unset($clusters);

        return $pns;
    }

    private function _getHeader1($vendors) {
        for ($i = 1; $i <= 11; $i++) {
            $header[] = '';
        }

        $vendorCnt = count($vendors);
        $header[] = 'FCST Next Month Demand';
        $cnt = $vendorCnt * 3;//需要分国家预测F,W,R三种结果
        for ($i = 1; $i < $cnt; $i++) {
            $header[] = '';
        }

        $header[] = 'FCST Inventory';
        for ($i = 1; $i < $vendorCnt; $i++) {
            $header[] = '';
        }

        $header[] = 'Actual Inventory';
        for ($i = 1; $i < $vendorCnt; $i++) {
            $header[] = '';
        }

        $header[] = 'Shipping Order Qty.';
        for ($i = 1; $i < $vendorCnt; $i++) {
            $header[] = '';
        }

        $header[] = 'Parts Apply';
        for ($i = 1; $i < $vendorCnt; $i++) {
            $header[] = '';
        }

        $header[] = 'Inventory Discrepancy';
        for ($i = 1; $i < $vendorCnt; $i++) {
            $header[] = '';
        }

        $header[] = 'TO';
        $cnt = $vendorCnt * 3;//需要分国家预测F,W,R三种结果
        for ($i = 1; $i < $cnt; $i++) {
            $header[] = '';
        }
        return $header;
    }

    private function _getHeader2($vendors) {
        for ($i = 1; $i <= 11; $i++) {
            $header[] = '';
        }

        //FCST Next Month Demand
        foreach ($vendors as $vendor) {//需要分国家预测F,W,R三种结果
            $header[] = $vendor['countryShortName'];
            $header[] = '';
            $header[] = '';
        }

        //FCST Inventory
        foreach ($vendors as $vendor) {
            $header[] = $vendor['countryShortName'];
        }

        //Actual Inventory
        foreach ($vendors as $vendor) {
            $header[] = $vendor['countryShortName'];
        }

        //Shipping Order Qty.
        foreach ($vendors as $vendor) {
            $header[] = $vendor['countryShortName'];
        }

        //Parts Apply
        foreach ($vendors as $vendor) {
            $header[] = $vendor['countryShortName'];
        }

        //Inventory Discrepancy
        foreach ($vendors as $vendor) {
            $header[] = $vendor['countryShortName'];
        }

        //TO
        foreach ($vendors as $vendor) {//需要分国家预测F,W,R三种结果
            $header[] = $vendor['countryShortName'];
            $header[] = '';
            $header[] = '';
        }

        //Maitrox HK Good Inventory
        $header[] = 'Maitrox HK Good Inventory';
        $header[] = 'Maitrox Shipping Order';
        return $header;
    }

    private function _getHeader3($vendors) {
        $header[] = 'PN';
        $header[] = 'ABC Class';
        $header[] = 'Parts Description';
        $header[] = 'Group';
        $header[] = 'Category';
        $header[] = 'Model';
        $header[] = 'Remain Warranty Month(MODIFY)';
        $header[] = 'Begin Warranty Time';
        $header[] = 'End Warranty Time';
        $header[] = 'NPI Flag';
        $header[] = 'EOL Flag';

        //FCST Next Month Demand
        $vendorCnt = count($vendors);
        for ($i = 0; $i < $vendorCnt; $i++) {//需要分国家预测F,W,R三种结果
            $header[] = 'F';
            $header[] = 'W';
            $header[] = 'R';
        }

        //FCST Inventory
        for ($i = 0; $i < $vendorCnt; $i++) {
            $header[] = '';
        }

        //Actual Inventory
        for ($i = 0; $i < $vendorCnt; $i++) {
            $header[] = '';
        }

        //Shipping Order Qty.
        for ($i = 0; $i < $vendorCnt; $i++) {
            $header[] = '';
        }

        //Parts Apply
        for ($i = 0; $i < $vendorCnt; $i++) {
            $header[] = '';
        }

        //Inventory Discrepancy
        for ($i = 0; $i < $vendorCnt; $i++) {
            $header[] = '';
        }

        //TO
        for ($i = 0; $i < $vendorCnt; $i++) {//需要分国家预测F,W,R三种结果
            $header[] = 'F';
            $header[] = 'W';
            $header[] = 'R';
        }
        return $header;
    }

    private function _getInventoryCnt($inventories) {
        $cnt = 0;
        foreach ($inventories as $vendor => $inventory) {
            if ($vendor == 'CN') {
                $cnt += count($inventory)+1;
            } else {
                $cnt++;
            }
        }
        return $cnt;
    }

    private function _getModel($pn) {
        $phoneBomDao = new PhoneBomDao();
        $modelDao = new ModelDao();
        $models = $phoneBomDao->findAllUnique(array('pn = ?', $pn), 'distinct modelId');
        $models = array_filter($models);
        $modelName = array();
        if (!empty($models)) {
            $models = implode(',', $models);
            $modelName = $modelDao->findAllUnique('id in ('.$models.')', 'distinct modeltype');
            $modelName = array_filter($modelName);
        }
        return $modelName;
    }

    private function _mergeInventory(&$planning, $pns, $vendors) {
        $dao = new PartsInventoryDao();
        foreach ($vendors as $vendor) {
            $qty = 0;
            foreach ($pns as $pn) {
                $qty += $dao->hasA('Warehouse')->findColumn('pn = ? and PartsInventory.vendorId = ? and month = ? and Warehouse.goodOrBad = 0', array($pn, $vendor['id'], self::$_months['thisMonth']), 'sum(qty)');
            }
            $planning[] = $qty;
            $inventories[$vendor['id']] = $qty;
        }
        return $inventories;
    }

    private function _mergeShipping(&$planning, $pns, $vendors) {
        $dao = new PartsShippingDao();
        foreach ($vendors as $vendor) {
            $qty = 0;
            foreach ($pns as $pn) {
                $qty += $dao->findColumn('pn = ? and vendorId = ?', array($pn, $vendor['id']), 'sum(qty)');
            }
            $planning[] = $qty;
            $shippings[$vendor['id']] = $qty;
        }
        return $shippings;
    }

    private function _mergePartsApply(&$planning, $pns, $vendors) {
        if (empty(self::$_apply)) {
            $dao = new ServiceOrderDao();
            foreach ($vendors as $vendor) {
                $condition = 'deleted = 0 and vendorId = ? and status in (?,?,?)';
                $params = array($vendor['id'], ServiceManage::STATUS_APPLY, ServiceManage::STATUS_PARTS_AVAILBLE, ServiceManage::STATUS_PARTS_AVAILBLE_SELF);
                $srs = $dao->findAll(array($condition, $params), 0, 0, 'newPN1,newPN2,newPN3');
                foreach ($srs as $sr) {
                    if (!empty($sr['newPN1'])) self::$_apply[$vendor['id']][$sr['newPN1']]++;
                    if (!empty($sr['newPN2'])) self::$_apply[$vendor['id']][$sr['newPN2']]++;
                    if (!empty($sr['newPN3'])) self::$_apply[$vendor['id']][$sr['newPN3']]++;
                }
            }
        }
        $apply = array();
        foreach ($vendors as $vendor) {
            $qty = 0;
            foreach ($pns as $pn) {
                $qty += self::$_apply[$vendor['id']][$pn];
            }
            $apply[$vendor['id']] = $qty;
            $planning[] = $qty;
        }
        return $apply;
    }

    private function _getInventoryDiscrepancy(&$planning, $vendors, $forecastInventories, $inventories, $shippings, $applies) {
        foreach ($vendors as $vendor) {
            $discrepancy = $forecastInventories[$vendor['id']] - ($inventories[$vendor['id']] + $shippings[$vendor['id']] - $applies[$vendor['id']]);
            $planning[] = $discrepancy;
        }
    }

    private function _getTO(&$planning, $vendors, $usages, $demands) {
        foreach ($vendors as $vendor) {
            $usage = $usages[$vendor['id']];
            $demand = $demands[$vendor['id']];
            foreach ($demand as $v) {
                if ($v == 0 && $usage == 0) {
                    $to = 'N/A';
                } else if ($v == 0 && $usage != 0) {
                    $to = '9999';
                } else if ($usage == 0) {
                    $to = 0;
                } else {
                    $to = round($usage / $v, 2);
                }
                $planning[] = $to;
            }
        }
    }

    private function _getForecastInventory(&$planning, $vendors, $demand) {
        $inventory = array();
        foreach ($vendors as $vendor) {
            $vendorId = $vendor['id'];
            $lt = round((self::$_basicData['lt'][$vendorId]) / 30, 1);
            $planning[] = $inventory[$vendorId] = ceil($demand[$vendorId] * (1 + $lt + self::$_basicData[BasicData::PSI_SAFTY_STOCK][$vendorId]));
        }
        return $inventory;
    }

    private function _getFailureRate($model, $categoryId, $vendorId, $month) {
        if (empty($model)) return 0;
        if (empty(self::$_failureRate)) {
            $dao = new FailureRateModelDao();
            $failureRates = $dao->findAll(array('month = ?', $month));
            foreach ($failureRates as $failureRate) {
                self::$_failureRate[$failureRate['model']][$failureRate['categoryId']][$failureRate['vendorId']] = $failureRate['rate'];
            }
        }

        return self::$_failureRate[$model][$categoryId][$vendorId];
    }

    private function _getModelWarranty($model, $vendorId, $date) {
        if (empty($model)) return 0;
        if (isset(self::$_modelWarranty[$vendorId][$model][$date])) {
            $number = self::$_modelWarranty[$vendorId][$model][$date];
        } else {
            $dao = new ModelWarrantyDao();
            $now = date('Y-m-t', strtotime($date));
            $number = $dao->findColumn('model = ? and vendorId = ? and expireTime > ? and salesTime < ?', array($model, $vendorId, $now, $now), 'sum(number)');
            self::$_modelWarranty[$vendorId][$model][$now] = $number;
        }
        return $number;
    }

    private function _getQtyByWeight($firstMonth, $secondMonth, $thirdMonth) {
        $qty = $firstMonth * (self::$_basicData[BasicData::PSI_WEIGHT1] / 100) +
            $secondMonth * (self::$_basicData[BasicData::PSI_WEIGHT2] / 100) +
            $thirdMonth * (self::$_basicData[BasicData::PSI_WEIGHT3] / 100);
        return ceil($qty);
    }

    private function _getDemand(&$planning, $pns, $models, $vendors, $categoryId, $npiLog) {
        $qtyByWeight = $qtyByRA = $qtyByActual = array();
        $dao = new PartsUseNumberDao();
        foreach ($vendors as $vendor) {
            $demandByMffr = 0;
            foreach ($models as $model) {
                $rate = $this->_getFailureRate($model, $categoryId, $vendor['id'], self::$_months['lastMonth']);
                $warranty = $this->_getModelWarranty($model, $vendor['id'], self::$_months['thisMonth']);
                $demandByMffr += ceil($warranty * $rate / 100);
            }
            $planning[] = $demandByMffr;
            $qtyByRA[$vendor['id']] = $demandByMffr;

            $last3Month = $last2Month = $lastMonth = 0;
            foreach ($pns as $pn) {
                $last3Month += $dao->findColumn('pn = ? and vendorId = ? and month = ?', array($pn, $vendor['id'], self::$_months['last3Month']), 'sum(qty)');
                $last2Month += $dao->findColumn('pn = ? and vendorId = ? and month = ?', array($pn, $vendor['id'], self::$_months['last2Month']), 'sum(qty)');
                $lastMonth += $dao->findColumn('pn = ? and vendorId = ? and month = ?', array($pn, $vendor['id'], self::$_months['lastMonth']), 'sum(qty)');
            }
            $demandByWeight = $this->_getQtyByWeight($last3Month, $last2Month, $lastMonth);
            $planning[] = $demandByWeight;
            $qtyByWeight[$vendor['id']] = $demandByWeight;
            $demandByActual = $npiLog ? $demandByMffr : $demandByWeight;
            $qtyByActual[$vendor['id']] = $demandByActual;
            $planning[] = $demandByActual;
        }
        return array($qtyByWeight, $qtyByRA, $qtyByActual);
    }

    public function beforeAction($action) {
        if (!User::logined()) return User::gotoLogin();
        if (!User::can()) redirect('error/accessDenied');
    }
}