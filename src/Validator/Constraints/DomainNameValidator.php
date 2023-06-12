<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class DomainNameValidator extends ConstraintValidator
{
    const PATTERN = '/^(?:[\p{L}\-A-Za-z0-9ÄÖÜßäöü]+\.)+[A-Za-z]{2,14}$/iu';

    public function validate($value, Constraint $constraint)
    {
        if (null === $value || '' === $value) {
            return;
        }
        if (!is_scalar($value) && !(is_object($value) && method_exists($value, '__toString'))) {
            throw new UnexpectedTypeException($value, 'string');
        }
        $value = (string)$value;
        if (!preg_match(self::PATTERN, $value)) {
            $this->context->addViolation($constraint->message);
        }
    }
}