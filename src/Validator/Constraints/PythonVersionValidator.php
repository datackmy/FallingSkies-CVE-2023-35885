<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Finder\Finder;

class PythonVersionValidator extends ConstraintValidator
{
    private const USR_BIN_DIRECTORY = '/usr/bin/';

    public function validate($value, Constraint $constraint)
    {
        if (null === $value || '' === $value) {
            return;
        }
        $pythonSettingsEntity = $this->context->getObject();
        if (true === isset($pythonSettingsEntity) && true === is_null($pythonSettingsEntity->getId())) {
            $pythonVersions = $this->getPythonVersions();
            $pythonVersion = $pythonSettingsEntity->getPythonVersion();
            if (false === isset($pythonVersions[$pythonVersion])) {
                $this->context->buildViolation($constraint->message)->addViolation();
            }
        }
    }

    private function getPythonVersions(): array
    {
        $pythonVersions = [];
        $finder = new Finder();
        $finder->files();
        $finder->name(['python*']);
        $finder->in(self::USR_BIN_DIRECTORY);
        if ($finder->hasResults()) {
            foreach ($finder as $file) {
                $pythonVersion = trim(str_replace('python', '', $file->getFilename()));
                if (false == empty($pythonVersion) && true === is_numeric($pythonVersion) && false !== strpos($pythonVersion, '.')) {
                    $pythonVersions[$pythonVersion] = $pythonVersion;
                }
            }
        }
        arsort($pythonVersions);
        return $pythonVersions;
    }
}
