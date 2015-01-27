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
	private $_mdb = null;//主db，用于insert update delete
	private $_sdb = null;//副db，用于select
	private $_tpl = null;
	private $_lang = null;
    private $_kvDb = null;
    private $_queue = null;

    static private $_instance = null;

	private function __construct() {
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
     * get a master DB Handler
     *
     * @return LdDatabase an instance of DBHandler
     */
    function getMDBHandler() {
    	if (empty($this->_mdb)) {
    		$this->_mdb = LdDatabase::factory(DB_HOST_M, DB_USER, DB_PASSWORD, DB_NAME, DB_PORT, DB_TYPE, DB_CONNECT);
    	}
    	return $this->_mdb;
    }

    /**
     * get a slave DB Handler
     *
     * @return LdDatabase an instance of DBHandler
     */
    function getSDBHandler() {
    	if (empty($this->_sdb)) {
    		$this->_sdb = LdDatabase::factory(DB_HOST_S, DB_USER, DB_PASSWORD, DB_NAME, DB_PORT, DB_TYPE, DB_CONNECT);
    	}
    	return $this->_sdb;
    }
    
    /**
     * get language handler
     * @return LdLanguage
     */
    function getLangHandler() {
        return $this->_lang;
    }

    /**
     * get a kv db handler
     *
     * @return null|Redis
     */
    function getKvDBHandler() {
        if (empty($this->_kvDb)) {
            $this->_kvDb = new Redis();
            $this->_kvDb->connect(KV_DB_HOST, KV_DB_PORT);
        }
        return $this->_kvDb;
    }

    /**
     * get a queue handler
     *
     * @return null|Redis
     */
    function getQueueHandler() {
        if (empty($this->_queue)) {
            $this->_queue = new Redis();
            $this->_queue->connect(QUEUE_HOST, QUEUE_PORT);
        }
        return $this->_queue;
    }
}