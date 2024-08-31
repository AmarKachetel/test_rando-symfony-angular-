<?php

namespace App\Controller;

use App\Entity\ContactMessage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    #[Route('/api/contact', name: 'contact', methods: ['POST'])]
    public function contact(Request $request, EntityManagerInterface $entityManager): Response
    {
        $data = json_decode($request->getContent(), true);

        $name = $data['name'] ?? '';
        $email = $data['email'] ?? '';
        $message = $data['message'] ?? '';

        // Validation des champs
        if (empty($name) || empty($email) || empty($message)) {
            return new Response('Tous les champs sont requis.', Response::HTTP_BAD_REQUEST);
        }

        // Validation de l'email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return new Response('Adresse email invalide.', Response::HTTP_BAD_REQUEST);
        }

        // Créer une nouvelle entité ContactMessage
        $contactMessage = new ContactMessage();
        $contactMessage->setName($name);
        $contactMessage->setEmail($email);
        $contactMessage->setMessage($message);

        // Enregistrer le message dans la base de données
        try {
            $entityManager->persist($contactMessage);
            $entityManager->flush();
            return new Response('Message enregistré avec succès.', Response::HTTP_OK);
        } catch (\Exception $e) {
            return new Response('Erreur lors de l\'enregistrement du message. Veuillez réessayer plus tard.', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
