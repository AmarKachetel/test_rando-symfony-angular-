<?php

namespace App\Entity;

use App\Repository\ReservationRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\MaxDepth; 


#[ORM\Entity(repositoryClass: ReservationRepository::class)]
class Reservation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['reservation:read', 'reservation:write'])]
    private ?int $id = null;

    #[ORM\Column(type: 'datetime')]
    #[Groups(['reservation:read', 'reservation:write'])]
    private \DateTimeInterface $reservationDate;

    #[ORM\Column(type: 'string', length: 50)]
    #[Groups(['reservation:read', 'reservation:write'])]
    private string $status;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'reservations')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['reservation:read'])]
    #[MaxDepth(1)]
    private ?User $user = null;

    #[ORM\ManyToOne(targetEntity: Rando::class, inversedBy: 'reservations')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['reservation:read'])]
    #[MaxDepth(1)]
    private ?Rando $rando = null;

    // Getters et setters...

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReservationDate(): ?\DateTimeInterface
    {
        return $this->reservationDate;
    }

    public function setReservationDate(\DateTimeInterface $reservationDate): self
    {
        $this->reservationDate = $reservationDate;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

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
