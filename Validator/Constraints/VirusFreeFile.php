<?php

namespace CL\Bundle\TissueBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraints\File;

/**
 * @Annotation
 * @Target({"PROPERTY", "METHOD", "ANNOTATION"})
 */
class VirusFreeFile extends File
{
    /**
     * @var string
     */
    public $virusDetectedMessage = 'This file contains a virus.';

    /**
     * {@inheritdoc}
     */
    public function validatedBy()
    {
        return 'virus_free_file';
    }
}
