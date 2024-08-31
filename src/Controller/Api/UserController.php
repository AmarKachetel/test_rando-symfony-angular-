<?php

namespace App\Controller\Api;

use App\Repository\RandoRepository;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class UserController extends AbstractController
{
    private $security;
    private $logger;

    public function __construct(Security $security, LoggerInterface $logger)
    {
        $this->security = $security;
        $this->logger = $logger;
    }

    #[Route('/api/profilez', name: 'api_user_profile', methods: ['GET'])]
    public function getProfile(Request $request): JsonResponse
    {
        $this->logger->info('Accessing /api/profile endpoint.');

        $data = json_decode($request->getContent(), true);
        dd($data);

        $user = $this->security->getUser();

        if (!$user) {
            $this->logger->warning('User not authenticated.');
            return new JsonResponse(['error' => 'User not authenticated'], JsonResponse::HTTP_UNAUTHORIZED);
        }

        $this->logger->info('Authenticated user: ' . $user->getEmail());

        $profileData = [
            'id' => $user->getId(),
            'name' => $user->getEmail(), // Remplacez par 'getName()' si vous avez un champ 'name'
            'email' => $user->getEmail(),
            'completedHikes' => 10, // Remplacez par une valeur réelle provenant de la base de données
        ];

        return $this->json($profileData);
    }

    #[Route('/api/user/randos', name: 'api_user_randos', methods: ['GET'])]
    public function getUserRandos(RandoRepository $randoRepository): JsonResponse
    {
        $this->logger->info('Accessing /api/user/randos endpoint.');

        $user = $this->security->getUser();

        if (!$user) {
            $this->logger->warning('User not authenticated.');
            return new JsonResponse(['error' => 'User not authenticated'], JsonResponse::HTTP_UNAUTHORIZED);
        }

        $this->logger->info('Fetching randos for user: ' . $user->getEmail());

        // Récupérer les randonnées associées à cet utilisateur
        $randos = $randoRepository->findBy(['user' => $user]);

        // Transformer les randonnées en tableau pour l'API
        $randoData = [];
        foreach ($randos as $rando) {
            $randoData[] = [
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

        return $this->json($randoData);
    }
}
