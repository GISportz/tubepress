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
 * @covers tubepress_event_ioc_compiler_RegisterListenersPass<extended>
 */
class tubepress_test_event_ioc_compiler_RegisterListenersPassTest extends tubepress_api_test_TubePressUnitTest
{
    /**
     * @var tubepress_event_ioc_compiler_RegisterListenersPass
     */
    private $_sut;

    /**
     * @var Mockery\MockInterface
     */
    private $_mockContainer;

    /**
     * @var Mockery\MockInterface
     */
    private $_mockEventDispatcherDefinition;

    public function onSetup()
    {
        $this->_sut                           = new tubepress_event_ioc_compiler_RegisterListenersPass();
        $this->_mockContainer                 = $this->mock('tubepress_api_ioc_ContainerBuilderInterface');
        $this->_mockEventDispatcherDefinition = $this->mock('tubepress_platform_api_ioc_Definition');
    }

    public function testNoDispatcherService()
    {
        $this->_mockContainer->shouldReceive('hasDefinition')->once()->with(tubepress_api_event_EventDispatcherInterface::_)->andReturn(false);
        $this->_sut->process($this->_mockContainer);
        $this->assertTrue(true);
    }

    public function testNoEventInTag()
    {
        $this->setExpectedException('InvalidArgumentException', 'Service "foo" must define the "event" attribute on "tubepress.lib.ioc.tag.eventListener" tags');

        $this->_mockContainer->shouldReceive('hasDefinition')->once()->with(tubepress_api_event_EventDispatcherInterface::_)->andReturn(true);
        $this->_mockContainer->shouldReceive('getDefinition')->once()->with(tubepress_api_event_EventDispatcherInterface::_)->andReturn($this->_mockEventDispatcherDefinition);
        $this->_mockContainer->shouldReceive('findTaggedServiceIds')->once()->with(tubepress_api_ioc_ServiceTags::EVENT_LISTENER)->andReturn(array('foo' => array(array('method' => 'some method'))));

        $this->_sut->process($this->_mockContainer);
    }

    public function testNoMethod()
    {
        $this->_mockContainer->shouldReceive('hasDefinition')->once()->with(tubepress_api_event_EventDispatcherInterface::_)->andReturn(true);
        $this->_mockContainer->shouldReceive('getDefinition')->once()->with(tubepress_api_event_EventDispatcherInterface::_)->andReturn($this->_mockEventDispatcherDefinition);
        $this->_mockContainer->shouldReceive('findTaggedServiceIds')->once()->with(tubepress_api_ioc_ServiceTags::EVENT_LISTENER)->andReturn(array('foo' => array(array('event' => 'some event'))));

        $this->_mockEventDispatcherDefinition->shouldReceive('addMethodCall')->once()->with('addListenerService', array('some event', array('foo', 'onSomeEvent'), 0));

        $this->_sut->process($this->_mockContainer);

        $this->assertTrue(true);
    }

    public function testNoPriority()
    {
        $this->_mockContainer->shouldReceive('hasDefinition')->once()->with(tubepress_api_event_EventDispatcherInterface::_)->andReturn(true);
        $this->_mockContainer->shouldReceive('getDefinition')->once()->with(tubepress_api_event_EventDispatcherInterface::_)->andReturn($this->_mockEventDispatcherDefinition);
        $this->_mockContainer->shouldReceive('findTaggedServiceIds')->once()->with(tubepress_api_ioc_ServiceTags::EVENT_LISTENER)->andReturn(array('foo' => array(array('method' => 'some method', 'event' => 'some event'))));

        $this->_mockEventDispatcherDefinition->shouldReceive('addMethodCall')->once()->with('addListenerService', array('some event', array('foo', 'some method'), 0));

        $this->_sut->process($this->_mockContainer);

        $this->assertTrue(true);
    }

    public function testFullyPopulatedListener()
    {
        $this->_mockContainer->shouldReceive('hasDefinition')->once()->with(tubepress_api_event_EventDispatcherInterface::_)->andReturn(true);
        $this->_mockContainer->shouldReceive('getDefinition')->once()->with(tubepress_api_event_EventDispatcherInterface::_)->andReturn($this->_mockEventDispatcherDefinition);
        $this->_mockContainer->shouldReceive('findTaggedServiceIds')->once()->with(tubepress_api_ioc_ServiceTags::EVENT_LISTENER)->andReturn(array('foo' => array(array('method' => 'some method', 'event' => 'some event', 'priority' => 5))));

        $this->_mockEventDispatcherDefinition->shouldReceive('addMethodCall')->once()->with('addListenerService', array('some event', array('foo', 'some method'), 5));

        $this->_sut->process($this->_mockContainer);

        $this->assertTrue(true);
    }
}
