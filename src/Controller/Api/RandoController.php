<?php


// App/Controller/Api/RandoController.php

namespace App\Controller\Api;

use App\Entity\Rando;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class RandoController extends AbstractController
{
    private $entityManager;
    private $security;
    private $logger;

    public function __construct(EntityManagerInterface $entityManager, Security $security, LoggerInterface $logger)
    {
        $this->entityManager = $entityManager;
        $this->security = $security;
        $this->logger = $logger;
    }

    #[Route('/api/randos', name: 'api_randos', methods: ['GET'])]
    public function getUserRandos(): JsonResponse
    {
        $this->logger->info('Accessing /api/randos endpoint.');

        $user = $this->security->getUser();

        if (!$user) {
            $this->logger->warning('User not authenticated.');
            return new JsonResponse(['error' => 'User not authenticated'], JsonResponse::HTTP_UNAUTHORIZED);
        }

        $this->logger->info('Authenticated user: ' . $user->getEmail());

        $randos = $this->entityManager->getRepository(Rando::class)->findBy(['user' => $user]);

        $this->logger->info('Found ' . count($randos) . ' randos for user.');

        return $this->json($randos);
    }
}

