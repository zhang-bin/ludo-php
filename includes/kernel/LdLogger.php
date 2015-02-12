<?php
/*
 *
 * Usage:
 *		$log = new LdLogger(LdLogger::INFO);
 *		$log->info("Returned a million search results");	//Prints to the log file
 *		$log->fatal("Oh dear.");				//Prints to the log file
 *		$log->debug("x = 5");					//Prints nothing due to priority setting
*/

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\ChromePHPHandler;

class LdLogger {
	private $_logger;
	private static $_instance;

	public function __construct($level = Logger::DEBUG) {
		$this->_logger = new Logger('log');
		$this->_logger->pushHandler(new StreamHandler(SITE_ROOT.'/log/access.log', $level));
		$this->_logger->pushHandler(new ChromePHPHandler($level));
	}

	/**
	 * @return LdLogger
	 */
	public static function getInstance() {
		if (is_null(self::$_instance)) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * 记录LOGGER_LEVEL_INFO级别的日志
	 *
	 * @param string $info 日志内容
	 * @param array $context
	 * @param string $file 文件名
	 * @param string $line 行号
	 * @return LdLogger
	 */
	public function info($info, $context = array(), $file = '', $line = '') {
		if (!DEBUG) return;
		$info = $this->format($info, $file, $line);
		$this->_logger->addInfo($info, $context);
		return $this;
	}

	/**
	 * 记录LOGGER_LEVEL_DEBUG级别的日志
	 *
	 * @param mixed $info 日志内容
	 * @param array $context
	 * @param string $file 文件名
	 * @param string $line 行号
	 * @return LdLogger
	 */
	public function debug($info, $context = array(), $file = '', $line = '') {
		if (!DEBUG) return;
		$info = $this->format($info, $file, $line);
		$this->_logger->addDebug($info, $context);
		return $this;
	}

	/**
	 * 记录LOGGER_LEVEL_WARN级别的日志
	 *
	 * @param mixed $info 日志内容
	 * @param array $context
	 * @param string $file 文件名
	 * @param string $line 行号
	 * @return LdLogger
	 */
	public function warn($info, $context = array(), $file = '', $line = '') {
		if (!DEBUG) return;
		$info = $this->format($info, $file, $line);
		$this->_logger->addWarning($info, $context);
		return $this;
	}

	/**
	 * 记录LOGGER_LEVEL_ERROR级别的日志
	 *
	 * @param mixed $info 日志内容
	 * @param array $context
	 * @param string $file 文件名
	 * @param string $line 行号
	 * @return LdLogger
	 */
	public function error($info, $context = array(), $file = '', $line = '') {
		if (!DEBUG) return;
		$info = $this->format($info, $file, $line);
		$this->_logger->addError($info, $context);
		return $this;
	}

	/**
	 * 记录LOGGER_LEVEL_FATAL级别的日志
	 *
	 * @param mixed $info 日志内容
	 * @param array $context
	 * @param string $file 文件名
	 * @param string $line 行号
	 * @return LdLogger
	 */
	public function fatal($info, $context = array(), $file = '', $line = '') {
		if (!DEBUG) return;
		$info = $this->format($info, $file, $line);
		$this->_logger->addCritical($info, $context);
		return $this;
	}

	/**
	 * 记录日志
	 *
	 * @param mixed $info 日志内容
	 * @param string $file
	 * @param string $line
	 * @return string $log
	 */
	public function format($info, $file, $line) {
		is_array($info) && $info = json_encode($info);
		$log = $info;
		if (!empty($file)) $log .= " in $file on line $line";
		return $log;
	}
}
