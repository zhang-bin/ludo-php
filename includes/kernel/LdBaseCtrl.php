<?php
/*
+-------------------------------------------------------------------------------
| {ProgName}
| =====================================================
| Author: Libok.Zhou <libkhorse@gmail.com>
| Home  : http://libk.8800.org
| Copyright (C)2004 - 2005 LdFirm All Rights Reserved.
| License: {LdLicense}
+-------------------------------------------------------------------------------
|
+-------------------------------------------------------------------------------
*/

/**
 * the ancestor of all Controller class
 */
abstract class LdBaseCtrl {
	/**
	 * @var LdKernel
	 */
	protected $kernel;
    protected $content = '';
    
	/** @var Array   include all input stream data */
	protected $in;
	
	/**
	 * @var LdOutput
	 */
	protected $out;
		
	/**
	 * @var LdTemplate
	 */
	protected $tpl;
    
	/**
	 * @var String current Ctrl name
	 */
	protected $name;
	
	/**
	 * @var Memcache
	 */
	protected $mem;
	
	/**
	 * used when you need to specify the http header information. <br>
	 * e.g.: when you sent gbk data back to ajax request, it should using header('Content-Type: text/html;charset:GBK') to prevent mash code.<br>
	 * another example is using header("Content-Disposition", "attachment;filename=xxxx.zip"); to popup a SaveAS dialog. <br>
	 * when using more than one header comman, you should use array here.
	 *
	 * @var String|Array
	 */
	public $httpHeader = null;
	
	function __construct($name) {
		$this->name = $name;
		$this->kernel = &$GLOBALS['ldKernel'];
		$this->in	  = &$GLOBALS['ldInputData'];
		$this->out 	  = &$GLOBALS['ldOutput'];
		$this->tpl 	  = $this->kernel->getTplHandler();
		$this->mem 	  = $this->kernel->getMemcacheHandler();
		$this->httpHeader = 'Content-Type:text/html;charset='.PROGRAM_CHARSET;
	}
	function getCurrentCtrlName() {
		return $this->name;
	}
	
	function resetGet() {
		$get = $_GET;
		unset($get['pager']);
		$params = http_build_query($get);
		if (!empty($params)) $params .= '&';
	
		return '?'.$params.'pager=';
	}

    function beforeAction($action) {
        if (!User::logined()) {
            return User::gotoLogin();
        }
        if (!User::can($action)) {
            redirect('error/accessDenied');
        }
    }
	function afterAction($action, $result) {}
}
?>