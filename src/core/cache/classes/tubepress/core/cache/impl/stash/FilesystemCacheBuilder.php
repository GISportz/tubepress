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
 * Adds shortcode handlers to TubePress.
 */
class tubepress_core_cache_impl_stash_FilesystemCacheBuilder
{
    /**
     * @var tubepress_core_options_api_ContextInterface
     */
    private $_context;

    /**
     * @var ehough_filesystem_FilesystemInterface
     */
    private $_filesystem;

    public function __construct(tubepress_core_options_api_ContextInterface $context,
                                ehough_filesystem_FilesystemInterface       $fs)
    {
        $this->_context    = $context;
        $this->_filesystem = $fs;
    }

    public function buildCache()
    {
        $dir = $this->_context->get(tubepress_core_cache_api_Constants::DIRECTORY);

        if (!$dir || !is_writable($dir)) {

            @mkdir($dir, 0755, true);
        }

        if (!$dir || !is_writable($dir)) {

            $dir = $this->_filesystem->getSystemTempDirectory() . DIRECTORY_SEPARATOR . 'tubepress-api-cache';
        }

        return new ehough_stash_Pool(new ehough_stash_driver_FileSystem(array(

            'path' => $dir
        )));
    }
}