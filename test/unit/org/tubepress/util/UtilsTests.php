<?php
require_once '/Applications/MAMP/bin/php5/lib/php/PHPUnit/Framework.php';
require_once 'StringUtilsTest.php';

class UtilsTests
{
	public static function suite()
	{
		$suite = new PHPUnit_Framework_TestSuite("TubePress Utility Tests");
		$suite->addTestSuite('org_tubepress_util_StringUtilsTest');
		return $suite;
	}
}
?>