<?php
class Test extends LdBaseCtrl {
	function __construct() {
		parent::__construct('Test');
	}
	
	function index() {
		$dao = new PartsSubstitutionDao();
		$partsDao = new PartsMaitroxDao();
		$pns = $dao->fetchAll();
		$dao->updateWhere(array('groupNo' => null), '1=1');
		$partsGroup = array();
		foreach ($pns as $pn) {
			$groupNo = $dao->fetchColumn($pn['id'], 'groupNo');
			
			if (!empty($groupNo)) continue;
			$group = $partsDao->findColumn('pn = ?', $pn['pn1'], 'partsGroup');
			if (!empty($group)) {
				if (isset($partsGroup[$group])) {
					$partsGroup[$group]++;
				} else {
					$partsGroup[$group] = 1;
				}
				$group = $group.str_pad($partsGroup[$group], 3, 0, STR_PAD_LEFT);
				$dao->update($pn['id'], array('groupNo' => $group));
				for ($i = 2;$i <= 10;$i++) {
					if (!empty($pn['pn'.$i])) $j = $i;
				}
				$where = '';
				$params = array();
				
				
				
				
				$where = 'model = ?';
				$params[] = $pn['model'];
				
				switch ($j) {
					case 2:
						$where .= ' and pn1 = ? and pn2 = ?';
						$params[] = $pn['pn2'];
						$params[] = $pn['pn1'];
						$dao->updateWhere(array('groupNo' => $group), $where, $params);
						break;
					case 3:
						$where .= ' and pn1 = ? and pn2 = ? and pn3 = ?';
						$params[] = $pn['pn3'];
						$params[] = $pn['pn2'];
						$params[] = $pn['pn1'];
						$dao->updateWhere(array('groupNo' => $group), $where, $params);

						$where = 'model = ? and pn1 = ? and pn2 = ? and pn3 = ?';
						$params = array();
						$params[] = $pn['model'];
						$params[] = $pn['pn2'];
						$params[] = $pn['pn1'];
						$params[] = $pn['pn3'];
						$dao->updateWhere(array('groupNo' => $group), $where, $params);
						break;
					default:
						break;
				}				
			}
		}
	}
	
	function index2() {
        return;
        $filename = '/tmp/PO整理.xlsx';
        ini_set('memory_limit', '2000M');
        set_time_limit(0);
        Load::helper('excel/PHPExcel');
        $reader = new PHPExcel_Reader_Excel2007();
        $reader->setReadDataOnly(true);
        $excel = $reader->load($filename);
        spl_autoload_register('__autoload');
        $sheet = $excel->getSheet(0);

        $dao = new PurchaseOrderDao();
        $detailDao = new PurchaseOrderDetailDao();
        $ltDao = new PartsLeadTimeDao();
        $suppliers = array(
            '联想移动' => 5,
            '华宝' => 4,
            '华勤' => 9,
            '龙旗' => 7,
            '莫斯特' => 8,
            '仁宝' => 4,
            '厦门联想' => 5,
            '武汉联想' => 5
        );

        $types = array(
            'regular' => 2,
            'NPI' => 1
        );

        $rates = array(
            '2012-01' => 6.3009,
            '2012-02' => 6.3103,
            '2012-03' => 6.3016,
            '2012-04' => 6.2943,
            '2012-05' => 6.267,
            '2012-06' => 6.3308,
            '2012-07' => 6.3146,
            '2012-08' => 6.314,
            '2012-09' => 6.3482,
            '2012-10' => 6.3392,
            '2012-11' => 6.3028,
            '2012-12' => 6.2908,
            '2013-01' => 6.2865,
            '2013-02' => 6.2745,
            '2013-03' => 6.2804,
            '2013-04' => 6.2716,
            '2013-05' => 6.2342,
            '2013-06' => 6.207,
            '2013-07' => 6.1677,
            '2013-08' => 6.1652,
            '2013-09' => 6.1675,
            '2013-10' => 6.1557,
            '2013-11' => 6.1335,
            '2013-12' => 6.1305,
            '2014-01' => 6.1105,
            '2014-02' => 6.1005,
            '2014-03' => 6.1103,
            '2014-04' => 6.1351,
            '2014-05' => 6.1589,
        );

        $day60 = 60 * 86400;
        $day30 = 30 * 86400;
        $now = date(TIME_FORMAT);

        $maxRow = $sheet->getHighestRow();
        $pos = array();
        for ($i = 2; $i < $maxRow; $i++) {
            $po = array();
            $code = trim($sheet->getCellByColumnAndRow(16, $i)->getValue());
            if (empty($code)) continue;
            if (!isset($pos[$code])) {
                $po['code'] = $code;
                $supplierName = trim($sheet->getCellByColumnAndRow(8, $i)->getValue());
                $po['supplierId'] = $suppliers[$supplierName];
                $po['warehouseId'] = 509;
                $po['type'] = $types[trim($sheet->getCellByColumnAndRow(3, $i)->getValue())];
                $date = PHPExcel_Style_NumberFormat::toFormattedString($sheet->getCellByColumnAndRow(0, $i)->getCalculatedValue(), 'yyyy-mm-dd');
                if ($po['type'] == 2) {//60天
                    $po['demandTime'] = date(DATE_FORMAT, strtotime($date) + $day60);
                } else if ($po['type'] == 1) {//30天
                    $po['demandTime'] = date(DATE_FORMAT, strtotime($date) + $day30);
                }
                $po['status'] = PurchaseOrder::STATUS_CLOSED;
                $po['currency'] = trim($sheet->getCellByColumnAndRow(12, $i)->getValue());
                $po['warranty'] = 1;
                $po['remark'] = 'historical purchase order';
                $po['commitTime'] = $po['createTime'] = $now;
                $po['createUserId'] = 207;
                $pos[$code] = $po;
            }
            $po = $pos[$code];
            $detail = array();
            $pn = trim($sheet->getCellByColumnAndRow(4, $i)->getValue());
            if (in_array($pn, array('5P69A15263', '5P69A15262', '5P69A15194', '5P69A15201'))) continue;
            $pns = Api::getPNs($pn);
            if (empty($pns['pn'])) {
                debug($pn);
            }
            $detail['pn'] = $pns['pn'];
            $detail['qty'] = trim($sheet->getCellByColumnAndRow(9, $i)->getValue());
            $detail['status'] = PurchaseOrder::PN_STATUS_CLOSE;
            $detail['aog'] = trim($sheet->getCellByColumnAndRow(10, $i)->getValue());
            $detail['createTime'] = $now;
            $detail['usd'] = sprintf('%.4f', $sheet->getCellByColumnAndRow(14, $i)->getValue());
            if ($detail['usd'] == 0.0000) $detail['usd'] = 0.01;
            $month = date('Y-m', strtotime($date));
            $detail['rmb'] = round($detail['usd'] * $rates[$month], 4);
            $detail['unitPrice'] = $detail[strtolower($po['currency'])];
            $detail['closeTime'] = $now;
            $detail['leadTime'] = $ltDao->findColumn('pn = ?', $detail['pn'], 'leadTime');
            $detail['pickupTime'] = PHPExcel_Style_NumberFormat::toFormattedString($sheet->getCellByColumnAndRow(17, $i)->getCalculatedValue(), 'yyyy-mm-dd');
            $pos[$code]['details'][] = $detail;
        }

        try {
            $dao->beginTransaction();
            foreach ($pos as $po) {
                $details = $po['details'];
                unset($po['details']);
                $poId = $dao->insert($po);
                $pnSum = $amount = 0;
                foreach ($details as $detail) {
                    $pnSum += $detail['qty'];
                    $amount += $detail['qty'] * $detail['unitPrice'];

                    $detail['purchaseOrderId'] = $poId;
                    $detail['usd'] = Crypter::encrypt($detail['usd']);
                    $detail['rmb'] = Crypter::encrypt($detail['rmb']);
                    $detail['unitPrice'] = Crypter::encrypt($detail['unitPrice']);
                    $detailDao->insert($detail);
                }

                $dao->update($poId, array('pnSum' => $pnSum, 'amount' => round($amount, 2)));
            }
            $dao->commit();
        } catch (SqlException $e) {
            $dao->rollback();
        }


//            $sql = 'update PurchaseOrder set pnSum = pnSum + '.$detail['qty'].', amount = amount + '.($detail['unitPrice'] * $detail['qty']).' where id = ?';
//            $dao->tbl()->exec($sql, $poId);
	}

    public function index3() {
        $arr = array(1,2,3,4);
        $a = 1;
        array_walk($arr, function($value, $a) {
            debug($value);
            debug($a);

        }, $a);
    }
}