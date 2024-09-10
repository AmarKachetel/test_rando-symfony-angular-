<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class AuthController extends AbstractController
{
    #[Route('/api/login', name: 'api_login', methods: ['POST'])]
    public function login(Request $request, SessionInterface $session): JsonResponse
    {
        // Récupère les informations d'identification de la requête
        $credentials = json_decode($request->getContent(), true);

        // Ajouter la logique de validation et d'authentification ici

        return new JsonResponse(['message' => 'Login successful'], JsonResponse::HTTP_OK);
    }

    #[Route('/api/logout', name: 'api_logout', methods: ['POST'])]
    public function logout(SessionInterface $session): JsonResponse
    {
        // Supprimer les données de la session
        $session->invalidate();

        return new JsonResponse(['message' => 'Logged out'], JsonResponse::HTTP_OK);
    }
}
