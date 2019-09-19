<?php

namespace App\Daos;

use \Ludo\Database\Dao;

class BaseDao extends Dao
{
    public function __construct($name, $connectionName = null)
    {
        parent::__construct($name, $connectionName);
    }
}