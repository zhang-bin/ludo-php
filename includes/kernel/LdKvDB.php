<?php
class LdKvDB {
    /**
     * @var Redis
     */
    private $_db = null;

    function __construct() {
        $this->_db = LdKernel::getInstance()->getKvDBHandler();
    }

    /**
     * add key-value pair if key doesn't exist in the database
     *
     * @param string $key
     * @param string $value
     * @param int $ttl timeout
     * @return bool TRUE in case of success, FALSE in case of failure or key exist.
     */
    function add($key, $value, $ttl = 0) {
        if ($this->_db->exists($key)) return false;
        return $this->set($key, $value, $ttl);
    }

    /**
     * set the string value in argument as value of the key
     *
     * @param string $key
     * @param string $value
     * @param int $ttl timeout
     * @return bool TRUE if the command is successful.
     */
    function set($key, $value, $ttl = 0) {
        if (empty($ttl)) {
            return $this->_db->set($key, $value);
        } else {
            return $this->_db->setex($key, $ttl, $value);
        }
    }

    /**
     * Get the value related to the specified key
     *
     * @param $key
     * @return bool|string If key didn't exist, FALSE is returned. Otherwise, the value related to this key is returned.
     */
    function get($key) {
        return $this->_db->get($key);
    }

    /**
     * set the string value in argument as value of the key if key exist in the database
     *
     * @param string $key
     * @param string $value
     * @param int $ttl timeout
     * @return bool If key didn't exist, FALSE is returned. Otherwise TRUE is the command is successful
     */
    function replace($key, $value, $ttl = 0) {
        if (!$this->_db->exists($key)) return false;
        return $this->set($key, $value, $ttl);
    }

    /**
     * Remove specified keys.
     *
     * @param string $key
     * @return int Number of keys deleted.
     */
    function delete($key) {
        return $this->delete($key);
    }

    /**
     * Get the values of all the specified keys. If one or more keys don't exist, the array will contain FALSE at the
     * position of the key.
     *
     * @param array $keys containing the list of the keys
     * @return array containing the values related to keys in argument
     */
    function mGet(array $keys) {
        return $this->_db->getMultiple($keys);
    }

    /**
     * set a timeout on an item
     *
     * @param string $key
     * @param int $ttl
     * @return bool If key didn't exist or command failure, FALSE is returned. Otherwise TRUE in case of success.
     */
    function ttl($key, $ttl) {
        if (!$this->_db->exists($key)) return false;
        return $this->_db->setTimeout($key, $ttl);
    }

    /**
     * set a timeout timestamp on an item
     *
     * @param string $key
     * @param int $timestamp
     * @return bool If key didn't exist or command failure, FALSE is returned. Otherwise TRUE in case of success.
     */
    function expireAt($key, $timestamp) {
        if (!$this->_db->exists($key)) return false;
        return $this->_db->expireAt($key, $timestamp);
    }

    /**
     * get last error
     *
     * @return string
     */
    function error() {
        return $this->_db->getLastError();
    }

    /**
     * Switches to a given database.
     *
     * @param int $dbIndex
     * @return TRUE in case of success, FALSE in case of failure.
     */
    function select($dbIndex) {
        return $this->_db->select($dbIndex);
    }
}