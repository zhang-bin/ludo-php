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
 | Table operator class
 +-------------------------------------------------------------------------------
 */
require LD_KERNEL_PATH.'/LdDatabase.php';
require LD_KERNEL_PATH.'/LdTable.php';


abstract class LdBaseDao {
	/**
	 * Handler of LdDatabase
	 *
	 * @var LdDatabase
	 */
	protected $mDbh = null;

	/**
	 * Handler of LdDatabase
	 *
	 * @var LdDatabase
	 */
	protected $sDbh = null;

	/**
	 * Instance of LdTable
	 *
	 * @var LdTable
	 */
	protected $mTbl = null;

	/**
	 * Instance of LdTable
	 *
	 * @var LdTable
	 */
	protected $sTbl = null;

	protected $tblName = null;

	function __construct($tblName, $dbh = null) {
		$this->tblName = $tblName;
		$this->mDbh = $dbh ? $dbh : LdKernel::getInstance()->getMDBHandler();
		$this->sDbh = $dbh ? $dbh : LdKernel::getInstance()->getSDBHandler();
		$this->mTbl = new LdTable($this->mDbh, $tblName);
		$this->sTbl = new LdTable($this->sDbh, $tblName);
	}

	/**
	 * insert data into DB
	 *
	 * @param Array $arr array('field'=>value, 'field2'=>value2);
	 * @return int Last insert id if insert successful, else SqlException will be throw
	 */
	function add($arr) {
		return $this->mTbl->insert($arr);
	}
	/**
	 * identical to LdBaseDao::add($arr);
	 *
	 * @param Array $arr array('field'=>value, 'field2'=>value2);
	 * @return int Last insert id if insert successful, else SqlException will be throw
	 */
	function insert($arr) {
		return $this->add($arr);
	}

	/**
	 * used for batch insert lots data into the table
	 *
	 * @param Array $arr 2D array, 
	 * 	assoc array: 			array(array('field'=>value, 'field2'=>value2), array('field'=>value, 'field2'=>value2));
	 * 	or just indexed array:	array(array(value1, value2), array(value1, value2)); //if use indexedNames, the 2nd argument "$fieldNames" must be passed.
	 * @param Array|String $fieldNames [Optional] only needed in indexed Data. field names for batch insert
	 * @param bool $ignore
	 * @return true if insert successful, else SqlException will be throw
	 */
	function batchInsert($arr, $fieldNames = array(), $ignore = false) {
		if (empty($arr)) return false;
		
		$keys = '(';
		if (!empty($fieldNames)) { 
			if (is_array($fieldNames)) {
				$comma = '';
				foreach ($fieldNames as $field) {
					$keys .= $comma.$this->mDbh->quoteIdentifier($field);
					$comma = ',';
				}
			} else {
				$keys = $this->mDbh->quoteIdentifier($fieldNames);
			}
		} else {
			$fields = array_keys($arr[0]);
			$comma = '';
			foreach ($fields as $field) {
				$keys .= $comma.$this->mDbh->quoteIdentifier($field);
				$comma = ',';
			}
		}
		$keys .= ')';
			
		$sql = 'INSERT';
		if ($ignore) $sql .= ' IGNORE ';
		$sql .= ' INTO '.$this->mDbh->quoteIdentifier($this->tblName)." {$keys} VALUES ";
		
		$comma = '';
		$params = array();
		foreach ($arr as $a) {
			$sql .= $comma.'(';
			$comma2 = '';
			foreach($a as $v) {
				$sql .= $comma2.'?';
				$params[] = $v;
				$comma2 = ',';
			}
			$sql .= ')';
			$comma = ',';
		}
		return $this->mTbl->exec($sql, $params);
	}
	
	/**
	 * update fields of object with id=$id
	 *
	 * @param Int $id
	 * @param Array $arr
	 * @return affected
	 */
	function update($id, $arr) {
		return $this->updateWhere($arr, 'id = ?', array($id));
	}

	/**
	 * update fields of object with some conditions
	 *
	 * @param Array $newData
	 * @param String $condition
	 * @param mixed
	 * @return affected
	 */
	function updateWhere($newData, $condition, $params = array()) {
		return $this->mTbl->update($newData, array($condition, $params));
	}

	/**
	 * delete record with id=$id
	 *
	 * @param $id
	 * @return affected
	 */
	function delete($id) {
		return $this->deleteWhere('id = ?', $id);
	}

	/**
	 * delete record with condition
	 *
	 * @param string $condition
	 * @param null|array $params
	 * @return affected
	 */
	function deleteWhere($condition, $params = null) {
		return $this->mTbl->delete($condition, $params);
	}

	/**
	 * get one row from table by ID
	 *
	 * @param $id
	 * @param String $fields fields needs to be fetched, comma separated
	 * @param int $fetchMode
	 * @return Array key is field name and value is field value.
	 */
	function fetch($id, $fields = '', $fetchMode = PDO::FETCH_ASSOC) {
		if (!empty($fields)) $this->sTbl->setField($fields);
		$this->sTbl->where($this->tblName.'.id = ?', $id);
		return $this->sTbl->fetch(NULL, $fetchMode);
	}

	/**
	 * get one row from table by condition
	 *
	 * @param string $condition
	 * @param array $params
	 * @param string $fields fields needs to be fetched, comma separated
	 * @param int $fetchMode
	 * @return array|bool
	 */
	function find($condition, $params, $fields = '', $fetchMode = PDO::FETCH_ASSOC) {
		if (!empty($fields)) $this->sTbl->setField($fields);
		return $this->sTbl->where($condition, $params)->fetch(NULL, $fetchMode);
	}

	/**
	 * get one column string from table by condition
	 *
	 * @param string $condition
	 * @param string|array $params
	 * @param string $column
	 * @return String
	 */
	function findColumn($condition, $params, $column) {
		if (!empty($column)) $this->sTbl->setField($column);
		return $this->sTbl->where($condition, $params)->fetchColumn();
	}

	/**
	 * get one column string from table by id
	 *
	 * @param int $id
	 * @param string $column
	 * @return String
	 */
	function fetchColumn($id, $column) {
		if (!empty($column)) $this->sTbl->setField($column);
		return $this->sTbl->where($this->tblName.'.id = ?', array($id))->fetchColumn();
	}

	/**
	 * get record from table
	 *
	 * @param int $rows
	 * @param int $start
	 * @param string $order
	 * @param string $fields
	 * @param int $fetchMode
	 * @return array
	 */
	function fetchAll($rows = 0, $start = 0, $order = '', $fields = '*', $fetchMode = PDO::FETCH_ASSOC) {
		return $this->sTbl->field($fields)->limit($rows, $start)->orderby($order)->fetchAll(NULL, $fetchMode);
	}

	/**
	 * get one column list from table
	 *
	 * @param string $fields
	 * @param int $rows
	 * @param int $start
	 * @param string $order
	 * @return array
	 */
	function fetchAllUnique($fields = '*', $rows = 0, $start = 0, $order = '') {
		return $this->sTbl->field($fields)->limit($rows, $start)->orderby($order)->fetchAllUnique();
	}

	/**
	 * get record from table by condition
	 *
	 * @param string $condition
	 * @param int $rows
	 * @param int $start
	 * @param string $order
	 * @param string $fields
	 * @param int $fetchMode
	 * @return array
	 */
	function findAll($condition = '', $rows = 0, $start = 0, $order='', $fields = '*', $fetchMode = PDO::FETCH_ASSOC) {
		if (is_array($condition)) {
			$where = $condition[0];
			$params = $condition[1];
		} else {
			$where = $condition;
			$params = null;
		}

		return $this->sTbl->field($fields)->where($where, $params)->orderby($order)->limit($rows, $start)->fetchAll(NULL, $fetchMode);
	}

	/**
	 * get one column list from table by condition
	 *
	 * @param string $condition
	 * @param string $fields
	 * @param int $rows
	 * @param int $start
	 * @param string $order
	 * @return array
	 */
	function findAllUnique($condition = '', $fields = '', $rows = 0, $start = 0, $order = '') {
		if (is_array($condition)) {
			$where = $condition[0];
			$params = $condition[1];
		} else {
			$where = $condition;
			$params = null;
		}

		return $this->sTbl->field($fields)->where($where, $params)->orderby($order)->limit($rows, $start)->fetchAllUnique();
	}

	/**
	 * get key=>value formatted result from table
	 *
	 * @param string $condition
	 * @param string $fields
	 * @param int $rows
	 * @param int $start
	 * @param string $order
	 * @return array
	 */
	function findAllKvPair($condition = '', $fields = '', $rows = 0, $start = 0, $order='') {
		if (is_array($condition)) {
			$where = $condition[0];
			$params = $condition[1];
		} else {
			$where = $condition;
			$params = null;
		}
	
		return $this->sTbl->field($fields)->where($where, $params)->orderby($order)->limit($rows, $start)->fetchAllKvPair();
	}

	/**
	 * count records
	 *
	 * @param string $condition
	 * @param null $params
	 * @param bool $distinct
	 * @return int
	 */
	function count($condition = '', $params = null, $distinct = false) {
		if (!empty($condition)) {
			$this->sTbl->where($condition, $params);
		}
		return $this->sTbl->recordsCount($distinct);
	}

	/**
	 * Check if the records exists according to the $condition.
	 *
	 * @param String $condition
	 * @param mixed $params
	 * @return boolean
	 */
	function exists($condition = '', $params = null) {
		if (!is_array($params)) $params = array($params);
		$cnt = $this->sTbl->setField('count(*)')->where($condition, $params)->fetchColumn();
		return $cnt > 0 ? true : false;
	}

	/**
	 * Check if the records exists according to the $condition. if exists, return the row data.
	 * result[0] is a bool value represent exists or not.
	 * If exists, result[1] will store the "1st db row" result
	 * 
	 * @param String $condition
	 * @param string|array $params
	 * @param string $fields
	 * @return Array list(exists, row) = Array(0=>true/false, 1=>rowArray/false)
	 */
	function existsRow($condition='', $params = null, $fields = null) {
		if (!empty($fields)) $this->sTbl->setField($fields);
		$row = $this->sTbl->where($condition, $params)->fetch(NULL, PDO::FETCH_BOTH);
		$exists = empty($row) ? false : true;
		return array($exists, $row);
	}
	
	/**
	 * return the max Id from current table
	 * 
	 * @return int the max id
	 */
	function maxId() {
		return $this->sTbl->setField('id')->orderby('id DESC')->fetchColumn();
	}
	/**
	 * one to one relation.
	 * 
	 * @param String $table table name [and alias] which need to be joined. eg. User as Author
	 * @param String $fields the fields you need to retrieve. default is all. E.G. Author.uname as authorUname, Author.nickname as nickname.
	 * @param String $foreignKey ForeignKey field name. default is null which will use tableName+Id as its FK. eg. userId, productId
	 * @param String $joinType one of the three [inner, left, right]. default is left. 
	 * @return LdBaseDao
	 */
	function hasA($table, $fields='', $foreignKey = null, $joinType = 'left'){
		 //if $table have alias like ('User  author'), extract the table name and alias.
		if (strpos($table, ' ') !==false) {
			$tmp = preg_split('/\s+/', str_replace(' as ', ' ', $table));
			$tblName = ucfirst($tmp[0]);
			$tblAlias = $tmp[1];
		} else {
			$tblName = ucfirst($table);
			$tblAlias = $table;
		}
		
		$foreignKey = $foreignKey ? $foreignKey : lcfirst($tblName).'Id';
		$foreignKey = $this->sDbh->quoteIdentifier($foreignKey);
		$joinType = $joinType.' JOIN';
		
		$tblName = $this->sDbh->quoteIdentifier($tblName);
		$this->sTbl->join("$tblName $tblAlias", "$this->tblName.$foreignKey=$tblAlias.id", $fields, $joinType);

		return $this;
	}

	function beginTransaction() {
		$this->mDbh->beginTransaction();
	}
	function commit() {
		$this->mDbh->commit();
	}
	function rollback() {
		$this->mDbh->rollback();
	}
	function debug($dbh = null) {
		if (is_null($dbh)) $dbh = $this->sDbh;
		return $dbh->debug();
	}
	function lastSql($tbl = null) {
		if (is_null($tbl)) $tbl = $this->sTbl;
		return $tbl->sql();
	}
	/**
	 * return the slave table handler object
	 *
	 * @return LdTable
	 */
	function sTbl() {
		return $this->sTbl;
	}

	/**
	 * return the master table handler object
	 *
	 * @return LdTable
	 */
	function mTbl() {
		return $this->mTbl;
	}

	function tblName() {
		return $this->tblName;
	}
	function daoName($trailingDao = true, $lcFirst=false) {
		$daoName = get_class($this);
		if (!$trailingDao) {
			$daoName = substr($daoName, 0, strpos($daoName, 'Dao'));
		}
		if ($lcFirst) $daoName[0] = strtolower($daoName[0]);
		return $daoName;
	}
	function truncate() {
		$this->mTbl->exec('TRUNCATE '.$this->tblName);
	}
}