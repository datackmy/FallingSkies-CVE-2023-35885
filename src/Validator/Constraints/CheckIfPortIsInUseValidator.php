<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use App\System\CommandExecutor;
use App\System\Command\CheckIfPortIsInUseCommand;

class CheckIfPortIsInUseValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        if (null === $value || '' === $value) {
            return;
        }
        $port = (string)$value;
        $commandExecutor = new CommandExecutor();
        $checkIfPortIsInUseCommand = new CheckIfPortIsInUseCommand();
        $checkIfPortIsInUseCommand->setPort($port);
        $commandExecutor->execute($checkIfPortIsInUseCommand);
        if (true === $checkIfPortIsInUseCommand->isPortInUse()) {
            $this->context->addViolation($constraint->message);
        }
    }
}