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
| Ld Kernel 
+-------------------------------------------------------------------------------
*/

/**
 * The kernel of the framework which holds all available resource
 */
class LdKernel {
	private $_inStream = null; 
	private $_inputData = array(); 
	private $_outStream = null;
	private $_mdb = null;//主db，用于insert update delete
	private $_sdb = null;//副db，用于select
	private $_tpl = null;
	private $_lang = null;
	private $_mem = null;

    static private $_instance = null;

	private function __construct() {
//		$this->inStream = new LdInputStream();
//		$this->inputData = &$this->inStream->getInputData();
//		
//		$this->outStream = new LdOutputStream();
		$this->_lang = new LdLanguage();
	}
    
    /**
     * get unique instance of kernel
     * @return LdKernel
     */
    static function getInstance() {
        if (self::$_instance == null) {
            self::$_instance = new LdKernel();
        }
        return self::$_instance;
    }
    
    /**
     * get the input stream array which include all input information like:
     * $_GET, $_POST, $COOKIE, $SESSION
     */
    function &getInputData() {
        return $this->_inputData;
    }
    
    function getInputStream() {
        return $this->_inStream;
    }
    
    function getOutputStream() {
        return $this->_outStream;
    }
	
    /**
     * 
     * @return LdTemplate
     */
	function getTplHandler() {
        if ($this->_tpl == null) {
			include_once LD_KERNEL_PATH.'/LdTemplate.php';
	        $this->_tpl = new LdTemplate();

        }
		return $this->_tpl;
	}
    /**
     * get a DBHandler, if DBHandler does't exist, default will init a new one.
     *
     * @param  boolean $initialize whether to initialize DBH
     * @return LdDatabase an instance of DBHandler
     */
    function getDBHandler($initialize = true) {
        if (empty($this->_db) && $initialize) {
			$this->_db = LdDatabase::getInstance(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME, DB_PORT, DB_TYPE, DB_CONNECT);
        }
        
        return $this->_db;
    }
    
    /**
     * get a DBHandler, if DBHandler does't exist, default will init a new one.
     *
     * @param  boolean $initialize whether to initialize DBH
     * @return LdDatabase an instance of DBHandler
     */
    function getMDBHandler($initialize = true) {
    	if (empty($this->_mdb) && $initialize) {
    		$this->_mdb = LdDatabase::factory(DB_HOST_M, DB_USER, DB_PASSWORD, DB_NAME, DB_PORT, DB_TYPE, DB_CONNECT);
    	}
    	return $this->_mdb;
    }
    
    function getSDBHandler($initialize = true) {
    	if (empty($this->_sdb) && $initialize) {
    		$this->_sdb = LdDatabase::factory(DB_HOST_S, DB_USER, DB_PASSWORD, DB_NAME, DB_PORT, DB_TYPE, DB_CONNECT);
    	}
    	return $this->_sdb;
    }
    
    function getMemcacheHandler() {
    	if (!MEMCACHE_ENABLE) return false;
    	if ($this->_mem == null) {
				$this->_mem = new Memcache;
				$servers = explode(',', MEMCACHE_SERVER);
				foreach ($servers as $server) {
					list($host, $port) = explode(':', $server);
					$this->_mem->addServer($host, $port);
				}
    	}
    	return $this->_mem;
    }
    
    /**
     * get language handler
     * @return LdLanguage
     */
    function getLangHandler() {
        return $this->_lang;
    }
}
?>