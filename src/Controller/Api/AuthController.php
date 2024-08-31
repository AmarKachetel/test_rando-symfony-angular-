<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\JWTService;

class AuthController extends AbstractController
{
    private $jwtService;

    public function __construct(JWTService $jwtService)
    {
        $this->jwtService = $jwtService;
    }

    #[Route('/login', name: 'login', methods: ['POST'])]
    public function login(Request $request, SessionInterface $session): Response
    {
        $credentials = json_decode($request->getContent(), true);
        $token = $this->jwtService->validateJWT($credentials);

        if ($token) {
            // Extraire l'utilisateur du token
            $userData = $this->jwtService->getUserDataFromToken($token);

            // Stocker les informations de l'utilisateur dans la session
            $session->set('user', $userData);

            return new Response('Login successful', Response::HTTP_OK);
        }

        return new Response('Invalid credentials', Response::HTTP_UNAUTHORIZED);
    }

    #[Route('/logout', name: 'logout', methods: ['POST'])]
    public function logout(SessionInterface $session): Response
    {
        // Supprimer les données de la session
        $session->invalidate();

        return new Response('Logged out', Response::HTTP_OK);
    }
}
