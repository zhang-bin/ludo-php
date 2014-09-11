<?php
/**
 * Ludo BillGo Platform
 *
 * @author     zhangbin <hunter.zhangbin@gmail.com>
 * @copyright  Copyright (c) 2012 Ludo team (http://www.loongjoy.com)
 * @version    $Id: Api.php 369 2013-05-31 02:42:58Z zhangbin $
 */
class Api extends LdBaseCtrl {
	public function __construct() {
		parent::__construct('Api');
	}
	
	public function getStationByVendor() {
		$id = intval($_REQUEST['id']);
		$result = LdFactory::dao('station')->findAll(array('vendorId = ?', $id), 0, 0, 'name', '*', PDO::FETCH_ASSOC);
    	return json_encode($result);	
	}
	
	public function getCarryInCenterStationList(){
		$vendorId = intval($_REQUEST['vendorId']);
		$result = LdFactory::dao('station')->findAll(array('vendorId = ? and type = 1', $vendorId), 0, 0, '', '*', PDO::FETCH_ASSOC);
		return json_encode($result);
	}
	
	public function getContactByStation() {
		$id = intval($_REQUEST['id']);
		$result = LdFactory::dao('contact')->findAll(array('stationId = ?', $id), 0, 0, '', '*', PDO::FETCH_ASSOC);
    	return json_encode($result);	
	}
	
	public function getFailureCode() {
		$id = intval($_REQUEST['id']);
		$result = LdFactory::dao('failure')->findAll(array('failureTypeId = ?', $id), 0, 0, '', '*', PDO::FETCH_ASSOC);
		return json_encode($result);
	}
	public function getGoodWarehouseByVendor() {
		$vendorId = intval($_REQUEST['id']);
		$result = LdFactory::dao('warehouse')->findAll(array('vendorId = ? and goodOrBad = 0', $vendorId), 0, 0, '', '*', PDO::FETCH_ASSOC);
		return json_encode($result);
	}
	
	public function getGoodWarehouseByStation() {
		$stationId = intval($_REQUEST['id']);
		$result = LdFactory::dao('warehouse')->findAll(array('stationId = ? and goodOrBad = 0', $stationId), 0, 0, '', '*', PDO::FETCH_ASSOC);
		return json_encode($result);
	}
	
	public function getWarehouseByVendorAndType() {
		$condition = $and = '';
		$param = array();
		$vendorId = intval($_REQUEST['vendorId']);
		if (!empty($vendorId)) {
			$condition = 'vendorId = ?';
			$param[] = $vendorId;
			$and = ' and ';
		}
		
		$goodOrBad = intval($_REQUEST['type']);
		if ($goodOrBad != '-1') {
			$condition .= $and.'goodOrBad = ?';
			$param[] = $goodOrBad;
		}
		$dao = new WarehouseDao();
		if (empty($condition)) {
			$result = $dao->fetchAll();
		} else {
			$result = $dao->findAll(array($condition, $param));
		}
		
		if (empty($result)) return 0;
		return json_encode($result);
	}
	
	public static function getPNs($pn) {
		if (empty($pn)) return null;
		$dao = new PartsMaitroxPN2Dao();
		return $dao->find('pn = ? or pn2 = ? or pn3 = ?', array($pn, $pn, $pn));
	}
	
    public static function getVendors($sort = 'countryShortName') {
        return LdFactory::dao('vendor')->findAll('countryShortName is not null', 0, 0, $sort, '*', PDO::FETCH_ASSOC);
    }

    public static function getModelTypes() {
        return LdFactory::dao('model')->fetchAllUnique('distinct modeltype',0, 0, 'modeltype');
    }

    public static function getPartsCategories() {
        return LdFactory::dao('partsGroup')->findAll('deleted = 0', 0, 0, 'partsGroupName', '*', PDO::FETCH_ASSOC);
    }

    public static function getCountries() {
        return LdFactory::dao('country')->fetchAll(0, 0, 'country');
    }

    public static function getScPoints() {
        return LdFactory::dao('scPoint')->findAll('deleted = 0', 0, 0, 'point', '*', PDO::FETCH_ASSOC);
    }

    public static function getModels() {
        return LdFactory::dao('model')->fetchAllUnique('distinct name',0, 0, 'name');
    }

    public function getPnByCategory() {
        return json_encode(LdFactory::dao('partsMaitrox')->findAllUnique(array('partsGroupId = ?', intval($_REQUEST['id'])), 'pn'));
    }

    public function getWarehouseByVendorsAndType() {
        $condition = '';
        $param = array();
        $vendorIds = $_REQUEST['vendorId'];
        if (!empty($vendorIds)) {
            $condition = 'vendorId in ('.implode(',', $vendorIds).')';
            $and = ' and ';
        }

        $goodOrBad = intval($_REQUEST['type']);
        if ($goodOrBad != '-1') {
            $condition .= $and.'goodOrBad = ?';
            $param[] = $goodOrBad;
        }
        $dao = new WarehouseDao();
        if (empty($condition)) {
            $result = $dao->fetchAll();
        } else {
            $result = $dao->findAll(array($condition, $param));
        }

        if (empty($result)) return 0;
        return json_encode($result);
    }

    public static function getWarningVendors() {
        return LdFactory::dao('vendor')->findAll('forWarning = 1 and countryShortName is not null', 0, 0, 'countryShortName', '*', PDO::FETCH_ASSOC);
    }

    public static function psiPlan() {
        return LdFactory::dao('country')->findAll('forPSI = 1', 0, 0, 'country', '*', PDO::FETCH_ASSOC);
    }

    public static function psiPlanVendors() {
        return LdFactory::dao('vendor')->tbl()
                    ->leftJoin('Country', 'Country.country = Vendor.country', 'Country.country')
                    ->where('Country.forPSI = 1')
                    ->orderby('Country.code')
                    ->fetchAll();
    }

    public function beforeAction($action) {
		if (!User::logined()) return User::gotoLogin();
	}
}