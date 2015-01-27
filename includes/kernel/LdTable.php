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

/**
 * Table utility class which used to simplify the CRUD operation
 * These fields: $fields, $join, $orderFields, $orderMode, $group
 * are only used by select() and other read-only method, not by the method
 * which will change the data of table like update(), insert(), delete()
 * And another fields share in the two (read-only and changeable):
 * $db, $tableName, $tableAlias, $condition
 *
 * @author Libok.Zhou <libkhorse@gmail.com>
 */
class LdTable {
	/**
	 * @var LdDatabase
	 */
	private $_db;

    /** @var String  table name */
    private $_tableName = '';

    /** @var String  current table's alias, default is table name without prefix */
    private $_tableAlias = '';

    /** @var String  fields part of the select clause, default is '*' */
    private $_fields = '*';

    /** @var String  Join clause */
    private $_join = '';

    /** @var String  condition*/
    private $_where = '';
    
    /** @var String  condition*/
    private $_having = '';

    /** @var Array  params used to replace the placehold in condition*/
    private $_params = NULL;

    /** @var String  e.g. Id ASC */
    private $_order = '';

    /** @var String  group by */
    private $_group = '';

	/** @var current sql clause */
	private $_sql = '';
	
	/** @var sql clause directly assigned by User */
	private $_userSql = '';

	private $_distinct = false;
	
	/** @var limit rows, start */
	private $_limit = '';
	
    /*=== CONSTS ===*/
    /** @var String left join */
    const LEFT_JOIN = 'LEFT JOIN';
    /** @var String left join */
    const INNER_JOIN = 'INNER JOIN';
    /** @var String left join */
    const RIGHT_JOIN = 'RIGHT JOIN';

    /**
	 * @param LdDatabase $dbObj
     * @param String $tableName	table name without prefix
     * @param String $tableAlias alias of table, Default equals table name without prefix
     */
    function __construct($dbObj, $tableName, $tableAlias = '') {
		$this->_db = $dbObj;
        $this->_tableName = $this->_db->prefix($tableName);

        //tableAlias default is the table name without prefix
        $this->_tableAlias = $tableAlias ? $tableAlias : $tableName;
    }

    /**
     * Set table Alias
     *
     * @param String $tableAlias Table's alias
     * @return LdTable $this
     * @access public
     */
    function setTableAlias($tableAlias) {
        $this->_tableAlias = $tableAlias;
        return $this;
    }
    
    /**
     * set or get sql
     *
     * @param string $sql if empty will return last sql condition
	 * @param string|array $params
     * @return LdTable $this or String sql
     */
	function sql($sql = '', $params = NULL) {
		if (!empty($sql)) {
			$this->_sql = '';
			$this->_userSql = $sql;
			$this->_params = $this->autoarr($params);
			return $this;
		} else {
			return $this->_sql;
		}
	}
	
	/**
	 * set the field part of sql clause
	 *
	 * @param String $fieldName comma separated list: id, User.name, UserType.id
	 * @return LdTable
	 */
	function setField($fieldName) {
		if ($fieldName) {
			if ($this->_fields && $this->_fields != '*') {
				if ($fieldName !='*') {
					$this->_fields .= ",$fieldName";
				} else {
					if (strpos($this->_fields, $this->_tableAlias.'.*') === false) 
						$this->_fields .= ','.$this->_tableAlias.'.*';
				}
			} else {
				$this->_fields = $fieldName;
			}
		}
		return $this;
	}
	/**
	 * identical to setField()
	 *
	 * @param String $fieldName comma separated list: id, User.name, UserType.id
	 * @return LdTable
	 */
	function field($fieldName) {
		return $this->setField($fieldName);
	}
	
	/**
	 * whether to distinct search for the fields.
	 * 
	 * @param bool $distinct whether to distinct rows, default is false;
	 * @return LdTable
	 */
	function distinct($distinct = false) {
		$this->_distinct = $distinct;
		return $this;
	}

    /**
     * used by $this->join()
     * @param string $fields field part of joined table
     * @return LdTable
     */
	private function addJoinField($fields) {
		if ($this->_fields == '*') {
			$this->_fields = "{$this->_tableAlias}.*, {$fields}";
		} else {
			$this->_fields .= ','.$fields;
		}
		return $this;
	}
    /**
     * join a table, This function can be multiple called and each call will be concatenated.
     *
     * @param String $table the table will be joined, which can have alias like "user u" or "user as u"
     * @param String $on on condition
     * @param String $fields the fields came from the joined table
     * @param String $join join type: LdTable::LEFT_JOIN OR LdTable::RIGHT_JOIN OR LdTable::INNER_JOIN.
     * @return LdTable
     */
    function join($table, $on = '', $fields = '', $join = LdTable::INNER_JOIN) {
    	$as = $table;
    	//if $table have ' ' which means $table have a alias,
    	//so replace the as if have and separate the table name and alias name.
    	if (strchr($table, ' ')) {
    		$tmp = explode(' ', str_replace(' as ', ' ', $table));
    		$table = $tmp[0];
    		$as = $tmp[1];
    	}

        $table = $this->_db->quoteIdentifier($this->_db->prefix($table));

        if ($fields) $this->addJoinField($fields);

        $on = $on ? 'ON '.$on : '';

        $this->_join .= " {$join} {$table} {$as} {$on} ";
        return $this;
     }
	 
	/**
	 * left join a table, This function can be multiple called and each call will be concatenated.
	 *
     * @param String $table the table will be joined, which can have alias like "user u" or "user as u"
     * @param String $on on condition
     * @param String $fields the fields came from the joined table
	 * @return LdTable
	 */
     public function leftJoin($table, $on = '', $fields = '') {
     	return $this->join($table, $on, $fields, LdTable::LEFT_JOIN);
     }
     
	/**
	 * Right join a table, This function can be multiple called and each call will be concatenated.
	 *
     * @param String $table the table will be joined, which can have alias like "user u" or "user as u"
     * @param String $on on condition
     * @param String $fields the fields came from the joined table
	 * @return LdTable
	 */     
     function rightJoin($table, $on = '', $fields = '') {
     	return $this->join($table, $on, $fields, LdTable::RIGHT_JOIN);
     }
	/**
	 * inner join a table, This function can be multiple called and each call will be concatenated.
	 *
     * @param String $table the table will be joined, which can have alias like "user u" or "user as u"
     * @param String $on on condition
     * @param String $fields the fields came from the joined table
	 * @return LdTable
	 */     
     function innerJoin($table, $on = '', $fields = '') {
     	return $this->join($table, $on, $fields, LdTable::INNER_JOIN);
     }

    /**
    * set condition part in query clause
    *
	* @param String $condition e.g. 'field1=1 & tableAlias.field3=3' or 'field1=? & tableAlias.field3=?' or
	*                               'field1=:name & tableAlias.field3=:user'
	* @param Array $params
	* @return LdTable
    */
    function where($condition, $params = NULL) {
    	if (!empty($condition)) {
	        $this->_where = 'WHERE '.$condition;
	        $this->_params = $this->autoarr($params);
    	}
        return $this;
    }
    /**
    * set condition part in query clause
    *
	* @param String $condition e.g. 'field1=1 & tableAlias.field3=3' or 'field1=? & tableAlias.field3=?' or
	*                               'field1=:name & tableAlias.field3=:user'
	* @param Array $params
	* @return LdTable
    */
    function having($condition, $params = NULL) {
    	$this->_having = 'HAVING '.$condition;
        $this->_params = empty($this->_params) ?  $this->autoarr($params) : array_merge($this->_params, $this->autoarr($params));
        return $this;
    }
    /**
    * set order part in query clause
    * @param String order : e.g. id DESC
    * @return LdTable
    */
    function orderby($order) {
        $this->_order = $order;
        return $this;
    }

    /**
    * set group part in query clause
    *
	* @param String $group e.g. 'field1'
	* @return LdTable
    */
    function groupby($group) {
        $this->_group = $group;
        return $this;
    }
    
    /**
    * set group part in query clause
    *
	* @param int $rows
	* @param int $start
	* @return LdTable
    */
    function limit($rows = 0, $start = 0) {
        if (empty($rows)) {
        	$this->_limit = '';
        } else {
        	$this->_limit = "LIMIT {$rows} OFFSET {$start}";
        }
        return $this;
    }

	/**
	 * construct all the given information to a sql clause. often used by read-only query.
	 * @param bool $return true: return the sql clause (Default is true). false: assign sql clause to this->sql.
	 * @return void
	 */
	private function constructSql($return = true) {
		if (empty($this->_userSql)) {
			$distinct = $this->_distinct ? 'DISTINCT' : '';
			
			$groupby = '';
			if (!empty($this->_group)) {
				$groupby = 'GROUP BY '.$this->_group;
				if (!empty($this->_having)) $groupby .= ' '.$this->_having;
			}
			$order = !empty($this->_order) ? 'ORDER BY '.$this->_order : '';
	
			$sql = "SELECT $distinct $this->_fields FROM {$this->_db->quoteIdentifier($this->_tableName)} {$this->_tableAlias} {$this->_join} {$this->_where} {$groupby} {$order} {$this->_limit}";
		} else {
			$sql = $this->_userSql;
		}
		$this->reset();
		if ($return) {
			return $sql;
		} else {
			$this->_sql = $sql;
		}
	}

	/**
	 * do an query directly, which will return a statement object
	 *
	 * @param Array/String $params
	 * @return PDOStatement query result
	 */	
	function query($multi_call_params = NULL) {
		# 1. A multi-call means that sql have been prepared to do multiple call with different params.
		# 2. if $multi_call_params is null, means this is an once-call.
		# 	 once-call does not exist this->sql.
		# 3. if $multi_call_params is not null, means this is an multi-call.
		# 	 this->sql only exists when this is an multi-call
		
		if (is_null($multi_call_params)) {//once-call, this->sql have no value
			return $this->_db->query($this->constructSql(), $this->_params);
		} else { //multiple-call: 
			if (empty($this->_sql)) $this->constructSql(false);
			return $this->_db->query($this->_sql, $this->autoarr($multi_call_params));
		}	
	}
   /**
    * get one row from table into an array
    * @param int $fetchMode PDO::FETCH_ASSOC, PDO::FETCH_NUM, PDO::FETCH_BOTH
    * @param String|Array $multi_call_params params used for multi call, assign only if you wanna using multi-call
    * 	A multi-call means that sql have been prepared to do multiple call with different params.
    *   if $multi_call_params is not null, means this is an multi-call.
    *
    * @return array|bool represent one row in a table, or false if failure
    */
	function fetch($multi_call_params = NULL, $fetchMode = PDO::FETCH_ASSOC) {
		$this->limit(1);
		return $this->query($multi_call_params)->fetch($fetchMode);
	}

    /**
    * get all rows from table into an 2D array
    * 
    * @param int $fetchMode Controls the contents of the returned array.
    * Defaults to PDO::FETCH_BOTH. Other useful options is: 
    * PDO::FETCH_COLUMN|PDO::FETCH_UNIQUE: To fetch only the unique values of a single column from the result set
    * PDO::FETCH_COLUMN|PDO::FETCH_GROUP: To return an associative array grouped by the values of a specified column
    * @return array represents an table
    */
	function fetchAll($multi_call_params = NULL, $fetchMode = PDO::FETCH_ASSOC) {
		return $this->query($multi_call_params)->fetchAll($fetchMode);
	}
   /**
    * get the same column in each rows from table into an 1D array.
    * eg. select col1 from table limit 0,3. 
    * will return: array(row1_col1, row2_col1, row3_col1);
    * 
    * @return array represents an table
    */
	function fetchAllUnique($multi_call_params = NULL) {
		return $this->query($multi_call_params)->fetchAll(PDO::FETCH_COLUMN|PDO::FETCH_UNIQUE, 0);
	}
	
	/**
	 * get the same column in each rows from table into an 1D array.
	 * note:
	 *
	 * @example
	 * <pre>
	 * select col1, col2 from table limit 0,3. \n
	 * will return: array(row1_col1=>row1_col2, row2_col1=>row2_col2, row3_col1=>row3_col2);
	 * </pre>
	 *
	 * @return array represents an table
	 */
	function fetchAllKvPair($multi_call_params=NULL) {
		return $this->query($multi_call_params)->fetchAll(PDO::FETCH_COLUMN|PDO::FETCH_GROUP, 0);
	}

    /**
    * Returns a single column from the next row of a result set
    *
    * @param int $columnNumber 0-indexed number of the column you wish to retrieve from the row.
    *            If no value is supplied, PDOStatement->fetchColumn() fetches the first column.
    * @return String Returns a single column from the next row of a result set or FALSE if there are no more rows.
    */
	function fetchColumn($multi_call_params=NULL) {
		return $this->query($multi_call_params)->fetchColumn();
	}

	/**
	* get one row of the table into an indexed array (contrast with association array)
	* @return Array indexed array, value is $fields value.
	*/
	function fetchIndexed($multi_call_params = NULL) {
		return $this->fetch($multi_call_params, PDO::FETCH_NUM);
	}

	/**
	 * get the records count
	 *
	 * @param String $distinctFields which field(s) for identifying distinct.
	 * @return int the record count
	 */
    function recordsCount($distinctFields = '') {
    	$this->_fields = $distinctFields ? "count(DISTINCT {$distinctFields})" : 'count(*)';  
        return $this->fetchColumn();
    }

    /**
     * insert a new record into table
     *
     * @param array $arr key is the field name and value is the field value
     *              array(  'field1_name' => 'value',
     *                      'field2_name' => 'value',
     *                      ...
     *                    );
     * @return int Last insert id if insert successful, else SqlException will be throwed
     */
    function insert($arr) {
    	//TODO for security reason, we should auto cache table scheme, and validate the data needs to be insert into DB.
    	if ( empty($arr) ) return false;

        $comma = '';
        $setFields = '(';
        $setValues = '(';
        foreach($arr as $key => $value) {
 			$params[] = $value;
 			$key = $this->_db->quoteIdentifier($key);
            $setFields .= "{$comma}{$key}";
            $setValues .= $comma.'?';
            $comma = ',';
        }
        $setFields .= ')';
        $setValues .= ')';
        
        $sql = "INSERT INTO  {$this->_db->quoteIdentifier($this->_tableName)} {$setFields} values {$setValues}";
        $this->_db->exec($sql, $params);
        if (DB_TYPE == 'pgsql') $name = strtolower($this->_tableName).'_id_seq';
        return $this->_db->lastInsertId($name);
    }

    /**
     * update a record in the table
     *
     * @param array $arr key is the field name and value is the field value
     *              array(  'field1_name' => 'value',
     *                      'field2_name' => 'value',
     *                      ...
     *                    );
     * @param String|Array $condition The query condition. with following format:<br />
     * 		String: 'id=2 and uanme="libok"'
     * 		Array:  array('id=? and uname=?', array(2, 'libok')); //TODO at present, here can only support placeholder(?) style prepared statement
     *
     * @return affected row nums if insert successful, else SqlException will be throwed
     */
    function update($arr, $condition = '') {
        if ( empty($arr) ) return false;

        $comma = '';
        $setFields = '';
        foreach($arr as $key => $value) {
 			$params[] = $value;
 			$key = $this->_db->quoteIdentifier($key);
            $setFields .= "{$comma} {$key}=?";
            $comma = ',';
        }
        $sql = "UPDATE {$this->_db->quoteIdentifier($this->_tableName)} set {$setFields}";

	    if (!empty($condition)) {
	    	if (is_array($condition)) {
	    		$sql .= ' WHERE '.$condition[0];
	    		$params = array_merge($params, $this->autoarr($condition[1]));
	    	} else {
	    		$sql .= ' WHERE '.$this->_db->quote($condition);
	    		$params = null;
	    	}
	    }

        return $this->_db->exec($sql, $params);
    }

    /**
     * delete record from table
     *
     * @param String $condition The query condition. with following format:<br />
     * 		String: 'id=2 and uanme="libok"' or 'id=? and uname=?' or 'id=:id and uname=:uname'
     * @param String|Array $params params which will be used in prepared statement, with following format: <br />
     * 		String: if you just need one parameter in above prepared statement. e.g. '1111'
     *		Array: array(2, 'libok') or array(':id'=>2, ':uname'=>'libok')
     * 
     * @return affected row nums if insert successful, else SqlException will be throwed
     * @access public
     */
    function delete($condition = '', $params = null) {
        $sql = "DELETE FROM {$this->_db->quoteIdentifier($this->_tableName)}";

	    if (!empty($condition)) {
	    	if (!is_null($params)) { //using prepared statement.
	    		if (!is_array($params)) $params = array($params); 
	    		$sql .= ' WHERE '.$condition;
	    	} else {
	    		$sql .= ' WHERE '.$this->_db->quote($condition);
	    	}
	    }

        return $this->_db->exec($sql, $params);
    }

    function dropTable() {
        return $this->_db->exec("DROP TABLE {$this->_db->quoteIdentifier($this->_tableName)}");
    }

	/**
	 * reset some data member of LdTable which used to construct a sql clause
	 * this method usally called after an DataBase query finished (e.g. $this->select();)
	 */
	private function reset() {
    	$this->_fields = '*';
		$this->_join = '';
		$this->_where = '';
		$this->_having = '';
		$this->_order = '';
		$this->_group = '';
		$this->_distinct = false;
		$this->_userSql = '';
		$this->_limit = '';
	}
	/**
	 * execute an insert/update/delete sql clause directly,
	 * @param String $sql sql clause
	 * @param Mixed $params
	 * @return int affected rows
	 */
	function exec($sql, $params = NULL){
		if (func_num_args() == 2) {
			$params = $this->autoarr($params);
		} else {
			$params = func_get_args();
			array_shift($params);
		}
		return $this->_db->exec($sql, $params);
	}
	/**
	 * just for LdTable inner use to auto wrap any param to an array.  
	 * @param String|Array $params
	 */
	private function autoarr($params) {
		if (!is_null($params) && !is_array($params)) $params = array($params);
		return $params;
	}
}
