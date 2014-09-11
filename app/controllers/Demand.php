<?php
class Demand extends LdBaseCtrl {
    public function __construct() {
        parent::__construct('Demand');
    }

    public function failureRate() {
        $this->tpl->setFile('demand/failureRate')
                  ->assign('models', Api::getModelTypes())
                  ->assign('categories', Api::getPartsCategories())
                  ->assign('countries', Api::getCountries())
                  ->display();
    }

    public function failureRateTbl() {
        list($condition, $params) = $this->_getSearchFailureRateCondition();
        $dao = new FailureRateModelDao();
        $pager = pager(array(
            'base' => 'demand/failureRateTbl',
            'cur'  => empty($_GET['id']) ? 1 : intval($_GET['id']),
            'cnt'  => $dao->count($condition, $params),
        ));
        $mffrs = $dao->findAll(array($condition, $params), $pager['rows'], $pager['start']);

        $warrantyDao = new ModelWarrantyDao();
        $today = date(DATE_FORMAT);
        foreach ($mffrs as $k => $mffr) {
            $warranty = $warrantyDao->findColumn('model = ? and country = ? and expireTime > ? and salesTime < ?', array($mffr['model'], $mffr['country'], $today, $today), 'sum(number)');
            $mffrs[$k]['warranty'] = $warranty;
            $mffrs[$k]['demand'] = ceil($warranty * $mffr['rate'] / 100);
        }

        $this->tpl->setFile('demand/failureRateTbl')
            ->assign('mffrs', $mffrs)
            ->assign('pager', $pager['html'])
            ->display();
    }

    public function failureRateReport() {
        list($condition, $params) = $this->_getSearchFailureRateCondition();
        $dao = new FailureRateModelDao();
        $mffrs = $dao->findAll(array($condition, $params));
        $warrantyDao = new ModelWarrantyDao();
        $today = date(DATE_FORMAT);
        foreach ($mffrs as $k => $mffr) {
            $warranty = $warrantyDao->findColumn('model = ? and country = ? and expireTime > ? and salesTime < ?', array($mffr['model'], $mffr['country'], $today, $today), 'sum(number)');
            $mffrs[$k]['warranty'] = $warranty;
            $mffrs[$k]['demand'] = ceil($warranty * $mffr['rate'] / 100);
        }
        $menu = array(
            'category' => 'Parts Category',
            'model' => 'Model',
            'country' => 'Country',
            'rate' => 'Setting Failure Rate',
            'warranty' => 'Current Sales Volume',
            'demand' => 'FCST Monthly Demand',
        );
        $excel = new Excel();
        return SUCCESS.'|'.url('demand/failureRateDownload/'.base64_encode($excel->write($menu, $mffrs)));
    }

    public function failureRateDownload() {
        $name = base64_decode(trim($_GET['id']));
        downloadLink($name, 'Lenovo Mobile Demand Forecast('.getCurrentDate(gmdate(DATE_FORMAT)).').csv');
    }


    private function _getSearchFailureRateCondition() {
        $condition = 'month = ?';
        $params = array(lastMonth());
        if (!empty($_POST['model'])) {
            $model = $_POST['model'];
            $model = implode('","', array_filter($model));
            $model = '"'.$model.'"';
            $condition .= ' and model in ('.$model.')';
        }

        if (!empty($_POST['category'])) {
            $categoryId = $_POST['category'];
            $categoryId = implode(',', array_filter($categoryId));
            $condition .= ' and categoryId in ('.$categoryId.')';
        }

        if (!empty($_POST['country'])) {
            $country = trim($_POST['country']);
            $condition .= ' and country = ?';
            $params[] = $country;
        }
        return array($condition, $params);
    }

    function beforeAction($action) {
        if (!User::logined()) return User::gotoLogin();
        if (!User::can()) redirect('error/accessDenied');
    }
}