<?php

/*
 * This file is part of the CLTissueBundle.
 *
 * (c) Cas Leentfaar <info@casleentfaar.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CL\Bundle\TissueBundle\DependencyInjection\Compiler;

use CL\Tissue\Adapter\ClamAV\ClamAVAdapter;
use CL\Tissue\Adapter\ClamAVPHP\ClamAVPHPAdapter;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

class RegisterAdaptersCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $registryDefinition = $container->getDefinition('cl_tissue.adapter_registry');
        $chosenAdapterAlias = $container->getParameter('cl_tissue.chosen_adapter_alias');
        switch ($chosenAdapterAlias) {
            case 'clamav':
                $definition = $container->getDefinition('cl_tissue.adapter.clamav');
                $definition->addTag('cl_tissue.adapter', ['alias' => 'clamav']);

                break;
            case 'clamavphp':
                $definition = $container->getDefinition('cl_tissue.adapter.clamavphp');
                $definition->addTag('cl_tissue.adapter', ['alias' => 'clamavphp']);

                break;
        }

        foreach ($container->findTaggedServiceIds('cl_tissue.adapter') as $id => $adapters) {
            foreach ($adapters as $adapter) {
                if (!isset($adapter['alias'])) {
                    throw new \InvalidArgumentException(sprintf('Services tagged with "cl_tissue.adapter" must define the "alias" attribute ("%s" does not have one).', $id));
                }

                if ($chosenAdapterAlias === $adapter['alias']) {
                    $container->setDefinition('cl_tissue.scanner', $container->getDefinition($id));
                }

                $registryDefinition->addMethodCall('register', [new Reference($id), $adapter['alias']]);
            }
        }

        if (!$container->hasDefinition('cl_tissue.scanner')) {
            throw new \RuntimeException('The scanner service has not been set yet');
        }
    }
}
