<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use App\Entity\Manager\DatabaseServerManager as DatabaseServerEntityManager;
use App\Database\Connection as DatabaseConnection;

class DatabaseNameValidator extends ConstraintValidator
{
    private DatabaseServerEntityManager $databaseServerEntityManager;

    public function __construct(DatabaseServerEntityManager $databaseServerEntityManager)
    {
        $this->databaseServerEntityManager = $databaseServerEntityManager;
    }

    public function validate($value, Constraint $constraint)
    {
        if (null === $value || '' === $value) {
            return;
        }
        try {
            $databaseName = $value;
            $activeDatabaseServerEntity = $this->databaseServerEntityManager->getActiveDatabaseServer();
            $databaseConnection = new DatabaseConnection($activeDatabaseServerEntity);
            $databases = $databaseConnection->getDatabases();
            if (true === in_array($databaseName, $databases)) {
                $this->context->addViolation($constraint->messageAlreadyExists);
            }
        } catch (\Exception $e) {
            $this->context->addViolation($e->getMessage());
        }
    }
}