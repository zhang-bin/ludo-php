<?php
/**
 * Ludo BillGo Platform
 *
 * @author     zhangbin <hunter.zhangbin@gmail.com>
 * @copyright  Copyright (c) 2012 Ludo team (http://www.loongjoy.com)
 * @version    $Id$
 */
class Excel extends LdBaseCtrl {
	private static $_excelDir;
	public function __construct() {
		parent::__construct('Excel');
		self::$_excelDir = SITE_ROOT.'/uploads/'.gmdate(DATE_FORMAT).'/';
		if (!is_dir(self::$_excelDir)) mkdir(self::$_excelDir);
	}
	
	public function write($menu, $list) {
		$filename = self::$_excelDir.uniqid(time()).'.csv';
	
		$sep  = "\t";
		$eol  = "\n";
	
		$csv = '';
        $arr = array();
		foreach ($menu as $v) {
			$arr[] = $v;
		}
		$csv .= '"'. implode('"'.$sep.'"', $arr).'"'.$eol;

        $fp = fopen($filename, 'w');
        fwrite($fp, chr(255).chr(254));
        fwrite($fp, mb_convert_encoding($csv, 'UTF-16LE', 'UTF-8'));

		foreach ($list as $v) {
			$arr = array();
			foreach ($menu as $k=>$vv) {
				$arr[] = $v[$k];
			}
			$csv = '"'. implode('"'.$sep.'"', $arr).'"'.$eol;
            fwrite($fp, mb_convert_encoding($csv, 'UTF-16LE', 'UTF-8'));
		}
        fflush($fp);
        fclose($fp);
		return $filename;
	}

    public function writeData($data) {
        $filename = self::$_excelDir.uniqid(time()).'.csv';

        $sep  = "\t";
        $eol  = "\n";

        $csv = '';

        $fp = fopen($filename, 'w');
        fwrite($fp, chr(255).chr(254));
        fwrite($fp, mb_convert_encoding($csv, 'UTF-16LE', 'UTF-8'));

        foreach ($data as $v) {
            $csv = '"'. implode('"'.$sep.'"', $v).'"'.$eol;
            fwrite($fp, mb_convert_encoding($csv, 'UTF-16LE', 'UTF-8'));
        }
        fflush($fp);
        fclose($fp);
        return $filename;
    }
}