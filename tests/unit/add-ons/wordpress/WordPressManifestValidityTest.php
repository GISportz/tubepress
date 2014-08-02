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

class tubepress_test_app_WordPressManifestValidityTestBoothelperuncached extends tubepress_test_platform_impl_boot_helper_uncached_contrib_AbstractManifestTest
{
    public function testManifest()
    {
        /**
         * @var $addon tubepress_platform_api_addon_AddonInterface
         */
        $addon = $this->getAddonFromManifest($this->getPathToManifest());

        $this->assertEquals('tubepress/wordpress', $addon->getName());
        $this->assertEquals('1.0.0', $addon->getVersion());
        $this->assertEquals('WordPress', $addon->getTitle());
        $this->assertEquals(array(array('name' => 'TubePress LLC', 'url' => 'http://tubepress.com')), $addon->getAuthors());
        $this->assertEquals(array(array('type' => 'MPL-2.0', 'url' => 'http://www.mozilla.org/MPL/2.0/')), $addon->getLicenses());
        $this->assertEquals('Allows TubePress to integrate with WordPress', $addon->getDescription());
    }

    protected function getPathToManifest()
    {
        return TUBEPRESS_ROOT . '/src/add-ons/wordpress/manifest.json';
    }

    protected function getClassNamesToIgnore()
    {
        return array(
            'tubepress_wordpress_ApiIntegrator'
        );
    }
}