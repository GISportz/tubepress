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
 * @covers tubepress_addons_wordpress_impl_options_PersistenceBackend<extended>
 */
class tubepress_test_addons_wordpress_impl_options_PersistenceBackendTest extends tubepress_test_TubePressUnitTest
{
    /**
     * @var tubepress_addons_wordpress_impl_options_PersistenceBackend
     */
    private $_sut;

    /**
     * @var ehough_mockery_mockery_MockInterface
     */
    private $_mockEnvironmentDetector;

    /**
     * @var ehough_mockery_mockery_MockInterface
     */
    private $_mockEventDispatcher;

    /**
     * @var ehough_mockery_mockery_MockInterface
     */
    private $_mockOptionValidator;

    /**
     * @var ehough_mockery_mockery_MockInterface
     */
    private $_mockOptionProvider;

    /**
     * @var ehough_mockery_mockery_MockInterface
     */
    private $_mockWordPressFunctionWrapper;

    /**
     * @var array[]
     */
    private $_existingStoredOptions;

    /**
     * Leave this here for the tests.
     */
    public $options = 'xyz';

    public function onSetup()
    {
        global $wpdb;

        $wpdb = $this;

        $this->_mockEnvironmentDetector      = $this->createMockSingletonService(tubepress_api_environment_EnvironmentInterface::_);
        $this->_mockEventDispatcher          = $this->createMockSingletonService(tubepress_api_event_EventDispatcherInterface::_);
        $this->_mockOptionProvider           = $this->createMockSingletonService(tubepress_spi_options_OptionProvider::_);
        $this->_mockWordPressFunctionWrapper = $this->createMockSingletonService(tubepress_addons_wordpress_spi_WpFunctionsInterface::_);

        $this->_sut = new tubepress_addons_wordpress_impl_options_PersistenceBackend();
    }

    public function onTearDown()
    {
        global $wpdb;

        unset($wpdb);
    }


    public function testCreate()
    {
        $this->_existingStoredOptions = array();

        $this->_mockWordPressFunctionWrapper->shouldReceive('add_option')->once()->with('tubepress-a', 'b');
        $this->_mockWordPressFunctionWrapper->shouldReceive('add_option')->once()->with('tubepress-c', 'd');

        $this->_sut->createEach(array('a' => 'b', 'c' => 'd'));
    }

    public function testSaveAll()
    {
        $this->_existingStoredOptions = array('a' => 'b');

        $this->_mockWordPressFunctionWrapper->shouldReceive('update_option')->once()->with('tubepress-foo', 'bar');

        $result = $this->_sut->saveAll(array('foo' => 'bar'));

        $this->assertNull($result);
    }

    public function get_results($query)
    {
        $this->assertEquals("SELECT option_name, option_value FROM xyz WHERE option_name LIKE 'tubepress-%'", $query);

        $toReturn = array();

        foreach ($this->_existingStoredOptions as $name => $value) {

            $fake = new stdClass();

            $fake->option_name  = $name;
            $fake->option_value = $value;

            $toReturn[] = $fake;
        }

        return $toReturn;
    }
}

