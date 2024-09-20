<?php

namespace App\DTO;

use App\Entity\Avis;

class AvisDTO
{
    public int $id;
    public string $commentaire;
    public int $note;
    public string $date;
    public string $userName;

    public function __construct(Avis $avis)
    {
        $this->id = $avis->getId();
        $this->commentaire = $avis->getCommentaire();
        $this->note = $avis->getNote();
        $this->date = $avis->getDate()->format('Y-m-d H:i:s');
        $this->userName = $avis->getUser()->getEmail(); // You can change this to another user field if required.
    }
}
