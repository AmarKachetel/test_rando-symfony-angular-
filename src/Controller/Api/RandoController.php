<?php

namespace App\Controller\Api;

use App\Repository\RandoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

class RandoController extends AbstractController
{
    #[Route('/api/randos', name: 'api_randos', methods: ['GET'])]
    public function getRandos(RandoRepository $randoRepository): JsonResponse
    {
        // Récupérer toutes les randonnées
        $randos = $randoRepository->findAll();

        // Retourner la réponse JSON en utilisant les groupes de sérialisation
        return $this->json($randos, 200, [], [AbstractNormalizer::GROUPS => ['rando:read']]);
    }
}
