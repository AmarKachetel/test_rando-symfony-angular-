<?php

namespace App\Controller\Api;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class RegisterController extends AbstractController
{
    private $entityManager;
    private $logger;

    public function __construct(EntityManagerInterface $entityManager, LoggerInterface $logger)
    {
        $this->entityManager = $entityManager;
        $this->logger = $logger;
    }

    #[Route('/api/register', name: 'api_register', methods: ['POST'])]
    public function register(Request $request, UserPasswordHasherInterface $passwordHasher, JWTTokenManagerInterface $jwtManager): Response
    {
        $this->logger->info('Accessing /api/register endpoint.');

        $data = json_decode($request->getContent(), true);

        if (!isset($data['email']) || !isset($data['password'])) {
            $this->logger->error('Invalid registration data provided.');
            return new JsonResponse(['error' => 'Invalid data'], Response::HTTP_BAD_REQUEST);
        }

        $user = new User();
        $user->setEmail($data['email']);
        $hashedPassword = $passwordHasher->hashPassword($user, $data['password']);
        $user->setPassword($hashedPassword);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $this->logger->info('User registered successfully: ' . $user->getEmail());

        $token = $jwtManager->create($user);

        return new JsonResponse(['token' => $token], Response::HTTP_CREATED);
    }

    #[Route('/api/login', name: 'api_login', methods: ['POST'])]
public function login(Request $request, JWTTokenManagerInterface $jwtManager): Response
{
    $this->logger->info('Accessing /api/login endpoint.');

    $data = json_decode($request->getContent(), true);

    if (!isset($data['email']) || !isset($data['password'])) {
        $this->logger->error('Invalid login data provided.');
        return new JsonResponse(['error' => 'Invalid data'], Response::HTTP_BAD_REQUEST);
    }

    $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $data['email']]);

    if (!$user || !password_verify($data['password'], $user->getPassword())) {
        $this->logger->warning('Invalid login attempt for email: ' . $data['email']);
        return new JsonResponse(['error' => 'Invalid credentials'], Response::HTTP_UNAUTHORIZED);
    }

    $this->logger->info('User logged in successfully: ' . $user->getEmail());

    $token = $jwtManager->create($user);

    return new JsonResponse(['token' => $token, 'username' => $user->getEmail()], Response::HTTP_OK);
}

}
