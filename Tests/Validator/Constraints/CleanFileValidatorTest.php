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

    public function testCleanFileIsValid()
    {
        $this->validator->validate(AdapterTestCase::getPathToCleanFile(), new CleanFile(['useParentValidation' => false]));

        $this->assertNoViolation();
    }

    public function testInfectedFileIsInvalid()
    {
        $this->validator->validate(AdapterTestCase::getPathToInfectedFile(), new CleanFile(['useParentValidation' => false]));

        $this->buildViolation('This file contains a virus.')->assertRaised();
    }

    /**
     * @param string $invalidFilename
     *
     * @dataProvider getValidFilenames
     */
    public function testFilenameIsValid($invalidFilename)
    {
        $this->validator->validate($invalidFilename, new CleanFile(['restrictedFilename' => true, 'useParentValidation' => false]));

        $this->assertNoViolation();
    }

    /**
     * @param string $invalidFilename
     *
     * @dataProvider getInvalidFilenames
     */
    public function testFilenameIsInvalid($invalidFilename)
    {
        $this->validator->validate($invalidFilename, new CleanFile(['restrictedFilename' => true, 'useParentValidation' => false]));

        $this->buildViolation('This file does not have a valid name.')->assertRaised();
    }

    /**
     * @return array
     */
    public function getValidFilenames()
    {
        return [
            ['A novel by Jöhn Døê.pdf'],
            ['foobar.txt'],
        ];
    }

    /**
     * @return array
     */
    public function getInvalidFilenames()
    {
        return [
            ['/path/to/novel.pdf'],
        ];
    }
}
