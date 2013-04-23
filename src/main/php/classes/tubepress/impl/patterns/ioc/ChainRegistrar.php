<?php
/**
 * Copyright 2006 - 2013 TubePress LLC (http://tubepress.org)
 *
 * This file is part of TubePress (http://tubepress.org)
 *
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/.
 */

/**
 * Builds instances of ehough_chaingang_api_Chain
 */
class tubepress_impl_patterns_ioc_ChainRegistrar
{
    public static function registerChainDefinitionByReferences(ehough_iconic_ContainerBuilder $container, $chainName, array $references)
    {
        $container->setDefinition(

            $chainName,
            new ehough_iconic_Definition(

                'ehough_chaingang_api_Chain',
                $references
            )

        )->setFactoryClass('tubepress_impl_patterns_ioc_ChainRegistrar')
         ->setFactoryMethod('buildChain');
    }

    public static function registerChainDefinitionByClassNames(ehough_iconic_ContainerBuilder $container, $chainName, array $classNames)
    {
        $references = array();

        foreach ($classNames as $className) {

            $container->register($className, $className);

            array_push($references, new ehough_iconic_Reference($className));
        }

        self::registerChainDefinitionByReferences($container, $chainName, $references);
    }

    public function buildChain()
    {
        $chain    = new ehough_chaingang_impl_StandardChain();
        $commands = func_get_args();

        foreach ($commands as $command) {

            $chain->addCommand($command);
        }

        return $chain;
    }
}