<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    #[Route('/api/profile', name: 'user_profile', methods: ['GET', 'POST'])]
    public function profile(SessionInterface $session, Request $request): Response
    {
        // Vérifier si l'utilisateur est connecté
       // if (!$session->has('user')) {
            //return new Response('Unauthorized', Response::HTTP_UNAUTHORIZED);
       // }

       // $userData = $session->get('user');

        $data = json_decode($request->getContent(), true);
        dd($data);

        if ($request->isMethod('POST')) {
            // Mise à jour du profil de l'utilisateur
            $data = json_decode($request->getContent(), true);
            dd($data);
            // Mettre à jour le profil dans la base de données
            // $this->getUserService()->updateUserProfile($userData['id'], $data);

            return new Response('Profile updated', Response::HTTP_OK);
        }

        // Retourner le profil de l'utilisateur
        return $this->json($userData);
    }
}
