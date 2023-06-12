<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use App\Entity\Manager\DatabaseUserManager as DatabaseUserEntityManager;

class DatabaseUserNameValidator extends ConstraintValidator
{
    private DatabaseUserEntityManager $databaseUserEntityManager;

    public function __construct(DatabaseUserEntityManager $databaseUserEntityManager)
    {
        $this->databaseUserEntityManager = $databaseUserEntityManager;
    }

    public function validate($value, Constraint $constraint)
    {
        if (null === $value || '' === $value) {
            return;
        }
        try {
            $userName = $value;
            $databaseUserEntity = $this->databaseUserEntityManager->findOneByUserName($userName);
            if (false === is_null($databaseUserEntity)) {
                $this->context->addViolation($constraint->message);
            }
        } catch (\Exception $e) {
            $this->context->addViolation($e->getMessage());
        }
    }
}