<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class AmazonS3Bucket extends Constraint
{
    public $message = 'Does not exist.';

    public function validatedBy(): string
    {
        return \get_class($this).'Validator';
    }

    public function getTargets(): string
    {
        return self::CLASS_CONSTRAINT;
    }
}
