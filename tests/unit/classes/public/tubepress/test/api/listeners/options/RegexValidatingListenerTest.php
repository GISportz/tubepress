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
 * @covers tubepress_api_options_listeners_RegexValidatingListener<extended>
 */
class tubepress_test_api_listeners_options_RegexValidatingListenerTest extends tubepress_api_test_TubePressUnitTest
{
    /**
     * @var tubepress_api_options_listeners_RegexValidatingListener
     */
    private $_sut;

    /**
     * @var Mockery\MockInterface
     */
    private $_mockEvent;

    /**
     * @var Mockery\MockInterface
     */
    private $_mockTranslation;

    /**
     * @var Mockery\MockInterface
     */
    private $_mockReference;

    public function onSetup()
    {
        $this->_mockEvent       = $this->mock('tubepress_api_event_EventInterface');
        $this->_mockTranslation = $this->mock(tubepress_api_translation_TranslatorInterface::_);
        $this->_mockReference   = $this->mock(tubepress_api_options_ReferenceInterface::_);

        $this->_mockEvent->shouldReceive('getSubject')->once()->andReturnNull();
        $this->_mockEvent->shouldReceive('getArgument')->once()->with('optionName')->andReturn('option-name');
    }

    /**
     * @dataProvider getData
     */
    public function testOnOptionSet($type, $incoming, $expectedToPass)
    {
        $this->_sut = new tubepress_api_options_listeners_RegexValidatingListener($type, $this->_mockReference, $this->_mockTranslation);

        $this->_mockEvent->shouldReceive('getArgument')->once()->with('optionValue')->andReturn($incoming);

        if (!$expectedToPass) {

            $this->_mockTranslation->shouldReceive('trans')->once()->with('Invalid value supplied for "%s".')->andReturn('omg %s');
            $this->_mockTranslation->shouldReceive('trans')->once()->with('something awesome')->andReturn('holy smokes');
            $this->_mockReference->shouldReceive('getUntranslatedLabel')->twice()->with('option-name')->andReturn('something awesome');
            $this->_mockEvent->shouldReceive('setSubject')->once()->with(array('omg holy smokes'));
            $this->_mockEvent->shouldReceive('stopPropagation')->once();
        }

        $this->_sut->onOptionValidation($this->_mockEvent);

        $this->assertTrue(true);
    }

    public function getData()
    {
        return array(

            array(tubepress_api_options_listeners_RegexValidatingListener::TYPE_INTEGER_POSITIVE, 1, true),
            array(tubepress_api_options_listeners_RegexValidatingListener::TYPE_INTEGER_POSITIVE, '1', true),
            array(tubepress_api_options_listeners_RegexValidatingListener::TYPE_INTEGER_POSITIVE, 0, false),
            array(tubepress_api_options_listeners_RegexValidatingListener::TYPE_INTEGER_POSITIVE, '0', false),
            array(tubepress_api_options_listeners_RegexValidatingListener::TYPE_INTEGER_POSITIVE, -1, false),
            array(tubepress_api_options_listeners_RegexValidatingListener::TYPE_INTEGER_POSITIVE, '-1', false),

            array(tubepress_api_options_listeners_RegexValidatingListener::TYPE_INTEGER_NONNEGATIVE, 1, true),
            array(tubepress_api_options_listeners_RegexValidatingListener::TYPE_INTEGER_NONNEGATIVE, '1', true),
            array(tubepress_api_options_listeners_RegexValidatingListener::TYPE_INTEGER_NONNEGATIVE, 0, true),
            array(tubepress_api_options_listeners_RegexValidatingListener::TYPE_INTEGER_NONNEGATIVE, '0', true),
            array(tubepress_api_options_listeners_RegexValidatingListener::TYPE_INTEGER_NONNEGATIVE, -1, false),
            array(tubepress_api_options_listeners_RegexValidatingListener::TYPE_INTEGER_NONNEGATIVE, '-1', false),

            array(tubepress_api_options_listeners_RegexValidatingListener::TYPE_INTEGER, 1, true),
            array(tubepress_api_options_listeners_RegexValidatingListener::TYPE_INTEGER, -1, true),
            array(tubepress_api_options_listeners_RegexValidatingListener::TYPE_INTEGER, 0, true),
            array(tubepress_api_options_listeners_RegexValidatingListener::TYPE_INTEGER, '1', true),
            array(tubepress_api_options_listeners_RegexValidatingListener::TYPE_INTEGER, '-1', true),
            array(tubepress_api_options_listeners_RegexValidatingListener::TYPE_INTEGER, '0', true),
            array(tubepress_api_options_listeners_RegexValidatingListener::TYPE_INTEGER, '1.5', false),
            array(tubepress_api_options_listeners_RegexValidatingListener::TYPE_INTEGER, '-1.5', false),
            array(tubepress_api_options_listeners_RegexValidatingListener::TYPE_INTEGER, '0.5', false),

            array(tubepress_api_options_listeners_RegexValidatingListener::TYPE_ZERO_OR_MORE_WORDCHARS, '', true),
            array(tubepress_api_options_listeners_RegexValidatingListener::TYPE_ZERO_OR_MORE_WORDCHARS, 'x', true),
            array(tubepress_api_options_listeners_RegexValidatingListener::TYPE_ZERO_OR_MORE_WORDCHARS, '3', true),
            array(tubepress_api_options_listeners_RegexValidatingListener::TYPE_ZERO_OR_MORE_WORDCHARS, 3, true),
            array(tubepress_api_options_listeners_RegexValidatingListener::TYPE_ZERO_OR_MORE_WORDCHARS, '-', false),
            array(tubepress_api_options_listeners_RegexValidatingListener::TYPE_ZERO_OR_MORE_WORDCHARS, '_', true),

            array(tubepress_api_options_listeners_RegexValidatingListener::TYPE_ONE_OR_MORE_WORDCHARS, '', false),
            array(tubepress_api_options_listeners_RegexValidatingListener::TYPE_ONE_OR_MORE_WORDCHARS, 'x', true),
            array(tubepress_api_options_listeners_RegexValidatingListener::TYPE_ONE_OR_MORE_WORDCHARS, '3', true),
            array(tubepress_api_options_listeners_RegexValidatingListener::TYPE_ONE_OR_MORE_WORDCHARS, 3, true),
            array(tubepress_api_options_listeners_RegexValidatingListener::TYPE_ONE_OR_MORE_WORDCHARS, '-', false),
            array(tubepress_api_options_listeners_RegexValidatingListener::TYPE_ONE_OR_MORE_WORDCHARS, '_', true),

            array(tubepress_api_options_listeners_RegexValidatingListener::TYPE_ONE_OR_MORE_WORDCHARS_OR_HYPHEN, '', false),
            array(tubepress_api_options_listeners_RegexValidatingListener::TYPE_ONE_OR_MORE_WORDCHARS_OR_HYPHEN, 'x', true),
            array(tubepress_api_options_listeners_RegexValidatingListener::TYPE_ONE_OR_MORE_WORDCHARS_OR_HYPHEN, '3', true),
            array(tubepress_api_options_listeners_RegexValidatingListener::TYPE_ONE_OR_MORE_WORDCHARS_OR_HYPHEN, 3, true),
            array(tubepress_api_options_listeners_RegexValidatingListener::TYPE_ONE_OR_MORE_WORDCHARS_OR_HYPHEN, '-', true),
            array(tubepress_api_options_listeners_RegexValidatingListener::TYPE_ONE_OR_MORE_WORDCHARS_OR_HYPHEN, '_', true),

            array(tubepress_api_options_listeners_RegexValidatingListener::TYPE_STRING_HEXCOLOR, '', false),
            array(tubepress_api_options_listeners_RegexValidatingListener::TYPE_STRING_HEXCOLOR, 'x', false),
            array(tubepress_api_options_listeners_RegexValidatingListener::TYPE_STRING_HEXCOLOR, 'aaaaaa', true),
            array(tubepress_api_options_listeners_RegexValidatingListener::TYPE_STRING_HEXCOLOR, 111222, true),

            array(tubepress_api_options_listeners_RegexValidatingListener::TYPE_STRING_YOUTUBE_VIDEO_ID, '12345678901', true),
            array(tubepress_api_options_listeners_RegexValidatingListener::TYPE_STRING_YOUTUBE_VIDEO_ID, '1234567890', false),
            array(tubepress_api_options_listeners_RegexValidatingListener::TYPE_STRING_YOUTUBE_VIDEO_ID, '1234567890-', true),
            array(tubepress_api_options_listeners_RegexValidatingListener::TYPE_STRING_YOUTUBE_VIDEO_ID, '1234567890_', true),
            array(tubepress_api_options_listeners_RegexValidatingListener::TYPE_STRING_YOUTUBE_VIDEO_ID, '1234567890&', false),

            array(tubepress_api_options_listeners_RegexValidatingListener::TYPE_DOMAIN, 'foo.com',      true),
            array(tubepress_api_options_listeners_RegexValidatingListener::TYPE_DOMAIN, 'foo.bar.com',  true),
            array(tubepress_api_options_listeners_RegexValidatingListener::TYPE_DOMAIN, '123.com',      true),
            array(tubepress_api_options_listeners_RegexValidatingListener::TYPE_DOMAIN, '123.biz',      true),
            array(tubepress_api_options_listeners_RegexValidatingListener::TYPE_DOMAIN, 'foo.bar.com.', false),
            array(tubepress_api_options_listeners_RegexValidatingListener::TYPE_DOMAIN, 'foo',          false),
            array(tubepress_api_options_listeners_RegexValidatingListener::TYPE_DOMAIN, '.bar.foo',     false),
            array(tubepress_api_options_listeners_RegexValidatingListener::TYPE_DOMAIN, '123.123',      false),

            array(tubepress_api_options_listeners_RegexValidatingListener::TYPE_TWO_DIGIT_COUNTRY_CODE, 'US', true),
            array(tubepress_api_options_listeners_RegexValidatingListener::TYPE_TWO_DIGIT_COUNTRY_CODE, 'IT', true),
            array(tubepress_api_options_listeners_RegexValidatingListener::TYPE_TWO_DIGIT_COUNTRY_CODE, 'us', false),
            array(tubepress_api_options_listeners_RegexValidatingListener::TYPE_TWO_DIGIT_COUNTRY_CODE, 'ABC', false),
            array(tubepress_api_options_listeners_RegexValidatingListener::TYPE_TWO_DIGIT_COUNTRY_CODE, '123', false),

            array(tubepress_api_options_listeners_RegexValidatingListener::TYPE_TWO_DIGIT_LANGUAGE_CODE, 'en', true),
            array(tubepress_api_options_listeners_RegexValidatingListener::TYPE_TWO_DIGIT_LANGUAGE_CODE, 'EN', false),

            array(tubepress_api_options_listeners_RegexValidatingListener::TYPE_DOM_ELEMENT_ID_OR_NAME, 'foo', true),
            array(tubepress_api_options_listeners_RegexValidatingListener::TYPE_DOM_ELEMENT_ID_OR_NAME, 'foo1', true),
            array(tubepress_api_options_listeners_RegexValidatingListener::TYPE_DOM_ELEMENT_ID_OR_NAME, 'foo:', true),
            array(tubepress_api_options_listeners_RegexValidatingListener::TYPE_DOM_ELEMENT_ID_OR_NAME, '1foo', false),
            array(tubepress_api_options_listeners_RegexValidatingListener::TYPE_DOM_ELEMENT_ID_OR_NAME, ':foo', false),
        );
    }
}