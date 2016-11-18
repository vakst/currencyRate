<?php
/*
 * This file is part of the CurrencyRateBundle package.
 *
 * (c) Vladimir Akst <contribute@akst.me>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CurrencyRateBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 * 
 * @author Vladimir Akst <contribute@akst.me>
 */
class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
    	$treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('currency_rate', 'array');
        $rootNode
            ->children()
            ->arrayNode('import_service')
                ->prototype('array')->prototype('variable')->end()->end()->end()
        ->end();

        return $treeBuilder;
    }
}
?>