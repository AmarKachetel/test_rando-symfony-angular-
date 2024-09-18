<?php

namespace App\DTO;

use App\Entity\User;

class UserValidationDTO
{
    public int $id;
    public string $email;
    public bool $isValidated;

    public function __construct(User $user)
    {
        $this->id = $user->getId();
        $this->email = $user->getEmail();
        $this->isValidated = $user->getIsValidated();
    }
}
