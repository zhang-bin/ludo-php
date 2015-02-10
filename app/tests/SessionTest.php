<?php
require 'app/helpers/Session.php';
class SessionTest extends PHPUnit_Framework_TestCase {

    public function testSessionFlash() {
        Session::flash('m', 'n');
        $this->assertArrayHasKey('m', $_SESSION, 'flash error');
    }
}