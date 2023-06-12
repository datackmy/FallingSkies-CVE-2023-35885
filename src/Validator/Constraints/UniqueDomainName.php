<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class UniqueDomainName extends Constraint
{
    public $message = 'This value already exists.';

    public function validatedBy(): string
    {
        return \get_class($this).'Validator';
    }
}
