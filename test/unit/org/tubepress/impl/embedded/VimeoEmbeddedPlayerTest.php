<?php

require_once dirname(__FILE__) . '/../../../../../../classes/org/tubepress/impl/embedded/VimeoEmbeddedPlayer.class.php';
require_once dirname(__FILE__) . '/../../../../TubePressUnitTest.php';

class org_tubepress_impl_embedded_VimeoEmbeddedPlayerTest extends TubePressUnitTest {

    private $_sut;
    
    function setUp()
    {
        $this->initFakeIoc();
        $this->_sut = new org_tubepress_impl_embedded_VimeoEmbeddedPlayer();
    }
    
    function testToString()
    {
        $this->assertEquals($this->expected(), $this->_sut->toString('FAKEID'));
    }
    
    function expected()
    {
        return <<<EOT
<iframe src="http://player.vimeo.com/video/FAKEID?autoplay=0&amp;color=999999&amp;loop=0&amp;title=0&amp;byline=0&amp;portrait=0" width="425" height="350" frameborder="0"></iframe>

EOT;
    }

}
?>