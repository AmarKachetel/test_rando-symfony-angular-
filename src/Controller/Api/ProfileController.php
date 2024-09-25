<?php

namespace App\Controller\Api;

use App\DTO\UserProfileDTO;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;

class ProfileController extends AbstractController
{
    #[Route('/api/profile', name: 'user_profile', methods: ['GET'])]
    public function profile(Request $request): Response
    {
        $user = $this->getUser();

        if (!$user) {
            return $this->json(['error' => 'User not authenticated'], Response::HTTP_UNAUTHORIZED);
        }

        return $this->json($user, 200, [], ['groups' => ['user:read']]);
    }

    #[Route('/api/profile', name: 'user_profile_update', methods: ['PUT'])]
    public function updateProfile(Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();

        if (!$user) {
            return $this->json(['error' => 'User not authenticated'], Response::HTTP_UNAUTHORIZED);
        }

        $data = json_decode($request->getContent(), true);

        // Mise à jour de l'email
        if (isset($data['email'])) {
            $user->setEmail($data['email']);
        }

        // Mise à jour du nom d'utilisateur
        if (isset($data['username'])) {
            $user->setUsername($data['username']);
        }

        // Mise à jour du mot de passe
        if (isset($data['newPassword'])) {
            $encodedPassword = $passwordHasher->hashPassword($user, $data['newPassword']);
            $user->setPassword($encodedPassword);
        }

        $entityManager->persist($user);
        $entityManager->flush();

        return $this->json(['message' => 'Profile updated successfully'], 200);
    }

    #[Route('/api/profile/update-password', name: 'user_profile_update_password', methods: ['PUT'])]
    public function updatePassword(Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();

        if (!$user) {
            return $this->json(['error' => 'User not authenticated'], Response::HTTP_UNAUTHORIZED);
        }

        $data = json_decode($request->getContent(), true);

        // Vérification de la présence du nouveau mot de passe
        if (isset($data['newPassword']) && !empty($data['newPassword'])) {
            $encodedPassword = $passwordHasher->hashPassword($user, $data['newPassword']);
            $user->setPassword($encodedPassword);

            $entityManager->persist($user);
            $entityManager->flush();

            return $this->json(['message' => 'Password updated successfully'], 200);
        }

        return $this->json(['error' => 'New password is required'], 400);
    }
}