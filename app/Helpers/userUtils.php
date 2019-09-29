<?php

use Ludo\Foundation\Application;
use Ludo\Support\Facades\Config;

/**
 * get an pathInfo url from an innerUrl.
 * e.g. url('blog/add') will get http://SITE_URL/index.php/blog/add
 *
 * @param  string $innerUrl ==pathInfo
 * @return string right url with pathInfo.
 */
function url(string $innerUrl = ''): string
{
    return SITE_URL.'/'.$innerUrl;
}

/**
 * get the absolute path for template file
 * e.g. tpl('user/login') will get /SITE_ROOT/app/templates/THEME/user/login.tpl
 *
 * @param string $tplPath tpl path related to tpl root
 * @return string
 */
function tpl(string $tplPath): string
{
    return TPL_ROOT.'/'.$tplPath.php;
}

/**
 * get image link
 *
 * @param string $filename
 * @return string
 */
function imageUrl(string $filename): string
{
    return LD_PUBLIC_URL.DIRECTORY_SEPARATOR.'img'.DIRECTORY_SEPARATOR.$filename;
}

/**
 * Redirect to an inner site url.
 * Note: you need to using this function with <b>return</b>
 * eg. return redirect('user/login');
 *
 * @param string $innerUrl inner url(controller/action)
 * @return void
 */
function redirect(string $innerUrl = '')
{
    if (Config::get('app.debug')) {
        Application::debug();
    }

    $innerUrl = url($innerUrl);
    if (isAjax()) {
        echo json_encode(array(STATUS => GO, URL => $innerUrl));
    } else {
        header('location:'.$innerUrl);
    }
    die;
}

/**
 * Redirect to outside url
 *
 * @param string $outUrl
 */
function redirectOut(string $outUrl)
{
    if (Config::get('app.debug')) {
        Application::debug();
    }
    if (isAjax()) {
        echo json_encode(array(STATUS => GO, URL => $outUrl));
    } else {
        header('location:'.$outUrl);
    }
    die;
}

/**
 * Decide current request whether an ajax request
 *
 * @return bool
 */
function isAjax(): bool
{
    return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
}

/**
 * return current user's real IP. It can get IP behind Proxy.
 */
function realIp(): string
{
    $keys = ['HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR'];
    foreach ($keys as $key) {
        if (!isset($_SERVER[$key])) {
            continue;
        }

        foreach (explode('.', $_SERVER[$key]) as $address) {
            $address = trim($address);

            if (true === Validator::publicIp($address)) {
                return $address;
            }
        }
    }

    return '0.0.0.0';
}

/**
 * Download excel
 *
 * @param string $filename
 * @param string $downloadName
 */
function downloadExcel(string $filename, string $downloadName)
{
    $downloadName = urlencode($downloadName);
    $downloadName = str_replace('+', '%20', $downloadName);
    header('Content-Type: application/vnd.ms-excel; charset=UTF-8');
    header('Accept-Ranges:bytes');
    header('Accept-Length:' . filesize($filename));

    if (preg_match('/MSIE/', $_SERVER['HTTP_USER_AGENT'])) {
        header('Content-Disposition: attachment; filename=' . $downloadName);
    } elseif (preg_match('/Firefox/', $_SERVER['HTTP_USER_AGENT'])) {
        header("Content-Disposition: attachment; filename*='utf8'".$downloadName);
    } else {
        header('Content-Disposition: attachment; filename=' . urldecode($downloadName));
    }

    if (stristr($_SERVER['SERVER_SOFTWARE'], 'Apache')) {
        header('X-Sendfile:' . $filename);//Apache
    } else {
        $filename = str_replace(SITE_ROOT, '', $filename);
        header('X-Accel-Redirect:'.$filename);//nginx
    }
    die;
}

/**
 * 判断字符串是否json
 *
 * @param string $content 文字
 * @return bool
 */
function isJsonString($content)
{
	if (is_numeric($content)) {
	    return false;
    }

	json_decode($content);
	return json_last_error() == JSON_ERROR_NONE;
}

function generateCsv($menu, $data)
{
	$dir = LD_UPLOAD_TMP_PATH.'/'.date(DATE_FORMAT).'/';
	if (!is_dir($dir)) {
	    mkdir($dir);
    }

	$filename = $dir.uniqid(time()).'.csv';
	$sep  = "\t";
	$eol  = "\n";

	$csv = '';
    $arr = [];
	foreach ($menu as $v) {
		$arr[] = $v;
	}
	$csv .= '"'. implode('"'.$sep.'"', $arr).'"'.$eol;

	$fp = fopen($filename, 'w');
	fwrite($fp, chr(255).chr(254));
	fwrite($fp, mb_convert_encoding($csv, 'UTF-16LE', 'UTF-8'));

	foreach ($data as $v) {
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