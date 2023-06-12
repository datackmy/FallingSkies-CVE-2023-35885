<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class PhpVersionValidator extends ConstraintValidator
{
    private const PHP_DIRECTORY = '/etc/php/';

    public function validate($value, Constraint $constraint)
    {
        if (null === $value || '' === $value) {
            return;
        }
        $phpSettingsEntity = $this->context->getObject();
        if (true === isset($phpSettingsEntity) && true === is_null($phpSettingsEntity->getId())) {
            $phpVersions = $this->getPhpVersions();
            $phpVersion = $phpSettingsEntity->getPhpVersion();
            if (false === isset($phpVersions[$phpVersion])) {
                $this->context->buildViolation($constraint->message)->addViolation();
            }
        }
    }

    private function getPhpVersions(): array
    {
        $phpVersions = [];
        foreach (new \DirectoryIterator(self::PHP_DIRECTORY) as $fileInfo) {
            if (false === $fileInfo->isDot()) {
                $phpVersion = $fileInfo->getBasename();
                if (true === is_float($phpVersion + 0)) {
                    $phpVersions[$phpVersion] = $phpVersion;
                }
            }
        }
        return $phpVersions;
    }
}
