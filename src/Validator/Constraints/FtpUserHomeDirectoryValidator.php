<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use App\System\Command\CheckIfFileExistsCommand;
use App\System\CommandExecutor;

class FtpUserHomeDirectoryValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        if (null === $value || '' === $value) {
            return;
        }
        try {
            $homeDirectory = $value;
            $commandExecutor = new CommandExecutor();;
            $checkIfHomeDirectoryExistsCommand = new CheckIfFileExistsCommand();
            $checkIfHomeDirectoryExistsCommand->setFile($homeDirectory);
            $commandExecutor->execute($checkIfHomeDirectoryExistsCommand);
        } catch (\Exception $e) {
            $this->context->addViolation($constraint->message);
        }
    }
}