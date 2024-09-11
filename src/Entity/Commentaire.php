<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\MaxDepth; // Ajout de l'import pour MaxDepth
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection; // Ajout pour les collections
use App\Entity\Avis;
use App\Entity\User;

#[ORM\Entity]
#[ORM\HasLifecycleCallbacks] // Ajout pour activer les callbacks comme PrePersist
class Commentaire
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['commentaire:read', 'commentaire:write'])]
    private ?int $id = null; // Ajout de type nullable

    #[ORM\Column(type: 'text')]
    #[Groups(['commentaire:read', 'commentaire:write'])]
    private ?string $content = null; // Ajout de type nullable

    #[ORM\Column(type: 'datetime')]
    #[Groups(['commentaire:read'])]
    private ?\DateTimeInterface $createdAt = null; // Ajout de type nullable

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'commentaires')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['commentaire:read', 'user:read'])]
    #[MaxDepth(1)]
    private ?User $user = null; // Ajout de type nullable

    #[ORM\ManyToOne(targetEntity: Avis::class, inversedBy: 'commentaires')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['commentaire:read', 'avis:read'])]
    #[MaxDepth(1)]
    private ?Avis $avis = null; // Ajout de type nullable

    #[ORM\PrePersist]
    public function setCreatedAtValue(): void
    {
        if ($this->createdAt === null) {
            $this->createdAt = new \DateTime(); // Assure que la date de création est définie avant de persister
        }
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

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

    public function getAvis(): ?Avis
    {
        return $this->avis;
    }

    public function setAvis(?Avis $avis): self
    {
        $this->avis = $avis;

        return $this;
    }
}
