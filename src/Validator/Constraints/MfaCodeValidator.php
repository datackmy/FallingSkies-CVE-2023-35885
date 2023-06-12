<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use App\Security\Authenticator\MfaAuthenticator;

class MfaCodeValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint): void
    {
        if (null === $value || '' === $value) {
            return;
        }
        try {
            $mfaCode = $value;
            $user = $constraint->getUser();
            $mfaAuthenticator = new MfaAuthenticator();
            $isMfaCodeValid = $mfaAuthenticator->verifyCode($user->getMfaSecret(), $mfaCode);
            if (false === $isMfaCodeValid) {
                $this->context->buildViolation($constraint->message)->addViolation();
            }
        } catch (\Exception $e) {
            $this->context->buildViolation($constraint->message)->addViolation();
        }
    }
}
