<?php
// $Id: decorator_tests.php,v 1.1 2005/12/25 21:50:40 ryu Exp $

require_once('simple_include.php');
require_once('calendar_include.php');

class DecoratorTests extends GroupTest {
    function DecoratorTests() {
        $this->GroupTest('Decorator Tests');
        $this->addTestFile('decorator_test.php');
        $this->addTestFile('decorator_textual_test.php');
        $this->addTestFile('decorator_uri_test.php');
    }
}

if (!defined('TEST_RUNNING')) {
    define('TEST_RUNNING', true);
    $test = &new DecoratorTests();
    $test->run(new HtmlReporter());
}
?>