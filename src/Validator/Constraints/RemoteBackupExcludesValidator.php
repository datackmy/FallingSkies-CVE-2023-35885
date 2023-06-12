<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use App\System\CommandExecutor;
use App\System\Command\CheckIfFileExistsCommand;

class RemoteBackupExcludesValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        try {
            $excludes = $value;
            if (false === empty($excludes)) {
                $excludes = array_map('trim', array_filter(explode(PHP_EOL, trim($excludes))));
                if (false === empty($excludes)) {
                    $commandExecutor = new CommandExecutor();
                    foreach ($excludes as $file) {
                        try {
                            $checkIfFileExistsCommand = new CheckIfFileExistsCommand();
                            $checkIfFileExistsCommand->setFile($file);
                            $commandExecutor->execute($checkIfFileExistsCommand);
                        } catch (\Exception $e) {
                            throw new \DomainException('This value is not valid.');
                        }
                    }
                }
            }
        } catch (\DomainException $e) {
            $errorMessage = sprintf('%s is not valid.', $file);
            $this->context->buildViolation($errorMessage)->addViolation();
        } catch (\Exception $e) {
            $this->context->buildViolation($constraint->message)->addViolation();
        }
    }
}
