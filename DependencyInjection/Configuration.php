<?php

/*
 * This file is part of the CLTissueBundle.
 *
 * (c) Cas Leentfaar <info@casleentfaar.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CL\Bundle\TissueBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Configuration implements ConfigurationInterface
{
    /**
     * @var array
     */
    private $supportedAdapters = ['clamav', 'clamavphp'];

    /**
     * @var OptionsResolver[]
     */
    private $resolvers = [];

    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $tb = new TreeBuilder();
        $rootNode = $tb->root('cl_tissue');
        $self = $this;

        $rootNode
            ->addDefaultsIfNotSet()
            ->children()
                ->arrayNode('adapter')
                    ->isRequired()
                    ->beforeNormalization()
                        ->ifString(function ($v) { return $v; })
                        ->then(function ($v) use ($self) {
                            $v = ['alias' => $v, 'options' => []];
                            if ($resolver = $self->getResolver($v['alias'])) {
                                $v['options'] = $resolver->resolve([]);
                            }

                            return $v;
                        })
                        ->ifArray(function ($v) { return $v; })
                        ->then(function ($v) use ($self) {
                            if ($resolver = $self->getResolver($v['alias'])) {
                                $v['options'] = $resolver->resolve($v['options']);
                            }

                            return $v;
                        })
                    ->end()
                    ->children()
                        ->scalarNode('alias')->isRequired()->defaultValue('clamav')->end()
                        ->variableNode('options')->defaultValue([])->end()
                    ->end()
                ->end()
            ->end()
        ->end();

        return $tb;
    }

    /**
     * @param string $alias
     *
     * @return OptionsResolver|null
     */
    private function getResolver($alias)
    {
        if (!in_array($alias, $this->supportedAdapters)) {
            return null;
        }

        if (!isset($this->resolvers[$alias])) {
            $this->resolvers[$alias] = $this->createResolver($alias);
        }

        return $this->resolvers[$alias];
    }

    private function createResolver($alias)
    {
        $resolver = new OptionsResolver();
        switch ($alias) {
            case 'clamav':
                $resolver->setDefaults([
                    'bin' => '/usr/bin/clamdscan',
                    'database' => null,
                ]);
                $resolver->setOptional([
                    'database',
                ]);
                $resolver->setAllowedTypes([
                    'bin' => ['string'],
                    'database' => ['string', 'null'],
                ]);
                break;
            default:
                break;
        }

        return $resolver;
    }
}
