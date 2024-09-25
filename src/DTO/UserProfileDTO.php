<?php

namespace App\DTO;

use App\Entity\User;

class UserProfileDTO
{
    public int $id;
    public string $email;
    public string $username;
    public array $roles;
    public bool $isValidated;
    public array $photos = [];
    public array $posts = [];
    public array $randos = [];
    public array $reservations = [];
    public array $avis = [];

    public function __construct(User $user)
    {
        $this->id = $user->getId();
        $this->email = $user->getEmail();
        $this->username = $user->getUsername();
        $this->roles = $user->getRoles();
        $this->isValidated = $user->getIsValidated();

        // Convertir les collections en tableaux simples
        foreach ($user->getPhotos() as $photo) {
            $this->photos[] = [
                'id' => $photo->getId(),
                'url' => $photo->getUrl(),
                'description' => $photo->getDescription(),
            ];
        }

        foreach ($user->getPosts() as $post) {
            $this->posts[] = [
                'id' => $post->getId(),
                'title' => $post->getTitle(),
                'content' => $post->getContent(),
                'createdAt' => $post->getCreatedAt()->format('Y-m-d H:i:s'),
            ];
        }

        foreach ($user->getRandos() as $rando) {
            $this->randos[] = [
                'id' => $rando->getId(),
                'title' => $rando->getTitle(),
                'description' => $rando->getDescription(),
                'location' => $rando->getLocation(),
                'distance' => $rando->getDistance(),
                'duration' => $rando->getDuration(),
                'difficulty' => $rando->getDifficulty(),
                'image' => $rando->getImage(),
            ];
        }

        foreach ($user->getReservations() as $reservation) {
            $this->reservations[] = [
                'id' => $reservation->getId(),
                'reservationDate' => $reservation->getReservationDate()->format('Y-m-d H:i:s'),
                'status' => $reservation->getStatus(),
            ];
        }

        foreach ($user->getAvis() as $avi) {
            $this->avis[] = [
                'id' => $avi->getId(),
                'commentaire' => $avi->getCommentaire(),
                'note' => $avi->getNote(),
                'date' => $avi->getDate()->format('Y-m-d H:i:s'),
                'approved' => $avi->getApproved(),
            ];
        }
    }
}
