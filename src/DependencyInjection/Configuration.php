<?php

declare(strict_types=1);

namespace Typesetsh\PdfBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('typesetsh_pdf');

        $treeBuilder->getRootNode()
            ->children()
                ->arrayNode('allowed_directories')->defaultValue(['%kernel.project_dir%/public'])->scalarPrototype()->end()->end()
                ->scalarNode('base_dir')->defaultValue('%kernel.project_dir%/public')->end()
                ->arrayNode('http')
                    ->children()
                        ->scalarNode('cache_dir')->defaultValue('%kernel.cache_dir%/typesetsh')->end()
                        ->booleanNode('allow')->defaultValue(false)->end()
                        ->integerNode('download_limit')->info('Download size limit in bytes')->defaultValue(1024 * 1024)->end()
                        ->integerNode('timeout')->info('Download timeout in seconds')->defaultValue(5)->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
