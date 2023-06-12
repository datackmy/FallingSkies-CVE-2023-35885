<?php

namespace App\Validator\Constraints;

use App\Backup\Rclone;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class CustomRcloneConfigValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        try {
            $form = $this->context->getRoot();
            $storageDirectory = $form->get('storageDirectory')->getData();
            if (false === empty($storageDirectory)) {
                $remotePath = sprintf('%s/', rtrim($storageDirectory, '/'));
                $rclone = new Rclone();
                $rclone->lsJson($remotePath);
            }
        } catch (\Exception $e) {
            $errorMessage = $e->getMessage();
            $this->context->buildViolation($errorMessage)->addViolation();
        }
    }
}
