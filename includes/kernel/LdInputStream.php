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
| Get all input things into LdInputStream
+-------------------------------------------------------------------------------
*/

//session_start();

function processVariables(& $var, $key) {
    if (!get_magic_quotes_gpc()) {
        if (is_array($var)) {
            foreach ($var as $k => $v)
                processVariables($var[$k], $k);
        } else {
            $var = addslashes($var);
        }
    }
}

class LdInputStream {
    //private variables
    private $inputData = Array ();

    private $processfunc = 'processVariables';
    
    
    function __construct($processfunc = '') {
        if (@ function_exists($processfunc))
            $this->processfunc = $processfunc;
        $in = array_merge($_REQUEST, $_SESSION, $_FILES);
        
        if (!empty($processfunc)) {
            array_walk($in, $this->processFunc);
        }

        $in['_GET'] = & $_GET;
        $in['_POST'] = & $_POST;
        $in['_COOKIE'] = & $_COOKIE;
        $in['_SESSION'] = & $_SESSION;
        $in['_FILES'] = & $_FILES;
        $in['_SERVER'] = & $_SERVER;
        $in['_ENV'] = & $_ENV;

        $this->inputData = & $in;
    }
	
	
    function & getInputData() {
        return $this->inputData;
    }
}
?>