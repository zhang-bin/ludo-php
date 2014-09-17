<?php
class Session {
    /**
     * Flash a key / value pair to the session.
     *
     * @param string $key
     * @param string $value
     * @return void
     */
    public static function flash($key, $value) {
        $_SESSION[$key] = $value;
        $_SESSION['flash.new'][] = $key;
    }

    /**
     * Reflash all of the session flash data.
     *
     * @return void
     */
    public static function reflash() {
        $new = array_merge($_SESSION['flash.old'], $_SESSION['flash.new']);
        $_SESSION['flash.new'] = array_unique($new);
        $_SESSION['flash.old'] = array();
    }

    /**
     * Reflash a subset of the current flash data.
     *
     * @param sting $key
     * @return void
     */
    public static function keep($key) {
        $new = $_SESSION['flash.new'];
        $_SESSION['flash.new'] = array_unique(array_merge($new, (array)$key));
        $old = $_SESSION['flash.old'];
        $_SESSION['flash.old'] = array_diff($old, (array)$key);
    }

    /**
     * Age the flash data for the session.
     *
     * @return void
     */
    public static function ageFlashData() {
        foreach (array_get($_SESSION, 'flash.old', array()) as $old) {
            array_forget($_SESSION, $old);
        }
        $new = array_get($_SESSION, 'flash.new', '');
        $_SESSION['flash.old'] = $new;
        $_SESSION['flash.new'] = array();
    }
}