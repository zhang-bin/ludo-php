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
 * represent the template engine
 */
class LdTemplate {
	/** current template file */
	private $tplFile = '';
	
	/** all data used in template */
	private $assignValues = array();
	
	function __construct() {
		
	}
	
	/**
	 * this is an overloading method, which can have one or two arguments
	 * if one: the arg should be a ASSOC array
	 * if two: the 1st arg should be the $varname, the 2nd arg should be $varValue
	 * assign the key => value pair to template
	 * @param array $varArr	an ASSOC array
	 * @param String $varName variable name
	 * @param String $varValue variable value
	 * @return LdTemplate
	 */
	function assign($varName, $varValue = '') {
        $argNums = func_num_args();
        if ($argNums == 2) {
			$this->assignValues[$varName] = $varValue;
        } else {
        	$this->assignValues = array_merge($this->assignValues, $varName);
        }
        return $this;
	}
        
	/**
	 * assign an ASSOC array to the template
	 * 
	 * @param array $varArr the structure will be:
	 * 		array('user' => $user, 'gender' => $gender, 'skill' => $skill);
	 * @return LdTemplate
	 */
	function assignArr($varArr) {
		$this->assignValues = array_merge($this->assignValues, $varArr);
		return $this;
	}
	
	function display() {
       	$templateFileWithFullPath = TPL_ROOT.'/'.$this->tplFile.tpl;
       	if (!file_exists($templateFileWithFullPath)) {
//       		throw new LdException(sprintf(LG_TEMPLATE_FILE_NOT_FOUND, $this->tplFile), 
//								  "tpl file: [$templateFileWithFullPath] does not exist", 6);
       		throw new LdException(sprintf(LG_TEMPLATE_FILE_NOT_FOUND, $this->tplFile), 
								  sprintf(Ld::$err[6], $templateFileWithFullPath), 6);
       	}
        
		extract($this->assignValues);
		include $templateFileWithFullPath;
	}
	/**
	 * get the template stuff
	 * 
	 * @return String template stuff
	 */
    function fetch() {
       	$templateFileWithFullPath = TPL_ROOT.'/'.$this->tplFile.tpl;
       	if (!file_exists($templateFileWithFullPath)) {
           	throw new LdException(sprintf(LG_TEMPLATE_FILE_NOT_FOUND, $this->tplFile), 
							  	  sprintf(Ld::$err[6], $templateFileWithFullPath), 6);
       	}
        
		extract($this->assignValues);
		ob_start();
		include $templateFileWithFullPath;
		$buffer = ob_get_contents();
		ob_end_clean();
		
		return $buffer;
    }
    	
    function loadFilter() {
    }

    function unloadFilter() {
    }

    function getFile() {
		return $this->tplFile;
    }
	/**
	 * Set template file
	 *
	 * @param String $tplFile relative path to TPL_ROOT. eg. user/login, user/register
	 * @return LdTemplate
	 */
    function setFile($tplFile) {
		$this->tplFile = $tplFile;
		//clear assign values
		$this->assignValues = array();
		return $this;
    }
	
	function setTplPath($tplPath) {
		$this->tplPath = $tplPath;
	}
	
	function setThemeName($themeName) {
		$this->themeName = $themeName;
	}
	
	/**
	 * used for import other template files, like header, footer or any other small block
	 * for example: <br />
	 * import('header') which will import the TPL_ROOT/header.tpl to your current template
	 * import('user/login') which will import TPL_ROOT/user/login.tpl into your current template
	 * 
	 * @param String $path relative path to TPL_ROOT. e.g. import('header'); import('user/login')
	 * @param Mixed $arr params to be transformed to the imported tpl, <br>
	 */
	public static function import($path, Array $arr=null) {
		if ($arr && is_array($arr)) extract($arr);
		
		include(TPL_ROOT.'/'.trim($path).tpl);
	}
}