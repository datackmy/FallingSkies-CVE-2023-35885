<?php

namespace App\Validator\Constraints;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class DropboxAccessCode extends Constraint
{
    public string $message = 'This value is not valid.';
    private ?Request $request = null;

    public function setRequest(Request $request): void
    {
        $this->request = $request;
    }

    public function getRequest(): ?Request
    {
        return $this->request;
    }

    public function validatedBy(): string
    {
        return \get_class($this).'Validator';
    }

    public function getTargets(): string
    {
        return self::CLASS_CONSTRAINT;
    }
}
