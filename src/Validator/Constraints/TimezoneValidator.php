<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use App\Entity\User;

class TimezoneValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint): void
    {
        try {
            $timezone = $value;
            if (true === is_null($timezone)) {
                $this->context->buildViolation($constraint->message)->addViolation();
            }
        } catch (\Exception $e) {
            $this->context->buildViolation($constraint->message)->addViolation();
        }
    }
}
