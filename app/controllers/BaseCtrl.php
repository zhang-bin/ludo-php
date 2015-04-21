<?php
class BaseCtrl extends \Ludo\Routing\Controller
{
    public function __construct($name)
    {
        parent::__construct($name);
    }

    public function beforeAction($action)
    {
        $this->login();
        $this->illegalRequest();
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
            if ($token == $_SESSION[USER]['token']) {
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
