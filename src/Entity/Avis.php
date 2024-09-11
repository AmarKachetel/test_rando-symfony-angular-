<?php

namespace App\Entity;

use App\Repository\AvisRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\MaxDepth; 

#[ORM\Entity(repositoryClass: AvisRepository::class)]
class Avis
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['avis:read', 'avis:write'])]
    private ?int $id = null;

    #[ORM\Column(type: 'text')]
    #[Groups(['avis:read', 'avis:write'])]
    private string $commentaire;

    #[ORM\Column(type: 'integer')]
    #[Groups(['avis:read', 'avis:write'])]
    private int $note;

    #[ORM\Column(type: 'datetime')]
    #[Groups(['avis:read'])]
    private \DateTimeInterface $date;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'avis')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['avis:read', 'user:read'])]
    #[MaxDepth(1)]
    private ?User $user = null;

    #[ORM\ManyToOne(targetEntity: Rando::class, inversedBy: 'avis')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['avis:read', 'rando:read'])]
    #[MaxDepth(1)]
    private ?Rando $rando = null;

    // Getters et setters...

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCommentaire(): ?string
    {
        return $this->commentaire;
    }

    public function setCommentaire(string $commentaire): self
    {
        $this->commentaire = $commentaire;

        return $this;
    }

    public function getNote(): ?int
    {
        return $this->note;
    }

    public function setNote(int $note): self
    {
        $this->note = $note;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

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
