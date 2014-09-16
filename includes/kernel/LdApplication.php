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
class LdApplication {
    /** @var kernel instance */
	private $_kernel = null;

    private $_ctrl = 'Index';
    private $_ctrlPath = '';
    private $_act = 'index';

	function __construct($ctrl = '') {
		if (!empty($ctrl))	$this->_ctrl = ucfirst($ctrl);
		$this->parsePathInfo();
		$GLOBALS['ldCtrl'] = $this->_ctrl;
		
		$this->_kernel = LdKernel::getInstance();
        $GLOBALS['ldKernel'] = $this->_kernel;
	}

	function parsePathInfo() {
		if (!empty($_SERVER['PATH_INFO'])) $pathInfo = str_replace('.html', '', $_SERVER['PATH_INFO']);
		if (!empty($pathInfo)) {
			$pathInfo = explode('/', trim($pathInfo, '/'));
			//==Ctrl
			if (!empty($pathInfo[0])) {
				if ( ($pos = strrpos($pathInfo[0], '_')) === false) {
					$this->_ctrl = ucfirst($pathInfo[0]);
				} else {
					$this->_ctrl = ucfirst(substr($pathInfo[0], $pos+1));
					$this->_ctrlPath = str_replace('_', '/', substr($pathInfo[0], 0, $pos+1));
				}
			}
			
			//==Act
			if (!empty($pathInfo[1])) {
				if (ctype_digit($pathInfo[1])) {
					$GLOBALS['ldInputData']['id'] = $_REQUEST['id'] = $_GET['id'] = $pathInfo[1];
					$this->_act = 'index';
				} else {
					$this->_act = $pathInfo[1];
					if($this->_act == 'list') $this->_act = 'doList';
				}
			}
			
			//==id
			$cnt = count($pathInfo);
			if ($cnt < 2) return; //if pathinfo only one param which is ctrl, just return back;
			
			$param_start = 2;
			if ($cnt % 2 != 0) {
				$GLOBALS['ldInputData']['id'] = $_REQUEST['id'] = $_GET['id'] = $pathInfo[2];
				$param_start = 3;
			}
			//==other Variables
			for ($i = $param_start; $i < $cnt; $i+=2) {
				$GLOBALS['ldInputData'][$pathInfo[$i]] = $_REQUEST[$pathInfo[$i]] = $_GET[$pathInfo[$i]] = $pathInfo[$i+1];
			}
		}
	}

	function run(){
		try {
			$ctrlFile = LD_CTRL_PATH.'/'.$this->_ctrlPath.$this->_ctrl.php;

			if (file_exists($ctrlFile)) {
				include_once $ctrlFile;
				
				$controller = new $this->_ctrl();
				$action = $this->_act;
				
				define('CURRENT_CONTROLLER', $controller->getCurrentCtrlName());
				define('CURRENT_ACTION', $this->_act);
				define('CURRENT_CONTROLLER_PATH', $this->_ctrlPath);
				
				$this->_kernel->getLangHandler()->setLanguage(CURRENT_CONTROLLER);
				
				if (!method_exists($controller, $action)) {
	       			throw new LdException(sprintf(LG_ACTION_NOT_FOUND, $action), 
				  	  	sprintf(Ld::$err[4], $this->_ctrl.'->'.$action), 4);
				}
			
				$method = new ReflectionMethod($this->_ctrl, $action);
				if ($method->isStatic()) {
	       			throw new LdException(sprintf(LG_ACTION_CANNOT_BE_STATIC, $action), 
				  	  	sprintf(Ld::$err[5], $this->_ctrl.'::'.$action), 5);
				}
				
				$controller->beforeAction($action);
				$output = $method->invoke($controller);
				$controller->afterAction($action, $output);

				//if have output, means this action is an ajax call.
				if (isset($output)) {
					if (!empty($controller->httpHeader)) {
						if (!is_array($controller->httpHeader)) {
							header($controller->httpHeader);
						} else {
							foreach ($controller->httpHeader as $header) {
								header($header);
							}
						}
					}
					is_array($output) && $output = json_encode($output);
                    echo $output;
				}
				if (DEBUG) self::debug($output);
				
				//==release all DB resource
				if (class_exists('LdDatabase', false) && !empty(LdDatabase::$pool)) {
					foreach(LdDatabase::$pool as $dbh) {
						$stmtCache = $dbh->getStmtCache();
						foreach ($stmtCache as $stmt) {
							$stmt = null;
						}
						$dbh = null;
					}
				}				
			} else {
       			throw new LdException(sprintf(LG_CTRL_NOT_FOUND, $this->_ctrl), 
								  	  sprintf(Ld::$err[3], $ctrlFile), 3);
			}
		} catch(Exception $ex) {
			if ($ex instanceof LdException ) {
				echo $ex->vividMsg();
			}
			$error = '<pre>'.$ex->__toString()."\n\n".$ex->getTraceAsString().'</pre>';
			error_log($error);
			if (DEBUG) {
				echo $error;
				self::debug($error);
			}
				//TODO maybe using a wellformed error like displaying the error below the page in a table which sql does
		}
	}
	function loadCtrl() {
		$ctrlFile = LD_CTRL_PATH.'/'.$this->_ctrl.php;

		if (file_exists($ctrlFile)) {
			include_once $ctrlFile;		
		}
	}
	function setCtrl($ctrl) {
		if ($ctrl) {
			$this->_ctrl = ucfirst($ctrl);
			$GLOBALS['ldCtrl'] = $this->_ctrl;
		}
	}
	function setAct($act) {
		if ($act) $this->_act = $act;
	}
	function getCtrlPath() {
		return $this->_ctrlPath;
	}
	function getCtrl() {
		return $this->_ctrl;
	}
	function getAct() {
		return $this->_act;
	}
	public static function debug($lastOutput='') {
		$debugInfo = '<h2>Time:'.date('Y-m-d H:i:s').':'.currUrl().'</h2>';
		$debugInfo .= '@@@@error:<pre>'.var_export(error_get_last(), true).'</pre>@@@@<br />';
		if (!empty($lastOutput)) {
			$debugInfo .= '@@@@output:<pre>'.htmlentities($lastOutput, ENT_QUOTES).'</pre>@@@@';
		}
		
		if (class_exists('LdDatabase', false) && !empty(LdDatabase::$pool)) {
			foreach(LdDatabase::$pool as $dbh) {
				$debugInfo .= $dbh->dsn() .'<br>'. $dbh->debug();
			}
		}

		$debugInfo .= '<h2>GET:</h2><pre>'.(!empty($_GET)?var_export($_GET, true):'').'</pre>';
		$debugInfo .= '<h2>POST:</h2><pre>'.(!empty($_POST)?var_export($_POST, true):'').'</pre>';
		$debugInfo .= '<h2>COOKIE:</h2><pre>'.(!empty($_COOKIE)?var_export($_COOKIE, true):'').'</pre>';
		$debugInfo .= '<h2>SESSION:</h2><pre>'.(!empty($_SESSION)?var_export($_SESSION, true):'').'</pre>';
		$debugInfo .= '<h2>FILES:</h2><pre>'.(!empty($_FILES)?var_export($_FILES, true):'').'</pre>';
		$debugInfo .= '<h2>SERVER:</h2><pre>'.(!empty($_SERVER)?var_export($_SERVER, true):'').'</pre>';
		$debugInfo .= '<h2>ENV:</h2><pre>'.(!empty($_ENV)?var_export($_ENV, true):'').'</pre>';
		$debugInfo = str_replace('<?', '&lt;?', $debugInfo);
		$debugInfo = str_replace('?>', '&gt;?', $debugInfo);
		
		$debugFile = LD_UPLOAD_PATH.'/debug_console.php';
		$debugUrl = LD_UPLOAD_URL.'/debug_console.php';
		

		if(file_exists("config.php")){
			$prefix = '<?php include_once("../config.php");';
		}else{
			$prefix = '<?php include_once("../config.inc.php");';
		}
		$prefix .= 'if (DEBUG) : '.
					'if(@$_GET["clear"]) {'.
						'file_put_contents("'.$debugFile.'", ""); '.
						'header("location:'.$debugUrl.'");'.
			  		'}	?>';
		$postfix = '<?php endif; ?>';
		
		$oldDebuginfo = file_get_contents($debugFile);
		
		$oldDebuginfo = str_replace($prefix, '', $oldDebuginfo);
		$oldDebuginfo = str_replace($postfix, '', $oldDebuginfo);
		
		$delimiter = '<br><br><br><br><br>=========================================================================================================================';
		$oldDebuginfo = $debugInfo . $delimiter . $oldDebuginfo;
		$arr = explode($delimiter, $oldDebuginfo);
		
		$cnt = count($arr);

		if ($cnt > 5) unset($arr[5]);
		
		$debugInfo = implode($delimiter, $arr);

		$debugOutput = $prefix.$debugInfo.$postfix;
		file_put_contents($debugFile, $debugOutput);
	}
}