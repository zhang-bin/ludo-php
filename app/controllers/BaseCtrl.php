<?php
class BaseCtrl extends \Ludo\Routing\Controller
{
    /**
     * @var \Monolog\Logger
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
//        $this->illegalRequest();
    }

    public function afterAction($action, $result)
    {
        Session::ageFlashData();
    }

    /**
     * 判断是否合法请求
     */
    protected function illegalRequest()
    {
        //非法请求
        if (isset($_REQUEST['_token']) && !empty($_REQUEST['_token'])) {
            $token = trim($_REQUEST['_token']);
            if ($token != $_SESSION[USER]['token']) {
                die;
            }
        }
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
