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
 *
 */
class tubepress_jwplayer_ioc_JwPlayerExtension implements tubepress_api_ioc_ContainerExtensionInterface
{
    /**
     * Allows extensions to load services into the TubePress IOC container.
     *
     * @param tubepress_api_ioc_ContainerBuilderInterface $containerBuilder A tubepress_api_ioc_ContainerBuilderInterface instance.
     *
     * @return void
     *
     * @api
     * @since 3.1.0
     */
    public function load(tubepress_api_ioc_ContainerBuilderInterface $containerBuilder)
    {
        $containerBuilder->register(

            'tubepress_jwplayer_impl_embedded_JwPlayerEmbeddedProvider',
            'tubepress_jwplayer_impl_embedded_JwPlayerEmbeddedProvider'

        )->addArgument(new tubepress_api_ioc_Reference(tubepress_core_url_api_UrlFactoryInterface::_))
         ->addArgument(new tubepress_api_ioc_Reference(tubepress_core_template_api_TemplateFactoryInterface::_))
         ->addTag(tubepress_core_embedded_api_EmbeddedProviderInterface::_);

        $containerBuilder->register(

            'tubepress_jwplayer_impl_listeners_template_JwPlayerTemplateVars',
            'tubepress_jwplayer_impl_listeners_template_JwPlayerTemplateVars'
        )->addArgument(new tubepress_api_ioc_Reference(tubepress_core_options_api_ContextInterface::_))
         ->addTag(tubepress_core_ioc_api_Constants::TAG_EVENT_LISTENER, array(
            'event'    => tubepress_core_embedded_api_Constants::EVENT_TEMPLATE_EMBEDDED,
            'method'   => 'onEmbeddedTemplate',
            'priority' => 10000
        ));

        $containerBuilder->setParameter(tubepress_core_options_api_Constants::IOC_PARAM_EASY_REFERENCE . '_jwplayer', array(

            'defaultValues' => array(
                tubepress_jwplayer_api_Constants::OPTION_COLOR_BACK   => 'FFFFFF',
                tubepress_jwplayer_api_Constants::OPTION_COLOR_FRONT  => '000000',
                tubepress_jwplayer_api_Constants::OPTION_COLOR_LIGHT  => '000000',
                tubepress_jwplayer_api_Constants::OPTION_COLOR_SCREEN => '000000',
            ),
            'labels' => array(
                tubepress_jwplayer_api_Constants::OPTION_COLOR_BACK   => 'Background color',//>(translatable)<
                tubepress_jwplayer_api_Constants::OPTION_COLOR_FRONT  => 'Front color',     //>(translatable)<
                tubepress_jwplayer_api_Constants::OPTION_COLOR_LIGHT  => 'Light color',     //>(translatable)<
                tubepress_jwplayer_api_Constants::OPTION_COLOR_SCREEN => 'Screen color',    //>(translatable)<
            ),
            'descriptions' => array(
                tubepress_jwplayer_api_Constants::OPTION_COLOR_BACK   => sprintf('Default is %s', "FFFFFF"),   //>(translatable)<
                tubepress_jwplayer_api_Constants::OPTION_COLOR_FRONT  => sprintf('Default is %s', "000000"),   //>(translatable)<
                tubepress_jwplayer_api_Constants::OPTION_COLOR_LIGHT  => sprintf('Default is %s', "000000"),   //>(translatable)<
                tubepress_jwplayer_api_Constants::OPTION_COLOR_SCREEN => sprintf('Default is %s', "000000"),   //>(translatable)<
            )
        ));

        $containerBuilder->setParameter(tubepress_core_options_api_Constants::IOC_PARAM_EASY_VALIDATION . '_jwplayer', array(
            'priority' => 3000,
            'map' => array(
                'hexColor' => array(
                    tubepress_jwplayer_api_Constants::OPTION_COLOR_BACK,
                    tubepress_jwplayer_api_Constants::OPTION_COLOR_FRONT,
                    tubepress_jwplayer_api_Constants::OPTION_COLOR_LIGHT,
                    tubepress_jwplayer_api_Constants::OPTION_COLOR_SCREEN,
                )
            )
        ));

        $containerBuilder->setParameter(tubepress_core_options_api_Constants::IOC_PARAM_EASY_TRIMMER . '_jwplayer', array(

            'priority'    => 3000,
            'charlist'    => '#',
            'ltrim'       => true,
            'optionNames' => array(
                tubepress_jwplayer_api_Constants::OPTION_COLOR_BACK,
                tubepress_jwplayer_api_Constants::OPTION_COLOR_FRONT,
                tubepress_jwplayer_api_Constants::OPTION_COLOR_LIGHT,
                tubepress_jwplayer_api_Constants::OPTION_COLOR_SCREEN,
            )
        ));

        $colors = array(

            tubepress_jwplayer_api_Constants::OPTION_COLOR_BACK,
            tubepress_jwplayer_api_Constants::OPTION_COLOR_FRONT,
            tubepress_jwplayer_api_Constants::OPTION_COLOR_LIGHT,
            tubepress_jwplayer_api_Constants::OPTION_COLOR_SCREEN,
        );

        $fieldIndex = 0;
        foreach ($colors as $color) {

            $containerBuilder->register(

                'jwplayer_field_' . $fieldIndex++,
                'tubepress_core_options_ui_api_FieldInterface'
            )->setFactoryService(tubepress_core_options_ui_api_FieldBuilderInterface::_)
             ->setFactoryMethod('newInstance')
             ->addArgument($color)
             ->addArgument('spectrum');
        }

        $fieldReferences = array();
        for ($x = 0; $x < $fieldIndex; $x++) {
            $fieldReferences[] = new tubepress_api_ioc_Reference('jwplayer_field_' . $x);
        }

        $containerBuilder->register(

            'jw_player_field_provider',
            'tubepress_jwplayer_impl_options_ui_JwPlayerFieldProvider'
        )->addArgument(new tubepress_api_ioc_Reference(tubepress_core_translation_api_TranslatorInterface::_))
         ->addArgument($fieldReferences)
         ->addTag('tubepress_core_options_ui_api_FieldProviderInterface');
    }
}