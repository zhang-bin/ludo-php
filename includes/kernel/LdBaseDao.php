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
	 * Handler of LdKernel
	 *
	 * @var LdKernel
	 */
	protected $kernel = null;
	
	/**
	 * Handler of LdDatabase
	 *
	 * @var LdDatabase
	 */
	protected $dbh = null;
	
	/**
	 * Instance of LdTable
	 *
	 * @var LdTable
	 */
	protected $tbl = null;
	
	protected $tblName = null;
	protected $one2many = null;
	
	/**
	 * master type of db handler
	 * @var String
	 */
	const MASTER = 'm';
	
	/**
	 * slave type of db handler
	 * @var String
	 */
	const SLAVE = 's';

	function __construct($tblName, $dbh=null) {
		$this->kernel = $GLOBALS['ldKernel'];
		$this->dbh['m'] = $dbh ? $dbh : $this->kernel->getMDBHandler();
		$this->dbh['s'] = $dbh ? $dbh : $this->kernel->getSDBHandler();
		$this->tblName = $tblName;
		foreach ($this->dbh as $k=>$v) {
			$this->tbl[$k] = new LdTable($v, $tblName);
		}
	}

	/**
	 * insert data into DB
	 *
	 * @param Array $arr array('field'=>value, 'field2'=>value2);
	 * @return int Last insert id if insert successful, else SqlException will be throwed
	 */
	function add($arr) {
		return $this->tbl['m']->insert($arr);
	}
	/**
	 * identical to LdBaseDao::add($arr);
	 *
	 * @param Array $arr array('field'=>value, 'field2'=>value2);
	 * @return int Last insert id if insert successful, else SqlException will be throwed
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
	 * @return true if insert successful, else SqlException will be throwed
	 */
	function batchInsert($arr, $fieldNames=array(), $ignore = false) {
		if (empty($arr)) return false;
		
		$keys = '(';
		if (!empty($fieldNames)) { 
			if (is_array($fieldNames)) {
				$comma = '';
				foreach ($fieldNames as $field) {
					$keys .= $comma.$this->dbh['m']->quoteIdentifier($field);
					$comma = ',';
				}
			} else {
				$keys = $this->dbh['m']->quoteIdentifier($fieldNames);
			}
		} else {
			$fields = array_keys($arr[0]);
			$comma = '';
			foreach ($fields as $field) {
				$keys .= $comma.$this->dbh['m']->quoteIdentifier($field);
				$comma = ',';
			}
		}
		$keys .= ')';
			
		$sql = 'INSERT';
		if ($ignore) $sql .= ' IGNORE ';
		$sql .= ' INTO '.$this->dbh['m']->quoteIdentifier($this->tblName)." {$keys} VALUES ";
		
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
		return $this->tbl['m']->exec($sql, $params);
	}
	
	/**
	 * update filelds of object with id=$id
	 *
	 * @param Int $id
	 * @param Array $arr
	 */
	function update($id, $arr) {
		return $this->updateWhere($arr, 'id=?', array($id));
	}
	/**
	 * update filelds of object with some conditions
	 *
	 * @param Array $newData
	 * @param String $condition
	 * @param mixed 
	 */
	function updateWhere($newData, $condition, $params=array()) {
		return $this->tbl['m']->update($newData, array($condition, $params));
	}
	/**
	 * same as update
	 *
	 * @param Int $id
	 * @param Array $arr
	 */
	function edit($id, $arr) {
		return $this->update($id, $arr);
	}
	function delete($id) {
		return $this->deleteWhere('id = ?', $id);
	}
	function deleteWhere($condition, $params=null) {
		return $this->tbl['m']->delete($condition, $params);		
	}

	/**
	 * get one row from table by ID
	 *
	 * @param $id
	 * @param String $fields fields needs to be fetched, comma seperated
	 * @return Array key is field name and value is field value.
	 */
	function fetch($id, $fields = '', $fetchMode = PDO::FETCH_BOTH) {
		if (!empty($fields)) $this->tbl['s']->setField($fields);
		$this->tbl['s']->where($this->tblName.'.id=?', $id);
		$result = $this->tbl['s']->fetch(NULL, $fetchMode);
		if ($this->one2many) {
			foreach($this->one2many as $daoName=>$relKey) {
				$dao = LdFactory::dao($daoName);
				$relKey = $this->dbh['s']->quoteIdentifier($relKey);
				$result[$dao->daoName(false, true).'List'] = $dao->findAll(array("$relKey=?", $id));
			}
		}
		return $result;
	}

	function find($condition, $params, $fields = '', $fetchMode = PDO::FETCH_BOTH) {
		if (!empty($fields)) $this->tbl['s']->setField($fields);
		return $this->tbl['s']->where($condition, $params)->fetch(NULL, $fetchMode);
	}
	function findColumn($condition, $params, $column) {
		if (!empty($column)) $this->tbl['s']->setField($column);
		return $this->tbl['s']->where($condition, $params)->fetchColumn();		
	}
	function fetchColumn($id, $column) {
		if (!empty($column)) $this->tbl['s']->setField($column);
		return $this->tbl['s']->where($this->tblName.'.id=?', array($id))->fetchColumn();
	}
	function fetchAll($rows = 0, $start = 0, $order = '', $fields = '*', $fetchMode = PDO::FETCH_BOTH) {
		return $this->tbl['s']->field($fields)->limit($rows, $start)->orderby($order)->fetchAll(NULL, $fetchMode);
	}
	function fetchAllUnique($fields = '*', $rows = 0, $start = 0, $order = '') {
		return $this->tbl['s']->field($fields)->limit($rows, $start)->orderby($order)->fetchAllUnique();
	}
	
	function findAll($condition = '', $rows = 0, $start = 0, $order='', $fields = '*', $fetchMode = PDO::FETCH_BOTH) {
		if (is_array($condition)) {
			$where = $condition[0];
			$params = $condition[1];
		} else {
			$where = $condition;
			$params = null;
		}

		return $this->tbl['s']->field($fields)->where($where, $params)->orderby($order)->limit($rows, $start)->fetchAll(NULL, $fetchMode);
	}
	function findAllUnique($condition = '', $fields = '', $rows = 0, $start = 0, $order = '') {
		if (is_array($condition)) {
			$where = $condition[0];
			$params = $condition[1];
		} else {
			$where = $condition;
			$params = null;
		}

		return $this->tbl['s']->field($fields)->where($where, $params)->orderby($order)->limit($rows, $start)->fetchAllUnique();
	}
	
	function findAllKvPair($condition = '', $fields = '', $rows = 0, $start = 0, $order='') {
		if (is_array($condition)) {
			$where = $condition[0];
			$params = $condition[1];
		} else {
			$where = $condition;
			$params = null;
		}
	
		return $this->tbl['s']->field($fields)->where($where, $params)->orderby($order)->limit($rows, $start)->fetchAllKvPair();
	}
	
	/**
	 * Do query, and return the PDOStatement Object
	 *
	 * @param String $condition
	 * @param int $rows
	 * @param int $start
	 * @param String $order
	 * @param String $fields
	 * @return PDOStatement
	 */
	function queryAll($condition = '', $rows = 0, $start = 0, $order = 'id DESC', $fields = '') {
		if (is_array($condition)) {
			$where = $condition[0];
			$params = $condition[1];
		} else {
			$where = $condition;
			$params = null;
		}
		return $this->tbl['s']->field($fields)->where($where)->orderby($order)->limit($rows, $start)->query($params);
	}
	
	function count($condition = '', $params = null, $distinct = false) {
		if (!empty($condition)) {
			$this->tbl['s']->where($condition, $params);
		}
		return $this->tbl['s']->recordsCount($distinct);
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
		$cnt = $this->tbl['s']->setField('count(*)')->where($condition, $params)->fetchColumn();
		return $cnt > 0 ? true : false;
	}

	/**
	 * Check if the records exists according to the $condition. if exists, return the row data.
	 * result[0] is a bool value represent exists or not.
	 * If exists, result[1] will store the "1st db row" result
	 * 
	 * @param String $condition
	 * @return Array list(exists, row) = Array(0=>true/false, 1=>rowArray/false)
	 */
	function existsRow($condition='', $params = null, $fields = null) {
		if (!empty($fields)) $this->tbl['s']->setField($fields);
		$row = $this->tbl['s']->where($condition, $params)->fetch(NULL, PDO::FETCH_BOTH);
		$exists = empty($row) ? false : true;
		return array($exists, $row);
	}
	
	/**
	 * return the max Id from current table
	 * 
	 * @return int the max id
	 */
	function maxId() {
		return $this->tbl['s']->setField('id')->orderby('id DESC')->fetchColumn();
	}
	/**
	 * one to one relation.
	 * 
	 * @param String $table table name [and alias] which need to be joined. eg. User as Author
	 * @param String $fields the fields you need to retrive. default is all. E.G. Author.uname as authorUname, Author.nickname as nickname.
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
		$foreignKey = $this->dbh['s']->quoteIdentifier($foreignKey);
		$joinType = $joinType.' JOIN';
		
		$tblName = $this->dbh['s']->quoteIdentifier($tblName);
		$this->tbl['s']->join("$tblName $tblAlias", "$this->tblName.$foreignKey=$tblAlias.id", $fields, $joinType);

		return $this;
	}

	function belongs2($table, $fields, $foreignKey = null){
		$this->hasA($table, $fields, $foreignKey);
	}

	function hasMany($daoName, $relKey = null) {
		if ($relKey == null) $relKey = $this->tblName.'Id';
		$this->one2many[$daoName] = $relKey;
		return $this;
	}
	function beginTransaction() {
		$this->dbh['m']->beginTransaction();
	}
	function commit() {
		$this->dbh['m']->commit();
	}
	function rollback() {
		$this->dbh['m']->rollback();
	}
	function debug($dbhType = 's') {
		return $this->dbh[$dbhType]->debug();
	}
	function lastSql($dbhType = 's') {
		return $this->tbl[$dbhType]->sql();
	}
	/**
	 * return the table handler object
	 *
	 * @return LdTable
	 */
	function tbl($dbhType = 's') {
		return $this->tbl[$dbhType];
	}
	function tblName() {
		return $this->tblName;
	}
	function daoName($trailingDao = true, $lcfirst=false) {
		$daoName = get_class($this);
		if (!$trailingDao) {
			$daoName = substr($daoName, 0, strpos($daoName, 'Dao'));
		}
		if ($lcfirst) $daoName[0] = strtolower($daoName[0]);
		return $daoName;
	}
	function truncate() {
		$this->tbl['m']->exec('TRUNCATE '.$this->tblName);
	}
}
?>