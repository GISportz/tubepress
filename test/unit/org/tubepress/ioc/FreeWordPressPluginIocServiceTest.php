<?php

require_once dirname(__FILE__) . '/../../../../../test/unit/TubePressUnitTest.php';

function get_option() { }
function update_option() { }

class org_tubepress_ioc_FreeWordPressPluginIocServiceTest extends TubePressUnitTest {

    private $_sut;
    private $_expectedMapping;
    
    function setUp()
    {
        $this->_sut = new org_tubepress_ioc_impl_FreeWordPressPluginIocService();
        $this->_expectedMapping = array(
            'org_tubepress_browser_BrowserDetector'                     => 'org_tubepress_browser_MobileEspBrowserDetector',
            'org_tubepress_cache_CacheService'                          => 'org_tubepress_cache_PearCacheLiteCacheService',
            'org_tubepress_embedded_EmbeddedPlayerService'              => 'org_tubepress_embedded_impl_DelegatingEmbeddedPlayerService',
            'org_tubepress_embedded_impl_YouTubeEmbeddedPlayerService'  => 'org_tubepress_embedded_impl_YouTubeEmbeddedPlayerService',
            'org_tubepress_gallery_Gallery'                             => 'org_tubepress_gallery_SimpleGallery',
            'org_tubepress_message_MessageService'                      => 'org_tubepress_message_impl_WordPressMessageService',
            'org_tubepress_options_manager_OptionsManager'              => 'org_tubepress_options_manager_SimpleOptionsManager',    
            'org_tubepress_options_storage_StorageManager'              => 'org_tubepress_options_storage_WordPressStorageManager',
            'org_tubepress_options_validation_InputValidationService'   => 'org_tubepress_options_validation_SimpleInputValidationService',    
            'org_tubepress_pagination_PaginationService'                => 'org_tubepress_pagination_DiggStylePaginationService',
            'org_tubepress_player_Player'                               => 'org_tubepress_player_SimplePlayer',
            'org_tubepress_querystring_QueryStringService'              => 'org_tubepress_querystring_SimpleQueryStringService',
            'org_tubepress_shortcode_ShortcodeParser'                   => 'org_tubepress_shortcode_SimpleShortcodeParser',
            'org_tubepress_single_SingleVideo'                          => 'org_tubepress_single_SimpleSingleVideo',
            'org_tubepress_theme_ThemeHandler'                          => 'org_tubepress_theme_SimpleThemeHandler',
            'org_tubepress_url_UrlBuilder'                              => 'org_tubepress_url_impl_DelegatingUrlBuilder',
            'org_tubepress_video_factory_VideoFactory'                  => 'org_tubepress_video_factory_DelegatingVideoFactory',
            'org_tubepress_video_feed_inspection_FeedInspectionService' => 'org_tubepress_video_feed_inspection_DelegatingFeedInspectionService',
            'org_tubepress_video_feed_provider_Provider'                => 'org_tubepress_video_feed_provider_SimpleProvider',
            'org_tubepress_video_feed_retrieval_FeedRetrievalService'   => 'org_tubepress_video_feed_retrieval_HTTPRequest2',
        
            'org_tubepress_embedded_impl_VimeoEmbeddedPlayerService'                => 'org_tubepress_embedded_impl_VimeoEmbeddedPlayerService',
            'org_tubepress_url_impl_VimeoUrlBuilder'                                => 'org_tubepress_url_impl_VimeoUrlBuilder',
            'org_tubepress_video_feed_inspection_impl_VimeoFeedInspectionService'   => 'org_tubepress_video_feed_inspection_impl_VimeoFeedInspectionService',
            'org_tubepress_video_feed_inspection_impl_YouTubeFeedInspectionService' => 'org_tubepress_video_feed_inspection_impl_YouTubeFeedInspectionService',
            'org_tubepress_url_impl_YouTubeUrlBuilder'                              => 'org_tubepress_url_impl_YouTubeUrlBuilder',
            'org_tubepress_video_factory_impl_YouTubeVideoFactory'                  => 'org_tubepress_video_factory_impl_YouTubeVideoFactory',
            'org_tubepress_video_factory_impl_VimeoVideoFactory'                    => 'org_tubepress_video_factory_impl_VimeoVideoFactory'
        );
    }

    function testMapping()
    {
        foreach ($this->_expectedMapping as $key => $value) {
            $test = is_a($this->_sut->get($key), $value);
            if (!$test) {
                print "$key is not a $value\n";
            }
            $this->assertTrue($test);
        }
    }
}
?>
