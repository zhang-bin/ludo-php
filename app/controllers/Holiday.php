<?php
/**
 * Ludo BillGo Platform
 *
 * @author     zhangbin <hunter.zhangbin@gmail.com>
 * @copyright  Copyright (c) 2012 Ludo team (http://www.loongjoy.com)
 * @version    $Id$
 */
class Holiday extends LdBaseCtrl {
	public function __construct() {
		parent::__construct('Holiday');
	}
	
	public function index() {
		$this->tpl->setFile('holiday/index')
				->assign('vendors', Report::getVendors())
				->display();
	}
	
	public function tbl() {
        $condition = $and = '';
        $params = array();
		if (!empty($_POST['vendorId'])) {
			$condition = 'vendorId = ?';
			$params[] = intval($_POST['vendorId']);
			$and = ' and ';
		}
		if (!empty($_POST['year'])) {
			$condition .= $and.'holiday like ?';
			$params[] = intval($_POST['year']).'%';
		}
		
		
		$_SESSION[USER]['page'] = empty($_GET['id']) ? 1 : intval($_GET['id']);
		$dao = new HolidayDao();
		$pager = pager(array(
				'base' => 'holiday/tbl',
				'cur'  => $_SESSION[USER]['page'],
				'cnt'  => $dao->count($condition, $params)
		));
		if (empty($condition)) {
			$holidays = $dao->hasA('Vendor', 'Vendor.countryShortName')->fetchAll($pager['rows'], $pager['start'], 'vendorId asc, holiday asc');			
		} else {
			$holidays = $dao->hasA('Vendor', 'Vendor.countryShortName')->findAll(array($condition, $params), $pager['rows'], $pager['start'], 'vendorId asc, holiday asc');
		}
		$this->tpl->setFile('holiday/tbl')
		->assign('holidays', $holidays)
		->assign('pager', $pager['html'])
		->display();
	}
	
	function import() {
		if (empty($_FILES)) {
			$this->tpl->setFile('holiday/import')
			->assign('vendors', Report::getVendors())
			->display();
		} else {
			$dao = new HolidayDao();
			$filename = $_FILES['Filedata']['tmp_name'];
			set_time_limit(0);
			ini_set('memory_limit', '1000M');
			Load::helper('excel/PHPExcel');
				
			$cacheMethod = PHPExcel_CachedObjectStorageFactory:: cache_to_phpTemp;
			$cacheSettings = array( ' memoryCacheSize ' => '8MB');
			PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);
				
			$reader = new PHPExcel_Reader_Excel5();
			if (!$reader->canRead($filename)) {
				$reader = new PHPExcel_Reader_Excel2007();
			}
			$reader->setReadDataOnly(true);
			$excel = $reader->load($filename);
			$sheet = $excel->getSheet(0);
				
			$maxRow = $sheet->getHighestRow();

			$vendorId = intval($_POST['vendorId']);
			try {
				$dao->beginTransaction();
				
				for ($i = 1; $i <= $maxRow; $i++) {
					$day = $sheet->getCellByColumnAndRow(0, $i);
					$add = array(
						'vendorId' => $vendorId,
						'holiday' => PHPExcel_Style_NumberFormat::toFormattedString($day->getCalculatedValue(), 'yyyy-mm-dd'),
						'remark' => trim($sheet->getCellByColumnAndRow(1, $i)->getValue()),
					);
					$add['holiday'] = date(DATE_FORMAT, strtotime($add['holiday']));
					list($exist, list($id)) = $dao->existsRow('vendorId = ? and holiday = ?', array($add['vendorId'], $add['holiday']), 'id');
					if ($exist) {
						$dao->update($id, $add);						
					} else {
						$dao->insert($add);
					}
				}
				
				$dao->commit();
				return 1;
			} catch (SqlException $e) {
				$dao->rollback();
				return 0;
			}
			
		}
	}
	
	public function del() {
        $condition = $and = '';
        $params = array();
		if (!empty($_POST['vendorId'])) {
			$condition = 'vendorId = ?';
			$params[] = intval($_POST['vendorId']);
			$and = ' and ';
		}
		if (!empty($_POST['year'])) {
			$condition .= $and.'holiday like ?';
			$params[] = intval($_POST['year']).'%';
		}

		$dao = new HolidayDao();
		try {
			$dao->beginTransaction();
			$dao->deleteWhere($condition, $params);
			$dao->commit();
			return SUCCESS.'|'.url('holiday');
		} catch (SqlException $e) {
			$dao->rollback();
			return ALERT.'|delete holiday failed';
		}
		
	}
	
	function beforeAction($action) {
		if ($action == 'import') return;
		if (!User::logined()) return User::gotoLogin();
		if (!User::can()) redirect('error/accessDenied');
	}
}