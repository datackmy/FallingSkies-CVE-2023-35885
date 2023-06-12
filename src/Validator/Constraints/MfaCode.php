<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use App\Entity\User;

class MfaCode extends Constraint
{
    public string $message = 'This value is not valid.';

    private ?User $user = null;

    public function validatedBy(): string
    {
        return \get_class($this).'Validator';
    }

    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }
}
