<?php


// src/Entity/Photo.php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\MaxDepth; 
use App\Entity\User;
use App\Entity\Rando;

#[ORM\Entity]
class Photo
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['photo:read'])]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['photo:read'])]
    private $url;

    #[ORM\Column(type: 'text', nullable: true)]
    #[Groups(['photo:read'])]
    private $description;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'photos')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['photo:read'])]
    private $user;

    #[ORM\ManyToOne(targetEntity: Rando::class, inversedBy: 'photos')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['photo:read'])]
    private $rando;

    // Getters and Setters...

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;
        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;
        return $this;
    }

    public function getRando(): ?Rando
    {
        return $this->rando;
    }

    public function setRando(?Rando $rando): self
    {
        $this->rando = $rando;
        return $this;
    }
}