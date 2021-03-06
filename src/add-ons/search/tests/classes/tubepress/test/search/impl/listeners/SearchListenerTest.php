<?php
/**
 * Copyright 2006 - 2018 TubePress LLC (http://tubepress.com)
 *
 * This file is part of TubePress (http://tubepress.com)
 *
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/.
 */

/**
 * @covers tubepress_search_impl_listeners_SearchListener
 */
class tubepress_test_search_impl_listeners_SearchListenerTest extends tubepress_api_test_TubePressUnitTest
{
    /**
     * @var tubepress_search_impl_listeners_SearchListener
     */
    private $_sut;

    /**
     * @var Mockery\MockInterface
     */
    private $_mockEvent;

    /**
     * @var Mockery\MockInterface
     */
    private $_mockMediaProvider1;

    /**
     * @var Mockery\MockInterface
     */
    private $_mockMediaProvider2;

    /**
     * @var Mockery\MockInterface
     */
    private $_mockExecutionContext;

    /**
     * @var Mockery\MockInterface
     */
    private $_mockTemplating;

    /**
     * @var Mockery\MockInterface
     */
    private $_mockLogger;

    /**
     * @var Mockery\MockInterface
     */
    private $_mockRequestParams;

    public function onSetup()
    {
        $this->_mockEvent            = $this->mock('tubepress_api_event_EventInterface');
        $this->_mockMediaProvider1   = $this->mock(tubepress_spi_media_MediaProviderInterface::__);
        $this->_mockMediaProvider2   = $this->mock(tubepress_spi_media_MediaProviderInterface::__);
        $this->_mockExecutionContext = $this->mock(tubepress_api_options_ContextInterface::_);
        $this->_mockTemplating       = $this->mock(tubepress_api_template_TemplatingInterface::_);
        $this->_mockLogger           = $this->mock(tubepress_api_log_LoggerInterface::_);
        $this->_mockRequestParams    = $this->mock(tubepress_api_http_RequestParametersInterface::_);

        $this->_sut = new tubepress_search_impl_listeners_SearchListener(
            $this->_mockLogger,
            $this->_mockExecutionContext,
            $this->_mockTemplating,
            $this->_mockRequestParams
        );

        $this->_sut->setMediaProviders(array($this->_mockMediaProvider1, $this->_mockMediaProvider2));
    }

    public function testSearchOutput()
    {
        $this->_mockLogger->shouldReceive('isEnabled')->atLeast(1)->andReturn(true);
        $this->_mockLogger->shouldReceive('debug')->once()->with('(Search Listener) User is searching. We\'ll handle this.');
        $this->_mockExecutionContext->shouldReceive('get')->once()->with(tubepress_api_options_Names::HTML_OUTPUT)->andReturn(tubepress_api_options_AcceptableValues::OUTPUT_SEARCH_RESULTS);
        $this->_mockExecutionContext->shouldReceive('get')->once()->with(tubepress_api_options_Names::SEARCH_RESULTS_ONLY)->andReturn(true);
        $this->_mockExecutionContext->shouldReceive('get')->once()->with(tubepress_api_options_Names::SEARCH_PROVIDER)->andReturn('provider2');
        $this->_mockRequestParams->shouldReceive('getParamValue')->twice()->with('tubepress_search')->andReturn('search terms');

        $this->_mockMediaProvider1->shouldReceive('getName')->once()->andReturn('provider1');
        $this->_mockMediaProvider2->shouldReceive('getName')->once()->andReturn('provider2');
        $this->_mockMediaProvider2->shouldReceive('getSearchModeName')->once()->andReturn('provider 2 search mode');
        $this->_mockMediaProvider2->shouldReceive('getSearchQueryOptionName')->once()->andReturn('provider 2 search query option');

        $this->_mockExecutionContext->shouldReceive('setEphemeralOption')->once()->with(tubepress_api_options_Names::GALLERY_SOURCE, 'provider 2 search mode');
        $this->_mockExecutionContext->shouldReceive('setEphemeralOption')->once()->with('provider 2 search query option', 'search terms');

        $this->_sut->onHtmlGenerationSearchOutput($this->_mockEvent);
    }

    public function testSearchOutputNotSearchingMustShow()
    {
        $this->_mockLogger->shouldReceive('isEnabled')->atLeast(1)->andReturn(true);
        $this->_mockLogger->shouldReceive('debug')->once()->with('(Search Listener) User doesn\'t appear to be searching. Will not display anything.');
        $this->_mockExecutionContext->shouldReceive('get')->once()->with(tubepress_api_options_Names::HTML_OUTPUT)->andReturn(tubepress_api_options_AcceptableValues::OUTPUT_SEARCH_RESULTS);
        $this->_mockExecutionContext->shouldReceive('get')->once()->with(tubepress_api_options_Names::SEARCH_RESULTS_ONLY)->andReturn(true);
        $this->_mockRequestParams->shouldReceive('getParamValue')->twice()->with('tubepress_search')->andReturnNull();

        $this->_mockEvent->shouldReceive('setSubject')->once()->with('');
        $this->_mockEvent->shouldReceive('stopPropagation')->once();

        $this->_sut->onHtmlGenerationSearchOutput($this->_mockEvent);
    }

    public function testSearchOutputNotSearchingMayShow()
    {
        $this->_mockLogger->shouldReceive('isEnabled')->once()->andReturn(true);
        $this->_mockLogger->shouldReceive('debug')->once()->with('(Search Listener) The user isn\'t searching.');
        $this->_mockExecutionContext->shouldReceive('get')->once()->with(tubepress_api_options_Names::HTML_OUTPUT)->andReturn(tubepress_api_options_AcceptableValues::OUTPUT_SEARCH_RESULTS);
        $this->_mockExecutionContext->shouldReceive('get')->once()->with(tubepress_api_options_Names::SEARCH_RESULTS_ONLY)->andReturn(false);
        $this->_mockRequestParams->shouldReceive('getParamValue')->once()->with('tubepress_search')->andReturnNull();

        $this->_sut->onHtmlGenerationSearchOutput($this->_mockEvent);
    }

    public function testSearchOutputNotConfigured()
    {
        $this->_mockLogger->shouldReceive('isEnabled')->once()->andReturn(true);
        $this->_mockLogger->shouldReceive('debug')->once()->with('(Search Listener) Not configured for search results');
        $this->_mockExecutionContext->shouldReceive('get')->once()->with(tubepress_api_options_Names::HTML_OUTPUT)->andReturn('foo');


        $this->_sut->onHtmlGenerationSearchOutput($this->_mockEvent);
    }

    public function testNoSearchInput()
    {
        $this->_mockExecutionContext->shouldReceive('get')->once()->with(tubepress_api_options_Names::HTML_OUTPUT)->andReturn(tubepress_api_options_AcceptableValues::OUTPUT_SEARCH_RESULTS);

        $this->_sut->onHtmlGenerationSearchInput($this->_mockEvent);

        $this->assertTrue(true);
    }

    public function testSearchInput()
    {
        $this->_mockExecutionContext->shouldReceive('get')->once()->with(tubepress_api_options_Names::HTML_OUTPUT)->andReturn(tubepress_api_options_AcceptableValues::OUTPUT_SEARCH_INPUT);

        $this->_mockTemplating->shouldReceive('renderTemplate')->once()->with('search/input', array())->andReturn('bla');

        $this->_mockEvent->shouldReceive('setSubject')->once()->with('bla');
        $this->_mockEvent->shouldReceive('stopPropagation');

        $this->_sut->onHtmlGenerationSearchInput($this->_mockEvent);

        $this->assertTrue(true);
    }

    public function testAcceptableValues()
    {
        $this->_mockMediaProvider1->shouldReceive('getName')->once()->andReturn('provider-1');
        $this->_mockMediaProvider2->shouldReceive('getName')->once()->andReturn('provider-2');
        $this->_mockMediaProvider1->shouldReceive('getDisplayName')->once()->andReturn('provider 1');
        $this->_mockMediaProvider2->shouldReceive('getDisplayName')->once()->andReturn('provider 2');

        $this->_mockEvent->shouldReceive('getSubject')->once()->andReturnNull();
        $this->_mockEvent->shouldReceive('setSubject')->once()->with(array(
            'provider-1' => 'provider 1',
            'provider-2' => 'provider 2',
        ));

        $this->_sut->onAcceptableValues($this->_mockEvent);

        $this->assertTrue(true);
    }
}