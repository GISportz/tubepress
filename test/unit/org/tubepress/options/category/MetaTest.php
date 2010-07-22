<?php
require_once dirname(__FILE__) . '/../../../../../../classes/org/tubepress/options/category/Meta.class.php';
class org_tubepress_options_category_MetaTest extends PHPUnit_Framework_TestCase {
    
    private $_expectedNames;
	private $_actualNames;
	private $_sut;
	
	public function setup()
	{
		$this->_expectedNames = array(
			'author', 'category', 'description', 'id', 'length', 'rating', 
			'ratings', 'tags', 'title', 'uploaded', 'url', 'views', 'likes'
    	);
    	$class = new ReflectionClass('org_tubepress_options_category_Meta');    
        $this->_actualNames = $class->getConstants();
        $this->_sut = new org_tubepress_options_category_Meta();
	}

	
	public function testHasRightOptionNames()
	{
		foreach ($this->_expectedNames as $expectedName) {
			$this->assertTrue(in_array($expectedName, $this->_actualNames));
		}
	}
	
	public function testHasRightNumberOfOptions()
	{
		$this->assertEquals(sizeof($this->_expectedNames), sizeof($this->_actualNames));
	}    
}
?>