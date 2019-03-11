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

    /**
     * 正常返回
     *
     * @param array $data
     * @param int $count
     * @return string
     */
    protected function response($data = [], $count = 0) {
        return json_encode(['code'  => 0, 'msg'   => '', 'count' => $count, 'data'  => $data]);
    }

    /**
     * 错误返回
     *
     * @param $code
     * @param $msg
     * @return string
     */
    protected function errResponse($code, $msg) {
        return json_encode(['code' => $code, 'msg' => $msg]);
    }
}
