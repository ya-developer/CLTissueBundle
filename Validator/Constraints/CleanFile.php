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
    public $restrictFilename = false;

    /**
     * @var string
     */
    public $restrictFilenameRegex = '/^[a-zA-Z0-9._-]{2,250}\.[a-zA-Z]{2,4}$/';

    /**
     * {@inheritdoc}
     */
    public function validatedBy()
    {
        return 'virus_free_file';
    }
}
