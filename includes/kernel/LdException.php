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
| All system exception
+-------------------------------------------------------------------------------
*/

/**
 * separate the real error messge with vivid message 
 * (which is comprehensive for the end user, and safe for the system)
 */
class LdException extends Exception {
    /** comprehensive error message for the end user */
    protected $vividMsg;
    
    function __construct($vividMsg, $errMsg=null, $errNo=0) {
        //parent::__construct($errMsg, $errNo);
		$this->code = $errNo;
		
        $this->vividMsg = $vividMsg;
        $this->message = $errMsg;
    }
    function vividMsg() {
    	return $this->vividMsg;
    }
    function __toString() {
		return 'ERROR #'.$this->code.': '.$this->message;
    }
}
    
class SqlException extends LdException {
    private $_sql;
	private $_params;
    function __construct($vividMsg, $errMsg, $sql, $params=null, $errNo=0) {
        parent::__construct($vividMsg, $errMsg, $errNo);
		
        $this->_sql = $sql;
        $this->_params = $params;
    }
    
    function __toString() {
    	$err = parent::__toString()."\n\n";
	    $err .= 'sql error: '.print_r($this->message, true)."\n";
	    $err .= 'sql clause: '.$this->_sql."\n";
	    $err .= 'sql params:'.print_r($this->_params, true)."\n";
    	return $err;
    }

    function getSql() {
        return $this->_sql;
    }
}
?>