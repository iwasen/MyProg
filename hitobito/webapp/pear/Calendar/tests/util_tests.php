<?php
// $Id: util_tests.php,v 1.1 2005/12/25 21:50:40 ryu Exp $

require_once('simple_include.php');
require_once('calendar_include.php');

class UtilTests extends GroupTest {
    function UtilTests() {
        $this->GroupTest('Util Tests');
        $this->addTestFile('util_uri_test.php');
        $this->addTestFile('util_textual_test.php');
    }
}

if (!defined('TEST_RUNNING')) {
    define('TEST_RUNNING', true);
    $test = &new UtilTests();
    $test->run(new HtmlReporter());
}
?>