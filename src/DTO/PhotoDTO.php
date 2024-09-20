<?php

namespace App\DTO;

use App\Entity\Photo;

class PhotoDTO
{
    public int $id;
    public string $url;
    public ?string $description;
    public ?array $user;
    public int $randoId;

    public function __construct(Photo $photo)
    {
        $this->id = $photo->getId();
        $this->url = $photo->getUrl();
        $this->description = $photo->getDescription();
        $this->randoId = $photo->getRando()->getId();

        // Extract user details
        $user = $photo->getUser();
        $this->user = $user ? [
            'id' => $user->getId(),
            'email' => $user->getEmail(),
        ] : null;
    }
}
