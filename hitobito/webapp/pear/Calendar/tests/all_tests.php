<?php
// $Id: all_tests.php,v 1.1 2005/12/25 21:50:40 ryu Exp $

require_once('simple_include.php');
require_once('calendar_include.php');

define("TEST_RUNNING", true);

require_once('./calendar_tests.php');
require_once('./calendar_tabular_tests.php');
require_once('./validator_tests.php');
require_once('./calendar_engine_tests.php');
require_once('./calendar_engine_tests.php');
require_once('./table_helper_tests.php');
require_once('./decorator_tests.php');
require_once('./util_tests.php');


class AllTests extends GroupTest {
    function AllTests() {
        $this->GroupTest('All PEAR::Calendar Tests');
        $this->AddTexxxase(new CalendarTests());
        $this->AddTexxxase(new CalendarTabularTests());
        $this->AddTexxxase(new ValidatorTests());
        $this->AddTexxxase(new CalendarEngineTests());
        $this->AddTexxxase(new TableHelperTests());
        $this->AddTexxxase(new DecoratorTests());
        $this->AddTexxxase(new UtilTests());
    }
}

$test = &new AllTests();
$test->run(new HtmlReporter());
?>