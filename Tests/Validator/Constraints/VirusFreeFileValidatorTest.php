<?php

namespace CL\Bundle\TissueBundle\Tests\Validator\Constraints;

use CL\Bundle\TissueBundle\Validator\Constraints\VirusFreeFile;
use CL\Bundle\TissueBundle\Validator\Constraints\VirusFreeFileValidator;
use CL\Tissue\Tests\Adapter\AdapterTestCase;
use CL\Tissue\Tests\Adapter\MockAdapter;
use Symfony\Component\Validator\Tests\Constraints\AbstractConstraintValidatorTest;
use Symfony\Component\Validator\Validation;

class VirusFreeFileValidatorTest extends AbstractConstraintValidatorTest
{
    /**
     * @var VirusFreeFileValidator
     */
    protected $validator;

    /**
     * @var string
     */
    protected $cleanFile;

    /**
     * @var string
     */
    protected $infectedFile;

    protected function getApiVersion()
    {
        return Validation::API_VERSION_2_5;
    }

    protected function createValidator()
    {
        return new VirusFreeFileValidator(new MockAdapter());
    }

    protected function setUp()
    {
        parent::setUp();

        $this->cleanFile = AdapterTestCase::getPathToCleanFile();
        $this->infectedFile = AdapterTestCase::getPathToInfectedFile();
    }

    public function testNullIsValid()
    {
        $this->validator->validate(null, new VirusFreeFile());

        $this->assertNoViolation();
    }

    public function testEmptyStringIsValid()
    {
        $this->validator->validate('', new VirusFreeFile());

        $this->assertNoViolation();
    }

    public function testCleanFile()
    {
        $this->validator->validate($this->cleanFile, new VirusFreeFile());

        $this->assertNoViolation();
    }

    public function testVirusFile()
    {
        $this->validator->validate($this->infectedFile, new VirusFreeFile());

        $this->buildViolation('This file contains a virus.')->assertRaised();
    }
}
