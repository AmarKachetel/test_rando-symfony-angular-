<?php

namespace App\Entity;

use App\Repository\RandoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\MaxDepth; 

/**
 * Représente une randonnée disponible dans l'application.
 */
#[ORM\Entity(repositoryClass: RandoRepository::class)]
class Rando
{
    /**
     * Identifiant unique de la randonnée.
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['rando:read', 'rando:write'])]
    private ?int $id = null;

    /**
     * Titre de la randonnée.
     */
    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['rando:read', 'rando:write'])]
    private ?string $title = null;

    /**
     * Description détaillée de la randonnée.
     */
    #[ORM\Column(type: 'text')]
    #[Groups(['rando:read', 'rando:write'])]
    private ?string $description = null;

    /**
     * Localisation géographique de la randonnée.
     */
    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['rando:read', 'rando:write'])]
    private ?string $location = null;

    /**
     * Distance de la randonnée en kilomètres.
     */
    #[ORM\Column(type: 'float')]
    #[Groups(['rando:read', 'rando:write'])]
    private ?float $distance = null;

    /**
     * Durée estimée de la randonnée.
     */
    #[ORM\Column(type: 'string', length: 50)]
    #[Groups(['rando:read', 'rando:write'])]
    private ?string $duration = null;

    /**
     * Niveau de difficulté de la randonnée.
     */
    #[ORM\Column(type: 'string', length: 50)]
    #[Groups(['rando:read', 'rando:write'])]
    private ?string $difficulty = null;

    /**
     * URL ou chemin de l'image représentant la randonnée.
     */
    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['rando:read', 'rando:write'])]
    private ?string $image = null;

    /**
     * Coordonnées GPS de la randonnée.
     */
    #[ORM\Column(type: 'json', nullable: true)]
    #[Groups(['rando:read', 'rando:write'])]
    private ?array $coordinates = [];

    /**
     * Utilisateur qui a créé la randonnée.
     */
    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'randos')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['rando:read', 'rando:write'])]
    #[MaxDepth(1)]
    private ?User $user = null;

    /**
     * Collection des réservations associées à la randonnée.
     */
    #[ORM\OneToMany(mappedBy: 'rando', targetEntity: Reservation::class, orphanRemoval: true, cascade: ['persist', 'remove'])]
    #[Groups(['rando:read'])]
    #[MaxDepth(1)] // Limite la profondeur de la sérialisation pour éviter les références circulaires
    private Collection $reservations;

    /**
     * Collection des avis associés à la randonnée.
     */
    #[ORM\OneToMany(mappedBy: 'rando', targetEntity: Avis::class, orphanRemoval: true, cascade: ['persist', 'remove'])]
    #[Groups(['rando:read', 'rando:write'])]
    #[MaxDepth(1)]
    private Collection $avis;

    public function __construct()
    {
        $this->reservations = new ArrayCollection();
        $this->avis = new ArrayCollection();
    }

    /**
     * Obtient l'identifiant unique de la randonnée.
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Obtient le titre de la randonnée.
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * Définit le titre de la randonnée.
     */
    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Obtient la description de la randonnée.
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * Définit la description de la randonnée.
     */
    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Obtient la localisation de la randonnée.
     */
    public function getLocation(): ?string
    {
        return $this->location;
    }

    /**
     * Définit la localisation de la randonnée.
     */
    public function setLocation(string $location): self
    {
        $this->location = $location;

        return $this;
    }

    /**
     * Obtient la distance de la randonnée.
     */
    public function getDistance(): ?float
    {
        return $this->distance;
    }

    /**
     * Définit la distance de la randonnée.
     */
    public function setDistance(float $distance): self
    {
        $this->distance = $distance;

        return $this;
    }

    /**
     * Obtient la durée de la randonnée.
     */
    public function getDuration(): ?string
    {
        return $this->duration;
    }

    /**
     * Définit la durée de la randonnée.
     */
    public function setDuration(string $duration): self
    {
        $this->duration = $duration;

        return $this;
    }

    /**
     * Obtient la difficulté de la randonnée.
     */
    public function getDifficulty(): ?string
    {
        return $this->difficulty;
    }

    /**
     * Définit la difficulté de la randonnée.
     */
    public function setDifficulty(string $difficulty): self
    {
        $this->difficulty = $difficulty;

        return $this;
    }

    /**
     * Obtient l'image de la randonnée.
     */
    public function getImage(): ?string
    {
        return $this->image;
    }

    /**
     * Définit l'image de la randonnée.
     */
    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Obtient les coordonnées de la randonnée.
     */
    public function getCoordinates(): ?array
    {
        return $this->coordinates;
    }

    /**
     * Définit les coordonnées de la randonnée.
     */
    public function setCoordinates(?array $coordinates): self
    {
        $this->coordinates = $coordinates;

        return $this;
    }

    /**
     * Obtient l'utilisateur qui a créé la randonnée.
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * Définit l'utilisateur qui a créé la randonnée.
     */
    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Obtient la collection des réservations de la randonnée.
     */
    public function getReservations(): Collection
    {
        return $this->reservations;
    }

    /**
     * Ajoute une réservation à la randonnée.
     */
    public function addReservation(Reservation $reservation): self
    {
        if (!$this->reservations->contains($reservation)) {
            $this->reservations->add($reservation);
            $reservation->setRando($this);
        }

        return $this;
    }

    /**
     * Supprime une réservation de la randonnée.
     */
    public function removeReservation(Reservation $reservation): self
    {
        if ($this->reservations->removeElement($reservation)) {
            // Définit le propriétaire à null si nécessaire
            if ($reservation->getRando() === $this) {
                $reservation->setRando(null);
            }
        }

        return $this;
    }

    /**
     * Obtient la collection des avis de la randonnée.
     */
    public function getAvis(): Collection
    {
        return $this->avis;
    }

    /**
     * Ajoute un avis à la randonnée.
     */
    public function addAvis(Avis $avis): self
    {
        if (!$this->avis->contains($avis)) {
            $this->avis->add($avis);
            $avis->setRando($this);
        }

        return $this;
    }

    /**
     * Supprime un avis de la randonnée.
     */
    public function removeAvis(Avis $avis): self
    {
        if ($this->avis->removeElement($avis)) {
            // Définit le propriétaire à null si nécessaire
            if ($avis->getRando() === $this) {
                $avis->setRando(null);
            }
        }

        return $this;
    }
}
