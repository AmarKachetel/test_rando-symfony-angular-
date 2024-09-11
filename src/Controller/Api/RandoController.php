<?php

namespace App\Controller\Api;

use App\Repository\RandoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Security\Http\Attribute\IsGranted;

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

#[Route('/api/user/randos', name: 'api_user_randos', methods: ['GET'])]
#[IsGranted('IS_AUTHENTICATED_FULLY')] // Require authentication
public function getUserRandos(RandoRepository $randoRepository): JsonResponse
{
    // Obtenir l'utilisateur connecté
    $user = $this->getUser();

    if (!$user) {
        return new JsonResponse(['error' => 'User not authenticated'], JsonResponse::HTTP_UNAUTHORIZED);
    }

    // Récupérer les randonnées associées à cet utilisateur
    $randos = $randoRepository->findBy(['user' => $user]);

    // Retourner la réponse JSON en utilisant les groupes de sérialisation
    return $this->json($randos, 200, [], [AbstractNormalizer::GROUPS => ['rando:read']]);
}

}
