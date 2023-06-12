<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class CheckIfPortIsInUse extends Constraint
{
    public string $message = 'Port is already in use.';

    public function validatedBy(): string
    {
        return \get_class($this).'Validator';
    }
}