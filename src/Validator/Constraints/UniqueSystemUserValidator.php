<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Form\Form;
use App\Entity\Site as SiteEntity;
use App\Entity\FtpUser as FtpUserEntity;
use App\Entity\SshUser as SshUserEntity;

class UniqueSystemUserValidator extends ConstraintValidator
{
    private const ETC_PASSWD_FILE = '/etc/passwd';

    public function validate($value, Constraint $constraint)
    {
        if (null === $value || '' === $value) {
            return;
        }
        $contextObject = $this->context->getObject();
        if ($contextObject instanceof SiteEntity) {
            $systemUsers = $this->getSystemUsers();
            $userName = $contextObject->getUser();
            if (false === is_null($userName)) {
                if (true === isset($systemUsers[$userName])) {
                    $this->context->buildViolation($constraint->message)->addViolation();
                }
            }
        }
        if (($contextObject instanceof SshUserEntity) || ($contextObject instanceof FtpUserEntity)) {
            $systemUsers = $this->getSystemUsers();
            $userName = $contextObject->getUserName();
            if (false === is_null($userName) && true === is_null($contextObject->getId())) {
                if (true === isset($systemUsers[$userName])) {
                    $this->context->buildViolation($constraint->message)->addViolation();
                }
            }
        }
        if ($contextObject instanceof Form) {
            $systemUsers = $this->getSystemUsers();
            $userName = $contextObject->getData();
            if (false === is_null($userName)) {
                if (true === isset($systemUsers[$userName])) {
                    $this->context->buildViolation($constraint->message)->addViolation();
                }
            }
        }
    }

    private function getSystemUsers(): array
    {
        $systemUsers = [];
        $lines = file(self::ETC_PASSWD_FILE);
        if (true === isset($lines) && count($lines)) {
            foreach ($lines as $line) {
                $data = explode(':', trim($line));
                if (true === isset($data[0])) {
                    $systemUsers[$data[0]] = $data[0];
                }
            }
        }
        return $systemUsers;
    }
}
