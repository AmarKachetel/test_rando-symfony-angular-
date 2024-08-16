<?php

namespace App\Controller\Api;

use Psr\Log\LoggerInterface;
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

    #[Route('/api/profile', name: 'api_user_profile', methods: ['GET'])]
    public function getProfile(): JsonResponse
    {
        $this->logger->info('Accessing /api/profile endpoint.');

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
}
