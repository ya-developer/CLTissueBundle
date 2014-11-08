<?php

namespace CL\Bundle\TissueBundle\Tests\DependencyInjection;

use CL\Bundle\TissueBundle\DependencyInjection\Configuration;

class ConfigurationTest extends AbstractConfigurationTestCase
{
    protected function getConfiguration()
    {
        return new Configuration();
    }

    public function testValuesAreInvalidIfRequiredValueIsNotProvided()
    {
        $this->assertConfigurationIsInvalid(
            [
                [] // no values at all
            ],
            'required_value' // (part of) the expected exception message - optional
        );
    }
}
