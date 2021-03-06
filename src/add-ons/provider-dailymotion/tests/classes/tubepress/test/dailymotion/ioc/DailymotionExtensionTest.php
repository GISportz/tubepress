<?php
/*
 * Copyright 2006 - 2018 TubePress LLC (http://tubepress.com)
 *
 * This file is part of TubePress (http://tubepress.com)
 *
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/.
 */

/**
 * @covers tubepress_dailymotion_ioc_DailymotionExtension
 */
class tubepress_test_dailymotion_ioc_DailymotionExtensionTest extends tubepress_api_test_ioc_AbstractContainerExtensionTest
{
    /**
     * @return tubepress_spi_ioc_ContainerExtensionInterface
     */
    protected function buildSut()
    {
        return new tubepress_dailymotion_ioc_DailymotionExtension();
    }

    protected function prepareForLoad()
    {
        $this->_registerApiServices();
        $this->_registerEmbedded();
        $this->_registerListeners();
        $this->_registerMediaProvider();
        $this->_registerOptionTransformers();
        $this->_registerOptions();
        $this->_registerOptionsUi();
        $this->_registerPlayer();
    }

    private function _registerApiServices()
    {
        $this->expectRegistration(
            'tubepress_dailymotion_impl_dmapi_ApiUtility',
            'tubepress_dailymotion_impl_dmapi_ApiUtility'
        )->withArgument(new tubepress_api_ioc_Reference(tubepress_api_log_LoggerInterface::_))
            ->withArgument(new tubepress_api_ioc_Reference(tubepress_api_options_ContextInterface::_))
            ->withArgument(new tubepress_api_ioc_Reference(tubepress_api_http_HttpClientInterface::_))
            ->withArgument(new tubepress_api_ioc_Reference(tubepress_api_array_ArrayReaderInterface::_));

        $this->expectRegistration(
            'tubepress_dailymotion_impl_dmapi_LanguageLocaleSupplier.languages',
            'tubepress_dailymotion_impl_dmapi_LanguageSupplier'
        )->withArgument(new tubepress_api_ioc_Reference(tubepress_api_url_UrlFactoryInterface::_))
            ->withArgument(new tubepress_api_ioc_Reference(tubepress_api_util_StringUtilsInterface::_))
            ->withArgument(new tubepress_api_ioc_Reference('tubepress_dailymotion_impl_dmapi_ApiUtility'))
            ->withArgument('https://api.dailymotion.com/languages')
            ->withArgument('code');

        $this->expectRegistration(
            'tubepress_dailymotion_impl_dmapi_LanguageLocaleSupplier.locales',
            'tubepress_dailymotion_impl_dmapi_LocaleSupplier'
        )->withArgument(new tubepress_api_ioc_Reference(tubepress_api_url_UrlFactoryInterface::_))
            ->withArgument(new tubepress_api_ioc_Reference(tubepress_api_util_StringUtilsInterface::_))
            ->withArgument(new tubepress_api_ioc_Reference('tubepress_dailymotion_impl_dmapi_ApiUtility'))
            ->withArgument('https://api.dailymotion.com/locales')
            ->withArgument('locale');
    }

    private function _registerEmbedded()
    {
        $this->expectRegistration(
            'tubepress_dailymotion_impl_embedded_DailymotionEmbeddedProvider',
            'tubepress_dailymotion_impl_embedded_DailymotionEmbeddedProvider'
        )->withArgument(new tubepress_api_ioc_Reference(tubepress_api_options_ContextInterface::_))
            ->withArgument(new tubepress_api_ioc_Reference(tubepress_api_util_LangUtilsInterface::_))
            ->withArgument(new tubepress_api_ioc_Reference(tubepress_api_url_UrlFactoryInterface::_))
            ->withTag('tubepress_spi_embedded_EmbeddedProviderInterface')
            ->withTag('tubepress_spi_template_PathProviderInterface');
    }

    private function _registerListeners()
    {
        $this->expectRegistration(
            'tubepress_dailymotion_impl_listeners_media_HttpItemListener',
            'tubepress_dailymotion_impl_listeners_media_HttpItemListener'
        )->withArgument(new tubepress_api_ioc_Reference(tubepress_api_media_AttributeFormatterInterface::_))
            ->withArgument(new tubepress_api_ioc_Reference(tubepress_api_options_ContextInterface::_))
            ->withArgument(new tubepress_api_ioc_Reference(tubepress_api_url_UrlFactoryInterface::_))
            ->withArgument(new tubepress_api_ioc_Reference(tubepress_api_array_ArrayReaderInterface::_))
            ->withTag(tubepress_api_ioc_ServiceTags::EVENT_LISTENER, array(
                'event'    => tubepress_api_event_Events::MEDIA_ITEM_HTTP_NEW . '.dailymotion',
                'method'   => 'onHttpItem',
                'priority' => 100000,
            ));

        $fixedValues = array(
            tubepress_dailymotion_api_Constants::OPTION_PLAYER_QUALITY => array(
                tubepress_dailymotion_api_Constants::PLAYER_QUALITY_AUTO => 'Auto',
                tubepress_dailymotion_api_Constants::PLAYER_QUALITY_2160 => '2160p',
                tubepress_dailymotion_api_Constants::PLAYER_QUALITY_1440 => '1440p',
                tubepress_dailymotion_api_Constants::PLAYER_QUALITY_1080 => '1080p',
                tubepress_dailymotion_api_Constants::PLAYER_QUALITY_720  => '720p',
                tubepress_dailymotion_api_Constants::PLAYER_QUALITY_480  => '480p',
                tubepress_dailymotion_api_Constants::PLAYER_QUALITY_380  => '380p',
                tubepress_dailymotion_api_Constants::PLAYER_QUALITY_240  => '240p',
            ),
            tubepress_dailymotion_api_Constants::OPTION_PLAYER_THEME => array(
                tubepress_dailymotion_api_Constants::PLAYER_THEME_LIGHT => 'Light',
                tubepress_dailymotion_api_Constants::PLAYER_THEME_DARK  => 'Dark',
            ),
            tubepress_dailymotion_api_Constants::OPTION_FEED_LIVE_FILTER => array(
                tubepress_dailymotion_api_Constants::FILTER_LIVE_ALL           => 'All videos',
                tubepress_dailymotion_api_Constants::FILTER_LIVE_LIVE_ONLY     => 'Live only',
                tubepress_dailymotion_api_Constants::FILTER_LIVE_NON_LIVE      => 'Non-live only',
                tubepress_dailymotion_api_Constants::FILTER_LIVE_LIVE_ON       => 'On-air live only',
                tubepress_dailymotion_api_Constants::FILTER_LIVE_LIVE_OFF      => 'Off-air live only',
                tubepress_dailymotion_api_Constants::FILTER_LIVE_LIVE_UPCOMING => 'Upcoming live only',
            ),
            tubepress_dailymotion_api_Constants::OPTION_FEED_PREMIUM_FILTER => array(
                tubepress_dailymotion_api_Constants::FILTER_PREMIUM_ALL              => 'All videos',
                tubepress_dailymotion_api_Constants::FILTER_PREMIUM_PREMIUM_ONLY     => 'Paid content only',
                tubepress_dailymotion_api_Constants::FILTER_PREMIUM_NON_PREMIUM_ONLY => 'Free content only',
            ),
            tubepress_dailymotion_api_Constants::OPTION_FEED_PARTNER_FILTER => array(
                tubepress_dailymotion_api_Constants::FILTER_PARTNER_ALL              => 'All videos',
                tubepress_dailymotion_api_Constants::FILTER_PARTNER_PARTNER_ONLY     => 'Partner videos only',
                tubepress_dailymotion_api_Constants::FILTER_PARTNER_NON_PARTNER_ONLY => 'User-generated videos only',
            ),
            tubepress_dailymotion_api_Constants::OPTION_THUMBS_RATIO => array(
                tubepress_dailymotion_api_Constants::THUMB_RATIO_ORIGINAL   => 'Original',
                tubepress_dailymotion_api_Constants::THUMB_RATIO_WIDESCREEN => 'Widescreen',
                tubepress_dailymotion_api_Constants::THUMB_RATIO_SQUARE     => 'Square',
            ),
            tubepress_dailymotion_api_Constants::OPTION_THUMB_SIZE => array(
                tubepress_dailymotion_api_Constants::THUMB_SIZE_MAX => 'Maximum',
                tubepress_dailymotion_api_Constants::THUMB_SIZE_720 => sprintf('%d pixels', 720),
                tubepress_dailymotion_api_Constants::THUMB_SIZE_480 => sprintf('%d pixels', 480),
                tubepress_dailymotion_api_Constants::THUMB_SIZE_360 => sprintf('%d pixels', 360),
                tubepress_dailymotion_api_Constants::THUMB_SIZE_240 => sprintf('%d pixels', 240),
                tubepress_dailymotion_api_Constants::THUMB_SIZE_180 => sprintf('%d pixels', 180),
                tubepress_dailymotion_api_Constants::THUMB_SIZE_120 => sprintf('%d pixels', 120),
                tubepress_dailymotion_api_Constants::THUMB_SIZE_60  => sprintf('%d pixels', 60),
            ),
        );

        foreach ($fixedValues as $optionName => $values) {

            $this->expectRegistration(
                "fixed_values.$optionName",
                'tubepress_api_options_listeners_FixedValuesListener'
            )->withArgument($values)
                ->withTag(tubepress_api_ioc_ServiceTags::EVENT_LISTENER, array(
                    'event'    => tubepress_api_event_Events::OPTION_ACCEPTABLE_VALUES . ".$optionName",
                    'priority' => 100000,
                    'method'   => 'onAcceptableValues',
                ));
        }

        $languageLocaleMap = array(
            tubepress_dailymotion_api_Constants::OPTION_FEED_LANGUAGE_DETECTED => 'languages',
            tubepress_dailymotion_api_Constants::OPTION_FEED_LOCALE            => 'locales',
        );

        foreach ($languageLocaleMap as $optionName => $suffix) {

            $this->expectRegistration(
                "fixed_values.$optionName",
                'tubepress_dailymotion_impl_listeners_options_LanguageLocaleListener'
            )->withArgument(new tubepress_api_ioc_Reference("tubepress_dailymotion_impl_dmapi_LanguageLocaleSupplier.$suffix"))
                ->withTag(tubepress_api_ioc_ServiceTags::EVENT_LISTENER, array(
                    'event'    => tubepress_api_event_Events::OPTION_ACCEPTABLE_VALUES . ".$optionName",
                    'priority' => 100000,
                    'method'   => 'onAcceptableValues',
                ));
        }

        $validators = array(
            tubepress_api_options_listeners_RegexValidatingListener::TYPE_INTEGER_NONNEGATIVE => array(
                tubepress_dailymotion_api_Constants::OPTION_FEED_LONGER_THAN,
                tubepress_dailymotion_api_Constants::OPTION_FEED_SHORTER_THAN,
            ),
            tubepress_api_options_listeners_RegexValidatingListener::TYPE_STRING_HEXCOLOR => array(
                tubepress_dailymotion_api_Constants::OPTION_PLAYER_COLOR,
            ),
        );

        foreach ($validators as $type => $optionNames) {
            foreach ($optionNames as $optionName) {

                $this->expectRegistration(
                    "regex_validation.$optionName",
                    'tubepress_api_options_listeners_RegexValidatingListener'
                )->withArgument($type)
                    ->withArgument(new tubepress_api_ioc_Reference(tubepress_api_options_ReferenceInterface::_))
                    ->withArgument(new tubepress_api_ioc_Reference(tubepress_api_translation_TranslatorInterface::_))
                    ->withTag(tubepress_api_ioc_ServiceTags::EVENT_LISTENER, array(
                        'event'    => tubepress_api_event_Events::OPTION_SET . ".$optionName",
                        'priority' => 100000,
                        'method'   => 'onOption',
                    ));
            }
        }

        $strlenValidators = array(
            tubepress_dailymotion_api_Constants::OPTION_SEARCH_VALUE,
            tubepress_dailymotion_api_Constants::OPTION_TAG_VALUE,
            tubepress_dailymotion_api_Constants::OPTION_FEED_SEARCH,
            tubepress_dailymotion_api_Constants::OPTION_FEED_TAGS,
            tubepress_dailymotion_api_Constants::OPTION_FEED_TAGS_STRONG,
            tubepress_dailymotion_api_Constants::OPTION_FEED_GENRE,
            tubepress_dailymotion_api_Constants::OPTION_FEED_NO_GENRE,
        );

        foreach ($strlenValidators as $optionName) {

            $this->expectRegistration(
                "strlen_validation.$optionName",
                'tubepress_api_options_listeners_PatternValidatingListener'
            )->withArgument('/^.{0,150}$/')
                ->withArgument('"%s" cannot be longer than 150 characters')
                ->withArgument(new tubepress_api_ioc_Reference(tubepress_api_options_ReferenceInterface::_))
                ->withArgument(new tubepress_api_ioc_Reference(tubepress_api_translation_TranslatorInterface::_))
                ->withTag(tubepress_api_ioc_ServiceTags::EVENT_LISTENER, array(
                    'event'    => tubepress_api_event_Events::OPTION_SET . ".$optionName",
                    'priority' => 100000,
                    'method'   => 'onOptionValidation',
                ));
        }

        $patternValidators = array(
            tubepress_api_options_listeners_RegexValidatingListener::TYPE_DOMAIN => '/^(?:(?!\-)(?:[a-zA-Z\d\-]{0,62}[a-zA-Z\d]\.){1,126}(?!\d+)[a-zA-Z\d]{1,63})?$/',

            tubepress_dailymotion_api_Constants::OPTION_PLAYER_ID => '/^(?:[a-z]+[a-z0-9\-_:\.]*)?$/i',

            tubepress_dailymotion_api_Constants::OPTION_FEED_COUNTRY => '/^(?:[A-Z]{2})?$/',
        );

        foreach ($patternValidators as $optionName => $pattern) {

            $this->expectRegistration(
                "pattern_validation.$optionName",
                'tubepress_api_options_listeners_PatternValidatingListener'
            )->withArgument($pattern)
                ->withArgument('Invalid value supplied for "%s".')
                ->withArgument(new tubepress_api_ioc_Reference(tubepress_api_options_ReferenceInterface::_))
                ->withArgument(new tubepress_api_ioc_Reference(tubepress_api_translation_TranslatorInterface::_))
                ->withTag(tubepress_api_ioc_ServiceTags::EVENT_LISTENER, array(
                    'event'    => tubepress_api_event_Events::OPTION_SET . ".$optionName",
                    'priority' => 100000,
                    'method'   => 'onOptionValidation',
                ));
        }

        $this->expectRegistration(
            'tubepress_dailymotion_impl_listeners_options_TransformListener__dmUser',
            'tubepress_dailymotion_impl_listeners_options_TransformListener'
        )->withArgument(new tubepress_api_ioc_Reference('tubepress_dailymotion_impl_listeners_options_transform_UserTransformer'))
            ->withArgument('Invalid Dailymotion user ID')
            ->withArgument(false)
            ->withTag(tubepress_api_ioc_ServiceTags::EVENT_LISTENER, array(
                'event'    => tubepress_api_event_Events::OPTION_SET . '.' . tubepress_dailymotion_api_Constants::OPTION_FAVORITES_VALUE,
                'method'   => 'onOption',
                'priority' => 100000,
            ))->withTag(tubepress_api_ioc_ServiceTags::EVENT_LISTENER, array(
                'event'    => tubepress_api_event_Events::OPTION_SET . '.' . tubepress_dailymotion_api_Constants::OPTION_FEATURED_VALUE,
                'method'   => 'onOption',
                'priority' => 100000,
            ))->withTag(tubepress_api_ioc_ServiceTags::EVENT_LISTENER, array(
                'event'    => tubepress_api_event_Events::OPTION_SET . '.' . tubepress_dailymotion_api_Constants::OPTION_USER_VALUE,
                'method'   => 'onOption',
                'priority' => 100000,
            ))->withTag(tubepress_api_ioc_ServiceTags::EVENT_LISTENER, array(
                'event'    => tubepress_api_event_Events::OPTION_SET . '.' . tubepress_dailymotion_api_Constants::OPTION_SUBSCRIPTIONS_VALUE,
                'method'   => 'onOption',
                'priority' => 100000,
            ));

        $this->expectRegistration(
            'tubepress_dailymotion_impl_listeners_options_TransformListener__video',
            'tubepress_dailymotion_impl_listeners_options_TransformListener'
        )->withArgument(new tubepress_api_ioc_Reference('tubepress_dailymotion_impl_listeners_options_transform_VideoIdTransformer'))
            ->withArgument('Invalid Dailymotion video ID')
            ->withArgument(false)
            ->withTag(tubepress_api_ioc_ServiceTags::EVENT_LISTENER, array(
                'event'    => tubepress_api_event_Events::OPTION_SET . '.' . tubepress_dailymotion_api_Constants::OPTION_RELATED_VALUE,
                'method'   => 'onOption',
                'priority' => 100000,
            ));

        $this->expectRegistration(
            'tubepress_dailymotion_impl_listeners_options_TransformListener__playlist',
            'tubepress_dailymotion_impl_listeners_options_TransformListener'
        )->withArgument(new tubepress_api_ioc_Reference('tubepress_dailymotion_impl_listeners_options_transform_PlaylistTransformer'))
            ->withArgument('Invalid Dailymotion playlist ID')
            ->withArgument(false)
            ->withTag(tubepress_api_ioc_ServiceTags::EVENT_LISTENER, array(
                'event'    => tubepress_api_event_Events::OPTION_SET . '.' . tubepress_dailymotion_api_Constants::OPTION_PLAYLIST_VALUE,
                'method'   => 'onOption',
                'priority' => 100000,
            ));

        $this->expectRegistration(
            'tubepress_dailymotion_impl_listeners_options_TransformListener__videos',
            'tubepress_dailymotion_impl_listeners_options_TransformListener'
        )->withArgument(new tubepress_api_ioc_Reference('tubepress_dailymotion_impl_listeners_options_transform_CsvTransformer__videos'))
            ->withArgument('Invalid Dailymotion video ID(s)')
            ->withArgument(false)
            ->withTag(tubepress_api_ioc_ServiceTags::EVENT_LISTENER, array(
                'event'    => tubepress_api_event_Events::OPTION_SET . '.' . tubepress_dailymotion_api_Constants::OPTION_LIST_VALUE,
                'method'   => 'onOption',
                'priority' => 100000,
            ));

        $this->expectRegistration(
            'tubepress_dailymotion_impl_listeners_options_TransformListener__languages',
            'tubepress_dailymotion_impl_listeners_options_TransformListener'
        )->withArgument(new tubepress_api_ioc_Reference('tubepress_dailymotion_impl_listeners_options_transform_CsvTransformer__languages'))
            ->withArgument('')
            ->withArgument(true)
            ->withTag(tubepress_api_ioc_ServiceTags::EVENT_LISTENER, array(
                'event'    => tubepress_api_event_Events::OPTION_SET . '.' . tubepress_dailymotion_api_Constants::OPTION_FEED_LANGUAGES_DECLARED,
                'method'   => 'onOption',
                'priority' => 100000,
            ));

        $this->expectRegistration(
            'tubepress_dailymotion_impl_listeners_options_TransformListener__users',
            'tubepress_dailymotion_impl_listeners_options_TransformListener'
        )->withArgument(new tubepress_api_ioc_Reference('tubepress_dailymotion_impl_listeners_options_transform_CsvTransformer__users'))
            ->withArgument('')
            ->withArgument(true)
            ->withTag(tubepress_api_ioc_ServiceTags::EVENT_LISTENER, array(
                'event'    => tubepress_api_event_Events::OPTION_SET . '.' . tubepress_dailymotion_api_Constants::OPTION_FEED_OWNERS_FILTER,
                'method'   => 'onOption',
                'priority' => 100000,
            ));
    }

    private function _registerMediaProvider()
    {
        $this->expectRegistration(
            'tubepress_dailymotion_impl_media_FeedHandler',
            'tubepress_dailymotion_impl_media_FeedHandler'
        )->withArgument(new tubepress_api_ioc_Reference(tubepress_api_log_LoggerInterface::_))
            ->withArgument(new tubepress_api_ioc_Reference(tubepress_api_options_ContextInterface::_))
            ->withArgument(new tubepress_api_ioc_Reference(tubepress_api_url_UrlFactoryInterface::_))
            ->withArgument(new tubepress_api_ioc_Reference(tubepress_api_array_ArrayReaderInterface::_))
            ->withArgument(new tubepress_api_ioc_Reference('tubepress_dailymotion_impl_dmapi_ApiUtility'));

        $this->expectRegistration(
            'tubepress_dailymotion_impl_media_MediaProvider',
            'tubepress_dailymotion_impl_media_MediaProvider'
        )->withArgument(new tubepress_api_ioc_Reference(tubepress_api_media_HttpCollectorInterface::_))
            ->withArgument(new tubepress_api_ioc_Reference('tubepress_dailymotion_impl_media_FeedHandler'))
            ->withArgument(new tubepress_api_ioc_Reference(tubepress_api_environment_EnvironmentInterface::_))
            ->withArgument(new tubepress_api_ioc_Reference(tubepress_api_util_StringUtilsInterface::_))
            ->withTag(tubepress_spi_media_MediaProviderInterface::__);
    }

    private function _registerOptionTransformers()
    {
        $this->expectRegistration(
            'tubepress_dailymotion_impl_listeners_options_transform_LanguageTransformer',
            'tubepress_dailymotion_impl_listeners_options_transform_LanguageTransformer'
        );

        $this->expectRegistration(
            'tubepress_dailymotion_impl_listeners_options_transform_PlaylistTransformer',
            'tubepress_dailymotion_impl_listeners_options_transform_PlaylistTransformer'
        )->withArgument(new tubepress_api_ioc_Reference(tubepress_api_url_UrlFactoryInterface::_))
            ->withArgument(new tubepress_api_ioc_Reference(tubepress_api_util_StringUtilsInterface::_));

        $this->expectRegistration(
            'tubepress_dailymotion_impl_listeners_options_transform_UserTransformer',
            'tubepress_dailymotion_impl_listeners_options_transform_UserTransformer'
        )->withArgument(new tubepress_api_ioc_Reference(tubepress_api_url_UrlFactoryInterface::_))
            ->withArgument(new tubepress_api_ioc_Reference(tubepress_api_util_StringUtilsInterface::_));

        $this->expectRegistration(
            'tubepress_dailymotion_impl_listeners_options_transform_VideoIdTransformer',
            'tubepress_dailymotion_impl_listeners_options_transform_VideoIdTransformer'
        )->withArgument(new tubepress_api_ioc_Reference(tubepress_api_url_UrlFactoryInterface::_))
            ->withArgument(new tubepress_api_ioc_Reference(tubepress_api_util_StringUtilsInterface::_));

        $this->expectRegistration(
            'tubepress_dailymotion_impl_listeners_options_transform_CsvTransformer__videos',
            'tubepress_dailymotion_impl_listeners_options_transform_CsvTransformer'
        )->withArgument(new tubepress_api_ioc_Reference('tubepress_dailymotion_impl_listeners_options_transform_VideoIdTransformer'));

        $this->expectRegistration(
            'tubepress_dailymotion_impl_listeners_options_transform_CsvTransformer__languages',
            'tubepress_dailymotion_impl_listeners_options_transform_CsvTransformer'
        )->withArgument(new tubepress_api_ioc_Reference('tubepress_dailymotion_impl_listeners_options_transform_LanguageTransformer'));

        $this->expectRegistration(
            'tubepress_dailymotion_impl_listeners_options_transform_CsvTransformer__users',
            'tubepress_dailymotion_impl_listeners_options_transform_CsvTransformer'
        )->withArgument(new tubepress_api_ioc_Reference('tubepress_dailymotion_impl_listeners_options_transform_UserTransformer'));
    }

    private function _registerOptions()
    {
        $valueMap = array(

            tubepress_api_options_Reference::PROPERTY_DEFAULT_VALUE => array(

                /*
                 * Player options.
                 */
                tubepress_dailymotion_api_Constants::OPTION_PLAYER_COLOR          => 'ffcc33',
                tubepress_dailymotion_api_Constants::OPTION_PLAYER_QUALITY        => tubepress_dailymotion_api_Constants::PLAYER_QUALITY_AUTO,
                tubepress_dailymotion_api_Constants::OPTION_PLAYER_SHOW_CONTROLS  => true,
                tubepress_dailymotion_api_Constants::OPTION_PLAYER_SHOW_ENDSCREEN => true,
                tubepress_dailymotion_api_Constants::OPTION_PLAYER_SHOW_LOGO      => false,
                tubepress_dailymotion_api_Constants::OPTION_PLAYER_SHOW_SHARING   => false,
                tubepress_dailymotion_api_Constants::OPTION_PLAYER_THEME          => tubepress_dailymotion_api_Constants::PLAYER_THEME_DARK,
                tubepress_dailymotion_api_Constants::OPTION_PLAYER_ORIGIN_DOMAIN  => null,
                tubepress_dailymotion_api_Constants::OPTION_PLAYER_ID             => null,

                /*
                 * Source values.
                 */
                tubepress_dailymotion_api_Constants::OPTION_FAVORITES_VALUE     => 'Mashable',
                tubepress_dailymotion_api_Constants::OPTION_FEATURED_VALUE      => 'RedBull',
                tubepress_dailymotion_api_Constants::OPTION_LIST_VALUE          => 'x2qahyh,x14t97b,x2kja1x',
                tubepress_dailymotion_api_Constants::OPTION_PLAYLIST_VALUE      => 'x438te',
                tubepress_dailymotion_api_Constants::OPTION_RELATED_VALUE       => 'x38g0kr',
                tubepress_dailymotion_api_Constants::OPTION_SEARCH_VALUE        => 'san diego beer week',
                tubepress_dailymotion_api_Constants::OPTION_SUBSCRIPTIONS_VALUE => 'AssociatedPress',
                tubepress_dailymotion_api_Constants::OPTION_TAG_VALUE           => 'wedding',
                tubepress_dailymotion_api_Constants::OPTION_USER_VALUE          => 'IGN',

                /*
                 * Global params.
                 */
                tubepress_dailymotion_api_Constants::OPTION_FEED_FAMILY_FILTER => true,
                tubepress_dailymotion_api_Constants::OPTION_FEED_LOCALE        => 'none',

                /*
                 * Feed options.
                 */
                tubepress_dailymotion_api_Constants::OPTION_FEED_COUNTRY            => null,
                tubepress_dailymotion_api_Constants::OPTION_FEED_LANGUAGE_DETECTED  => 'none',
                tubepress_dailymotion_api_Constants::OPTION_FEED_LANGUAGES_DECLARED => null,
                tubepress_dailymotion_api_Constants::OPTION_FEED_FEATURED_ONLY      => false,
                tubepress_dailymotion_api_Constants::OPTION_FEED_GENRE              => null,
                tubepress_dailymotion_api_Constants::OPTION_FEED_NO_GENRE           => null,
                tubepress_dailymotion_api_Constants::OPTION_FEED_HD_ONLY            => false,
                tubepress_dailymotion_api_Constants::OPTION_FEED_LIVE_FILTER        => tubepress_dailymotion_api_Constants::FILTER_LIVE_ALL,
                tubepress_dailymotion_api_Constants::OPTION_FEED_PREMIUM_FILTER     => tubepress_dailymotion_api_Constants::FILTER_PREMIUM_ALL,
                tubepress_dailymotion_api_Constants::OPTION_FEED_PARTNER_FILTER     => tubepress_dailymotion_api_Constants::FILTER_PARTNER_ALL,
                tubepress_dailymotion_api_Constants::OPTION_FEED_SHORTER_THAN       => 0,
                tubepress_dailymotion_api_Constants::OPTION_FEED_LONGER_THAN        => 0,
                tubepress_dailymotion_api_Constants::OPTION_FEED_TAGS_STRONG        => null,
                tubepress_dailymotion_api_Constants::OPTION_FEED_TAGS               => null,
                tubepress_dailymotion_api_Constants::OPTION_FEED_OWNERS_FILTER      => null,
                tubepress_dailymotion_api_Constants::OPTION_FEED_SEARCH             => null,

                /*
                 * Thumbnail options.
                 */
                tubepress_dailymotion_api_Constants::OPTION_THUMBS_RATIO => tubepress_dailymotion_api_Constants::THUMB_RATIO_ORIGINAL,
                tubepress_dailymotion_api_Constants::OPTION_THUMB_SIZE   => tubepress_dailymotion_api_Constants::THUMB_SIZE_240,
            ),

            tubepress_api_options_Reference::PROPERTY_UNTRANSLATED_LABEL => array(

                /*
                 * Player options.
                 */
                tubepress_dailymotion_api_Constants::OPTION_PLAYER_COLOR          => 'Highlight color of controls',
                tubepress_dailymotion_api_Constants::OPTION_PLAYER_QUALITY        => 'Preferred playback quality',
                tubepress_dailymotion_api_Constants::OPTION_PLAYER_SHOW_CONTROLS  => 'Show player controls',
                tubepress_dailymotion_api_Constants::OPTION_PLAYER_SHOW_ENDSCREEN => 'Show end-screen',
                tubepress_dailymotion_api_Constants::OPTION_PLAYER_SHOW_LOGO      => 'Show Dailymotion logo',
                tubepress_dailymotion_api_Constants::OPTION_PLAYER_SHOW_SHARING   => 'Enable sharing controls',
                tubepress_dailymotion_api_Constants::OPTION_PLAYER_THEME          => 'Player theme',
                tubepress_dailymotion_api_Constants::OPTION_PLAYER_ORIGIN_DOMAIN  => 'Origin domain',
                tubepress_dailymotion_api_Constants::OPTION_PLAYER_ID             => 'Player identifier',

                /*
                 * Source values.
                 */
                tubepress_dailymotion_api_Constants::OPTION_FAVORITES_VALUE     => null,
                tubepress_dailymotion_api_Constants::OPTION_FEATURED_VALUE      => null,
                tubepress_dailymotion_api_Constants::OPTION_LIST_VALUE          => null,
                tubepress_dailymotion_api_Constants::OPTION_PLAYLIST_VALUE      => null,
                tubepress_dailymotion_api_Constants::OPTION_RELATED_VALUE       => null,
                tubepress_dailymotion_api_Constants::OPTION_SEARCH_VALUE        => null,
                tubepress_dailymotion_api_Constants::OPTION_SUBSCRIPTIONS_VALUE => null,
                tubepress_dailymotion_api_Constants::OPTION_TAG_VALUE           => null,
                tubepress_dailymotion_api_Constants::OPTION_USER_VALUE          => null,

                /*
                 * Global params.
                 */
                tubepress_dailymotion_api_Constants::OPTION_FEED_FAMILY_FILTER => 'Filter out explicit videos',
                tubepress_dailymotion_api_Constants::OPTION_FEED_LOCALE        => 'Preferred localization',

                /*
                 * Feed options.
                 */
                tubepress_dailymotion_api_Constants::OPTION_FEED_COUNTRY            => 'Limit to country',
                tubepress_dailymotion_api_Constants::OPTION_FEED_LANGUAGE_DETECTED  => 'Limit to detected language',
                tubepress_dailymotion_api_Constants::OPTION_FEED_LANGUAGES_DECLARED => 'Limit to declared languages',
                tubepress_dailymotion_api_Constants::OPTION_FEED_FEATURED_ONLY      => 'Featured videos only',
                tubepress_dailymotion_api_Constants::OPTION_FEED_GENRE              => 'Limit to genre',
                tubepress_dailymotion_api_Constants::OPTION_FEED_NO_GENRE           => 'Exclude genre',
                tubepress_dailymotion_api_Constants::OPTION_FEED_HD_ONLY            => 'High-definition videos only',
                tubepress_dailymotion_api_Constants::OPTION_FEED_LIVE_FILTER        => 'Broadcast status filter',
                tubepress_dailymotion_api_Constants::OPTION_FEED_PREMIUM_FILTER     => 'Premium filter',
                tubepress_dailymotion_api_Constants::OPTION_FEED_PARTNER_FILTER     => 'Partner filter',
                tubepress_dailymotion_api_Constants::OPTION_FEED_SHORTER_THAN       => 'Maximum duration (minutes)',
                tubepress_dailymotion_api_Constants::OPTION_FEED_LONGER_THAN        => 'Minimum duration (minutes)',
                tubepress_dailymotion_api_Constants::OPTION_FEED_TAGS_STRONG        => 'Limit to tags (exact)',
                tubepress_dailymotion_api_Constants::OPTION_FEED_TAGS               => 'Limit to tags (loose)',
                tubepress_dailymotion_api_Constants::OPTION_FEED_OWNERS_FILTER      => 'Limit to users',
                tubepress_dailymotion_api_Constants::OPTION_FEED_SEARCH             => 'Limit to full-text search',

                /*
                 * Thumbnail options.
                 */
                tubepress_dailymotion_api_Constants::OPTION_THUMBS_RATIO => 'Preferred thumbnail shape',
                tubepress_dailymotion_api_Constants::OPTION_THUMB_SIZE   => 'Preferred thumbnail size',

                tubepress_dailymotion_api_Constants::OPTION_PLAYLIST_VALUE      => 'This playlist',
                tubepress_dailymotion_api_Constants::OPTION_FAVORITES_VALUE     => 'Favorite videos from this user',
                tubepress_dailymotion_api_Constants::OPTION_FEATURED_VALUE      => 'Featured videos from this user',
                tubepress_dailymotion_api_Constants::OPTION_LIST_VALUE          => 'This list of videos',
                tubepress_dailymotion_api_Constants::OPTION_RELATED_VALUE       => 'Videos related to this video',
                tubepress_dailymotion_api_Constants::OPTION_SUBSCRIPTIONS_VALUE => 'Videos from this user\'s subscriptions',
                tubepress_dailymotion_api_Constants::OPTION_USER_VALUE          => 'Videos uploaded by this user',
                tubepress_dailymotion_api_Constants::OPTION_TAG_VALUE           => 'Videos tagged with',
                tubepress_dailymotion_api_Constants::OPTION_SEARCH_VALUE        => 'Dailymotion search for',
            ),

            tubepress_api_options_Reference::PROPERTY_UNTRANSLATED_DESCRIPTION => array(

                /*
                 * Player options.
                 */
                tubepress_dailymotion_api_Constants::OPTION_PLAYER_QUALITY        => 'Suggest a default playback quality. Set to "auto" for best results.',
                tubepress_dailymotion_api_Constants::OPTION_PLAYER_SHOW_ENDSCREEN => 'Show related videos after playback finishes.',
                tubepress_dailymotion_api_Constants::OPTION_PLAYER_SHOW_SHARING   => 'Allows the viewer to quickly share the video on social media.',
                tubepress_dailymotion_api_Constants::OPTION_PLAYER_ORIGIN_DOMAIN  => 'Most users should leave this blank. You may set a domain for the page hosting the video player, which may be useful in rare situations.',
                tubepress_dailymotion_api_Constants::OPTION_PLAYER_ID             => 'Most users should leave this blank. You may set a unique identifier for the player on the page, which may be useful for custom JavaScript programming.',

                /*
                 * Source values.
                 */
                tubepress_dailymotion_api_Constants::OPTION_FAVORITES_VALUE => sprintf('You may enter an exact user ID (e.g. <code>%s</code>) or their Dailymotion URL (e.g. <code>%s</code> or <code>%s</code>).',
                    'IGN', 'http://www.dailymotion.com/ign', 'http://www.dailymotion.com/user/ign/1'),

                tubepress_dailymotion_api_Constants::OPTION_FEATURED_VALUE => sprintf('You may enter an exact user ID (e.g. <code>%s</code>) or their Dailymotion URL (e.g. <code>%s</code> or <code>%s</code>).',
                    'HollywoodTV', 'http://www.dailymotion.com/hollywoodtv', 'http://www.dailymotion.com/user/hollywoodtv/1'),

                tubepress_dailymotion_api_Constants::OPTION_LIST_VALUE => sprintf('A comma-separated list of Dailymotion video IDs in the order that you would like them to appear. You may enter exact video IDs (e.g. <code>%s</code> or <code>%s</code>) or their URLs on Dailymotion (e.g. <code>%s</code>).',
                    'x3ni7qu', 'x3ni7qu_skating-into-the-wild-of-canada_sport', 'http://www.dailymotion.com/video/x3ni7qu_skating-into-the-wild-of-canada_sport'),

                tubepress_dailymotion_api_Constants::OPTION_PLAYLIST_VALUE => sprintf('You may enter an exact playlist ID (e.g. <code>%s</code> or <code>%s</code>) or its Dailymotion URL (e.g. <code>%s</code>).',
                    'x40h52', 'x40h52_RedBull_made-in-australia', 'http://www.dailymotion.com/playlist/x40h52_RedBull_made-in-australia'),

                tubepress_dailymotion_api_Constants::OPTION_RELATED_VALUE => sprintf('You may enter an exact video ID (e.g. <code>%s</code> or <code>%s</code>) or its URL on Dailymotion (e.g. <code>%s</code>).',
                    'x3ni7qu', 'x3ni7qu_skating-into-the-wild-of-canada_sport', 'http://www.dailymotion.com/video/x3ni7qu_skating-into-the-wild-of-canada_sport'),

                tubepress_dailymotion_api_Constants::OPTION_SEARCH_VALUE => 'Just as if you were searching on dailymotion.com.',

                tubepress_dailymotion_api_Constants::OPTION_SUBSCRIPTIONS_VALUE => sprintf('You may enter an exact user ID (e.g. <code>%s</code>) or their Dailymotion URL (e.g. <code>%s</code> or <code>%s</code>).',
                    'CBS', 'http://www.dailymotion.com/cbs', 'http://www.dailymotion.com/user/cbs/1'),

                tubepress_dailymotion_api_Constants::OPTION_TAG_VALUE => 'You may enter a comma-separated list of tags.',

                tubepress_dailymotion_api_Constants::OPTION_USER_VALUE => sprintf('You may enter an exact user ID (e.g. <code>%s</code>) or their Dailymotion URL (e.g. <code>%s</code> or <code>%s</code>).',
                    'AssociatedPress', 'http://www.dailymotion.com/associatedpress', 'http://www.dailymotion.com/user/associatedpress/1'),

                /*
                 * Global params.
                 */
                tubepress_dailymotion_api_Constants::OPTION_FEED_FAMILY_FILTER => 'Enable Dailymotion\'s "family filter" which attempts to exclude videos with adult content.',
                tubepress_dailymotion_api_Constants::OPTION_FEED_LOCALE        => 'Select your preferred locale to narrow the language and content of your galleries.',

                /*
                 * Feed options.
                 */
                tubepress_dailymotion_api_Constants::OPTION_FEED_COUNTRY            => 'Enter a <a href="https://en.wikipedia.org/wiki/ISO_3166-1_alpha-2" target="_blank">two-digit country code</a> to only include videos declared to be from the specific country. e.g. <code>FR</code> for France, or <code>IT</code> for Italy.',
                tubepress_dailymotion_api_Constants::OPTION_FEED_LANGUAGE_DETECTED  => 'Only videos detected to be in a specific language.',
                tubepress_dailymotion_api_Constants::OPTION_FEED_LANGUAGES_DECLARED => 'Enter a comma-separated list of <a href="https://en.wikipedia.org/wiki/List_of_ISO_639-1_codes" target="_blank">two-digit language codes</a> to include only videos declared to be in the specified languages. e.g. <code>sv</code> for Swedish, or <code>ru, be, uk</code> for Russian, Belarusian, and Ukranian.',
                tubepress_dailymotion_api_Constants::OPTION_FEED_FEATURED_ONLY      => 'Only include videos featured by Dailymotion.',
                tubepress_dailymotion_api_Constants::OPTION_FEED_GENRE              => 'Only include videos with the specified genre.',
                tubepress_dailymotion_api_Constants::OPTION_FEED_NO_GENRE           => 'Exclude videos with the specified genre.',
                tubepress_dailymotion_api_Constants::OPTION_FEED_HD_ONLY            => 'Only include videos with a vertical resolution of 720p or higher.',
                tubepress_dailymotion_api_Constants::OPTION_FEED_SHORTER_THAN       => 'Only include videos shorter than or equal to the given number of minutes. Set to <code>0</code> to disable this filter.',
                tubepress_dailymotion_api_Constants::OPTION_FEED_LONGER_THAN        => 'Only include videos longer than or equal to the given number of minutes.',
                tubepress_dailymotion_api_Constants::OPTION_FEED_TAGS_STRONG        => 'Enter a comma-separated list of terms to only include videos tagged with those <em>exact</em> terms. e.g. <code>Weddings</code> or <code>Wedding Planning, Wedding Venue</code>.',
                tubepress_dailymotion_api_Constants::OPTION_FEED_TAGS               => 'Enter a comma-separated list of terms to only include videos with tags that <em>contain</em> the given terms. e.g. <code>wedding</code> or <code>marriage, party, celebration</code>.',
                tubepress_dailymotion_api_Constants::OPTION_FEED_OWNERS_FILTER      => 'Enter a comma-separated list of Dailymotion users to include only videos uploaded by those users. You may enter exact user IDs (e.g. <code>IGN</code>, <code>splashnews</code>) or Dailymotion URLs for the users (e.g. <code>http://www.dailymotion.com/ign</code>, <code>http://www.dailymotion.com/user/splashnews/1</code>).',
                tubepress_dailymotion_api_Constants::OPTION_FEED_SEARCH             => 'Only include videos that match the given search query.',

                /*
                 *
                 * Thumbnail options.
                 */
                tubepress_dailymotion_api_Constants::OPTION_THUMB_SIZE => 'Choose the height of thumbnails that are served from Dailymotion. Higher resolutions will generally look better but will negatively impact your site\'s load time. For best results, choose the value closest to (but not less than) your actual thumbnail height (configured above).',
            ),
        );

        $boolMap = array(
            tubepress_api_options_Reference::PROPERTY_PRO_ONLY => array(

                /*
                 * Player options.
                 */
                tubepress_dailymotion_api_Constants::OPTION_PLAYER_COLOR,
                tubepress_dailymotion_api_Constants::OPTION_PLAYER_QUALITY,
                tubepress_dailymotion_api_Constants::OPTION_PLAYER_SHOW_CONTROLS,
                tubepress_dailymotion_api_Constants::OPTION_PLAYER_SHOW_ENDSCREEN,
                tubepress_dailymotion_api_Constants::OPTION_PLAYER_SHOW_LOGO,
                tubepress_dailymotion_api_Constants::OPTION_PLAYER_SHOW_SHARING,
                tubepress_dailymotion_api_Constants::OPTION_PLAYER_THEME,
                tubepress_dailymotion_api_Constants::OPTION_PLAYER_ORIGIN_DOMAIN,

                /*
                 * Global params.
                 */
                tubepress_dailymotion_api_Constants::OPTION_FEED_FAMILY_FILTER,
                tubepress_dailymotion_api_Constants::OPTION_FEED_LOCALE,

                /*
                 * Feed options.
                 */
                tubepress_dailymotion_api_Constants::OPTION_FEED_COUNTRY,
                tubepress_dailymotion_api_Constants::OPTION_FEED_LANGUAGE_DETECTED,
                tubepress_dailymotion_api_Constants::OPTION_FEED_LANGUAGES_DECLARED,
                tubepress_dailymotion_api_Constants::OPTION_FEED_FEATURED_ONLY,
                tubepress_dailymotion_api_Constants::OPTION_FEED_GENRE,
                tubepress_dailymotion_api_Constants::OPTION_FEED_NO_GENRE,
                tubepress_dailymotion_api_Constants::OPTION_FEED_HD_ONLY,
                tubepress_dailymotion_api_Constants::OPTION_FEED_LIVE_FILTER,
                tubepress_dailymotion_api_Constants::OPTION_FEED_PREMIUM_FILTER,
                tubepress_dailymotion_api_Constants::OPTION_FEED_PARTNER_FILTER,
                tubepress_dailymotion_api_Constants::OPTION_FEED_SHORTER_THAN,
                tubepress_dailymotion_api_Constants::OPTION_FEED_LONGER_THAN,
                tubepress_dailymotion_api_Constants::OPTION_FEED_TAGS_STRONG,
                tubepress_dailymotion_api_Constants::OPTION_FEED_TAGS,
                tubepress_dailymotion_api_Constants::OPTION_FEED_OWNERS_FILTER,
                tubepress_dailymotion_api_Constants::OPTION_FEED_SEARCH,

                /*
                 * Thumbnail options.
                 */
                tubepress_dailymotion_api_Constants::OPTION_THUMBS_RATIO,
                tubepress_dailymotion_api_Constants::OPTION_THUMB_SIZE,
            ),
        );

        $this->expectRegistration(
            'tubepress_api_options_Reference__dailymotion',
            'tubepress_api_options_Reference'
        )->withArgument($valueMap)
            ->withArgument($boolMap)
            ->withTag(tubepress_api_options_ReferenceInterface::_);
    }

    private function _registerOptionsUi()
    {
        $fieldIndex = 0;

        $gallerySourceMap = array(

            array(tubepress_dailymotion_api_Constants::GALLERY_SOURCE_USER,
                'multiSourceText',
                tubepress_dailymotion_api_Constants::OPTION_USER_VALUE, ),

            array(tubepress_dailymotion_api_Constants::GALLERY_SOURCE_PLAYLIST,
                'multiSourceText',
                tubepress_dailymotion_api_Constants::OPTION_PLAYLIST_VALUE, ),

            array(tubepress_dailymotion_api_Constants::GALLERY_SOURCE_SEARCH,
                'multiSourceText',
                tubepress_dailymotion_api_Constants::OPTION_SEARCH_VALUE, ),

            array(tubepress_dailymotion_api_Constants::GALLERY_SOURCE_LIST,
                'multiSourceTextArea',
                tubepress_dailymotion_api_Constants::OPTION_LIST_VALUE, ),

            array(
                tubepress_dailymotion_api_Constants::GALLERY_SOURCE_FAVORITES,
                'multiSourceText',
                tubepress_dailymotion_api_Constants::OPTION_FAVORITES_VALUE, ),

            array(tubepress_dailymotion_api_Constants::GALLERY_SOURCE_FEATURED,
                'multiSourceText',
                tubepress_dailymotion_api_Constants::OPTION_FEATURED_VALUE, ),

            array(tubepress_dailymotion_api_Constants::GALLERY_SOURCE_RELATED,
                'multiSourceText',
                tubepress_dailymotion_api_Constants::OPTION_RELATED_VALUE, ),

            array(tubepress_dailymotion_api_Constants::GALLERY_SOURCE_TAG,
                'multiSourceText',
                tubepress_dailymotion_api_Constants::OPTION_TAG_VALUE, ),

            array(tubepress_dailymotion_api_Constants::GALLERY_SOURCE_SUBSCRIPTIONS,
                'multiSourceText',
                tubepress_dailymotion_api_Constants::OPTION_SUBSCRIPTIONS_VALUE, ),
        );

        foreach ($gallerySourceMap as $gallerySourceFieldArray) {

            $this->expectRegistration(

                'dailymotion_options_subfield_' . $fieldIndex,
                'tubepress_api_options_ui_FieldInterface'
            )->withFactoryService(tubepress_api_options_ui_FieldBuilderInterface::_)
                ->withFactoryMethod('newInstance')
                ->withArgument($gallerySourceFieldArray[2])
                ->withArgument($gallerySourceFieldArray[1]);

            $this->expectRegistration(

                'dailymotion_options_field_' . $fieldIndex,
                'tubepress_api_options_ui_FieldInterface'
            )->withFactoryService(tubepress_api_options_ui_FieldBuilderInterface::_)
                ->withFactoryMethod('newInstance')
                ->withArgument($gallerySourceFieldArray[0])
                ->withArgument('gallerySourceRadio')
                ->withArgument(array('additionalField' => new tubepress_api_ioc_Reference('dailymotion_options_subfield_' . $fieldIndex++)));
        }

        $fieldMap = array(

            tubepress_dailymotion_api_Constants::OPTION_PLAYER_SHOW_CONTROLS  => 'bool',
            tubepress_dailymotion_api_Constants::OPTION_PLAYER_SHOW_ENDSCREEN => 'bool',
            tubepress_dailymotion_api_Constants::OPTION_PLAYER_SHOW_LOGO      => 'bool',
            tubepress_dailymotion_api_Constants::OPTION_PLAYER_SHOW_SHARING   => 'bool',
            tubepress_dailymotion_api_Constants::OPTION_FEED_FAMILY_FILTER    => 'multiSourceBool',
            tubepress_dailymotion_api_Constants::OPTION_FEED_FEATURED_ONLY    => 'multiSourceBool',
            tubepress_dailymotion_api_Constants::OPTION_FEED_HD_ONLY          => 'multiSourceBool',

            tubepress_dailymotion_api_Constants::OPTION_PLAYER_COLOR => 'spectrum',

            tubepress_dailymotion_api_Constants::OPTION_PLAYER_ORIGIN_DOMAIN    => 'text',
            tubepress_dailymotion_api_Constants::OPTION_PLAYER_ID               => 'text',
            tubepress_dailymotion_api_Constants::OPTION_FEED_COUNTRY            => 'multiSourceText',
            tubepress_dailymotion_api_Constants::OPTION_FEED_LANGUAGES_DECLARED => 'multiSourceText',
            tubepress_dailymotion_api_Constants::OPTION_FEED_GENRE              => 'multiSourceText',
            tubepress_dailymotion_api_Constants::OPTION_FEED_NO_GENRE           => 'multiSourceText',
            tubepress_dailymotion_api_Constants::OPTION_FEED_SHORTER_THAN       => 'multiSourceText',
            tubepress_dailymotion_api_Constants::OPTION_FEED_LONGER_THAN        => 'multiSourceText',
            tubepress_dailymotion_api_Constants::OPTION_FEED_TAGS_STRONG        => 'multiSourceText',
            tubepress_dailymotion_api_Constants::OPTION_FEED_TAGS               => 'multiSourceText',
            tubepress_dailymotion_api_Constants::OPTION_FEED_OWNERS_FILTER      => 'multiSourceText',
            tubepress_dailymotion_api_Constants::OPTION_FEED_SEARCH             => 'multiSourceText',

            tubepress_dailymotion_api_Constants::OPTION_PLAYER_QUALITY         => 'dropdown',
            tubepress_dailymotion_api_Constants::OPTION_PLAYER_THEME           => 'dropdown',
            tubepress_dailymotion_api_Constants::OPTION_THUMBS_RATIO           => 'dropdown',
            tubepress_dailymotion_api_Constants::OPTION_THUMB_SIZE             => 'dropdown',
            tubepress_dailymotion_api_Constants::OPTION_FEED_LIVE_FILTER       => 'multiSourceDropdown',
            tubepress_dailymotion_api_Constants::OPTION_FEED_PREMIUM_FILTER    => 'multiSourceDropdown',
            tubepress_dailymotion_api_Constants::OPTION_FEED_PARTNER_FILTER    => 'multiSourceDropdown',
            //tubepress_dailymotion_api_Constants::OPTION_FEED_LOCALE            => 'multiSourceDropdown',
            //tubepress_dailymotion_api_Constants::OPTION_FEED_LANGUAGE_DETECTED => 'multiSourceDropdown',
        );

        foreach ($fieldMap as $id => $class) {

            $this->expectRegistration(

                'dailymotion_options_field_' . $fieldIndex++,
                'tubepress_api_options_ui_FieldInterface'
            )->withFactoryService(tubepress_api_options_ui_FieldBuilderInterface::_)
                ->withFactoryMethod('newInstance')
                ->withArgument($id)
                ->withArgument($class);
        }

        $fieldReferences = array();

        for ($x = 0; $x < $fieldIndex; ++$x) {

            $fieldReferences[] = new tubepress_api_ioc_Reference('dailymotion_options_field_' . $x);
        }

        $map = array(
            tubepress_api_options_ui_CategoryNames::GALLERY_SOURCE => array(

                tubepress_dailymotion_api_Constants::GALLERY_SOURCE_USER,
                tubepress_dailymotion_api_Constants::GALLERY_SOURCE_PLAYLIST,
                tubepress_dailymotion_api_Constants::GALLERY_SOURCE_SEARCH,
                tubepress_dailymotion_api_Constants::GALLERY_SOURCE_FAVORITES,
                tubepress_dailymotion_api_Constants::GALLERY_SOURCE_FEATURED,
                tubepress_dailymotion_api_Constants::GALLERY_SOURCE_LIST,
                tubepress_dailymotion_api_Constants::GALLERY_SOURCE_TAG,
                tubepress_dailymotion_api_Constants::GALLERY_SOURCE_RELATED,
                tubepress_dailymotion_api_Constants::GALLERY_SOURCE_SUBSCRIPTIONS,
            ),

            tubepress_api_options_ui_CategoryNames::THUMBNAILS => array(

                tubepress_dailymotion_api_Constants::OPTION_THUMBS_RATIO,
                tubepress_dailymotion_api_Constants::OPTION_THUMB_SIZE,
            ),

            tubepress_api_options_ui_CategoryNames::EMBEDDED => array(

                tubepress_dailymotion_api_Constants::OPTION_PLAYER_COLOR,
                tubepress_dailymotion_api_Constants::OPTION_PLAYER_QUALITY,
                tubepress_dailymotion_api_Constants::OPTION_PLAYER_SHOW_CONTROLS,
                tubepress_dailymotion_api_Constants::OPTION_PLAYER_SHOW_ENDSCREEN,
                tubepress_dailymotion_api_Constants::OPTION_PLAYER_SHOW_LOGO,
                tubepress_dailymotion_api_Constants::OPTION_PLAYER_SHOW_SHARING,
                tubepress_dailymotion_api_Constants::OPTION_PLAYER_THEME,
                tubepress_dailymotion_api_Constants::OPTION_PLAYER_ORIGIN_DOMAIN,
                tubepress_dailymotion_api_Constants::OPTION_PLAYER_ID,
            ),

            tubepress_api_options_ui_CategoryNames::FEED => array(

                tubepress_dailymotion_api_Constants::OPTION_FEED_FAMILY_FILTER,
                tubepress_dailymotion_api_Constants::OPTION_FEED_LOCALE,
                tubepress_dailymotion_api_Constants::OPTION_FEED_COUNTRY,
                tubepress_dailymotion_api_Constants::OPTION_FEED_LANGUAGE_DETECTED,
                tubepress_dailymotion_api_Constants::OPTION_FEED_LANGUAGES_DECLARED,
                tubepress_dailymotion_api_Constants::OPTION_FEED_FEATURED_ONLY,
                tubepress_dailymotion_api_Constants::OPTION_FEED_GENRE,
                tubepress_dailymotion_api_Constants::OPTION_FEED_NO_GENRE,
                tubepress_dailymotion_api_Constants::OPTION_FEED_HD_ONLY,
                tubepress_dailymotion_api_Constants::OPTION_FEED_LIVE_FILTER,
                tubepress_dailymotion_api_Constants::OPTION_FEED_PREMIUM_FILTER,
                tubepress_dailymotion_api_Constants::OPTION_FEED_PARTNER_FILTER,
                tubepress_dailymotion_api_Constants::OPTION_FEED_SHORTER_THAN,
                tubepress_dailymotion_api_Constants::OPTION_FEED_LONGER_THAN,
                tubepress_dailymotion_api_Constants::OPTION_FEED_TAGS,
                tubepress_dailymotion_api_Constants::OPTION_FEED_TAGS_STRONG,
                tubepress_dailymotion_api_Constants::OPTION_FEED_OWNERS_FILTER,
                tubepress_dailymotion_api_Constants::OPTION_FEED_SEARCH,
            ),
        );

        $this->expectRegistration(

            'tubepress_api_options_ui_BaseFieldProvider',
            'tubepress_api_options_ui_BaseFieldProvider'

        )->withArgument('field-provider-dailymotion')
            ->withArgument('Dailymotion')
            ->withArgument(true)
            ->withArgument(true)
            ->withArgument(array())
            ->withArgument($fieldReferences)
            ->withArgument($map)
            ->withTag('tubepress_spi_options_ui_FieldProviderInterface');
    }

    private function _registerPlayer()
    {
        $this->expectRegistration(
            'tubepress_dailymotion_impl_player_DailymotionPlayerLocation',
            'tubepress_dailymotion_impl_player_DailymotionPlayerLocation'
        )->withTag('tubepress_spi_player_PlayerLocationInterface');
    }

    protected function getExpectedExternalServicesMap()
    {
        $mockLogger      = $this->mock(tubepress_api_log_LoggerInterface::_);
        $mockBaseUrl     = $this->mock(tubepress_api_url_UrlInterface::_);
        $mockEnvironment = $this->mock(tubepress_api_environment_EnvironmentInterface::_);

        $mockLogger->shouldReceive('isEnabled')->atLeast(1)->andReturn(true);
        $mockEnvironment->shouldReceive('getBaseUrl')->once()->andReturn($mockBaseUrl);
        $mockBaseUrl->shouldReceive('getClone')->once()->andReturn($mockBaseUrl);
        $mockBaseUrl->shouldReceive('addPath')->once()->with('src/add-ons/provider-dailymotion/web/images/icons/dailymotion-icon-34w_x_34h.png')->andReturn($mockBaseUrl);
        $mockBaseUrl->shouldReceive('toString')->once()->andReturn('mock-base-url');

        $mockUrlFactory = new tubepress_url_impl_puzzle_UrlFactory();

        $mockFieldBuilder = $this->mock(tubepress_api_options_ui_FieldBuilderInterface::_);
        $mockField        = $this->mock('tubepress_api_options_ui_FieldInterface');

        $mockFieldBuilder->shouldReceive('newInstance')->atLeast(1)->andReturn($mockField);

        return array(
            tubepress_api_options_ContextInterface::_          => tubepress_api_options_ContextInterface::_,
            tubepress_api_util_LangUtilsInterface::_           => tubepress_api_util_LangUtilsInterface::_,
            tubepress_api_url_UrlFactoryInterface::_           => $mockUrlFactory,
            tubepress_api_log_LoggerInterface::_               => $mockLogger,
            tubepress_api_http_HttpClientInterface::_          => tubepress_api_http_HttpClientInterface::_,
            tubepress_api_array_ArrayReaderInterface::_        => tubepress_api_array_ArrayReaderInterface::_,
            tubepress_api_media_HttpCollectorInterface::_      => tubepress_api_media_HttpCollectorInterface::_,
            tubepress_api_environment_EnvironmentInterface::_  => $mockEnvironment,
            tubepress_api_util_StringUtilsInterface::_         => tubepress_api_util_StringUtilsInterface::_,
            tubepress_api_media_AttributeFormatterInterface::_ => tubepress_api_media_AttributeFormatterInterface::_,
            tubepress_api_options_ReferenceInterface::_        => tubepress_api_options_ReferenceInterface::_,
            tubepress_api_translation_TranslatorInterface::_   => tubepress_api_translation_TranslatorInterface::_,
            tubepress_api_options_ui_FieldBuilderInterface::_  => $mockFieldBuilder,
        );
    }
}
