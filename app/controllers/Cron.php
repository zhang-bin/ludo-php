<?php
/**
 * Ludo BillGo Platform
 *
 * @author     zhangbin <hunter.zhangbin@gmail.com>
 * @copyright  Copyright (c) 2012 Ludo team (http://www.loongjoy.com)
 * @version    $Id$
 */
class Cron extends LdBaseCtrl {
	public function __construct() {
		parent::__construct('Cron');
	}
	
	public function partsChecking() {
		$dao = new PartsMaitroxDao();
		$inventoryDao = new InventoryDao();
		$shippingDao = new ShippingDetailsDao();
		$purchaseDao = new PurchaseOrderDetailDao();
		$pns = $dao->fetchAll(0, 0, '', 'id,pn,timestamp', PDO::FETCH_ASSOC);
		$now = time();
        $basic = LdFactory::dao('basicData')->fetchAll();

        foreach ($basic as $v) {
            switch ($v['name']) {
                case BasicData::SLOW_MONTH_LIMIT:
                    $monthLimit = $v['value'];
                    break;
                case BasicData::SLOW_TO_LIMIT:
                    $toLimit = $v['value'];
                    break;
                case BasicData::OBSOLETE:
                    $obsolete = $v['value'];
                    break;
                default:
                    break;
            }
        }
		foreach ($pns as $pn) {
			if (empty($pn['pn'])) continue;
			$update = array(
				'active' => 1,
				'slowMoving' => 0,
				'obsolete' => 0
			);
			do {
				$pnTimestamp = strtotime($pn['timestamp']);
                $min = min($monthLimit, $obsolete);
				if ($now - $pnTimestamp < $min * 2592000) {//min({No Usage Upper Limit},{obsolete})个月内添加的pn,直接不计算
					break;
				}

				$hasInventory = $inventoryDao->exists('pn = ? and qty > 0', $pn['pn']);

                if (($now - $pnTimestamp > $monthLimit * 2592000) && $hasInventory) {//在{No Usage Upper Limit}个月内有库存，无需求的PN,并且TO>{TO Value Upper Limit}的PN

                }
				
				$shippings = $shippingDao->hasA('ShippingOrder', 'ShippingOrder.timestamp')->findAll(array('ShippingDetails.partsPN = ?', $pn['pn']));
				$hasShipping6 = $hasShipping12 = false;
				if (!empty($shippings)) {
					foreach ($shippings as $shipping) {
						$timestamp = strtotime($shipping['timestamp']);
						if ($now - $timestamp < $monthLimit * 2592000) {//{No Usage Upper Limit}个月内产生shipping order
							$hasShipping6 = true;
						}
						if ($now - $timestamp < 31104000) {//12个月内产生shipping order
							$hasShipping12 = true;
							break;
						}
					}
				}
				
				$purchases = $purchaseDao->hasA('PurchaseOrder', 'PurchaseOrder.commitTime')->findAll(array('PurchaseOrderDetail.pn = ?', $pn['pn']));
				$hasPurchase6 = $hasPurchase12 = false;
				if (!empty($purchases)) {
					foreach ($purchases as $purchase) {
						$timestamp = strtotime($purchase['commitTime']);
						if ($now - $timestamp < $monthLimit * 2592000) {//{No Usage Upper Limit}个月内产生shipping order
							$hasPurchase6 = true;
						}
						if ($now - $timestamp < 31104000) {//12个月内产生shipping order
							$hasPurchase12 = true;
							break;
						}
					}
				}
				if ($hasInventory && !$hasShipping6 && !$hasPurchase6 && $now - $pnTimestamp >= 15552000) {
					$update['slowMoving'] = 1;
				}
				
				if ($hasInventory && !$hasShipping12 && !$hasPurchase12 && $now - $pnTimestamp >= 31104000) {//在{obsolete}个月内有库存，无需求的PN
					$update['obsolete'] = 1;
				}
				
				if (!$hasInventory && !$hasShipping12 && !$hasPurchase12 && $now - $pnTimestamp >= 31104000) {//如果备件12个月没有采购没有需求,没有库存（好坏件）,没有shipping order数量
					$update['active'] = 0;
				}
			} while(0);
			$dao->update($pn['id'], $update);
		}
	}
	
	public function partsUseNumber() {
        $thisMonth = strtotime(date('F 1'));
		$report = new Report();
		$_POST['month'] = date('Y-m', strtotime('-1 month', $thisMonth));
		$report->resetUseNumber();
		$_POST['month'] = date('Y-m', strtotime('-2 month', $thisMonth));
		$report->resetUseNumber();
		$_POST['month'] = date('Y-m', strtotime('-3 month', $thisMonth));
		$report->resetUseNumber();
	}
	
	public function partsInventory() {
		$report = new Report();
		$report->resetInventory();
	}
	
	public function partsShipping() {
		$report = new Report();
		$report->resetShipping();
	}

    public function modelUseNumber() {
        $thisMonth = strtotime(date('F 1'));
        $report = new Report();
        $_POST['month'] = date('Y-m', strtotime('-1 month', $thisMonth));
        $report->resetModel();
        $_POST['month'] = date('Y-m', strtotime('-2 month', $thisMonth));
        $report->resetModel();
        $_POST['month'] = date('Y-m', strtotime('-3 month', $thisMonth));
        $report->resetModel();
    }
	
	public function abcClass() {
		$_POST['month'] = date('Y-m');
		$abc = new AbcClass();
		$vendors = Report::getVendors();
		$abc->reset();
		foreach ($vendors as $vendor) {
			$_POST['vendor'] = $vendor['id'];
			$abc->reset();
		}
	}
	
	public function mffrByPn() {
		$_POST['type'] = 1;
		$_POST['month'] = date('Y-m', strtotime('-1 month'));
		$mffr = new FailureRate();
		$mffr->resetFailureRate();
	}
	
	public function mffrByReplacement() {
		$_POST['type'] = 2;
		$_POST['month'] = date('Y-m', strtotime('-1 month'));
		$mffr = new FailureRate();
		$mffr->resetFailureRate();
	}
	
	public function mffrByModel() {
		$_POST['type'] = 3;
		$_POST['month'] = date('Y-m', strtotime('-1 month'));
		$mffr = new FailureRate();
		$mffr->resetFailureRate();
	}
	
	public function mffrByShipment() {
		$_POST['type'] = 4;
		$_POST['month'] = date('Y-m', strtotime('-1 month'));
		$vendors = Report::getVendors();
		$mffr = new FailureRate();
		foreach ($vendors as $vendor) {
			$_POST['vendor'] = $vendor['id'];
			$mffr->resetFailureRate();
		}
	}
	
	public function modelWarranty() {
		$basic = new Product();
		$basic->autoWarranty();
	}
	
	public function psi() {
		$report = new Psi();
		$report->reset();
	}
	
	public function warning() {
		$warning = new Warning();
		$vendors = Report::getVendors();
		foreach ($vendors as $vendor) {
			$_POST['vendor'] = $vendor['id'];
			$warning->reset();
		}
	}

    public function partsNpiLog() {
        $dao = new PartsMaitroxDao();
        $bomDao = new PhoneBomDao();
        $warrantyDao = new ModelWarrantyDao();

        $last3Month = date('Y-m-01', strtotime('-3 month', strtotime(date('F 1'))));
        $parts = $dao->fetchAll();
        foreach ($parts as $part) {
            $models = $bomDao->hasA('Model')->findAllUnique(array('PhoneBom.pn = ?', $part['pn']), 'Model.name');
            $npiLog = 0;
            if (!empty($models)) {
                foreach ($models as $model) {
                    if ($warrantyDao->exists('modelName = ? and salesTime >= ?', array($model, $last3Month))) {
                        $npiLog = 1;
                        break;
                    }
                }
            }
            $dao->update($part['id'], array('npiLog' => $npiLog));
        }
    }
}