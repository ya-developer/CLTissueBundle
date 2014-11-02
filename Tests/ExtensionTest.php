<?php

namespace CL\Bundle\TissueBundle\Tests;

use CL\Bundle\TissueBundle\DependencyInjection\CLTissueExtension;
use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractExtensionTestCase;

class ExtensionTest extends AbstractExtensionTestCase
{
    /**
     * @test
     */
    public function testParameters()
    {
        $this->load(['foo' => 'bar']);

        $this->assertContainerBuilderHasParameter('apple', 'pear');
    }

    /**
     * {@inheritdoc}
     */
    protected function getContainerExtensions()
    {
        return array(
            new CLTissueExtension()
        );
    }
}
