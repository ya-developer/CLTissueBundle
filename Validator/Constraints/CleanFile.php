<?php

namespace CL\Bundle\TissueBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraints\File;

/**
 * @Annotation
 * @Target({"PROPERTY", "METHOD", "ANNOTATION"})
 */
class CleanFile extends File
{
    /**
     * @var string
     */
    public $virusDetectedMessage = 'This file contains a virus.';

    /**
     * @var string
     */
    public $invalidFilenameMessage = 'This file does not have a valid name.';

    /**
     * @var bool
     */
    public $restrictedFilename = false;

    /**
     * @var bool
     */
    public $useParentValidation = true;

    /**
     * @var string
     */
    public $restrictedFilenameRegex = '/^[\p{L}\p{N}\p{P}\p{S} ]{2,250}\.\w{3,4}$/';

    /**
     * {@inheritdoc}
     */
    public function validatedBy()
    {
        return 'clean_file';
    }
}
