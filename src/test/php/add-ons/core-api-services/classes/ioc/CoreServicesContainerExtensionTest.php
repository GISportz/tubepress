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
 * @covers tubepress_addons_coreapiservices_impl_ioc_CoreServicesContainerExtension
 */
class tubepress_test_addons_coreapiservices_ioc_CoreServicesContainerExtensionTest extends tubepress_test_impl_ioc_AbstractIocContainerExtensionTest
{

    /**
     * @return tubepress_api_ioc_ContainerExtensionInterface
     */
    protected function buildSut()
    {
        return new tubepress_addons_coreapiservices_impl_ioc_CoreServicesContainerExtension();
    }

    protected function prepareForLoad()
    {
        $this->_registerContext();
        $this->_registerPersistence();
        $this->_registerCurrentUrlService();
        $this->_registerEnvironment();
    }

    private function _registerPersistence()
    {
        $this->expectRegistration(

            tubepress_api_options_PersistenceInterface::_,
            'tubepress_addons_coreapiservices_impl_options_Persistence'
        )->withArgument(new tubepress_api_ioc_Reference(tubepress_api_options_PersistenceBackendInterface::_));
    }

    private function _registerContext()
    {
        $this->expectRegistration(

            tubepress_api_options_ContextInterface::_,
            'tubepress_addons_coreapiservices_impl_options_Context'
        );
    }

    private function _registerEnvironment()
    {
        $this->expectRegistration(

            tubepress_api_environment_EnvironmentInterface::_,
            'tubepress_addons_coreapiservices_impl_environment_Environment'
        )->withArgument(new tubepress_api_ioc_Reference(tubepress_api_url_UrlFactoryInterface::_));
    }

    private function _registerCurrentUrlService()
    {
        $this->expectRegistration(

            tubepress_api_url_CurrentUrlServiceInterface::_,
            'tubepress_addons_coreapiservices_impl_url_CurrentUrlService'
        )->withArgument($_SERVER)
         ->withArgument(new tubepress_api_ioc_Reference(tubepress_api_url_UrlFactoryInterface::_));
    }
}