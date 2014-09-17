<?php
//define('CURL_COOKIE_FILE', 	LD_UPLOAD_PATH.'/cookies/cookie.'.$_SESSION[ADM]['id'].'.txt');
define('CURL_COOKIE_FILE', 	LD_UPLOAD_PATH.'/cookies/cookie.txt');
function curlPost($url, $data, $otherOpt=array()) {
	$ch = curl_init();
	
	$options = array(
		CURLOPT_URL => $url,
		CURLOPT_HEADER => 0,
		CURLOPT_POST => true,
		CURLOPT_POSTFIELDS => $data,
		CURLOPT_RETURNTRANSFER=>1,
		CURLOPT_COOKIEJAR=>CURL_COOKIE_FILE, //保存cookie
	    CURLOPT_COOKIEFILE=>CURL_COOKIE_FILE, //发送cookie 
	);
	
	if (!empty($otherOpt)) {
		foreach ($otherOpt as $key =>$opt) {
			$options[$key] = $opt;
		}
	}
	curl_setopt_array($ch, $options);
	
	$result = curl_exec($ch);
	curl_close($ch);
	return $result;
}
function curlGet($url) {
	$ch = curl_init();
	$cookie = LD_UPLOAD_PATH.'/cookie.txt';
	
	$options = array(
		CURLOPT_URL => $url,
		CURLOPT_HEADER => 0,
		CURLOPT_RETURNTRANSFER=>1,
		CURLOPT_COOKIEJAR=>CURL_COOKIE_FILE, //保存cookie
	    CURLOPT_COOKIEFILE=>CURL_COOKIE_FILE, //发送cookie 
	);
	curl_setopt_array($ch, $options);
	
	$result = curl_exec($ch);
	curl_close($ch);
	return $result;
}
function socketSend($ip, $port, $msg, &$err) {
	/* Create a TCP/IP socket. */
	$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
	if ($socket === false) {
	    $err = "socket_create() failed: reason: " . socket_strerror(socket_last_error()) . "\n";
		return false;
	}
	
	$result = socket_connect($socket, $ip, $port);
	if ($result === false) {
	    $err = "socket_connect() failed.\nReason: ($result) " . socket_strerror(socket_last_error($socket)) . "\n";
		return false;
	}
	
	$in = "reload\n";
	socket_write($socket, $in, strlen($in));
	
	$out = '';
	while ($out .= socket_read($socket, 2048)) {}
	
	socket_close($socket);
	return $out;
}
/**
 * thumbnail the image files
 *
 * @param String $img the full path of image
 */
function thumbnail($img) {
	if (empty($img)) return;
	$path = pathinfo($img);

	$dir = $path['dirname'];
	$basename = $path['basename'];
	$ext = $path['extension'];

	$thumbL = $dir.$basename.'_l.'.$ext;
	$thumbM = $dir.$basename.'_m.'.$ext;
	$thumbS = $dir.$basename.'_s.'.$ext;
	//TODO no effects
	exec("convert -resize 240x180 $img $img");
	exec("convert -resize 75x75 $img $thumbL");
	exec("convert -resize 32x32 $img $thumbM");
	exec("convert -resize 16x16 $img $thumbS");
}

/**
 * Convert encoding from UTF-8 to GBK.
 * This is very useful when you post/get data(including chinese text) through Ajax to the server, 
 * you should use this function to convert the encoding, or you will got messy code and unexpected result will occur.   
 *
 * @param unknown_type $str
 * @return unknown
 */
function utf2gbk($str) {
	return mb_check_encoding($str, 'UTF-8') ? mb_convert_encoding($str, 'gbk', 'utf-8') : $str;
}
function alert2go($msg, $url) {
	return '<script type="text/javascript">alert("'.$msg.'"); window.location.href="'.$url.'";</script>';
}

function logLink($linkTxt, $url) {
	return '<a href="'.$url.'">'.$linkTxt.'</a>';
}

function getEntire($y) {
    if ($y<10) return 10;
    return  ($y[0]+1)*pow(10,strlen($y)-1);
}

function checkUnReedMessage() {
	return LdFactory::dao('message')->count('userId=? and hasSee=0 and isReply=1',$_SESSION[USER]['id']);
}

function fileLink($relFilePath) {
	return $relFilePath ? '<a href="'.SITE_URL.$relFilePath.'" target="_blank">view</a>' : '';
}

function getCurrentTime($time) {
	if (empty($time)) return;
	$offset = getTimezoneOffset();
	return date(TIME_FORMAT, strtotime($time)+$offset);
}

function getCurrentDate($date) {
	if (empty($date)) return;
	$offset = getTimezoneOffset();
	return date(DATE_FORMAT, strtotime($date)+$offset);
}

function getTimezoneOffset() {
	return $_SESSION[USER]['timezoneOffset'] * 3600;
}

function getCurrentTimeByVendor($time) {
	if (empty($time)) return;
	$offset = getTimezoneOffsetByVendor();
	return date(TIME_FORMAT, strtotime($time)+$offset);
}

function getCurrentDateByVendor($date) {
	if (empty($date)) return;
	$offset = getTimezoneOffsetByVendor();
	return date(DATE_FORMAT, strtotime($date)+$offset);
}

function getTimezoneOffsetByVendor() {
	return $_SESSION[USER]['timezoneOffsetVendor'] * 3600;
}

function downloadLink($file_url, $file_name){
	$file_name = urlencode($file_name);
	$file_name = str_replace('+', '%20', $file_name);
	header("Content-Type: application/vnd.ms-excel; charset=UTF-8");
	header("Accept-Ranges:bytes");
	header("Accept-Length:".filesize($file_url));
	getDownloadFileName($file_name);
	header('X-Sendfile:'.$file_url); 
	exit;
}

function getDownloadFileName($name) {
	if (preg_match('/MSIE/', $_SERVER['HTTP_USER_AGENT'])) {
		header("Content-Disposition: attachment; filename=".$name);
	} else if (preg_match('/Firefox/', $_SERVER['HTTP_USER_AGENT'])) {
		header("Content-Disposition: attachment; filename*='utf8'".$name);
	} else {
		header("Content-Disposition: attachment; filename=".urldecode($name));
	}
}

function lastMonth() {
    $thisMonth = strtotime(date('F 1'));
    return date('Y-m', strtotime('-1 month', $thisMonth));
}