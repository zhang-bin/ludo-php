<?php
/* 
+-------------------------------------------------------------------------------
| {ProgName}
| =====================================================
| Author: Libok.Zhou <likhorse@gmail.com>
| Home  : http://libk.8800.org
| Copyright (C)2004 - 2005 LdFirm All Rights Reserved.
| License: {LdLicense}
+-------------------------------------------------------------------------------
| 
+-------------------------------------------------------------------------------
*/

/**
 * a utility which should only be used when a id (normally a PK field) can determine a unique record.
 */
class LdRecord {
	/** @var instance of LdTable */
	private $tbl;
	
	/** @var the PK field */
	private $idField;
	
	/** @var the PK value */
	private $id;
	
	/** @var the search fields */
	private $fields;
	
	/** @var Array the result of the record */
	private $privData;
	
	/** @var extra condition contrast with PK condition */
	private $extraCondition;
	
	/**
	 * @param String $table	Table name
	 * @param String $idField PK field
	 * @param String $id PK value
	 * @param String $extraCondition extra condition contrast with PK condition 
	 */
	function __construct($table, $idField = '', $id = '', $extraCondition = '', $fields = '*') {
		$this->tbl = new LdTable($table);

		$this->$extraCondition = $extraCondition ? $extraCondition : false;
		if ($id && $idField) {
			$this->id = $id;
			$this->idField = $idField;
			$this->fields = $fields;
			$this->getRecord();
		}
	}
	
	/**
	 * get the value with the fields name
	 * @param String $key field name
	 */
	function get($key) {
		return $this->privData[$key];
	}
	
	/**
	 * set the filed with the value
	 * @param $key field name
	 * @param $val value for the field
	 */
	function set($key, $val) {
		$this->privData[$key] = $val;
	}
	
	/**
	 * when set() hava done, you should commit to store the change to DB. 
	 */
	function commit() {
		return $this->tbl->Update($this->privData, "$this->idField='$this->id'");
	}
	
	/**
	 * get the record in the form of Array
	 * @return Array the record
	 */
	function &fetchArray() {
		return $this->privData;
	}
	
	/**
	 * get the unique record to an ASSOC array
	 */
	private function getRecord() {
		$attachCondition = $this->attachCondition ? ' and '.$this->attachCondition : '';
		$this->tbl->setFields($this->fields);
		$this->tbl->where("$this->idField='$this->id' $attachCondition");
		$this->privData = $this->tbl->getRow(1);
	}
}
?>