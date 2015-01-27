<?php
class LdTaskQueue {
    /**
     * @var Redis
     */
    private $_db = null;
    private $_task = array();
    private $_errMsg = null;

    function __construct($queueName) {
        $this->_db = LdKernel::getInstance()->getQueueHandler();
        $this->_db->select(10);
        $this->_task['name'] = $queueName;
        $this->_task['queue'] = array();
    }

    /**
     * add task
     *
     * @param string $url the url of task that you want to execute
     * @param null $data post data
     * @param bool $prior the task execution priority level
     * @return bool
     */
    function addTask($url, $data = null, $prior = false) {
        if (empty($url)) {
            $this->_errMsg = 'Unavailable task';
            return false;
        }

        if (!Validator::url($url)) {
            $this->_errMsg = 'Unavailable task';
            return false;
        }

        $item = array();
        $item['url'] = $url;
        if (!empty($data)) $item['data'] = base64_encode($data);
        if ($prior) $item['prior'] = true;
        $this->_task['queue'][] = $item;
        return true;
    }


    /**
     * add multi task
     *
     * @param array $tasks supported formats like this
     * <code>
     * <?php
     * $tasks = array( array("url" => "www.xxx.com/task/queue",
     *                       "data" => "data"
     *                       "prior" => false
     * ));
     * </code>
     * @return bool
     */
    function addMultiTask($tasks) {
        if (empty($tasks)) {
            $this->_errMsg = 'Unavailable tasks';
            return false;
        }
        foreach ($tasks as $task) {
            if ($this->addTask($task['url'], $task['data'], $task['prior']) === false) return false;
        }
        return true;
    }

    /**
     * push task into task queue
     *
     */
    function push() {
        if (count($this->_task['queue']) > 0) {
            foreach ($this->_task['queue'] as $task) {
                if ($task['prior']) {
                    $this->_db->rPush($this->_task['name'], $task);
                } else {
                    $this->_db->lPush($this->_task['name'], $task);
                }
            }
            $this->_task['queue'] = array();
        }
    }

    /**
     * get current task queue length
     * @return int
     */
    function curLength() {
        return $this->_db->lLen($this->_task['name']);
    }

    /**
     * get error message
     *
     * @return null
     */
    function errMsg() {
        return $this->_errMsg;
    }
}
