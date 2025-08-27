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
                ->scalarNode('assets')
                    ->defaultNull()
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
                ->arrayNode('style')
                    ->canBeEnabled()
                    ->children()
                        ->enumNode('type')
                            ->values(['css', 'html', null])
                            ->defaultNull()
                        ->end()
                        ->stringNode('content')
                            ->defaultNull()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('cover')
                    ->canBeEnabled()
                    ->children()
                        ->enumNode('type')
                            ->values(['jpg', 'html', 'fallback', null])
                            ->defaultNull()
                        ->end()
                        ->stringNode('content')
                            ->defaultNull()
                        ->end()
                    ->end()
                ->end()
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
                ->arrayNode('header')
                    ->canBeEnabled()
                    ->children()
                        ->stringNode('content')
                            ->defaultValue('{PAGENO}')
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
