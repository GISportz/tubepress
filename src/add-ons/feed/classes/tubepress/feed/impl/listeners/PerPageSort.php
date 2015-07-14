<?php
/**
 * Copyright 2006 - 2015 TubePress LLC (http://tubepress.com)
 *
 * This file is part of TubePress (http://tubepress.com)
 *
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/.
 */

/**
 *
 */
class tubepress_feed_impl_listeners_PerPageSort
{
    public function onAcceptableValues(tubepress_api_event_EventInterface $event)
    {
        $current = $event->getSubject();

        if (!is_array($current)) {

            $current = array();
        }

        $result = array(
            tubepress_api_options_AcceptableValues::PER_PAGE_SORT_NONE          => 'none',                            //>(translatable)<
            tubepress_api_options_AcceptableValues::PER_PAGE_SORT_COMMENT_COUNT => 'comment count',                   //>(translatable)<
            tubepress_api_options_AcceptableValues::PER_PAGE_SORT_NEWEST        => 'date published (newest first)',   //>(translatable)<
            tubepress_api_options_AcceptableValues::PER_PAGE_SORT_OLDEST        => 'date published (oldest first)',   //>(translatable)<
            tubepress_api_options_AcceptableValues::PER_PAGE_SORT_DURATION      => 'length',                          //>(translatable)<
            tubepress_api_options_AcceptableValues::PER_PAGE_SORT_RANDOM        => 'random',                          //>(translatable)<
            tubepress_api_options_AcceptableValues::PER_PAGE_SORT_TITLE         => 'title',                           //>(translatable)<
            tubepress_api_options_AcceptableValues::PER_PAGE_SORT_VIEW_COUNT    => 'view count',                      //>(translatable)<
        );

        $toSet = array_merge($current, $result);

        $event->setSubject($toSet);
    }
}