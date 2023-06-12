<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class DatabaseName extends Constraint
{
    public $message = 'This value is not valid.';
    public $messageAlreadyExists = 'This value already exists.';

    public function validatedBy(): string
    {
        return \get_class($this).'Validator';
    }
}