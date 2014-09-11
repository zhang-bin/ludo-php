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
| LdOutputStream
+-------------------------------------------------------------------------------
*/

/**
 * represent an OutputStream which will handle all output staff
 */
class LdOutputStream {
	/**
	 * @var String output buffer
	 */
    private $buf = '';
    
    /**
     * @var boolean whether to gzip output buffer
     */
    private $gzipOutput = false;

    function __construct($gzipOutput = false) {
        $this->gzipOutput = $gzipOutput;
    }
	
	/**
	 * Whether to gzip output contents
	 * @param boolean $bool Whether to gzip output contents
	 */
    function setGzipOutput($bool) {
        $this->gzipOutput = $bool;
    }
	
	/**
	 * Add string to output buffer
	 * @param String $str
	 */
    function add($str) {
        $this->buf .= $str;
    }
    
	/**
	 * Get all contents in output buffer
	 * @return String all contents in output buffer
	 */
    function &getContent() {
        return $this->buf;
    }
	
	/**
	 * Replace keyword with format:{xxx} in output buffer
	 * @param Array $arr {$key=>$value ...} A list of keywords & values pair,
	 *  keywords needs to be replaced with related values
	 */
    function replaceKeyword($arr) {
        $tmp = Array ();

        foreach ($arr as $k => $v) {
            $tmp['{'.$k.'}'] = $v;
        }

        $this->buf = str_replace(array_keys($tmp), array_values($tmp), $this->buf);
    }
	
	/**
	 * Clear output buffer
	 */
    function clearBuffer() {
        $this->buf = '';
    }
	
	/**
	 * Do output the buffer
	 */
    function flush() {
        if ($this->gzipOutput) {
            ob_start('ob_gzhandler');
            echo $this->buf;
            while (@ ob_end_flush());
        } else {
            echo $this->buf;
        }
        
        exit();
    }
}
?>