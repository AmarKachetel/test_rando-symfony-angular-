<?php

namespace App\Controller\Api;

use App\DTO\UserProfileDTO;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    #[Route('/api/profile', name: 'user_profile', methods: ['GET'])]
    public function profile(Request $request): Response
    {
        $user = $this->getUser();
    
        if (!$user) {
            return new JsonResponse(['error' => 'User not authenticated'], JsonResponse::HTTP_UNAUTHORIZED);
        }
    
        // Utiliser le DTO pour le profil utilisateur
        $userProfileDTO = new UserProfileDTO($user);
    
        return $this->json($userProfileDTO);
    }
    
}
