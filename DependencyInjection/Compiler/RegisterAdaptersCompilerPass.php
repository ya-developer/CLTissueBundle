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
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

class RegisterAdaptersCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $chosenAlias = $container->getParameter('cl_tissue.adapter.alias');
        $registryId  = 'cl_tissue.adapter_registry';
        $tagName     = 'cl_tissue.adapter';

        if (!$container->hasDefinition($registryId)) {
            return;
        }

        if (in_array($chosenAlias, ['clamav', 'mock'])) {
            $class = $container->getParameter(sprintf('cl_tissue.adapter.%s.class', $chosenAlias));
            $args = [];
            if ($chosenAlias === 'clamav') {
                $args[] = $container->getParameter('cl_tissue.adapter.options.bin');
            }
            $chosenDefinition = new Definition($class, $args);
            $chosenDefinition->addTag('cl_tissue.adapter', ['alias' => $chosenAlias]);
            $container->setDefinition('cl_tissue.scanner', $chosenDefinition);
        }

        $registryDefinition = $container->getDefinition($registryId);
        foreach ($container->findTaggedServiceIds($tagName) as $id => $tagAttributes) {
            foreach ($tagAttributes as $attributes) {
                $registryDefinition->addMethodCall('register', [new Reference($id), $attributes['alias']]);
            }
        }
    }
}
