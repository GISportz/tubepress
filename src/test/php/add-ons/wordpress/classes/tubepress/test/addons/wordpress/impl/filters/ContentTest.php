<?php
/**
 * Copyright 2006 - 2014 TubePress LLC (http://tubepress.com)
 *
 * This file is part of TubePress (http://tubepress.com)
 *
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/.
 */

/**
 * @covers tubepress_addons_wordpress_impl_filters_Content
 */
class tubepress_test_addons_wordpress_impl_filters_ContentTest extends tubepress_test_TubePressUnitTest
{
    /**
     * @var tubepress_addons_wordpress_impl_filters_Content
     */
    private $_sut;

    /**
     * @var ehough_mockery_mockery_MockInterface
     */
    private $_mockStorageManager;

    /**
     * @var ehough_mockery_mockery_MockInterface
     */
    private $_mockShortcodeParser;

    /**
     * @var ehough_mockery_mockery_MockInterface
     */
    private $_mockShortcodeHtmlGenerator;

    /**
     * @var ehough_mockery_mockery_MockInterface
     */
    private $_mockExecutionContext;

    /**
     * @var ehough_mockery_mockery_MockInterface
     */
    private $_mockMessageService;

    /**
     * @var ehough_mockery_mockery_MockInterface
     */
    private $_mockEventDispatcher;

    public function onSetup()
    {

        $this->_mockExecutionContext       = ehough_mockery_Mockery::mock(tubepress_api_options_ContextInterface::_);
        $this->_mockMessageService         = $this->createMockSingletonService(tubepress_api_translation_TranslatorInterface::_);
        $this->_mockShortcodeHtmlGenerator = $this->createMockSingletonService(tubepress_api_html_HtmlGeneratorInterface::_);
        $this->_mockShortcodeParser        = $this->createMockSingletonService(tubepress_api_shortcode_ParserInterface::_);
        $this->_mockStorageManager         = $this->createMockSingletonService(tubepress_api_options_PersistenceInterface::_);
        $this->_mockEventDispatcher        = $this->createMockSingletonService(tubepress_api_event_EventDispatcherInterface::_);
        $this->_sut = new tubepress_addons_wordpress_impl_filters_Content(
            $this->_mockExecutionContext,
            $this->_mockStorageManager,
            $this->_mockShortcodeHtmlGenerator,
            $this->_mockShortcodeParser
        );
    }

    public function testNormalOperation()
    {
        $this->_mockStorageManager->shouldReceive('fetch')->once()->with(tubepress_api_const_options_names_Advanced::KEYWORD)->andReturn('trigger word');

        $this->_mockShortcodeParser->shouldReceive('somethingToParse')->times(2)->with('the content', 'trigger word')->andReturn(true);
        $this->_mockShortcodeParser->shouldReceive('somethingToParse')->times(2)->with('html for shortcode', 'trigger word')->andReturn(true, false);
        $this->_mockShortcodeParser->shouldReceive('getLastShortcodeUsed')->times(4)->andReturn('<current shortcode>');

        $this->_mockShortcodeHtmlGenerator->shouldReceive('getHtmlForShortcode')->once()->with('the content')->andReturn('html for shortcode');
        $this->_mockShortcodeHtmlGenerator->shouldReceive('getHtmlForShortcode')->once()->with('html for shortcode')->andReturn('html for shortcode');

        $this->_mockExecutionContext->shouldReceive('setAll')->twice()->with(array());

        $mockEvent = ehough_mockery_Mockery::mock('tubepress_api_event_EventInterface');
        $mockEvent->shouldReceive('getSubject')->once()->andReturn('the content');
        $mockEvent->shouldReceive('setSubject')->once()->with('html for shortcode');

        $this->_sut->filter($mockEvent);

        $this->assertTrue(true);
    }

    public function testErrorCondition()
    {
        $this->_mockStorageManager->shouldReceive('fetch')->once()->with(tubepress_api_const_options_names_Advanced::KEYWORD)->andReturn('trigger word');

        $this->_mockShortcodeParser->shouldReceive('somethingToParse')->times(2)->with('the content', 'trigger word')->andReturn(true);
        $this->_mockShortcodeParser->shouldReceive('somethingToParse')->once()->with('something bad happened', 'trigger word')->andReturn(false);
        $this->_mockShortcodeParser->shouldReceive('getLastShortcodeUsed')->times(2)->andReturn('<current shortcode>');

        $exception = new Exception('something bad happened');

        $this->_mockShortcodeHtmlGenerator->shouldReceive('getHtmlForShortcode')->once()->with('the content')->andThrow($exception);

        $this->_mockExecutionContext->shouldReceive('setAll')->once()->with(array());

        $this->_mockEventDispatcher->shouldReceive('dispatch')->once()->with(tubepress_api_const_event_EventNames::ERROR_EXCEPTION_CAUGHT, ehough_mockery_Mockery::on(function ($event) use ($exception) {

            return $event instanceof tubepress_api_event_EventInterface && $event->getArgument('message') === $exception->getMessage()
                && $event->getSubject() instanceof Exception;
        }));

        $mockEvent = ehough_mockery_Mockery::mock('tubepress_api_event_EventInterface');
        $mockEvent->shouldReceive('getSubject')->once()->andReturn('the content');
        $mockEvent->shouldReceive('setSubject')->once()->with('something bad happened');

        $this->_sut->filter($mockEvent);

        $this->assertTrue(true);
    }
}
