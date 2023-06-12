<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class PhpVersion extends Constraint
{
    public $message = 'This value is not valid.';

    public function validatedBy(): string
    {
        return \get_class($this).'Validator';
    }
}
