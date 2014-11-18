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

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

class CLTissueExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config        = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yml');

        $this->setParameters($config, $container);
    }

    /**
     * @param array            $config
     * @param ContainerBuilder $container
     */
    private function setParameters(array $config, ContainerBuilder $container)
    {
        foreach ($config['adapter'] as $key => $val) {
            if ($key === 'options') {
                foreach ($val as $k => $v) {
                    $container->setParameter(sprintf('cl_tissue.adapter.options.%s', $k), $v);
                }
                continue;
            }

            $container->setParameter(sprintf('cl_tissue.adapter.%s', $key), $val);
        }
    }
}
