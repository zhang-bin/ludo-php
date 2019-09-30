<?php

namespace App\Controllers;

class Index extends BaseCtrl
{
    public function __construct()
    {
        parent::__construct('Index');
    }

    public function index()
    {
        $this->tpl->setFile('index/index')->display();
    }
}