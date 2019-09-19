<?php

namespace App\Helpers;


class Load
{
	public static function conf(string $name)
    {
		return include LD_CONF_PATH.'/'.ucfirst($name).'.conf'.php;
	}
}
