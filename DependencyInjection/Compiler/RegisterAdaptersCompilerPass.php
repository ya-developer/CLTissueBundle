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

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class RegisterAdaptersCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $registryDefinition = $container->getDefinition('cl_tissue.adapter_registry');
        $chosenAdapterAlias = $container->getParameter('cl_tissue.chosen_adapter_alias');
        $preConfiguredAdapters = ['clamav', 'clamavphp'];

        foreach ($container->findTaggedServiceIds('cl_tissue.adapter') as $id => $adapters) {
            foreach ($adapters as $adapter) {
                if (!isset($adapter['alias'])) {
                    throw new \InvalidArgumentException(sprintf('Services tagged with "cl_tissue.adapter" must define the "alias" attribute ("%s" does not have one).', $id));
                }

                if (isset($adapter['owned']) && $adapter['owned'] === true && $chosenAdapterAlias !== $adapter['alias']) {
                    // not configured to be used
                    continue;
                }

                $registryDefinition->addMethodCall('register', [new Reference($id), $adapter['alias']]);
            }
        }
    }
}
