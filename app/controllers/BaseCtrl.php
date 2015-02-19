<?php
class BaseCtrl extends \Ludo\Routing\Controller{
    public function __construct($name) {
        parent::__construct($name);
    }

    public function beforeAction($action) {
        if (!logined()) {
            return gotoLogin();
        }
    }

    public function afterAction($action, $result) {
        Session::ageFlashData();
    }
}