<?php

namespace App\Daos;


class LogDao extends BaseDao
{
    public function __construct()
    {
        parent::__construct('Log');
    }
}