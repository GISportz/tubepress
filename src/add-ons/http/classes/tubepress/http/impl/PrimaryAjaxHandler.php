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

class tubepress_http_impl_PrimaryAjaxHandler implements tubepress_api_http_AjaxInterface
{
    /**
     * @var tubepress_api_log_LoggerInterface Logger.
     */
    private $_logger;

    /**
     * @var bool Is debugging enabled?
     */
    private $_isDebugEnabled;

    /**
     * @var tubepress_api_http_RequestParametersInterface
     */
    private $_requestParameters;

    /**
     * @var tubepress_api_http_ResponseCodeInterface
     */
    private $_responseCode;

    /**
     * @var tubepress_api_event_EventDispatcherInterface
     */
    private $_eventDispatcher;

    /**
     * @var tubepress_api_template_TemplatingInterface
     */
    private $_templating;

    public function __construct(tubepress_api_log_LoggerInterface             $logger,
                                tubepress_api_http_RequestParametersInterface $requestParams,
                                tubepress_api_http_ResponseCodeInterface      $responseCode,
                                tubepress_api_event_EventDispatcherInterface  $eventDispatcher,
                                tubepress_api_template_TemplatingInterface    $templating)
    {
        $this->_logger            = $logger;
        $this->_isDebugEnabled    = $logger->isEnabled();
        $this->_requestParameters = $requestParams;
        $this->_responseCode      = $responseCode;
        $this->_eventDispatcher   = $eventDispatcher;
        $this->_templating        = $templating;
    }

    /**
     * {@inheritdoc}
     */
    public function handle()
    {
        if ($this->_isDebugEnabled) {

            $this->_logger->debug('Handling incoming request');
        }

        if (!$this->_requestParameters->hasParam('tubepress_action')) {

            $this->_errorOut(new RuntimeException('Missing "tubepress_action" parameter'), 400);

            return;
        }

        $actionName = $this->_requestParameters->getParamValue('tubepress_action');
        $ajaxEvent  = $this->_eventDispatcher->newEventInstance();

        try {

            $this->_eventDispatcher->dispatch(tubepress_api_event_Events::HTTP_AJAX . ".$actionName", $ajaxEvent);

        } catch (Exception $e) {

            $this->_errorOut($e, 500);

            return;
        }

        $resultingArgs = $ajaxEvent->getArguments();

        if (!array_key_exists('handled', $resultingArgs) || !$resultingArgs['handled']) {

            $this->_errorOut(new RuntimeException('Action not handled'), 400);
        }
    }

    private function _errorOut(Exception $e, $code)
    {
        $this->_responseCode->setResponseCode($code);

        $event = $this->_eventDispatcher->newEventInstance($e);

        $this->_eventDispatcher->dispatch(tubepress_api_event_Events::HTML_EXCEPTION_CAUGHT, $event);

        $args = array(

            'exception' => $e,
        );
        $response = $this->_templating->renderTemplate('exception/ajax', $args);

        echo $response;
    }
}
