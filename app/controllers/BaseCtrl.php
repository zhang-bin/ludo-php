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

    public function beforeAction($action)
    {
        $this->login();
        if($_SESSION[USER]['first'] == 1) { //首次登录
            return redirect('user/changePassword');
        }
    }

    public function afterAction($action, $result)
    {
        Session::ageFlashData();
    }

    public function illegalRequest() {
        return !csrf_token_validate($_REQUEST['_token']);
    }
    /**
     * 判断是否管理员
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
     * @return string
     */
    protected function login()
    {
        if (!logined()) {
            return gotoLogin();
        }
    }
}
