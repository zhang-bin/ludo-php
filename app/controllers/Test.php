<?php
class Test extends BaseCtrl {
    public function __construct() {
        parent::__construct('Test');
    }

    public function testTaskQueue() {
        $url = url('test/run');
        $data = array('1' => 'aaa');
        $taskQueue = new TaskQueue('test');
        $taskQueue->addTask($url, $data);
        $taskQueue->push();
    }

    public function run() {
        sleep(1);
        return $_POST;
    }

    public function beforeAction($action) {
        return true;
    }
}