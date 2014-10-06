<?php

namespace Prism\PollBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('prism_poll');

        $rootNode
            ->children()
                ->arrayNode('entity')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('poll')->defaultValue('Prism\PollBundle\Entity\Poll')->end()
                        ->scalarNode('opinion')->defaultValue('Prism\PollBundle\Entity\Opinion')->end()
                    ->end()
                ->end()

                ->arrayNode('form')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('poll')->defaultValue('Prism\PollBundle\Form\PollType')->end()
                        ->scalarNode('opinion')->defaultValue('Prism\PollBundle\Form\OpinionType')->end()
                        ->scalarNode('vote')->defaultValue('Prism\PollBundle\Form\VoteType')->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
