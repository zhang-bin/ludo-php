<?php
/**
 * Ludo BillGo Platform
 * @author     going1000 <miaorenjin@gmail.com>
 * @copyright  Copyright (c) 2012 Ludo team (http://www.loongjoy.com)
 * @version    $Id: PartsPrice.php 153 2013-02-20 06:35:57Z zhangbin $
 */
class PartsPrice extends LdBaseCtrl {
	const TYPE_PURCHASE = 1;
	const TYPE_OOW = 2;
	const TYPE_COST = 3;
	
	const CURRENCY_RMB = 'RMB';
	const CURRENCY_USD = 'USD';
	
	public function __construct() {
		parent::__construct('PartsPrice');
	}
	
	public function index() {
		$this->supplierPrice();
	}


	public function supplierPrice() {
		$condition = 'endTime is null';
        $params = array();
		if (!empty($_GET['pn'])) {
			$condition .= ' and pn = ?';
			$params[] = trim($_GET['pn']);
		}
		if (!empty($_GET['type'])) {
			$condition .= ' and priceType = ?';
			$params[] = trim($_GET['type']);
		}
		
		$dao = new SupplierPriceDao();
		$pager = pager(array(
				'base' => 'partsPrice/supplierPrice'.$this->resetGet(),
				'cur'    => isset($_GET['pager']) ? intval($_GET['pager']) : 1,
				'cnt'    => $dao->count($condition, $params),
		));
				
		$list = $dao->hasA('Supplier', 'Supplier.supplier')->findAll(array($condition, $params), $pager['rows'], $pager['start']);
		$this->tpl->setFile('supplierPrice/list')
		->assign('list', $list)             
		->assign('pager', $pager['html'])
		->display();		
	}
	
    public function history() {
    	$id = intval($_GET['id']);
    	$dao = new SupplierPriceDao();
    	$price = $dao->fetch($id);
    	
    	$prices = $dao->hasA('Supplier', 'Supplier.supplier')->findAll(array('pn = ? and priceType = ? and supplierId = ?', array($price['pn'], $price['priceType'], $price['supplierId'])), 0, 0, 'createTime desc');
    	
    	$this->tpl->setFile('supplierPrice/history')
    			->assign('prices', $prices)
    			->display();
    }
    
    public function suggestParts() {
    	$pn = trim($_GET['query']);
    	$dao = new PartsMaitroxDao();
    	$dao2 = new PartsMaitroxPN2Dao();
    	
    	$parts = $dao->findAll(array('pn like ?', $pn.'%'), 10);
    	
    	$tmp = array();
    	foreach ($parts as $part) {
    		$part2 = $dao2->find('pn = ?', $part['pn']);
    		$label = $part2['pn'];
    		if (!empty($part2['pn2'])) $label .= '--'.$part2['pn2'];
    		if (!empty($part2['pn3'])) $label .= '--'.$part2['pn3'];
    		$label .= '--'.$part['en'];
    		$tmp[] = array(
    				'value' => $part['pn'],
    				'label' => $label,
    				'en' => $part['en'],
    				'category' => $part['partsGroup'],
    				'pn3' => $part2['pn3']
    		);
    	}
    	return json_encode($tmp);
    }
    
    public function supplierPriceReport() {
    	$condition = 'endTime is null';
		$dao = new SupplierPriceDao();
				
		$list = $dao->getList($condition);
		
		$menu = array(LG_PARTSPRICE_PARTSPN, LG_PARTSPRICE_MODEL, LG_PARTSPRICE_CATEGORY, LG_PARTSPRICE_EN, LG_PARTSPRICE_SUPPLIER, LG_PARTSPRICE_TYPE, LG_PARTSPRICE_USD, LG_PARTSPRICE_RMB);
		
		$data = array();
		$conf = Load::conf('PartsPrice');
		foreach ($list as $v) {
			$model = Parts::getModel($v['pn']);
			if (!empty($model)) $model = implode(',', Parts::getModel($v['pn']));
			$data[] = array($v['pn'], $model, $v['partsGroup'], $v['en'], $v['supplier'], $conf['type'][$v['priceType']], Crypter::decrypt($v['usd']), Crypter::decrypt($v['rmb']));
		}
		$excel = new Excel();
		$filename = $excel->write($menu, $data);
		downloadLink($filename, 'Lenovo Mobile Phone Supplier Parts List('.getCurrentDate(gmdate(DATE_FORMAT)).').csv');
    }
    
    public function exchangeRate() {
    	$exchangeRate = LdFactory::dao('BasicData')->findColumn('name = ?', BasicData::CR, 'value');
    	$this->tpl->setFile('partsPrice/exchangeRate')
    	->assign('exchangeRate', $exchangeRate)
    	->display();
    }
    
    public function changeExchangeRate() {
    	$dao = new BasicDataDao();
		if (empty($_POST)) {
			$exchangeRate = LdFactory::dao('BasicData')->findColumn('name = ?', BasicData::CR, 'value');
	    	$this->tpl->setFile('partsPrice/changeExchangeRate')
	    	->assign('exchangeRate', $exchangeRate)
	    	->display();
		} else {
			$value = trim($_POST['value']);
			try {
				$dao->beginTransaction();
    	 		if ($dao->exists('name = ?', BasicData::CR)) {
    	 			$dao->updateWhere(array('value' => $value), 'name = ?', BasicData::CR);
    	 		} else {
	    	 		$dao->insert(array('name' => BasicData::CR, 'value' => $value));
    	 		}
				
				$dao->commit();
	    	 	return SUCCESS.'|'.url('partsPrice/exchangeRate');
			} catch (SqlException $e) {
				$dao->rollback();
				return ALERT.'|'.LG_EXCHANGE_RATE_CHANGE_FAILED;
			}
		}
    }

    function beforeAction($action) {
    	if (!User::logined()) return User::gotoLogin();
    	if (!User::can()) redirect('error/accessDenied');
    }
}