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

//function _($str) {
//	echo $str;
//}
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
		return 'go|'.url($innerUrl);die;
	}
}
function redirectOut($outUrl) {
	if (!isAjax()) {
		header('location:'.$outUrl);
		if (DEBUG) LdApplication::debug();die;
	} else {
		return 'go|'.$outUrl;die;
	}	
}
function isAjax() {
	return 	isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
}
function strCut($str,$length = 30,$etc='...'){ 
    
    if ( strlen($str) > $length ){
        for ( $i=0; $i < $length; $i++ ){
            if ( ord($str[$i]) > 128 )
            $i++;
        }
        $str = substr($str, 0, $i).$etc;
    }
    return $str;
}

/** upload file to LD_UPLOADS_PATH, path is leading with yyyy/MM/dd/, and return the path */
function uploadFile($formNode) {
	$file = $_FILES[$formNode];
	if (empty($file) || $file['error'] != 0) return '';
	$destFolder = LD_UPLOAD_PATH.date('/Y/m/d/');
	mkdir_r(LD_UPLOAD_PATH, date('Y/m/d'));
	$destFile = $destFolder.md5(uniqid()).'.'.strtolower(ext($file['name']));
	return move_uploaded_file($file['tmp_name'], $destFile) ?  abs2rel($destFile) : '';
}
/**
 * upload images to LD_UPLOAD_IMG_PATH, path is leading with yyyy/MM/dd/, and return the path
 *
 * @param String $formNode form field name for the image
 * @param String $basename you can specify the destination name of the file
 * @param String $filepath you can specify the destination path of the file 
 * 				 which is relative path to /uploads/img. (no trailing slash)
 * @return the relative path for the dest file or nothing if upload failed.
 */
function uploadImg($formNode, $fileBaseName=null, $fileRelPath=null) {
	$file = $_FILES[$formNode];
	if (empty($file)) 	return '';
	if ($file['error'] != 0) {
		//TODO need a own log system
		//syslog(LOG_ERR, 'upload file '.$file['name'].'failed, error code#'.$file['error']);
		return '';
	}
	
	$ext = strtolower(ext($file['name']));
	if ($ext != 'jpg' && $ext != 'png' && $ext != 'gif') return '';
	
	//work out dest folder
	if (is_null($fileRelPath)) {
		$destFolder = LD_UPLOAD_IMG_PATH.date('/Y/m/d/');
		mkdir_r(LD_UPLOAD_IMG_PATH, date('Y/m/d'));
	} else {
		trim($fileRelPath, '/');
		$destFolder = LD_UPLOAD_IMG_PATH.'/'.$fileRelPath.'/';
		mkdir_r(LD_UPLOAD_IMG_PATH, $fileRelPath);
	}
	
	//if filename is not specified, generate a rand one
	if (is_null($fileBaseName)) $fileBaseName = md5(uniqid());
	
	$destFile = $destFolder."$fileBaseName.$ext";
	
	return move_uploaded_file($file['tmp_name'], $destFile) ?  abs2rel($destFile) : '';
}
/*
function uploadFiles() {
	$destFolder = LD_UPLOADS_PATH.date('/Y/m/d/');
	foreach()
}
*/
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
 * make dir recursively
 *
 * @param String $base the base dir to start
 * @param String $dir the dir path to create
 */
function mkdir_r($base, $dir) {
	$paths = explode('/', $dir);
	$dest = $base;
	foreach ($paths as $path) {
		if (empty($path) && $path != '0') continue;
		$dest .= '/'.$path;
		if (!file_exists($dest))	mkdir($dest);
	}
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
 * parse a map string to array. e.g.
 * $mapstr = a:1,b:2 4, c:3,
 * parseMapstrTArr($mapstr) will get:
 * $arr = array('a'=>1, 'b'=>'2 4', 'c'=>3);
 */
function parseStrToMap($str) {
	$arr = array();
	$pairs = explode(',', $str);
	foreach ($pairs as $pair) {
		$pair = trim($pair);
		if ($pair == '') continue;
		list($key, $value) = explode(':', $pair);
		$arr[rtrim($key)] = ltrim($value);
	}
	return $arr;
}
/**
 * parse a List string to array. e.g.
 * $liststr = a,b,c
 * parseStrToList($liststr) will get:
 * $arr = array('a','b','c');
 */
function parseStrToList($str) {
	$list = explode(',', trim($str, ','));
	return $list;
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
 * draw bit from an int into an array. e.g.
 * bit2arr(7, 3) will get: array(1, 1, 1);
 * bit2arr(7, 4) will get: array(1, 1, 1, 0);
 * bit2arr(8) will get: array(0, 0, 0, 1);
 * bit2arr(20) will get: array(0, 0, 0, 1, 0, 1);
 *
 * @param int $int the int to be drawed.
 * @param int $cnt how much bits to be drawed, default is 32. 
 * @return bits array
 */
function bit2arr($int, $cnt = 32) {
	$arr = array();
//	if (!$cnt) $cnt = floor(log($int, 2) + 1); //bits count = log2(N)+1
	for($i = 0; $i < $cnt; $i++) {
		$arr[$i] = $int >> $i & 1;
	}
	return $arr;	
}

/**
 * Update a config key.
 *
 * @param String $key config key, e.g. DB_HOST
 * @param String $value config value, e.g. localhost
 * @param Array $replaceComment comments needs to be updated which use str_replace, it should be an array
 * 		  array('the key need to be replace', 'the new value')
 */
function updateConf($key, $value, $replaceComment=array()) {
	$key = strtoupper($key);
	$configFile = SITE_ROOT.'/config.inc'.php;
    $config = file_get_contents($configFile);
    
	$patt =	'/(define\('.
				'[\'"]'. $key .'[\'"],\s*'.
				'[\'"]?\b)'.
					'.+'.
				'(\b[\'"]?\);'.
			')/mU';
	preg_match($patt, $config, $matches);
	$config = preg_replace($patt, '${1}'.$value.'${2}', $config, 1);

	if ($replaceComment) $config = str_replace($replaceComment[0], $replaceComment[1], $config);
    file_put_contents($configFile, $config);
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

/**
 * parse domain and return the toppest domain.
 * e.g: toppestDomain(passport.shstorm.com) will return .shstorm.com
 *
 * @param String $domain domain name
 * @param String $leadingDot whether to include leading dot
 * @return String top domain with/without leading with dot.
 */
function toppestDomain($domain, $leadingDot = true) {
	$domain = explode('.', $domain);
	$max = count($domain) - 1;
	$topDomain = $leadingDot ? '.' : '';
	$topDomain .= $domain[$max - 1] . '.' . $domain[$max];		 
	return $topDomain;
}	

/**
 * execute command unblocked. which will redirect the output into a file
 * @deprecated this function is still beta.
 * @param String $cmd
 * @param int $timeout
 * @param String $filename path to the filename
 * @return String result of the command
 */
function execute($cmd, $timeout=0, $filename='') {
	if (!$filename) $filename = md5($cmd);
	$file = LD_UPLOAD_PATH.'/tmp/'.$filename;
	$cmd .= ' > '.$file.' &';
	
	exec($cmd);
	
	if ($timeout) sleep($timeout);

	return file_get_contents($file);
}

function getUploadError($errno) {
    switch($errno) {
        case 0: // OK
            break;
        case 1: // exceded max post size
        case 2: // exceded max file size
            $error = "Upload Failed, the file is too big";
            break;
        case 3: // partial file
            $error = "Upload Failed, only a part of file uploaded";
            break;
        case 4: // no file
            $error = "Upload Failed, no file was uploaded";
            break;
        case 6: // no tmp dir
        case 7: // can't write to tmp
            $error = "Upload Failed, no tmp dir or cannot write to tmp";
            break;
    }
    return $error;
}

/**
 * get an week dates array which is relative to $currTime, the structure is:
 * key is the weekDay(1, 2, 3, 4, 5, 6, 7), value is relative timestamp, e.g.:
 * '1' => 'xxxx' //2005-10-31
 * '2' => 'zzzz' //2005-11-01
 * @param timestamp $currTime  which is the week dates array relative to, default is today(leave blank)
 * @return week dates array
 */
function getWeekDates($currTime = '') {
	$currWeekDay = $currTime ? date('w', $currTime) : date('w');
	$currWeekDay = $currWeekDay ? $currWeekDay : 7;

	for($i = 1; $i <= 7; $i++) {
		if ($i != $currWeekDay) {
			$weekDates[$i] = strToTime(($i - $currWeekDay).' day');
		} else {
			$weekDates[$i] = time();
		}
	}
	
	return $weekDates;
}

//function sendMail($MailList, $MailSubject, $MailContent, $headers) {
//    /*
//    	echo $MailList."<BR>";
//    	echo $MailSubject."<BR>";
//    	echo $MailContent."<BR>";
//    	echo "<HR>";
//    	echo nl2br(HtmlEntities($headers));
//    */
//    @ Mail($MailList, $MailSubject, $MailContent, $headers);
//}

function deltreeDir($dir) {
    $dir = realpath($dir);

    if (!$dir || !is_dir($dir))
        return 0;

    $handle = opendir($dir);

    if ($dir[strlen($dir) - 1] != DIRECTORY_SEPARATOR)
        $dir .= DIRECTORY_SEPARATOR;

    while ($file = readdir($handle)) {
        if ($file != '.' && $file != '..') {
            if (is_dir($dir.$file) && !is_link($dir.$file))
                DeltreeDir($dir.$file);
            else
                unlink($dir.$file);
        }
    }

    closedir($handle);

    rmdir($dir);
}

function getDirectorySize($dir, $transSize = false) {
    $dir = realpath($dir);

    if (!$dir || !is_dir($dir))
        return -1;
    $size = 0;

    if ($dir[strlen($dir) - 1] != DIRECTORY_SEPARATOR)
        $dir .= DIRECTORY_SEPARATOR;

    $handle = opendir($dir);

    if (!$handle)
        return 0;

    while ($file = readdir($handle)) {
        if ($file != '.' && $file != '..') {
            if (is_dir($dir.$file) && !is_link($dir.$file))
                $size += GetDirectorySize($dir.$file);
            else
                $size += filesize($dir.$file);
        }
    }

    closedir($handle);

    if ($transSize)
        return GetSizeStr($size);

    return $size;
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

function GeneratePreviewPicture($FileName, $SmallFile, $S_Width, $S_Height) {
    if (!file_exists($FileName))
        return;

    if (!function_exists('imagecreate')) {
        copy($FileName, $SmallFile);
        return;
    }

    $ImageInfo = GetImageSize($FileName);

    $NewWidth = $Width = $ImageInfo[0];
    $NewHeight = $Height = $ImageInfo[1];

    if ($Width > $S_Width && $Height > $S_Height) {
        $NewWidth = $S_Width;
        $NewHeight = $Height * $NewWidth / $Width;
    } else
        if ($Width > $S_Width) {
            $NewWidth = $S_Width;
            $NewHeight = $Height * $NewWidth / $Width;
        } else
            if ($Height > $S_Height) {
                $NewHeight = $S_Height;
                $NewWidth = $Width * $NewHeight / $Height;
            }

    if (function_exists('imagecreatetruecolor')) {
        $im_d = ImageCreateTrueColor($NewWidth, $NewHeight);
    } else {
        $im_d = ImageCreate($NewWidth, $NewHeigt);
    }

    if (function_exists('ImageCopyReSampled'))
        $ReSampledFunc = 'ImageCopyReSampled';
    else
        $ReSampledFunc = 'ImageCopyResized';

    switch ($ImageInfo[2]) {
        case 1 :
            if (!function_exists('imagegif')) {
                copy($FileName, $SmallFile);

                return;
            }

            $im_s = ImageCreateFromGif($FileName);

            $ReSampledFunc ($im_d, $im_s, 0, 0, 0, 0, $NewWidth, $NewHeight, $Width, $Height);

            ImageGif($im_d, $SmallFile);
            break;

        case 2 :
            if (!function_exists('imagejpeg')) {
                copy($FileName, $SmallFile);

                return;
            }

            $im_s = ImageCreateFromJpeg($FileName);

            $ReSampledFunc ($im_d, $im_s, 0, 0, 0, 0, $NewWidth, $NewHeight, $Width, $Height);

            ImageJpeg($im_d, $SmallFile);
            break;

        case 3 :
            if (!function_exists('imagepng')) {
                copy($FileName, $SmallFile);

                return;
            }

            $im_s = ImageCreateFromPng($FileName);

            $ReSampledFunc ($im_d, $im_s, 0, 0, 0, 0, $NewWidth, $NewHeight, $Width, $Height);

            ImagePng($im_d, $SmallFile);
            break;

        default :
            copy($FileName, $SmallFile);
    }
}

function CopyDirectory($dir_s, $dir_d) {
    $dir_s = realpath($dir_s);

    if (!is_dir($dir_s))
        return;

    if (!is_dir($dir_d))
        mkdir($dir_d);

    if ($dir_s[strlen($dir_s)] != DIRECTORY_SEPARATOR)
        $dir_s .= DIRECTORY_SEPARATOR;
    if ($dir_d[strlen($dir_d)] != DIRECTORY_SEPARATOR)
        $dir_d .= DIRECTORY_SEPARATOR;

    $hDir_s = opendir($dir_s);
    $hDir_d = opendir($dir_d);

    while ($file = readdir($hDir_s)) {
        if ($file != '.' && $file != '..') {
            if (is_dir($dir_s.$file) && !is_link($dir_s.$file)) {
                CopyDirectory($dir_s.$file, $dir_d.$file);
            } else {
                copy($dir_s.$file, $dir_d.$file);
            }
        }
    }

    closedir($hDir_s);
    closedir($hDir_d);
}

function debug($var, $print_r=true) {
	echo '<pre>';
	$print_r ? print_r($var) : var_dump($var);
	echo '</pre>';
}
/**
 * lowercase the 1st letter of string.
 */
if (!function_exists('lcfirst')) {
	function lcfirst($str) {
		$str[0] = strtolower($str[0]);
		return $str;
	}
}

?>