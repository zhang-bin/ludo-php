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
| system config utility
+-------------------------------------------------------------------------------
*/

/**
 * cofig utility which is used to handle all operation of config
 * @author libok.Zhou <libkhorse@gmail.com>
 */
class LdConfig {
	/** @var an array include all config data */
	private $config = array();
	private $configFile;

	function __construct($configFile) {
		if (!file_exists($configFile)) {
			throw new LdException(LG_CONFIG_FILE_NOT_EXIST, "Config File:'$configFile' doesn't exist.");
		}

		$this->configFile = $configFile;
	}
	
	/**
	 * get config value by key
	 * 
	 * @param $key the config key
	 * @return cofig value associate with the key
	 */
	function get($key) {
		return $this->config[$key];
	}
	
	/**
	 * set the value for one config item
	 * 
	 * @param $key config key
	 * @param $value config value
	 */
	function set($key, $value) {
		return $this->config[$key] = $value;
	}
	
	/**
	 * commit all the changes.
	 */
	function commit() {
		$fp = fopen($this->configFile, 'wb');
		
		if (!$fp) 	return false;
	
		fputs($fp, '<?php'."\n");
		
		foreach ($this->config as $k => $v) {
			if (is_string($v))
			$v = "'$v'";
			
			if (!$v)
			$v = "''";
			
			fputs($fp, "\t\$conf['$k']					= $v;\n");
		}
		
		fputs($fp, '?>'."\n");
		
		fclose($fp);
		
		return true;
	}

	function &fetchDataToArray() {
		return $this->config;
	}

}
?>