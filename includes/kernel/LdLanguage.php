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
| language utilities
+-------------------------------------------------------------------------------
*/

/** language utilities */
class LdLanguage {
    private $_langDir;
    private $_language;
    private $charset = '';
	private $languageDesc = '';
	
    function __construct() {
    	if (isset($_COOKIE['lang'])) {
    		$language = $_COOKIE['lang'];
    	} else {
			$language = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
    	}
    	
		//if user browser is mozilla based, the HTTP_ACCEPT_LANGUAGE will like this:zh-cn,zh;q=0.5
		if ( ($pos=strpos($language, ',')) !== false ) {
			$language = substr($language, 0, $pos);
		}
		
		$language = strtolower($language ? $language : DEFAULT_LANGUAGE);
    	$language = 'en-us';//屏蔽中文版
		
		$langDir = LD_LANGUAGE_PATH.DIRECTORY_SEPARATOR.$language.DIRECTORY_SEPARATOR;
		$defaultLangDir = LD_LANGUAGE_PATH.DIRECTORY_SEPARATOR.DEFAULT_LANGUAGE.DIRECTORY_SEPARATOR;
		
		if (file_exists($langDir)) {
			$this->_langDir = $langDir;
		} else if (file_exists($defaultLangDir)) {
			$this->_langDir = $defaultLangDir;
		} else {
			throw new LdException("Language file for [$language] does not exist!", 
								  "Language file [$langDir] does not exist!");
		}
		include_once $this->_langDir.'base.lang.php';
    }
	
	function getLanguage() {
		return $this->_language;
	}
	
	function getCharset() {
		return $this->charset;
	}
	
	function getLanguageDesc() {
		return $this->languageDesc;
	}
	function setLanguage($name) {
		$filename = $this->_langDir.lcfirst($name).'.lang.php';
		if (file_exists($filename)) include_once $filename;
	}
}