<?php
require_once dirname(__FILE__) . '/../../../includes/TubePressUnitTestSuite.php';
require_once 'ProviderResultTest.php';

class org_tubepress_api_provider_ProviderApiTestSuite
{
	public static function suite()
	{
		return new TubePressUnitTestSuite(array(
			'org_tubepress_api_provider_ProviderResultTest'
		));
	}
}