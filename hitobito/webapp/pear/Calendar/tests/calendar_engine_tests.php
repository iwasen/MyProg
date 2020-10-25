<?php
// $Id: calendar_engine_tests.php,v 1.1 2005/12/25 21:50:40 ryu Exp $

require_once('simple_include.php');
require_once('calendar_include.php');

class CalendarEngineTests extends GroupTest {
    function CalendarEngineTests() {
        $this->GroupTest('Calendar Engine Tests');
        $this->addTestFile('peardate_engine_test.php');
        $this->addTestFile('unixts_engine_test.php');
    }
}

if (!defined('TEST_RUNNING')) {
    define('TEST_RUNNING', true);
    $test = &new CalendarEngineTests();
    $test->run(new HtmlReporter());
}
?>