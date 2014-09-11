<?php
/**
 * Ludo BillGo Platform
 *
 * @author     zhangbin <hunter.zhangbin@gmail.com>
 * @copyright  Copyright (c) 2012 Ludo team (http://www.loongjoy.com)
 * @version    $Id$
 */
class Kpi extends LdBaseCtrl {
	const COMPARISON_GREATER = 1;
	const COMPARISON_GREATER_EQUAL = 2;
	const COMPARISON_LESS = 3;
	const COMPARISON_LESS_EQUAL = 4;
	
	const KPI_R_TAT = 'R-TAT(6BD)%';
	const KPI_FTF = 'FTF(30D)%';
	const KPI_RR = 'RR(30D)%';
	const KPI_PPI = 'PPI';
	const KPI_PAL = 'PAL';
	const KPI_CLOSED_SR = 'Total Closed SR';
	const KPI_OOW = 'OOW Ratio';
	const KPI_HW = 'HW Ratio';
    const KPI_PARTS_USED_IW = 'Parts Used IW';
    const KPI_PARTS_USED_OOW = 'Parts Used OOW';
    const KPI_DEFECT_PARTS_RETURNED = 'Defect Parts Returned';
    const KPI_DEFECT_RETURN_RATIO = 'Defect Return Ratio';
	
	public static $month = array('Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec');
	
	public function __construct() {
		parent::__construct('Kpi');
		list($this->_stationId, $this->_vendorId) = Permission::getIdentity();
	}
	
	public function index() {
		$dao = new VendorDao();
		if (empty($this->_vendorId)) {
			$vendors = $dao->fetchAll();
		} else {
			$vendors = $dao->findAll(array('id = ?', $this->_vendorId));
		}
		$this->tpl->setFile('kpi/index')
				->assign('vendors', $vendors)
				->display();	
	}
	
	public function tbl() {
		$vendorId = intval($_POST['vendorId']);
		$year = intval($_POST['year']);
		
		$dao = new KpiDao();
		$kpi = $dao->findAll(array('vendorId = ? and year = ?', array($vendorId, $year)));
		$vendor = LdFactory::dao('vendor')->fetch($vendorId);
		
		$this->_sortKpi($kpi);
		
		$this->tpl->setFile('kpi/tbl')
				->assign('kpi', $kpi)
				->assign('vendor', $vendor)
				->display();
	}	
	
	public function chartIndex() {
		$dao = new VendorDao();
		if (empty($this->_vendorId)) {
			$vendors = $dao->fetchAll();
		} else {
			$vendors = $dao->findAll(array('id = ?', $this->_vendorId));
		}
		$this->tpl->setFile('kpi/chart')
		->assign('vendors', $vendors)
		->display();
		
	}
	
	public function chart() {
		$vendorId = intval($_GET['vendorId']);
		$year = intval($_GET['year']);
		
		$dao = new KpiDao();
		$kpi = $dao->findAll(array('vendorId = ? and year = ?', array($vendorId, $year)));
		
		$tmp = array();
		foreach ($kpi as $v) {
			$data = json_decode($v['data'], true);
			for ($i = 1; $i <= 12; $i++) {
				$month = $year.'-'.str_pad($i, 2, 0, STR_PAD_LEFT);
				$tmp[$month]['month'] = $month;
				$tmp[$month][$v['kpi']] = $data[$i]['value'] * 100;
			}
		}
		
		$kpi = array();
		foreach ($tmp as $v) {
			$kpi[] = $v;
		}
		
		return json_encode($kpi);
	}
	
	public function export() {
		$vendorId = intval($_POST['vendorId']);
		$year = intval($_POST['year']);
		$filename = $this->report($vendorId, $year);
		return SUCCESS.'|'.url('kpi/download/'.base64_encode($filename));
	}
	
	public function download() {
		$name = base64_decode(trim($_GET['id']));
		downloadLink($name, 'KPI Report('.getCurrentDate(gmdate(DATE_FORMAT)).').xls');
	}
	
	/**
	 * 获取excel报表
	 * 
	 * @param int $vendorId
	 * @param int $year
	 * @return string filename
	 */
	public function report($vendorId, $year) {
		$dao = new KpiDao();
		$kpi = $dao->findAll(array('vendorId = ? and year = ?', array($vendorId, $year)));
		$vendor = LdFactory::dao('vendor')->fetch($vendorId);
		
		Load::helper('excel/PHPExcel');
		$phpExcel = new PHPExcel();
		$phpExcel->getDefaultStyle()->getFont()->setName('宋体');
		$phpExcel->getDefaultStyle()->getFont()->setSize(12);
		$phpActExcel = $phpExcel->getActiveSheet();
		
		$x = 0;
		$y = 1;
		$phpActExcel->setCellValueByColumnAndRow($x, $y, LG_KPI);
		$this->_bgColor($phpActExcel, $x, $y, 'D4DFED');
		$x++;
		$phpActExcel->setCellValueByColumnAndRow($x, $y, LG_KPI_TARGET);
		$this->_bgColor($phpActExcel, $x, $y, 'D4DFED');
		$x++;
		$phpActExcel->setCellValueByColumnAndRow($x, $y, LG_KPI_COUNTRY);
		$this->_bgColor($phpActExcel, $x, $y, 'D4DFED');
		$x++;
		$phpActExcel->setCellValueByColumnAndRow($x, $y, LG_KPI_YTM);
		$this->_bgColor($phpActExcel, $x, $y, 'D4DFED');
		$x++;
		foreach (self::$month as $v) {
			$phpActExcel->setCellValueByColumnAndRow($x, $y, $v);
			$this->_bgColor($phpActExcel, $x, $y, '183769');
			$this->_fontColor($phpActExcel, $x, $y, 'FFFFFF');
			$x++;
		}
		
		$x = 0;
		$y++;
		
		$this->_sortKpi($kpi);
		$color = array('green' => '1D7000', 'red' => 'F40000');
		foreach ($kpi as $v) {
			$phpActExcel->setCellValueByColumnAndRow($x, $y, $v['kpi']);
			$x++;
			$phpActExcel->setCellValueByColumnAndRow($x, $y, $v['targetText']);
			$x++;
			$phpActExcel->setCellValueByColumnAndRow($x, $y, $vendor['countryShortName']);
			$x++;
			$phpActExcel->setCellValueByColumnAndRow($x, $y, $v['ytm']);
			$x++;
			
			$data = json_decode($v['data'], true);
			$class = json_decode($v['class'], true);
			for ($i = 1; $i <= 12; $i++) {
				$phpActExcel->setCellValueByColumnAndRow($x, $y, $data[$i]['value']);
				if (!empty($class[$i])) $this->_bgColor($phpActExcel, $x, $y, $color[$class[$i]]);
				$x++;
			}
			$x = 0;
			$y++;
		}
		
		$phpActExcel->setTitle('KPI');
		$objWriter = PHPExcel_IOFactory::createWriter($phpExcel, 'Excel5');
		$dir = LD_UPLOAD_PATH.'/'.gmdate(DATE_FORMAT).'/';
		if (!is_dir($dir)) mkdir($dir);
		$filename = $dir.uniqid(time()).'.xls';
		$objWriter->save($filename);
		return $filename;
	} 
	
	/**
	 * 填充单元格背景颜色
	 * 
	 * @param PHPExcel_Worksheet $excel
	 * @param int $column
	 * @param int $row
	 * @param string $color
	 */
	private function _bgColor(&$excel, $column, $row, $color) {
		$excel->getStyleByColumnAndRow($column, $row)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
		$excel->getStyleByColumnAndRow($column, $row)->getFill()->getStartColor()->setRGB($color);
	}
	
	/**
	 * 填充单元格背景颜色
	 *
	 * @param PHPExcel_Worksheet $excel
	 * @param int $column
	 * @param int $row
	 * @param string $color
	 */
	private function _fontColor(&$excel, $column, $row, $color) {
		$excel->getStyleByColumnAndRow($column, $row)->getFont()->getColor()->setRGB($color);
	}
	
	private function _sortKpi(&$kpi) {
		$tmp = array();
        $sort = array(self::KPI_R_TAT, self::KPI_FTF, self::KPI_RR, self::KPI_PPI, self::KPI_PAL, self::KPI_CLOSED_SR, self::KPI_OOW, self::KPI_HW,
            self::KPI_PARTS_USED_IW, self::KPI_PARTS_USED_OOW, self::KPI_DEFECT_PARTS_RETURNED, self::KPI_DEFECT_RETURN_RATIO);
		foreach ($sort as $key) {
			foreach ($kpi as $v) {
				if ($key == $v['kpi']) {
					$tmp[$key] = $v;
					break;
				}
			}
		}
		$kpi = $tmp;
		
		$comparison = array(
				Kpi::COMPARISON_GREATER => '>',
				Kpi::COMPARISON_GREATER_EQUAL => '>=',
				Kpi::COMPARISON_LESS => '<',
				Kpi::COMPARISON_LESS_EQUAL => '<='
		);
        $ignore = array(self::KPI_CLOSED_SR, self::KPI_HW, self::KPI_OOW, self::KPI_PARTS_USED_IW, self::KPI_PARTS_USED_OOW, self::KPI_DEFECT_PARTS_RETURNED);
        $reserveNumber = array(self::KPI_CLOSED_SR, self::KPI_PARTS_USED_IW, self::KPI_PARTS_USED_OOW, self::KPI_DEFECT_PARTS_RETURNED);
		foreach ($kpi as $k => $v) {
			$needConvertToPercentage = ($v['target'] <= 1 && !in_array($k, $reserveNumber));
            if (!in_array($k, $ignore)) {
                $target = $needConvertToPercentage ? sprintf('%.2f', $v['target'] * 100).'%' : $v['target'];
                $kpi[$k]['targetText'] = $comparison[$v['comparison']].$target;
            }
            $kpi[$k]['ytm'] = $needConvertToPercentage ? sprintf('%.2f', $v['ytm'] * 100).'%' : $v['ytm'];
			$class = array();
			$data = json_decode($v['data'], true);
			if ($v['kpi'] == self::KPI_PAL) continue;//这项指标暂时未定义
			for ($i = 1; $i <= 12; $i++) {
				if (is_null($data[$i]['value'])) continue;
				switch ($v['comparison']) {
					case self::COMPARISON_GREATER:
						$class[$i] = ($data[$i]['value'] > $v['target']) ? 'green' : 'red';
						break;
					case self::COMPARISON_GREATER_EQUAL:
						$class[$i] = ($data[$i]['value'] >= $v['target']) ? 'green' : 'red';
						break;
					case self::COMPARISON_LESS:
						$class[$i] = ($data[$i]['value'] < $v['target']) ? 'green' : 'red';
						break;
					case self::COMPARISON_LESS_EQUAL:
						$class[$i] = ($data[$i]['value'] <= $v['target']) ? 'green' : 'red';
						break;
					default:
						break;
				}
				if ($needConvertToPercentage) $data[$i]['value'] = ($data[$i]['value'] * 100).'%';
			}
				
			$kpi[$k]['data'] = json_encode($data);
			$kpi[$k]['class'] = json_encode($class);
		}
	}
	
	public function beforeAction($action) {
		if (!User::logined()) return User::gotoLogin();
		if (!User::can()) redirect('error/accessDenied');
	}
}