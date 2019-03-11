<?php
class Index extends BaseCtrl
{
    public function __construct()
    {
		parent::__construct('Index');
	}

    public function index()
    {
		$this->tpl->setFile('main')->display();
	}


	public function home() {
        $this->tpl->setFile('index/home')->display();
    }
}