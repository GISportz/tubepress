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
 */
class tubepress_core_provider_impl_HttpMediaProvider implements tubepress_core_provider_api_MediaProviderInterface
{
    /**
     * @var tubepress_api_log_LoggerInterface
     */
    private $_logger;

    /**
     * @var tubepress_core_event_api_EventDispatcherInterface
     */
    private $_eventDispatcher;

    /**
     * @var tubepress_core_provider_api_HttpProviderInterface
     */
    private $_delegate;

    /**
     * @var tubepress_core_http_api_HttpClientInterface
     */
    private $_httpClient;

    public function __construct(tubepress_core_provider_api_HttpProviderInterface $delegate,
                                tubepress_api_log_LoggerInterface                 $logger,
                                tubepress_core_event_api_EventDispatcherInterface $eventDispatcher,
                                tubepress_core_http_api_HttpClientInterface       $httpClient)
    {
        $this->_logger          = $logger;
        $this->_eventDispatcher = $eventDispatcher;
        $this->_delegate        = $delegate;
        $this->_httpClient      = $httpClient;
    }

    /**
     * Fetch a media page.
     *
     * @param int $currentPage The requested page number of the gallery.
     *
     * @return tubepress_core_provider_api_Page The media gallery page for this page. May be empty, never null.
     *
     * @api
     * @since 4.0.0
     */
    public function fetchPage($currentPage)
    {
        $toReturn     = new tubepress_core_provider_api_Page();
        $debugEnabled = $this->_logger->isEnabled();

        if ($debugEnabled) {

            $this->_logger->debug(sprintf('Current page number is %d', $currentPage));
        }

        $url = $this->_delegate->buildUrlForPage($currentPage);
        $url = $this->_dispatchUrl($url, tubepress_core_provider_api_Constants::EVENT_URL_MEDIA_PAGE, array(
            'pageNumber' => $currentPage
        ));

        if ($debugEnabled) {

            $this->_logger->debug(sprintf('URL to fetch is <code>%s</code>', $url));
        }

        $rawFeed                  = $this->_fetchFeedAndPrepareForAnalysis($url);
        $reportedTotalResultCount = $this->_delegate->getTotalResultCount($rawFeed);

        /**
         * If no results, we can shortcut things here.
         */
        if ($reportedTotalResultCount < 1) {

            $this->_delegate->onAnalysisComplete($rawFeed);
            return $this->_emptyPage($toReturn);
        }

        if ($debugEnabled) {

            $this->_logger->debug(sprintf('Reported total result count is %d video(s)', $reportedTotalResultCount));
        }

        /* convert the feed to videos */
        $videoArray = $this->_feedToVideoArray($rawFeed);

        if (count($videoArray) == 0) {

            return $this->_emptyPage($toReturn);
        }

        $toReturn->setTotalResultCount($reportedTotalResultCount);
        $toReturn->setItems($videoArray);

        return $toReturn;
    }

    /**
     * Fetch a single media item.
     *
     * @param string $itemId The item ID to fetch.
     *
     * @return tubepress_core_provider_api_MediaItem The media item, or null if unable to retrive.
     *
     * @api
     * @since 4.0.0
     */
    public function fetchSingle($itemId)
    {
        $isLoggerDebugEnabled = $this->_logger->isEnabled();

        if ($isLoggerDebugEnabled) {

            $this->_logger->debug(sprintf('Fetching media item with ID <code>%s</code>', $itemId));
        }

        $videoUrl = $this->_delegate->buildUrlForSingle($itemId);
        $videoUrl = $this->_dispatchUrl($videoUrl, tubepress_core_provider_api_Constants::EVENT_URL_MEDIA_ITEM, array(
            'itemId' => $itemId
        ));

        if ($isLoggerDebugEnabled) {

            $this->_logger->debug(sprintf('URL to fetch is <a href="%s">this</a>', $videoUrl));
        }

        $feed       = $this->_fetchFeedAndPrepareForAnalysis($videoUrl);
        $videoArray = $this->_feedToVideoArray($feed);
        $toReturn   = null;

        if (count($videoArray) > 0) {

            return $videoArray[0];
        }

        return null;
    }

    /**
     * @return string
     *
     * @api
     * @since 4.0.0
     */
    public function getAttributeNameOfItemDescription()
    {
        return $this->_delegate->getAttributeNameOfItemDescription();
    }

    /**
     * @return string
     *
     * @api
     * @since 4.0.0
     */
    public function getAttributeNameOfItemTitle()
    {
        return $this->_delegate->getAttributeNameOfItemTitle();
    }

    /**
     * @return string
     *
     * @api
     * @since 4.0.0
     */
    public function getAttributeNameOfItemId()
    {
        return $this->_delegate->getAttributeNameOfItemId();
    }

    /**
     * @return array
     *
     * @api
     * @since 4.0.0
     */
    public function getMapOfFormattedDateAttributeNamesToUnixTimeAttributeNames()
    {
        return $this->_delegate->getMapOfFormattedDateAttributeNamesToUnixTimeAttributeNames();
    }

    /**
     * @param tubepress_core_provider_api_MediaItem $first
     * @param tubepress_core_provider_api_MediaItem $second
     * @param string                                $perPageSort
     *
     * @return int
     */
    public function compareForPerPageSort(tubepress_core_provider_api_MediaItem $first, tubepress_core_provider_api_MediaItem $second, $perPageSort)
    {
        return $this->_delegate->compareForPerPageSort($first, $second, $perPageSort);
    }

    /**
     * Ask this media provider if it recognizes the given item ID.
     *
     * @param string $mediaId The globally unique media item identifier.
     *
     * @return boolean True if this provider recognizes the given item ID, false otherwise.
     *
     * @api
     * @since 4.0.0
     */
    public function recognizesItemId($mediaId)
    {
        return $this->_delegate->recognizesItemId($mediaId);
    }

    /**
     * @return string The name of the "mode" value that this provider uses for searching.
     *
     * @api
     * @since 4.0.0
     */
    public function getSearchModeName()
    {
        return $this->_delegate->getSearchModeName();
    }

    /**
     * @return array An array of the valid option values for the "mode" option.
     */
    public function getGallerySourceNames()
    {
        return $this->_delegate->getGallerySourceNames();
    }

    /**
     * @return string The name of this video provider. Never empty or null. All lowercase alphanumerics and dashes.
     */
    public function getName()
    {
        return $this->_delegate->getName();
    }

    /**
     * @return array An array of meta names
     *
     * @api
     * @since 4.0.0
     */
    public function getMetaOptionNames()
    {
        return $this->_delegate->getMetaOptionNames();
    }

    /**
     * @return string The option name where TubePress should put the users search results.
     *
     * @api
     * @since 4.0.0
     */
    public function getSearchQueryOptionName()
    {
        return $this->_delegate->getSearchQueryOptionName();
    }

    /**
     * @return array
     *
     * @api
     * @since 4.0.0
     */
    public function getMapOfFeedSortNamesToUntranslatedLabels()
    {
        return $this->_delegate->getMapOfFeedSortNamesToUntranslatedLabels();
    }

    /**
     * @return array
     *
     * @api
     * @since 4.0.0
     */
    public function getMapOfHhMmSsAttributeNamesToSecondsAttributeNames()
    {
        return $this->_delegate->getMapOfHhMmSsAttributeNamesToSecondsAttributeNames();
    }

    /**
     * @return string The human-readable name of this media provider.
     *
     * @api
     * @since 4.0.0
     */
    public function getDisplayName()
    {
        return $this->_delegate->getDisplayName();
    }

    /**
     * @return string[]
     *
     * @api
     * @since 4.0.0
     */
    public function getAttributeNamesOfIntegersToFormat()
    {
        return $this->_delegate->getAttributeNamesOfIntegersToFormat();
    }

    /**
     * @return array
     *
     * @api
     * @since 4.0.0
     */
    public function getMapOfPerPageSortNamesToUntranslatedLabels()
    {
        return $this->_delegate->getMapOfPerPageSortNamesToUntranslatedLabels();
    }

    private function _feedToVideoArray($feed)
    {
        $toReturn       = array();
        $total          = $this->_delegate->getCurrentResultCount($feed);
        $isDebugEnabled = $this->_logger->isEnabled();

        if ($isDebugEnabled) {

            $this->_logger->debug(sprintf('Now attempting to build %d video(s) from raw feed', $total));
        }

        for ($index = 0; $index < $total; $index++) {

            if (! $this->_delegate->canWorkWithItemAtIndex($index, $feed)) {

                if ($isDebugEnabled) {

                    $this->_logger->debug(sprintf('Skipping video at index %d: %s', $index,
                        $this->_delegate->getReasonUnableToWorkWithItemAtIndex($index, $feed)));
                }

                continue;
            }

            $video = $this->_buildMediaItem($feed, $index);

            array_push($toReturn, $video);
        }

        $this->_delegate->onAnalysisComplete($feed);

        if ($isDebugEnabled) {

            $this->_logger->debug(sprintf('Built %d video(s) from raw feed', sizeof($toReturn)));
        }

        return $toReturn;
    }

    private function _fetchFeedAndPrepareForAnalysis($url)
    {
        try {

            $httpResponse = $this->_httpClient->get($url);

        } catch (tubepress_core_http_api_exception_RequestException $e) {

            throw new tubepress_core_provider_api_exception_ProviderException($e->getMessage());
        }

        $rawFeed = $httpResponse->getBody()->toString();

        $this->_delegate->onAnalysisStart($rawFeed);

        return $rawFeed;
    }

    private function _emptyPage(tubepress_core_provider_api_Page $page)
    {
        $page->setTotalResultCount(0);
        $page->setItems(array());

        return $page;
    }

    /**
     * @param $feed
     * @param $index
     *
     * @return mixed|tubepress_core_provider_api_MediaItem
     */
    private function _buildMediaItem($feed, $index)
    {
        $video = new tubepress_core_provider_api_MediaItem();

        /*
         * Every video needs to have a provider.
         */
        $video->setAttribute(tubepress_core_provider_api_Constants::ATTRIBUTE_PROVIDER_NAME, $this->getName());

        /*
         * Let add-ons build the rest of the video.
         */
        $event = $this->_eventDispatcher->newEventInstance($video, array(
            'provider'           => $this,
            'zeroBasedFeedIndex' => $index,
            'rawFeed'            => $feed
        ));

        /*
         * Let subclasses add to the event.
         */
        $this->_delegate->onPreFireNewMediaItemEvent($event);

        return $this->_dispatchEventAndReturnSubject($event, tubepress_core_provider_api_Constants::EVENT_NEW_MEDIA_ITEM);
    }

    /**
     * @return tubepress_core_url_api_UrlInterface
     */
    private function _dispatchUrl(tubepress_core_url_api_UrlInterface $url, $eventName, array $additionalArgs = array())
    {
        $args = array_merge(array(
            'provider' => $this
        ), $additionalArgs);

        $event = $this->_eventDispatcher->newEventInstance($url, $args);

        return $this->_dispatchEventAndReturnSubject($event, $eventName);
    }

    private function _dispatchEventAndReturnSubject(tubepress_core_event_api_EventInterface $event, $eventName)
    {
        $this->_eventDispatcher->dispatch($eventName, $event);

        return $event->getSubject();
    }
}