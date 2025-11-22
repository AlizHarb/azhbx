<?php

namespace AlizHarb\AzHbx\Bridge\Symfony\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Configuration class.
 *
 * @package AlizHarb\AzHbx
 */
class Configuration implements ConfigurationInterface
{
    /**
 * getConfigTreeBuilder method.
 *
 * @return mixed
 */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('azhbx');

        $treeBuilder->getRootNode()
            ->children()
                ->scalarNode('views_path')->defaultValue('%kernel.project_dir%/templates')->end()
                ->scalarNode('cache_path')->defaultValue('%kernel.cache_dir%/azhbx')->end()
                ->scalarNode('default_theme')->defaultValue('default')->end()
            ->end();

        return $treeBuilder;
    }
}
