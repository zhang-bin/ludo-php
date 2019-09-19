<?php

namespace App\Daos;

class UserDao extends BaseDao
{
    public function __construct()
    {
		parent::__construct('User');
	}
}