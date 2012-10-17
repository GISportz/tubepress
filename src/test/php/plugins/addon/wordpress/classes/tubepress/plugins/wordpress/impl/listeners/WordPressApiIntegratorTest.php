<?php
/**
 * Copyright 2006 - 2012 Eric D. Hough (http://ehough.com)
 *
 * This file is part of TubePress (http://tubepress.org)
 *
 * TubePress is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * TubePress is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with TubePress.  If not, see <http://www.gnu.org/licenses/>.
 *
 */
class tubepress_plugins_corewordpress_listeners_WordPressBootTest extends TubePressUnitTest
{
    private $_sut;

    private $_mockEnvironmentDetector;

    private $_mockWpFunctionWrapper;

    private $_mockContentFilter;

    private $_mockJsAndCssInjector;

    private $_mockWpAdminHandler;

    private $_mockWidgetHandler;

    function setup()
    {
        $this->_sut = new tubepress_plugins_wordpress_impl_listeners_WordPressApiIntegrator();

        $this->_mockEnvironmentDetector = Mockery::mock(tubepress_spi_environment_EnvironmentDetector::_);
        $this->_mockWpFunctionWrapper   = Mockery::mock(tubepress_plugins_wordpress_spi_WordPressFunctionWrapper::_);
        $this->_mockContentFilter       = Mockery::mock(tubepress_plugins_wordpress_spi_ContentFilter::_);
        $this->_mockJsAndCssInjector    = Mockery::mock(tubepress_plugins_wordpress_spi_FrontEndCssAndJsInjector::_);
        $this->_mockWpAdminHandler      = Mockery::mock(tubepress_plugins_wordpress_spi_WpAdminHandler::_);
        $this->_mockWidgetHandler       = Mockery::mock(tubepress_plugins_wordpress_spi_WidgetHandler::_);

        tubepress_impl_patterns_ioc_KernelServiceLocator::setEnvironmentDetector($this->_mockEnvironmentDetector);
        tubepress_plugins_wordpress_impl_patterns_ioc_WordPressServiceLocator::setWordPressFunctionWrapper($this->_mockWpFunctionWrapper);
        tubepress_plugins_wordpress_impl_patterns_ioc_WordPressServiceLocator::setContentFilter($this->_mockContentFilter);
        tubepress_plugins_wordpress_impl_patterns_ioc_WordPressServiceLocator::setFrontEndCssAndJsInjector($this->_mockJsAndCssInjector);
        tubepress_plugins_wordpress_impl_patterns_ioc_WordPressServiceLocator::setWpAdminHandler($this->_mockWpAdminHandler);
        tubepress_plugins_wordpress_impl_patterns_ioc_WordPressServiceLocator::setWidgetHandler($this->_mockWidgetHandler);
    }

    function testWordPress()
    {
        $this->_mockWpFunctionWrapper->shouldReceive('wp_version')->once()->andReturn('3.1');

        $this->_mockWpFunctionWrapper->shouldReceive('content_url')->once()->andReturn('valueofcontenturl');

        $this->_mockWpFunctionWrapper->shouldReceive('load_plugin_textdomain')->once()->with('tubepress', false, 'tubepress/src/main/resources/i18n');

        $this->_mockWpFunctionWrapper->shouldReceive('add_filter')->once()->with('the_content', array($this->_mockContentFilter, 'filterContent'), 10, 1);
        $this->_mockWpFunctionWrapper->shouldReceive('add_action')->once()->with('wp_head', array($this->_mockJsAndCssInjector, 'printInHtmlHead'), 10, 1);
        $this->_mockWpFunctionWrapper->shouldReceive('add_action')->once()->with('init', array($this->_mockJsAndCssInjector, 'registerStylesAndScripts'), 10, 1);
        $this->_mockWpFunctionWrapper->shouldReceive('add_action')->once()->with('admin_menu', array($this->_mockWpAdminHandler, 'registerAdminMenuItem'), 10, 1);
        $this->_mockWpFunctionWrapper->shouldReceive('add_action')->once()->with('admin_enqueue_scripts', array($this->_mockWpAdminHandler, 'registerStylesAndScripts'), 10, 1);
        $this->_mockWpFunctionWrapper->shouldReceive('add_action')->once()->with('widgets_init', array($this->_mockWidgetHandler, 'registerWidget'), 10, 1);
        $this->_mockWpFunctionWrapper->shouldReceive('add_filter')->once()->with('plugin_row_meta', array($this->_mockWpAdminHandler, 'modifyMetaRowLinks'), 10, 2);


        $this->_sut->onBoot(new ehough_tickertape_impl_GenericEvent());

        global $tubepress_base_url;
        $this->assertEquals('valueofcontenturl/plugins/tubepress', $tubepress_base_url);
    }

}
