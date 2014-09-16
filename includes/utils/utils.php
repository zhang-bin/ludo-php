<?php
/** 
+-------------------------------------------------------------------------------
| {ProgName}
| =====================================================
| Author: Libok.Zhou <libkhorse@gmail.com>
| Home  : http://libk.8800.org
| Copyright (C)2004 - 2005 LdFirm All Rights Reserved.
| License: {LdLicense}
+-------------------------------------------------------------------------------
| include all required files for application
+-------------------------------------------------------------------------------
*/

/**
 * get an pathInfo url from an innerUrl.
 * e.g. url('blog/add') will get http://SITE_URL/index.php/blog/add
 * 
 * @param  String $innerUrl ==pathInfo
 * @return String right url with pathInfo.
 */
function url($innerUrl='') {
	return USING_MOD_REWRITE ? SITE_URL.'/'.$innerUrl : LD_PORTAL_URL.'/'.$innerUrl;
}

/**
 * aka. Root Url, get the root url from an inner url(based on SITE_URL).
 * e.g. rurl('img/util.js') will get http://SITE_URL/img/util.js
 *
 * @param String $innerUrl innerUrl which is based from SITE_URL
 * @return String root url
 */
function rurl($innerUrl) {
    return SITE_URL.'/'.$innerUrl;
}

/**
 * aka. Theme URL, get the url for theme file
 * e.g. turl('img/style.css') will get http://SITE_URL/app/templates/THEME/img/style.css
 * 
 * @param String $innerFile innerUrl which is based from current theme dir
 * @return String theme url
 */
function turl($innerFile) {
    return THEME_URL .'/'. $innerFile;
}

/**
 * aka. Theme IMG, get the url for theme file
 * e.g. timg('style.css') will get http://SITE_URL/app/templates/THEME/img/style.css
 * 
 * @param String $innerFile innerUrl which is based from current theme dir
 * @return String theme img url
 */
function timg($innerFile) {
    return turl('img/'. $innerFile);
}

/**
 * get the absolute path for template file
 * e.g. tpl('user/login') will get /SITE_ROOT/app/templates/THEME/user/login.tpl
 * 
 * @param String $tplPath tpl path related to tpl root
 */
function tpl($tplPath) {
	return TPL_ROOT.'/'.$tplPath.tpl;
}
/**
 * redirect to an pathInfo url.
 * Note: you need to using this function with <b>return</b>
 * eg. return redirect('user/login');
 *
 * @param String $innerUrl ==pathInfo
 */
function redirect($innerUrl='') {
	if (!isAjax()) {
		header('location:'.url($innerUrl));
		if (DEBUG) LdApplication::debug();die;
	} else {
		return array(STATUS => GO, URL => url($innerUrl));
        die;
	}
}
function redirectOut($outUrl) {
	if (!isAjax()) {
		header('location:'.$outUrl);
		if (DEBUG) LdApplication::debug();die;
	} else {
        return array(STATUS => GO, URL => $outUrl);
        die;
	}
}
function isAjax() {
	return 	isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
}
/**
 * get the extension for a file
 *
 * @param String $fileName
 * @return extension of a file. like: jpg or PNG or txt or php
 */
function ext($fileName) {
    return substr(strrchr($fileName, '.'), 1);
}
/**
 * convert absolute location (eg. /usr/local/www/blackdog/uploads/1.html) 
 * to relative path (which is relative to the site root,eg. /uploads/1.html)
*/
function abs2rel($path) {
	return str_replace(SITE_ROOT, '', $path);
}
/** 
 * convert relative path (which is relative to the site root,eg. /uploads/1.html)
 * to absolute location (eg. /usr/local/www/blackdog/uploads/1.html) 
*/
function rel2abs($path) {
	if ($path[0] != '/') $path = '/'.$path;
	return SITE_ROOT.$path;
}

/**
 * ceil the units of a integer which is bigger than units.
 *
 * @param int $digit
 * @return int
 */
function ceil10($digit) {
	$str = strval(ceil($digit));
	$len = strlen($str);
	if ($str[$len-1] != 0) {
		$str[$len-1] = 0;
		$str[$len-2] = $str[$len-2] + 1;
	}
	return intval($str);	
}

/**
 * return the current page url, including http protocol, domain, port, and url, and query string.
 * e.g.: http://test.com/index.php?libk=yes 
 *
 * @return string current page url
 */
function currUrl() {
	$url = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on' ? 'https://'.$_SERVER['SERVER_NAME'] : 'http://'.$_SERVER['SERVER_NAME'];
	
	if ($_SERVER['SERVER_PORT'] != '80')	$url .= ':'.$_SERVER["SERVER_PORT"]; //add port
	
	return $url.$_SERVER["REQUEST_URI"];
}
/**
 * get a pager html render
 *
 * @param Array $p 
 * array(
 * 		'base'  => 'base url, like: product/list', 
 * 		'cnt' => 'total items count',
 * 		'cur'   => 'current page id',
 * 		'size' => 'Optional, item count per page',
 * 		'span' => 'Optional, gap count between pager button',
 * )
 * 
 * @return Array {
 * 		'start'=>'the start offset in queryLimit',
 * 		'rows'=>'rows to fetch in queryLimit', 
 * 		'html'=>'page html render, e.g. 1  3 4 5 6  8'
 * }
 */
function pager(array $p) {
	//==parse page variables
	if (empty($p['size'])) $p['size'] = PAGE_SIZE;
	if (empty($p['span'])) $p['span'] = PAGE_SPAN;
	
	//==if $p['base'] is not trailing with / or = (like user/list/ or user/list/?p=), 
	//add / to the end of base. eg. p[base] = user/list to user/list/. 
	$pBaseLastChar = substr($p['base'], -1);
	if ($pBaseLastChar != '/' && $pBaseLastChar != '=') $p['base'] .= '/';
	
	if ($p['cnt'] <= 0) {
		return array('start'=>0, 'rows'=>0, 'html'=>''); 
	}
	
	if (($p['cnt'] % $p['size']) == 0) {
		$p['total'] = $p['cnt'] / $p['size'];
	} else {
		$p['total'] = floor($p['cnt'] / $p['size']) + 1;
	}
	//if only have one page don't show the pager
	if ($p['total'] == 1) return array('start'=>0, 'rows'=>0, 'html'=>'');
	
	if (isset($p['cur'])) {
		$p['cur'] = intval($p['cur']);
	} else {
		$p['cur'] = 1;
	}
	if ($p['cur'] < 1) {
		$p['cur'] = 1;
	}
	if ($p['cur'] > $p['total']) {
		$p['cur'] = $p['total'];
	}

	if ($p['total'] <= $p['span']+1) {
		$p['start'] = 1;
		$p['end'] = $p['total']; 
	} else {
		if ($p['cur'] < $p['span']+1) {
			$p['start'] = 1;
			$p['end'] = $p['start'] + $p['span'];
		} else {
			$p['start'] = $p['cur'] - $p['span'] + 1;
			if ($p['start'] > $p['total']-$p['span']) $p['start'] = $p['total'] - $p['span'];
			$p['end'] = $p['start'] + $p['span'];
		}
	}
	if ($p['start'] < 1) $p['start'] = 1;
	if ($p['end'] > $p['total']) $p['end'] = $p['total'];
	

	$p['offset'] = ($p['cur'] - 1) * $p['size'];
	 

	//==render with html
	$html = '';
	if ($p['start'] != 1) {
		$html .='<a href="'. url($p['base'].'1') .'" class="p">1</a>';
		if ($p['start'] - 1 > 1) $html .='&bull;&bull;';
	}
	for ($i = $p['start']; $i <= $p['end']; $i++) {
		if ($p['cur'] == $i) {
			$html .='<strong class="p_cur">' . $i . '</strong>';
		} else {
			$html .='<a href="'. url($p['base'].$i) .'" class="p">' . $i . '</a>';
		}
	}
	if ($p['end'] != $p['total']) {
		if ($p['total'] - $p['end'] > 1) $html .='&bull;&bull;';
		$html .= '<a href="'. url($p['base'].$p['total']) .'" class="p">' . $p['total'] . '</a>';
	}
	$html .= '<strong class="p_info">' . $p['cnt'] . '&nbsp'.'total items | ' . $p['size'] .'&nbsp'.'items each page</strong>';

	return array('start'=>$p['offset'], 'rows'=>$p['size'], 'html'=>$html, '');
}

/**
 * return the right new line of the web server:
 * Unix: \n
 * Win: \r\n
 * Mac: \r
 *
 */
function nl() {
	return strtoupper(substr(PHP_OS, 0, 3)) === 'WIN' ? "\r\n" : "\n";
}

/**
 * return current user's real IP. It can get IP behind Proxy.
 */
function realIp() {
	static $realIp = '';
	
	if (!$realIp) {
		$cip = getenv('HTTP_CLIENT_IP');
		$xip = getenv('HTTP_X_FORWARDED_FOR');
		$rip = getenv('REMOTE_ADDR');
		$srip = $_SERVER['REMOTE_ADDR'];
		if($cip && strcasecmp($cip, 'unknown')) {
			$realIp = $cip;
		} elseif($xip && strcasecmp($xip, 'unknown')) {
			$realIp = $xip;
		} elseif($rip && strcasecmp($rip, 'unknown')) {
			$realIp = $rip;
		} elseif($srip && strcasecmp($srip, 'unknown')) {
			$realIp = $srip;
		}
		$match = array();
		preg_match('/[\d\.]{7,15}/', $realIp, $match);
		$realIp = $match[0] ? $match[0] : '0.0.0.0';
	}
	return $realIp;
}

function refineSize($size, $fix = 2) {
    if ($size < 1024)	return round($size, $fix).' B'; //<1K
    elseif ($size < 1048576) return round($size / 1024, $fix).' KB'; //<1M
    elseif ($size < 1073741824)	return round($size / 1048576, $fix).' MB'; //<1G
    else return round($size / 1073741824, $fix).' GB';
}

function addSuffix($FileName, $Suffix) {
    $ext = strrchr($FileName, '.');

    if (!$ext)
        return $FileName.$Suffix;

    return substr($FileName, 0, strpos($FileName, '.')).$Suffix.$ext;
}

function debug($var, $print_r=true) {
	echo '<pre>';
	$print_r ? print_r($var) : var_dump($var);
	echo '</pre>';
}