<?php

namespace App\Models;

use App\Daos\LogDao;
use Ludo\Support\Facades\Context;

class LogModel
{

    public static function log($name, $parameter = [])
    {
        $data = array(
            'userId' => $_SESSION[USER]['id'] ? $_SESSION[USER]['id'] : 0,
            'uname' => $_SESSION[USER]['uname'],
            'createTime' => date(TIME_FORMAT),
            'name' => $name,
            'ip' => realIp(),
            'ctrl' => Context::get('current-controller'),
            'act' => Context::get('current-action'),
            'url' => currentUrl(),
            'httpReferer' => $_SERVER['HTTP_REFERER'],
            'userAgent' => $_SERVER['HTTP_USER_AGENT'],
            'post' => json_encode($_POST),
            'get' => json_encode($_GET),
            'session' => json_encode($_SESSION),
            'cookie' => json_encode($_COOKIE),
            'success' => 1
        );
        if (realIp() != $_SERVER['REMOTE_ADDR']) {
            $cip = getenv('HTTP_CLIENT_IP') ? 'HTTP_CLIENT_IP:' . getenv('HTTP_CLIENT_IP') : '';
            $xip = getenv('HTTP_X_FORWARDED_FOR') ? ', HTTP_X_FORWARDED_FOR:' . getenv('HTTP_X_FORWARDED_FOR') : '';
            $rip = getenv('REMOTE_ADDR') ? ', getenv(REMOTE_ADDR):' . getenv('REMOTE_ADDR') : '';
            $sRip = $_SERVER['REMOTE_ADDR'] ? ', SERVER[REMOTE_ADDR]' . $_SERVER['REMOTE_ADDR'] : '';

            $data['proxyIp'] = $cip . $xip . $rip . $sRip;
        }

        if (!empty($parameter)) {
            $data = array_merge($data, $parameter);
        }

        (new LogDao())->add($data);
    }
}