<?php

namespace App\Controller\Api;

use App\Entity\Post;
use App\DTO\PostDTO; 
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class PostController extends AbstractController
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

    #[Route('/api/posts', name: 'api_all_posts', methods: ['GET'])]
    public function getAllPosts(): JsonResponse
    {
        $this->logger->info('Accessing /api/posts endpoint.');

        // Récupérer tous les posts
        $posts = $this->entityManager->getRepository(Post::class)->findAll();

        $this->logger->info('Found ' . count($posts) . ' posts.');

        return $this->json($posts);
    }

    #[Route('/api/user/posts', name: 'api_user_posts', methods: ['GET'])]
    public function getUserPosts(): JsonResponse
    {
        $this->logger->info('Accessing /api/user/posts endpoint.');
    
        $user = $this->security->getUser();
    
        if (!$user) {
            $this->logger->warning('User not authenticated.');
            return new JsonResponse(['error' => 'User not authenticated'], JsonResponse::HTTP_UNAUTHORIZED);
        }
    
        $this->logger->info('Authenticated user: ' . $user->getEmail());
    
        // Récupérer les posts de l'utilisateur
        $posts = $this->entityManager->getRepository(Post::class)->findBy(['user' => $user]);
    
        $this->logger->info('Found ' . count($posts) . ' posts for user.');
    
        // Convertir les entités Post en instances de PostDTO
        $postDTOs = array_map(function (Post $post) {
            return new PostDTO(
                $post->getId(),
                $post->getTitle(),
                $post->getContent(),
                $post->getCreatedAt(),
                $post->getUser()
            );
        }, $posts);
    
        return $this->json($postDTOs);
    }
    
    
    
}