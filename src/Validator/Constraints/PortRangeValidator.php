<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class PortRangeValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint): void
    {
        if (null === $value || '' === $value) {
            return;
        }
        try {
            $portRange = trim($value);
            $portRange = explode('-', $portRange);
            if (count($portRange) == 1) {
                $port = (int)$portRange[0];
                if ($port == 0) {
                    $this->context->buildViolation($constraint->message)->addViolation();
                }
            }
            if (count($portRange) == 2) {
                $fromPort = (int)$portRange[0];
                $toPort = (int)$portRange[1];
                if ($fromPort == 0 || $toPort == 0) {
                    $this->context->buildViolation($constraint->message)->addViolation();
                }
            }
            if (count($portRange) > 2) {
                $this->context->buildViolation($constraint->message)->addViolation();
            }
        } catch (\Exception $e) {
            $this->context->buildViolation($constraint->message)->addViolation();
        }
    }
}
