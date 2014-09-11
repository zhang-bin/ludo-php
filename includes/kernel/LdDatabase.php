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
 * represent database operation layer, here I use the singleton pattern to make sure 
 * there is a unique connection to the database for the entire system
 */
class LdDatabase {
	/** dsn */
	private $_dsn = '';
	/**
	 * PDO singleton instance
	 *
	 * @var PDO
	 */
	private $_dbh = null;
    /**
     * @var PDOStatement
     */
    private $_stmt = null;
    /**
     * @var Array
     */
    private $_stmtCache = array();
    
    /** a LdDatabase instance */
    private static $_instance = null;
    
    /** LdDatabase pool */
    public static $pool = array();
    
    /** query log */
    private $_log = array();
    
    /** query Cnt */
    private $_cnt = 0;
    private $_current = 0;
    
    /**
     * @param $driver database platform (e.g. mysql, sqlserver, oracle)
     * @param $username database user name
     * @param $password database user password
     * @param $hostname database host
     * @param $database database name
     * @param $pconnect whether to use pconnect or connect
     */
    private function __construct($host, $user, $pass, $dbname = '', $port = 5432, $driver = 'pgsql', $pconnect = false) {
    	try {
    		$this->_dsn = "$driver:host=$host;port=$port;" . ( empty($dbname) ? '':"dbname=$dbname" );
    		$this->_dbh = new PDO($this->_dsn, $user, $pass, array(
				PDO::ATTR_PERSISTENT => $pconnect, 
				PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES '.str_replace('-', '', PROGRAM_CHARSET)
    		));
    		self::$pool[] = $this;
    	} catch(PDOException $e) {
            echo "error";
            throw new LdException(LG_CONNECT_DB_FAILED, sprintf(Ld::$err[2], $e->getMessage()), 2);
        }
        
        $this->_dbh->debug = false;
    }

    /**
     * get the unique instance of LdDatabase
     * 
     * @return LdDatabase instance of LdDatabase
     */
	public static function getInstance($hostname, $userName, $password, $database='', $port=5432, $driver='pgsql', $pconnect=false) {
		if (self::$_instance == null) {
			self::$_instance = new LdDatabase($hostname, $userName, $password, $database, $port, $driver, $pconnect);
		}
		return self::$_instance;
	}
	
	public static function factory($hostname, $userName, $password, $database, $port=5432, $driver='pgsql', $pconnect=false) {
		return new LdDatabase($hostname, $userName, $password, $database, $port, $driver, $pconnect);
	}
	public function changeDb($db) {
		return $this->_dbh->exec('USE '.$db);
	}
    
    /**
     * query database, return the recordset object. common use to query database
     * @param sql clause
     * @return PDOStatement
     */
    function query($sql, $params=NULL) {
		$this->logStart($sql, $params);
        $this->prepare($sql);
        $result = is_null($params) ? $this->_stmt->execute() : $this->_stmt->execute($params);
		
        $this->logEnd();
        if ( false !== $result ) {
            return $this->_stmt;
        } else {
            $this->_log[$this->_current]['err'] = $this->err();
            throw new SqlException(LG_QUERY_FAILED, $this->err(), $sql, $params);
        }
    }
    function prepare($sql) {
    	$key = md5($sql);
        if (!isset($this->_stmtCache[$key])) $this->_stmtCache[$key] = $this->_dbh->prepare($sql);
        $this->_stmt = $this->_stmtCache[$key];
    }
    /**
     * execute insert, update, delete clause in database, return the affected rows
     * @param sql clause
     * @return affected rows
     */    
    function exec($sql, $params=NULL) {
		$this->logStart($sql, $params);
		
        $this->prepare($sql);
        $result = is_null($params) ? $this->_stmt->execute() : $this->_stmt->execute($params);
        
        $this->logEnd();
        if ( false !== $result ) {
            return $this->_stmt->rowCount();
        } else {
            $this->_log[$this->_current]['err'] = $this->err();
            throw new SqlException(LG_EXECUTE_FAILED, $this->err(), $sql, $params);
        } 
    }
    
    function beginTransaction() {
    	$this->_dbh->beginTransaction();
    }
    function commit() {
    	$this->_dbh->commit();
    }
    function rollBack() {
    	$this->_dbh->rollBack();
    }
    
    function err() {
    	$err = $this->_stmt->errorInfo();
    	return $err[0] .' : '. $err[1] .':'. $err[2];
    }
    /**
     * get last insert id
     * 
     * @return last insert id
     */    
    function lastInsertId($name = 'id') {
		return $this->_dbh->lastInsertId($name);
    }
    
    function quote($str) {
        return $this->_dbh->quote($str);
    }
    
    function quoteIdentifier($str) {
    	switch (DB_TYPE) {
    		case 'mysql':
    			$str = trim($str, '`');
    			$str = "`{$str}`";
    			break;
    		case 'sqlsrv':
    			$str = trim($str, '[]');
    			$str = "[{$str}]";
    			break;
    		case 'pgsql':
    			$str = trim($str, '"');
    			$str = "\"{$str}\"";
    			break;
    		default:
    			break;
    	}
    	return $str;
    }
    
    function prefix($tableName) {
        return TABLE_PREFIX.$tableName;
    }
    function dsn() {
    	return $this->_dsn;
    }
    function closeCursor() {
    	$this->_stmt->closeCursor();
    }
    
    /**
     * get the sql log which is a 2-dimension array with fllowing structure:
     * array(
     *          0 => array('sql' => 'select * from tableA',
     *                     'err' => 'some sql errors'), 
     *          1 => array('sql' => 'insert into tableA set xxx',
     *                     'err' => 'some sql errors'),
     *          ...
     *      ); 
     *                  
     */
    function &getLog() {
        return $this->_log;
    }
    
    /**
     * get query times in the whole current operation
     * @return query times
     */
    function getQueryTimes() {
        return $this->_cnt;
    }
    
    function logStart($sql, $params) {
    	if (!DEBUG) return;
    	$this->_current = $this->_cnt++;
    	$this->_log[$this->_current]['sql'] = $sql;
        $this->_log[$this->_current]['params'] = $params;
        $this->_log[$this->_current]['processTime'] = microtime(true);
    }
    function logEnd() {
    	if (!DEBUG) return;
        $this->_log[$this->_current]['processTime'] = microtime(true) - $this->_log[$this->_current]['processTime'];
    }
    function lastSql() {
    	if (!empty($this->_log)) {
    		$max = count($this->_log) - 1;
    		$log = $this->_log[$max];
    		base64_encode('sql: '.$log['sql']."\nparams:".var_export($log['params'], true));
    	}
    	return false;
    }
    function clearStmt() {
    	$this->_stmt = null;
    }
    function getStmtCache() {
    	return $this->_stmtCache;
    }
	function debug() {
		$totalProcessTime = 0;
		$totalSQL = 0;
		
		$Str = "
		<table id=debugtable width=100% border=0 cellspacing=1 style='background:#DDDDF0;word-break: break-all'>";

		$Str .= '<tr style="background:#A5BDD8;height:30;Color:White">
					<th>Query</th>
					<th width=100>Params</th>
					<th width=50>Error</th>
					<th width=100>ProcessTime</th>
				 </tr>';
                 
		foreach($this->_log as $log)
		{
			$Str .= '<tr style="background:#EEEEEE;Height:25;Text-Align:center">
						<td align=left>'.HtmlSpecialChars($log['sql']).'</td>
						<td align=left>'.var_export($log['params'], true).'</td>
						<td>'.@$log['err'].'</td>
						<td>'.sprintf('%.4f',$log['processTime']).'</td>
					 </tr>';
			$totalProcessTime += (double)$log["processTime"];
			$totalSQL++;
		}
		
		$Str .= "<tr style='background:#EEEEEE;Height:30;text-align:center'>
					<td colspan=5>
						Total execute queries: ". $totalSQL 
							. "&nbsp;Total ProcessTime:" 
							. sprintf('%.4f',$totalProcessTime) 
							. "</td>
				 </tr>\n";
		
		$Str .= "</table>";
		
		return $Str;
	}

}
?>
