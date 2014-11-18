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

class RegisterAdaptersCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $id = sprintf('cl_tissue.adapter.%s', $container->getParameter('cl_tissue.adapter.alias'));

        $container->setAlias('cl_tissue.scanner', $id);
    }
}
