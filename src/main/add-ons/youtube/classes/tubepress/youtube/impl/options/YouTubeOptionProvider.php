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
 * Allows TubePress to work with YouTube.
 */
class tubepress_youtube_impl_options_YouTubeOptionProvider implements tubepress_core_options_api_EasyProviderInterface
{
    private static $_valueMapTime = array(

        tubepress_youtube_api_Constants::TIMEFRAME_ALL_TIME   => 'all time',        //>(translatable)<
        tubepress_youtube_api_Constants::TIMEFRAME_TODAY      => 'today',           //>(translatable)<
    );

    private static $_regexWordChars    = '/\w+/';
    private static $_regexYouTubeVideo = '/[a-zA-Z0-9_-]{11}/';


    /**
     * @return array An associative array, which may be empty but not null, of option names
     *               to their corresponding fixed acceptable values.
     */
    public function getMapOfOptionNamesToFixedAcceptableValues()
    {
        return array(

            tubepress_youtube_api_Constants::OPTION_AUTOHIDE => array(

                tubepress_youtube_api_Constants::AUTOHIDE_HIDE_BAR_SHOW_CONTROLS => 'Fade progress bar only',     //>(translatable)<
                tubepress_youtube_api_Constants::AUTOHIDE_HIDE_BOTH              => 'Fade progress bar and video controls', //>(translatable)<
                tubepress_youtube_api_Constants::AUTOHIDE_SHOW_BOTH              => 'Disable fading - always show both'   //>(translatable)<
            ),

            tubepress_youtube_api_Constants::OPTION_SHOW_CONTROLS => array(

                tubepress_youtube_api_Constants::CONTROLS_SHOW_IMMEDIATE_FLASH => 'Show controls - load Flash player immediately',          //>(translatable)<
                tubepress_youtube_api_Constants::CONTROLS_SHOW_DELAYED_FLASH   => 'Show controls - load Flash player when playback begins', //>(translatable)<
                tubepress_youtube_api_Constants::CONTROLS_HIDE                 => 'Hide controls',                                          //>(translatable)<
            ),

            tubepress_youtube_api_Constants::OPTION_THEME => array(

                tubepress_youtube_api_Constants::PLAYER_THEME_DARK  => 'Dark',     //>(translatable)<
                tubepress_youtube_api_Constants::PLAYER_THEME_LIGHT => 'Light'    //>(translatable)<
            ),

            tubepress_youtube_api_Constants::OPTION_FILTER => array(

                tubepress_youtube_api_Constants::SAFESEARCH_NONE     => 'none',     //>(translatable)<
                tubepress_youtube_api_Constants::SAFESEARCH_MODERATE => 'moderate', //>(translatable)<
                tubepress_youtube_api_Constants::SAFESEARCH_STRICT   => 'strict',   //>(translatable)<
            ),

            tubepress_youtube_api_Constants::OPTION_YOUTUBE_MOST_POPULAR_VALUE => self::$_valueMapTime,
        );
    }

    /**
     * @return string[] An array, which may be empty but not null, of Pro option names from this provider.
     */
    public function getAllProOptionNames()
    {
        return array(

            tubepress_youtube_api_Constants::OPTION_CLOSED_CAPTIONS,
            tubepress_youtube_api_Constants::OPTION_DISABLE_KEYBOARD,
            tubepress_youtube_api_Constants::OPTION_SHOW_ANNOTATIONS,
            tubepress_youtube_api_Constants::OPTION_SHOW_CONTROLS,
            tubepress_youtube_api_Constants::OPTION_THEME,
        );
    }

    /**
     * @return array An associative array, which may be empty but not null, of option names
     *               to their corresponding valid value regexes.
     */
    public function getMapOfOptionNamesToValidValueRegexes()
    {
        return array(

            tubepress_youtube_api_Constants::OPTION_DEV_KEY => '/[\w-]+/',

            tubepress_youtube_api_Constants::OPTION_YOUTUBE_RELATED_VALUE   => self::$_regexYouTubeVideo,
            tubepress_youtube_api_Constants::OPTION_YOUTUBE_PLAYLIST_VALUE  => '/[\w-]+/',
            tubepress_youtube_api_Constants::OPTION_YOUTUBE_FAVORITES_VALUE => self::$_regexWordChars,
            tubepress_youtube_api_Constants::OPTION_YOUTUBE_USER_VALUE      => '/[\w-]+/',
        );
    }

    /**
     * @return array An associative array, which may be empty but not null, of option names
     *               to their corresponding untranslated label.
     */
    public function getMapOfOptionNamesToUntranslatedLabels()
    {
        return array(

            tubepress_youtube_api_Constants::OPTION_AUTOHIDE         => 'Fade progress bar and video controls', //>(translatable)<
            tubepress_youtube_api_Constants::OPTION_CLOSED_CAPTIONS  => 'Show closed captions by default',      //>(translatable)<
            tubepress_youtube_api_Constants::OPTION_DISABLE_KEYBOARD => 'Disable keyboard controls',            //>(translatable)<
            tubepress_youtube_api_Constants::OPTION_FULLSCREEN       => 'Allow fullscreen playback.',           //>(translatable)<
            tubepress_youtube_api_Constants::OPTION_MODEST_BRANDING  => '"Modest" branding',                    //>(translatable)<
            tubepress_youtube_api_Constants::OPTION_SHOW_ANNOTATIONS => 'Show video annotations by default',    //>(translatable)<
            tubepress_youtube_api_Constants::OPTION_SHOW_CONTROLS    => 'Show or hide video controls',          //>(translatable)<
            tubepress_youtube_api_Constants::OPTION_SHOW_RELATED     => 'Show related videos',                  //>(translatable)<
            tubepress_youtube_api_Constants::OPTION_THEME            => 'YouTube player theme',                 //>(translatable)<

            tubepress_youtube_api_Constants::OPTION_DEV_KEY         => 'YouTube API Developer Key',       //>(translatable)<
            tubepress_youtube_api_Constants::OPTION_EMBEDDABLE_ONLY => 'Only retrieve embeddable videos', //>(translatable)<
            tubepress_youtube_api_Constants::OPTION_FILTER          => 'Filter "racy" content',           //>(translatable)<

            tubepress_youtube_api_Constants::OPTION_YOUTUBE_MOST_POPULAR_VALUE => 'Most-viewed YouTube videos from',       //>(translatable)<
            tubepress_youtube_api_Constants::OPTION_YOUTUBE_RELATED_VALUE      => 'Videos related to this YouTube video',  //>(translatable)<
            tubepress_youtube_api_Constants::OPTION_YOUTUBE_PLAYLIST_VALUE     => 'This YouTube playlist',                 //>(translatable)<
            tubepress_youtube_api_Constants::OPTION_YOUTUBE_FAVORITES_VALUE    => 'This YouTube user\'s "favorites"',      //>(translatable)<
            tubepress_youtube_api_Constants::OPTION_YOUTUBE_TAG_VALUE          => 'YouTube search for',                    //>(translatable)<
            tubepress_youtube_api_Constants::OPTION_YOUTUBE_USER_VALUE         => 'Videos from this YouTube user',         //>(translatable)<

            tubepress_youtube_api_Constants::OPTION_RATING  => 'Average rating',     //>(translatable)<
            tubepress_youtube_api_Constants::OPTION_RATINGS => 'Number of ratings',  //>(translatable)<
        );
    }

    /**
     * @return array An associative array, which may be empty but not null, of option names
     *               to their corresponding untranslated label.
     */
    public function getMapOfOptionNamesToUntranslatedDescriptions()
    {
        return array(

            tubepress_youtube_api_Constants::OPTION_AUTOHIDE         => 'After video playback begins, choose which elements (if any) of the embedded video player to automatically hide.', //>(translatable)<
            tubepress_youtube_api_Constants::OPTION_MODEST_BRANDING  => 'Hide the YouTube logo from the control area.',                    //>(translatable)<
            tubepress_youtube_api_Constants::OPTION_SHOW_RELATED     => 'Toggles the display of related videos after a video finishes.',                  //>(translatable)<

            tubepress_youtube_api_Constants::OPTION_DEV_KEY         => sprintf('YouTube will use this developer key for logging and debugging purposes if you experience a service problem on their end. You can register a new client ID and developer key <a href="%s" target="_blank">here</a>. Don\'t change this unless you know what you\'re doing.', "http://code.google.com/apis/youtube/dashboard/"),       //>(translatable)<
            tubepress_youtube_api_Constants::OPTION_EMBEDDABLE_ONLY => 'Some videos have embedding disabled. Checking this option will exclude these videos from your galleries.', //>(translatable)<
            tubepress_youtube_api_Constants::OPTION_FILTER          => 'Don\'t show videos that may not be suitable for minors.',           //>(translatable)<

            tubepress_youtube_api_Constants::OPTION_YOUTUBE_PLAYLIST_VALUE     => sprintf('The URL to any YouTube playlist (e.g. <a href="%s" target="_blank">%s</a>) or just the playlist identifier (e.g. %s).',  //>(translatable)<
                'http://youtube.com/playlist?list=48A83AD3506C9D36', 'http://youtube.com/playlist?list=48A83AD3506C9D36', '48A83AD3506C9D36'),
            tubepress_youtube_api_Constants::OPTION_YOUTUBE_TAG_VALUE          => 'YouTube limits this to 1,000 results.',                    //>(translatable)<
        );
    }

    /**
     * @return array An associative array, which may be empty but not null, of option names
     *               to their corresponding default values.
     */
    public function getMapOfOptionNamesToDefaultValues()
    {
        return array(

            tubepress_youtube_api_Constants::OPTION_AUTOHIDE         => tubepress_youtube_api_Constants::AUTOHIDE_HIDE_BAR_SHOW_CONTROLS,
            tubepress_youtube_api_Constants::OPTION_CLOSED_CAPTIONS  => false,
            tubepress_youtube_api_Constants::OPTION_DISABLE_KEYBOARD => false,
            tubepress_youtube_api_Constants::OPTION_FULLSCREEN       => true,
            tubepress_youtube_api_Constants::OPTION_MODEST_BRANDING  => true,
            tubepress_youtube_api_Constants::OPTION_SHOW_ANNOTATIONS => false,
            tubepress_youtube_api_Constants::OPTION_SHOW_CONTROLS    => tubepress_youtube_api_Constants::CONTROLS_SHOW_IMMEDIATE_FLASH,
            tubepress_youtube_api_Constants::OPTION_SHOW_RELATED     => true,
            tubepress_youtube_api_Constants::OPTION_THEME            => tubepress_youtube_api_Constants::PLAYER_THEME_DARK,

            tubepress_youtube_api_Constants::OPTION_DEV_KEY         => 'AI39si5uUzupiQW9bpzGqZRrhvqF3vBgRqL-I_28G1zWozmdNJlskzMDQEhpZ-l2RqGf_6CNWooL96oJZRrqKo-eJ9QO_QppMg',
            tubepress_youtube_api_Constants::OPTION_EMBEDDABLE_ONLY => true,
            tubepress_youtube_api_Constants::OPTION_FILTER          => tubepress_youtube_api_Constants::SAFESEARCH_MODERATE,

            tubepress_youtube_api_Constants::OPTION_YOUTUBE_MOST_POPULAR_VALUE => tubepress_youtube_api_Constants::TIMEFRAME_TODAY,
            tubepress_youtube_api_Constants::OPTION_YOUTUBE_RELATED_VALUE      => 'P9M__yYbsZ4',
            tubepress_youtube_api_Constants::OPTION_YOUTUBE_PLAYLIST_VALUE     => 'F679CB240DD4C112',
            tubepress_youtube_api_Constants::OPTION_YOUTUBE_FAVORITES_VALUE    => 'FPSRussia',
            tubepress_youtube_api_Constants::OPTION_YOUTUBE_TAG_VALUE          => 'pittsburgh steelers',
            tubepress_youtube_api_Constants::OPTION_YOUTUBE_USER_VALUE         => '3hough',

            tubepress_youtube_api_Constants::OPTION_RATING  => false,
            tubepress_youtube_api_Constants::OPTION_RATINGS => false,
        );
    }

    /**
     * @return array An array, which may be empty but not null, of option names
     *               that cannot be set via shortcode.
     */
    public function getOptionNamesThatCannotBeSetViaShortcode()
    {
        return array();
    }

    /**
     * @return array An array, which may be empty but not null, of option names
     *               that cannot be set via shortcode.
     */
    public function getOptionsNamesThatShouldNotBePersisted()
    {
        return array();
    }

    /**
     * @return array An array, which may be empty but not null, of option names
     *               to that have
     */
    public function getOptionNamesWithDynamicDiscreteAcceptableValues()
    {
        return array();
    }

    /**
     * @param $optionName string The option name.
     *
     * @return array An associative array, which may be empty but not null, of option names
     *               to their corresponding dynamic acceptable values.
     */
    public function getDynamicDiscreteAcceptableValuesForOption($optionName)
    {
        return array();
    }

    /**
     * @return array An array, which may be empty but not null, of option names
     *               that represent positive integers.
     */
    public function getOptionNamesOfPositiveIntegers()
    {
        return array();
    }

    /**
     * @return array An array, which may be empty but not null, of option names
     *               that represent non-negative integers.
     */
    public function getOptionNamesOfNonNegativeIntegers()
    {
        return array();
    }
}