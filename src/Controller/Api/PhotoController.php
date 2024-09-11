<?php

namespace App\Controller\Api;

use App\Entity\Photo;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class PhotoController extends AbstractController
{
    private $entityManager;
    private $tokenStorage;
    private $logger;

    public function __construct(EntityManagerInterface $entityManager, TokenStorageInterface $tokenStorage, LoggerInterface $logger)
    {
        $this->entityManager = $entityManager;
        $this->tokenStorage = $tokenStorage;
        $this->logger = $logger;
    }

    #[Route('/api/photos', name: 'api_all_photos', methods: ['GET'])]
    public function getAllPhotos(): JsonResponse
    {
        $this->logger->info('Accessing /api/photos endpoint.');

        // Récupérer toutes les photos
        $photos = $this->entityManager->getRepository(Photo::class)->findAll();

        $this->logger->info('Found ' . count($photos) . ' photos.');

        return $this->json($photos);
    }

    #[Route('/api/user/photos', name: 'api_user_photos', methods: ['GET'])]
    public function getUserPhotos(): JsonResponse
    {
    $this->logger->info('Accessing /api/user/photos endpoint.');

    $user = $this->getUser();

    if (!$user) {
        $this->logger->warning('User not authenticated.');
        return new JsonResponse(['error' => 'User not authenticated'], JsonResponse::HTTP_UNAUTHORIZED);
    }

    $this->logger->info('Authenticated user: ' . $user->getEmail());

    $photos = $this->entityManager->getRepository(Photo::class)->findBy(['user' => $user]);

    $this->logger->info('Found ' . count($photos) . ' photos for user.');

    return $this->json($photos, 200, [], ['groups' => ['photo:read']]);
    }

}
