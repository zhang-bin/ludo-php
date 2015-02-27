<?php
class BaseDao extends \Ludo\Database\Dao {
    public function __construct($name, $connectionName = null) {
        parent::__construct($name, $connectionName);
    }
}