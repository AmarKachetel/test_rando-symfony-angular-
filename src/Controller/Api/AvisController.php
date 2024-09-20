<?php

namespace App\Controller\Api;

use App\Entity\Avis;
use App\Entity\Rando;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AvisController extends AbstractController
{
    #[Route('/api/avis', name: 'api_add_avis', methods: ['POST'])]
    public function addAvis(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $user = $this->getUser();
        if (!$user) {
            return new JsonResponse(['error' => 'User not authenticated'], JsonResponse::HTTP_UNAUTHORIZED);
        }

        $data = json_decode($request->getContent(), true);
        $rando = $entityManager->getRepository(Rando::class)->find($data['randoId']);
        
        if (!$rando) {
            return new JsonResponse(['error' => 'Rando not found'], JsonResponse::HTTP_NOT_FOUND);
        }

        $avis = new Avis();
        $avis->setUser($user);
        $avis->setRando($rando);
        $avis->setCommentaire($data['commentaire']);
        $avis->setNote($data['note']);
        $avis->setDate(new \DateTime());

        $entityManager->persist($avis);
        $entityManager->flush();

        return new JsonResponse(['message' => 'Avis ajouté avec succès'], JsonResponse::HTTP_CREATED);
    }
}

