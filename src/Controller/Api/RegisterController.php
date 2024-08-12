<?php

namespace App\Controller\Api;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class RegisterController extends AbstractController
{
    #[Route('/api/register', name: 'api_register', methods: ['POST'])]
    public function register(Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $em, JWTTokenManagerInterface $jwtManager): Response
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['email']) || !isset($data['password'])) {
            return new JsonResponse(['error' => 'Invalid data'], Response::HTTP_BAD_REQUEST);
        }

        $user = new User();
        $user->setEmail($data['email']);
        $hashedPassword = $passwordHasher->hashPassword($user, $data['password']);
        $user->setPassword($hashedPassword);

        $em->persist($user);
        $em->flush();

        $token = $jwtManager->create($user);

        return new JsonResponse(['token' => $token], Response::HTTP_CREATED);
    }

#[Route('/api/login', name: 'api_login', methods: ['POST'])]
public function login(Request $request, UserPasswordHasherInterface $passwordHasher, JWTTokenManagerInterface $jwtManager, EntityManagerInterface $em): Response
{
    $data = json_decode($request->getContent(), true);

    if (!isset($data['email']) || !isset($data['password'])) {
        return new JsonResponse(['error' => 'Invalid data'], Response::HTTP_BAD_REQUEST);
    }

    $user = $em->getRepository(User::class)->findOneBy(['email' => $data['email']]);

    if (!$user || !$passwordHasher->isPasswordValid($user, $data['password'])) {
        return new JsonResponse(['error' => 'Invalid credentials'], Response::HTTP_UNAUTHORIZED);
    }

    $token = $jwtManager->create($user);

    return new JsonResponse(['token' => $token, 'username' => $user->getEmail()], Response::HTTP_OK);
}




}
