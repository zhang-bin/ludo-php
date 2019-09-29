<?php

namespace App\Controllers;

use Ludo\Routing\Controller;
use Ludo\Support\ServiceProvider;
use Ludo\Log\Logger;

class BaseCtrl extends Controller
{
    /**
     * @var Logger
     */
    protected $log;

    protected $currentPage;

    public function __construct(string $name)
    {
        parent::__construct($name);
        $this->log = ServiceProvider::getMainInstance()->getLogHandler();
        $this->currentPage = empty($_GET['pager']) ? 1 : intval($_GET['pager']);
    }

    /**
     * action操作之前的判断处理
     *
     * @param string $action
     * @return void
     */
    public function beforeAction($action)
    {
        $this->checkLogin();
    }

    public function afterAction($action, $result)
    {

    }

    /**
     * 判断是否管理员
     *
     * @return void
     */
    protected function admin(): void
    {
        if (!$_SESSION[USER]['isAdmin']) {
            redirect('error/accessDenied');
        }
    }

    /**
     * 判断是否已登录
     *
     * @return void
     */
    protected function checkLogin(): void
    {
        if (!$this->logined()) {
            $this->gotoLogin();
        }
    }

    /**
     * 弹出警告框
     *
     * @param string $msg
     * @return array
     */
    protected function alert(string $msg): array
    {
        return [STATUS => ALERT, MSG => $msg];
    }

    /**
     * 成功返回跳转页面
     *
     * @param string $url
     * @return array
     */
    protected function success(string $url): array
    {
        return [STATUS => SUCCESS, URL => $url];
    }

    /**
     * 弹出警告框，并且跳转页面
     *
     * @param string $msg
     * @param string $url
     * @return array
     */
    protected function alert2go(string $msg, string $url): array
    {
        return array(STATUS => ALERT2GO, MSG => $msg, URL => $url);
    }


    /**
     * response的时候增加签名参数
     *
     * @param array $data
     */
    static function addResponseSign(array &$data): void
    {
        $data['rand'] = uniqid().time();
        $data['sign'] = md5($data['rand'].RESPONSE_API_SALT);
    }

    /**
     * 正常返回
     *
     * @param array $data
     * @return array
     */
    protected function response(array $data = []): array
    {
        return ['errCode' => 0, 'data'  => $data];
    }

    /**
     * 错误返回
     *
     * @param int $code
     * @param string $msg
     * @return array
     */
    protected function errResponse(int $code, string $msg): array
    {
        return ['errCode' => $code, 'msg' => $msg];
    }

    /**
     * Decide whether a user has login system.
     *
     * @return bool
     */
    protected function logined(): bool
    {
        return !empty($_SESSION[USER]);
    }

    /**
     * redirect to login page with callback url
     *
     * @param string $callbackUrl
     * @param bool $isOuterUrl
     * @return void
     */
    protected function gotoLogin(string $callbackUrl = '', bool $isOuterUrl = false): void
    {
        if (empty($callbackUrl)) {
            $callbackUrl = currentUrl();
            $isOuterUrl = true;
        }
        $url = $isOuterUrl ? '?callback=' . urlencode($callbackUrl) : '?callback=' . urlencode(url($callbackUrl));
        redirect('user/login' . $url);
    }

    /**
     * Logout system
     */
    protected function logout()
    {
        unset($_SESSION);
        session_destroy();
        redirect();
    }
}
