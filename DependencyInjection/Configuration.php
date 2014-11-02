<?php

namespace CL\Bundle\TissueBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('cl_tissue');
        $rootNode
            ->children()
                ->scalarNode('scanner')->isRequired()->end()
            ->end();

        return $treeBuilder;
    }
}
