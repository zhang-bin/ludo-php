<?php
class BaseCtrl extends \Ludo\Routing\Controller
{
    /**
     * @var \Ludo\Log\Logger
     */
    protected $log;

    public function __construct($name)
    {
        parent::__construct($name);
        $this->log = \Ludo\Support\ServiceProvider::getInstance()->getLogHandler();
    }

    /**
     * action操作之前的判断处理
     *
     * @param string $action
     * @return void
     */
    public function beforeAction($action)
    {
        $this->login();

        if($_SESSION[USER]['first'] == 1) { //首次登录
            redirect('user/changePassword');
        }
    }

    public function afterAction($action, $result)
    {
        Session::ageFlashData();
    }

    public function illegalRequest()
    {
        return !csrf_token_validate($_REQUEST['_token']);
    }

    /**
     * 判断是否管理员
     *
     * @return void
     */
    protected function admin()
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
    protected function login()
    {
        if (!logined()) {
            gotoLogin();
        }
    }

    /**
     * 弹出警告框
     *
     * @param $msg
     * @return array
     */
    protected function alert($msg) {
        return array(STATUS => ALERT, MSG => $msg);
    }

    /**
     * 成功返回跳转页面
     *
     * @param $url
     * @return array
     */
    protected function success($url) {
        return array(STATUS => SUCCESS, URL => $url);
    }

    /**
     * 弹出警告框，并且跳转页面
     *
     * @param $msg
     * @param $url
     * @return array
     */
    protected function alert2go($msg, $url) {
        return array(STATUS => ALERT2GO, MSG => $msg, URL => $url);
    }


    /**
     * response的时候增加签名参数
     *
     * @param $data
     */
    static function addResponseSign(&$data) {
        $data['rand'] = uniqid().time();
        $data['sign'] = md5($data['rand'].RESPONSE_API_SALT);
    }
}
