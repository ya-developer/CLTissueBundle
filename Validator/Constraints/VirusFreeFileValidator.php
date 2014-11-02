<?php

namespace CL\Bundle\TissueBundle\Validator\Constraints;

use CL\Tissue\Adapter\AdapterInterface;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\FileValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

/**
 * Validates whether a value is a valid file and does not contain any viruses
 */
class VirusFreeFileValidator extends FileValidator
{
    /**
     * @var AdapterInterface
     */
    private $scanningAdapter;

    /**
     * @param AdapterInterface $scanningAdapter
     */
    public function __construct(AdapterInterface $scanningAdapter)
    {
        $this->scanningAdapter = $scanningAdapter;
    }

    /**
     * {@inheritdoc}
     */
    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof VirusFreeFile) {
            throw new UnexpectedTypeException($constraint, __NAMESPACE__.'\VirusFreeFile');
        }

        $path = $value instanceof File ? $value->getPathname() : (string) $value;
        if ($this->scanningAdapter->scan($path)->hasVirus()) {
            $this->buildViolation($constraint->virusDetectedMessage)->addViolation();

            return;
        }

        // IMPORTANT: we do the regular file-validation AFTER scanning the file!
        // This is because the regular validation will access the (possibly infected) file to determine readability/information
        // and we obviously don't want that to happen if we are dealing with a virus.
        parent::validate($value, $constraint);
    }
}
