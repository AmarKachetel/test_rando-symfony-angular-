<?php

namespace App\Controller\Api;

use App\Entity\Avis;
use App\Entity\Rando;
use App\DTO\AvisDTO;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class AvisController extends AbstractController
{
    #[Route('/api/avis', name: 'api_add_avis', methods: ['POST'])]
    public function addAvis(
        Request $request,
        EntityManagerInterface $entityManager,
        SerializerInterface $serializer,
        ValidatorInterface $validator
    ): JsonResponse {
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

        $errors = $validator->validate($avis);
        if (count($errors) > 0) {
            return new JsonResponse(['error' => (string) $errors], JsonResponse::HTTP_BAD_REQUEST);
        }

        $entityManager->persist($avis);
        $entityManager->flush();

        $avisDTO = new AvisDTO($avis);
        return $this->json($avisDTO, JsonResponse::HTTP_CREATED);
    }

    #[Route('/api/avis/{randoId}', name: 'api_get_avis', methods: ['GET'])]
    public function getAvisForRando(int $randoId, EntityManagerInterface $entityManager): JsonResponse
    {
        $rando = $entityManager->getRepository(Rando::class)->find($randoId);

        if (!$rando) {
            return new JsonResponse(['error' => 'Rando not found'], JsonResponse::HTTP_NOT_FOUND);
        }

        $avisList = $rando->getAvis()->toArray();
        $avisDTOs = array_map(fn($avis) => new AvisDTO($avis), $avisList);

        return $this->json($avisDTOs);
    }
}

