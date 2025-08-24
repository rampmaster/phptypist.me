<?php

declare(strict_types=1);

namespace Rampmaster\PHPTypistMe\Configuration;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * @inheritDoc
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('typist_me');

        $rootNode = $treeBuilder->getRootNode();

        $rootNode
            ->children()
                ->arrayNode('assets')
                    ->scalarPrototype()
                        ->defaultNull()
                    ->end()
                ->end()
                ->arrayNode('content')
                    ->requiresAtLeastOneElement()
                    ->scalarPrototype()
                        ->isRequired()
                    ->end()
                ->end()
                ->scalarNode('title')->end()
                ->scalarNode('author')->end()
                ->scalarNode('theme')->end()
                ->arrayNode('toc')
                    ->canBeEnabled()
                    ->children()
                        ->stringNode('header')
                            ->defaultValue('Index')
                        ->end()
                        ->booleanNode('links')
                            ->defaultFalse()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('footer')
                    ->canBeEnabled()
                    ->children()
                        ->stringNode('content')
                            ->defaultValue('{PAGENO}')
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('extension')
                    ->cannotBeEmpty()
                    ->defaultValue(["*.md", "*.markdown"])
                    ->scalarPrototype()->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
