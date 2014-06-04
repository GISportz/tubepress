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
 * @covers tubepress_core_provider_ioc_CollectorExtension
 */
class tubepress_test_core_provider_ioc_CollectorExtensionTest extends tubepress_test_core_ioc_AbstractIocContainerExtensionTest
{
    /**
     * @return tubepress_core_provider_ioc_CollectorExtension
     */
    protected function buildSut()
    {
        return new tubepress_core_provider_ioc_CollectorExtension();
    }

    protected function prepareForLoad()
    {
        $this->expectRegistration(

            tubepress_core_provider_api_CollectorInterface::_,
            'tubepress_core_provider_impl_Collector'
        )->withArgument(new tubepress_api_ioc_Reference(tubepress_api_log_LoggerInterface::_))
            ->withArgument(new tubepress_api_ioc_Reference(tubepress_core_options_api_ContextInterface::_))
            ->withArgument(new tubepress_api_ioc_Reference(tubepress_core_event_api_EventDispatcherInterface::_))
            ->withArgument(new tubepress_api_ioc_Reference(tubepress_core_http_api_RequestParametersInterface::_));

        $this->expectRegistration(
            tubepress_core_provider_api_ItemSorterInterface::_,
            'tubepress_core_provider_impl_ItemSorter'
        );

        $this->expectRegistration(
            'tubepress_core_provider_impl_listeners_options_AcceptableValues',
            'tubepress_core_provider_impl_listeners_options_AcceptableValues'
        )->withTag(tubepress_core_ioc_api_Constants::TAG_TAGGED_SERVICES_CONSUMER, array(
                'tag' => 'tubepress_core_provider_api_MediaProviderInterface',
                'method' => 'setVideoProviders'
            ))->withTag(tubepress_core_ioc_api_Constants::TAG_EVENT_LISTENER, array(
                'event'    => tubepress_core_options_api_Constants::EVENT_OPTION_GET_ACCEPTABLE_VALUES . '.' . tubepress_core_provider_api_Constants::OPTION_ORDER_BY,
                'method'   => 'onOrderBy',
                'priority' => 10300
            ))->withTag(tubepress_core_ioc_api_Constants::TAG_EVENT_LISTENER, array(
                'event'    => tubepress_core_options_api_Constants::EVENT_OPTION_GET_ACCEPTABLE_VALUES . '.' . tubepress_core_media_gallery_api_Constants::OPTION_GALLERY_SOURCE,
                'method'   => 'onMode',
                'priority' => 10300
            ))->withTag(tubepress_core_ioc_api_Constants::TAG_EVENT_LISTENER, array(
                'event'    => tubepress_core_options_api_Constants::EVENT_OPTION_GET_ACCEPTABLE_VALUES . '.' . tubepress_core_provider_api_Constants::OPTION_PER_PAGE_SORT,
                'method'   => 'onPerPageSort',
                'priority' => 10300
            ));

        $this->expectRegistration(

            'tubepress_core_provider_impl_listeners_page_PerPageSorter',
            'tubepress_core_provider_impl_listeners_page_PerPageSorter'
        )->withArgument(new tubepress_api_ioc_Reference(tubepress_api_log_LoggerInterface::_))
            ->withArgument(new tubepress_api_ioc_Reference(tubepress_core_options_api_ContextInterface::_))
            ->withTag(tubepress_core_ioc_api_Constants::TAG_EVENT_LISTENER, array(
                'event'    => tubepress_core_provider_api_Constants::EVENT_NEW_MEDIA_PAGE,
                'method'   => 'onVideoGalleryPage',
                'priority' => 10300
            ));

        $this->expectRegistration(

            'tubepress_core_provider_impl_listeners_page_ResultCountCapper',
            'tubepress_core_provider_impl_listeners_page_ResultCountCapper'
        )->withArgument(new tubepress_api_ioc_Reference(tubepress_api_log_LoggerInterface::_))
            ->withArgument(new tubepress_api_ioc_Reference(tubepress_core_options_api_ContextInterface::_))
            ->withTag(tubepress_core_ioc_api_Constants::TAG_EVENT_LISTENER, array(
                'event'    => tubepress_core_provider_api_Constants::EVENT_NEW_MEDIA_PAGE,
                'method'   => 'onVideoGalleryPage',
                'priority' => 10100
            ));

        $this->expectRegistration(

            'tubepress_core_provider_impl_listeners_page_Blacklister',
            'tubepress_core_provider_impl_listeners_page_Blacklister'
        )->withArgument(new tubepress_api_ioc_Reference(tubepress_api_log_LoggerInterface::_))
            ->withArgument(new tubepress_api_ioc_Reference(tubepress_core_options_api_ContextInterface::_))
            ->withTag(tubepress_core_ioc_api_Constants::TAG_EVENT_LISTENER, array(
                'event'    => tubepress_core_provider_api_Constants::EVENT_NEW_MEDIA_PAGE,
                'method'   => 'onVideoGalleryPage',
                'priority' => 10200
            ));

        $this->expectRegistration(

            'tubepress_core_provider_impl_listeners_page_ItemPrepender',
            'tubepress_core_provider_impl_listeners_page_ItemPrepender'
        )->withArgument(new tubepress_api_ioc_Reference(tubepress_api_log_LoggerInterface::_))
            ->withArgument(new tubepress_api_ioc_Reference(tubepress_core_http_api_RequestParametersInterface::_))
            ->withArgument(new tubepress_api_ioc_Reference(tubepress_core_provider_api_CollectorInterface::_))
            ->withTag(tubepress_core_ioc_api_Constants::TAG_EVENT_LISTENER, array(
                'event' => tubepress_core_provider_api_Constants::EVENT_NEW_MEDIA_PAGE,
                'method' => 'onVideoGalleryPage',
                'priority' => 10000
            ));

        $this->expectParameter(tubepress_core_options_api_Constants::IOC_PARAM_EASY_REFERENCE . '_collector', array(

            'defaultValues' => array(

                tubepress_core_provider_api_Constants::OPTION_ORDER_BY         => 'default',
                tubepress_core_provider_api_Constants::OPTION_PER_PAGE_SORT    => tubepress_core_provider_api_Constants::PER_PAGE_SORT_NONE,
                tubepress_core_provider_api_Constants::OPTION_RESULT_COUNT_CAP => 0,
                tubepress_core_provider_api_Constants::OPTION_VIDEO_BLACKLIST  => null,
                tubepress_core_provider_api_Constants::OPTION_RESULTS_PER_PAGE => 20,
            ),

            'labels' => array(

                tubepress_core_provider_api_Constants::OPTION_ORDER_BY         => 'Order videos by',                    //>(translatable)<
                tubepress_core_provider_api_Constants::OPTION_PER_PAGE_SORT    => 'Per-page sort order',                //>(translatable)<
                tubepress_core_provider_api_Constants::OPTION_RESULT_COUNT_CAP => 'Maximum total videos to retrieve',   //>(translatable)<
                tubepress_core_provider_api_Constants::OPTION_VIDEO_BLACKLIST  => 'Video blacklist',                    //>(translatable)<
                tubepress_core_provider_api_Constants::OPTION_RESULTS_PER_PAGE => 'Thumbnails per page',                //>(translatable)<
            ),

            'descriptions' => array(

                tubepress_core_provider_api_Constants::OPTION_ORDER_BY         =>
                    sprintf('Not all sort orders can be applied to all gallery types. See the <a href="%s" target="_blank">documentation</a> for more info.', "http://docs.tubepress.com/page/reference/options/core.html#orderby"),  //>(translatable)<

                tubepress_core_provider_api_Constants::OPTION_PER_PAGE_SORT    =>
                    'Additional sort order applied to each individual page of a gallery',                           //>(translatable)<

                tubepress_core_provider_api_Constants::OPTION_RESULT_COUNT_CAP =>
                    'This can help to reduce the number of pages in your gallery. Set to "0" to remove any limit.', //>(translatable)<

                tubepress_core_provider_api_Constants::OPTION_VIDEO_BLACKLIST  =>
                    'A list of video IDs that should never be displayed.',                                          //>(translatable)<

                tubepress_core_provider_api_Constants::OPTION_RESULTS_PER_PAGE =>
                    sprintf('Default is %s. Maximum is %s.', 20, 50),                                               //>(translatable)<
            )
        ));

        $this->expectParameter(tubepress_core_options_api_Constants::IOC_PARAM_EASY_ACCEPTABLE_VALUES .
            '_' . tubepress_core_provider_api_Constants::OPTION_PER_PAGE_SORT, array(

                'optionName' => tubepress_core_provider_api_Constants::OPTION_PER_PAGE_SORT,
                'priority'   => 30000,
                'values'     => array(

                    tubepress_core_provider_api_Constants::PER_PAGE_SORT_NONE   => 'none',                          //>(translatable)<
                    tubepress_core_provider_api_Constants::PER_PAGE_SORT_RANDOM => 'random',                        //>(translatable)<
                )
            ));

        $this->expectParameter(tubepress_core_options_api_Constants::IOC_PARAM_EASY_VALIDATION . '_collector', array(

            'priority' => 30000,
            'map'      => array(
                'positiveInteger' => array(
                    tubepress_core_provider_api_Constants::OPTION_RESULTS_PER_PAGE,
                ),
                'nonNegativeInteger' => array(
                    tubepress_core_provider_api_Constants::OPTION_RESULT_COUNT_CAP,
                )
            )
        ));
    }

    protected function getExpectedExternalServicesMap()
    {
        $logger = $this->mock(tubepress_api_log_LoggerInterface::_);
        $logger->shouldReceive('isEnabled')->once()->andReturn(true);

        return array(

            tubepress_api_log_LoggerInterface::_                  => $logger,
            tubepress_core_options_api_ContextInterface::_        => tubepress_core_options_api_ContextInterface::_,
            tubepress_core_event_api_EventDispatcherInterface::_  => tubepress_core_event_api_EventDispatcherInterface::_,
            tubepress_core_http_api_RequestParametersInterface::_ => tubepress_core_http_api_RequestParametersInterface::_,
        );
    }
}