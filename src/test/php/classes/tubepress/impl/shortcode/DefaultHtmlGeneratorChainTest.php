<?php
/**
 * Copyright 2006 - 2013 TubePress LLC (http://tubepress.org)
 *
 * This file is part of TubePress (http://tubepress.org)
 *
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/.
 */
class tubepress_impl_shortcode_DefaultHtmlGeneratorChainTest extends TubePressUnitTest
{
    /**
     * @var tubepress_impl_shortcode_DefaultShortcodeHtmlGenerator
     */
    private $_sut;

    /**
     * @var ehough_mockery_mockery_MockInterface
     */
    private $_mockShortcodeParser;

    /**
     * @var ehough_mockery_mockery_MockInterface
     */
    private $_mockEventDispatcher;

    public function onSetup()
    {
        $this->_sut = new tubepress_impl_shortcode_DefaultShortcodeHtmlGenerator();

        $this->_mockShortcodeParser            = $this->createMockSingletonService(tubepress_spi_shortcode_ShortcodeParser::_);
        $this->_mockEventDispatcher            = $this->createMockSingletonService('ehough_tickertape_EventDispatcherInterface');

        $this->_mockShortcodeParser->shouldReceive('parse')->once()->with('shortcode');
    }

    public function testOneHandlerCouldHandle()
    {
        $mockHandler = $this->createMockPluggableService(tubepress_spi_shortcode_PluggableShortcodeHandlerService::_);
        $mockHandler->shouldReceive('shouldExecute')->once()->andReturn(true);
        $mockHandler->shouldReceive('getHtml')->once()->andReturn('foobar');

        $result = $this->_sut->getHtmlForShortcode('shortcode');

        $this->assertEquals('foobar', $result);
    }

    /**
     * @expectedException RuntimeException
     */
    public function testNoHandlersCouldHandle()
    {
        $mockHandler = $this->createMockPluggableService(tubepress_spi_shortcode_PluggableShortcodeHandlerService::_);
        $mockHandler->shouldReceive('shouldExecute')->once()->andReturn(false);

        $this->_sut->getHtmlForShortcode('shortcode');
    }

    /**
     * @expectedException RuntimeException
     */
    public function testNoHandlers()
    {
        $this->_sut->getHtmlForShortcode('shortcode');
    }

}