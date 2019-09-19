<?php

namespace App\Daos;


class PermissionDao extends BaseDao
{
    public function __construct()
    {
        parent::__construct('Permission');
    }
}