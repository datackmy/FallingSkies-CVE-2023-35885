<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class AmazonS3Credentials extends Constraint
{
    public string $message = 'This value is not valid.';

    public function validatedBy(): string
    {
        return \get_class($this).'Validator';
    }

    public function getTargets(): string
    {
        return self::CLASS_CONSTRAINT;
    }
}
