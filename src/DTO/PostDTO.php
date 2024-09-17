<?php

namespace App\DTO;

use App\Entity\User;

class PostDTO
{
    public int $id;
    public string $title;
    public string $content;
    public string $createdAt;
    public ?array $user;

    public function __construct(int $id, string $title, string $content, \DateTimeInterface $createdAt, ?User $user)
    {
        $this->id = $id;
        $this->title = $title;
        $this->content = $content;
        $this->createdAt = $createdAt->format('Y-m-d H:i:s'); // Formatage de la date en string

        // Convertir l'utilisateur en tableau, seulement avec les données nécessaires
        $this->user = $user ? [
            'id' => $user->getId(),
            'email' => $user->getEmail(),
        ] : null;
    }
}
