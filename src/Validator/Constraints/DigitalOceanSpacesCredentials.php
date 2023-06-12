<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class DigitalOceanSpacesCredentials extends Constraint
{
    public string $message = 'The credentials are not valid.';

    public function validatedBy(): string
    {
        return \get_class($this).'Validator';
    }

    public function getTargets(): string
    {
        return self::CLASS_CONSTRAINT;
    }
}
