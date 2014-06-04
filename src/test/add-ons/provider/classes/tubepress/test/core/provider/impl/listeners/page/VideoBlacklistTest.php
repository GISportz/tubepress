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
 * @covers tubepress_core_provider_impl_listeners_page_Blacklister
 */
class tubepress_test_core_provider_impl_listeners_page_VideoBlacklistTest extends tubepress_test_TubePressUnitTest
{
    /**
     * @var tubepress_core_provider_impl_listeners_page_Blacklister
     */
    private $_sut;

    /**
     * @var ehough_mockery_mockery_MockInterface
     */
    private $_mockExecutionContext;

    /**
     * @var ehough_mockery_mockery_MockInterface
     */
    private $_mockLogger;

    public function onSetup()
    {
        $this->_mockExecutionContext = $this->mock(tubepress_core_options_api_ContextInterface::_);
        $this->_mockLogger           = $this->mock(tubepress_api_log_LoggerInterface::_);


        $this->_sut = new tubepress_core_provider_impl_listeners_page_Blacklister($this->_mockLogger, $this->_mockExecutionContext);
    }

    public function testYouTubeFavorites()
    {
        $this->_mockLogger->shouldReceive('isEnabled')->once()->andReturn(true);
        $this->_mockLogger->shouldReceive('debug')->atLeast(1);
        $this->_mockExecutionContext->shouldReceive('get')->once()->with(tubepress_core_provider_api_Constants::OPTION_VIDEO_BLACKLIST)->andReturn('xxx');

        $mockVideoProvider = $this->mock(tubepress_core_provider_api_MediaProviderInterface::_);
        $mockVideoProvider->shouldReceive('getAttributeNameOfItemId')->atLeast(1)->andReturn('id');

        $mockVideo1 = new tubepress_core_provider_api_MediaItem();
        $mockVideo1->setAttribute(tubepress_core_provider_api_Constants::ATTRIBUTE_PROVIDER, $mockVideoProvider);
        $mockVideo1->setAttribute('id', 'p');

        $mockVideo2 = new tubepress_core_provider_api_MediaItem();
        $mockVideo2->setAttribute(tubepress_core_provider_api_Constants::ATTRIBUTE_PROVIDER, $mockVideoProvider);
        $mockVideo2->setAttribute('id', 'y');

        $mockVideo3 = new tubepress_core_provider_api_MediaItem();
        $mockVideo3->setAttribute(tubepress_core_provider_api_Constants::ATTRIBUTE_PROVIDER, $mockVideoProvider);
        $mockVideo3->setAttribute('id', 'xxx');

        $mockVideo4 = new tubepress_core_provider_api_MediaItem();
        $mockVideo4->setAttribute(tubepress_core_provider_api_Constants::ATTRIBUTE_PROVIDER, $mockVideoProvider);
        $mockVideo4->setAttribute('id', 'yyy');

        $videoArray = array($mockVideo1, $mockVideo2, $mockVideo3, $mockVideo4);

        $providerResult = new tubepress_core_provider_api_Page();
        $providerResult->setItems($videoArray);

        $event = $this->mock('tubepress_core_event_api_EventInterface');
        $event->shouldReceive('getSubject')->times(2)->andReturn($providerResult);

        $this->_sut->onVideoGalleryPage($event);

        $this->assertEquals(array($mockVideo1, $mockVideo2, $mockVideo4), $providerResult->getItems());
    }

}

