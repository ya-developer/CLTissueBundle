<?php

namespace CL\Bundle\TissueBundle\Tests\Validator\Constraints;

use CL\Bundle\TissueBundle\Validator\Constraints\CleanFile;
use CL\Bundle\TissueBundle\Validator\Constraints\CleanFileValidator;
use CL\Tissue\Tests\Adapter\AdapterTestCase;
use CL\Tissue\Tests\Adapter\MockAdapter;
use Symfony\Component\Validator\Tests\Constraints\AbstractConstraintValidatorTest;
use Symfony\Component\Validator\Validation;

class CleanFileValidatorTest extends AbstractConstraintValidatorTest
{
    /**
     * @var CleanFileValidator
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
        return new CleanFileValidator(new MockAdapter());
    }

    protected function setUp()
    {
        parent::setUp();

        $this->cleanFile = AdapterTestCase::getPathToCleanFile();
        $this->infectedFile = AdapterTestCase::getPathToInfectedFile();
    }

    public function testNullIsValid()
    {
        $this->validator->validate(null, new CleanFile());

        $this->assertNoViolation();
    }

    public function testEmptyStringIsValid()
    {
        $this->validator->validate('', new CleanFile());

        $this->assertNoViolation();
    }

    public function testCleanFile()
    {
        $this->validator->validate($this->cleanFile, new CleanFile());

        $this->assertNoViolation();
    }

    public function testVirusFile()
    {
        $this->validator->validate($this->infectedFile, new CleanFile());

        $this->buildViolation('This file contains a virus.')->assertRaised();
    }

    public function testInvalidFilename()
    {
        $this->validator->validate('/path/to/some malformed^file*%.txt', new CleanFile(['restrictFilename' => true]));

        $this->buildViolation('This file does not have a valid name.')->assertRaised();
    }
}
