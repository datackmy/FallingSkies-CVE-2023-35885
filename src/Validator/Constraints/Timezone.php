<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

class Timezone extends Constraint
{
    public string $message = 'This value is not valid.';

    public function validatedBy(): string
    {
        return \get_class($this).'Validator';
    }
}
