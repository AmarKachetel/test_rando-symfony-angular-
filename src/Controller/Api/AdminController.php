<?php

namespace App\Controller\Api;

use App\Entity\User;
use App\Entity\Rando;
use App\Entity\Avis;
use App\DTO\UserValidationDTO; 
use App\Repository\UserRepository;
use App\Repository\RandoRepository;
use App\Repository\AvisRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class AdminController extends AbstractController
{
    #[Route('/api/admin/validate-user/{id}', name: 'admin_validate_user', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function validateUser(int $id, UserRepository $userRepository, EntityManagerInterface $entityManager): JsonResponse
    {
        // Rechercher l'utilisateur par ID
        $user = $userRepository->find($id);

        if (!$user) {
            return new JsonResponse(['error' => 'Utilisateur non trouvé.'], JsonResponse::HTTP_NOT_FOUND);
        }

        // Valider l'utilisateur
        $user->setIsValidated(true);
        $entityManager->persist($user);
        $entityManager->flush();

        return new JsonResponse(['message' => 'Utilisateur validé avec succès.']);
    }

    #[Route('/api/admin/randos', name: 'admin_get_all_randos', methods: ['GET'])]
    #[IsGranted('ROLE_ADMIN')]
    public function getAllRandos(RandoRepository $randoRepository): JsonResponse
    {
        $randos = $randoRepository->findAll();
    
        return $this->json($randos, 200, [], ['groups' => ['rando:read']]);
    }

    #[Route('/api/admin/randos', name: 'admin_create_rando', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function createRando(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $rando = new Rando();
        $rando->setTitle($data['title']);
        $rando->setDescription($data['description']);
        $rando->setLocation($data['location']);
        $rando->setDistance($data['distance']);
        $rando->setDuration($data['duration']);
        $rando->setDifficulty($data['difficulty']);
        $rando->setImage($data['image']);

        $entityManager->persist($rando);
        $entityManager->flush();

        return new JsonResponse(['message' => 'Randonnée créée avec succès.'], JsonResponse::HTTP_CREATED);
    }
    
    #[Route('/api/admin/randos/{id}', name: 'admin_get_rando', methods: ['GET'])]
    #[IsGranted('ROLE_ADMIN')]
    public function getRandoById(int $id, RandoRepository $randoRepository): JsonResponse
    {
        $rando = $randoRepository->find($id);
    
        if (!$rando) {
            return new JsonResponse(['error' => 'Randonnée non trouvée.'], JsonResponse::HTTP_NOT_FOUND);
        }
    
        return $this->json($rando, 200, [], ['groups' => ['rando:read']]);
    }

    #[Route('/api/admin/users/unvalidated', name: 'admin_get_unvalidated_users', methods: ['GET'])]
    #[IsGranted('ROLE_ADMIN')]
    public function getUnvalidatedUsers(UserRepository $userRepository): JsonResponse
    {
        $unvalidatedUsers = $userRepository->findBy(['isValidated' => false]);

        // Convert each user to a DTO
        $userDTOs = array_map(function ($user) {
            return new UserValidationDTO($user);
        }, $unvalidatedUsers);

        return $this->json($userDTOs);
    }

    #[Route('/api/admin/reject-user/{id}', name: 'admin_reject_user', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function rejectUser(int $id, UserRepository $userRepository, EntityManagerInterface $entityManager): JsonResponse
    {
       $user = $userRepository->find($id);

       if (!$user) {
            return new JsonResponse(['error' => 'Utilisateur non trouvé.'], JsonResponse::HTTP_NOT_FOUND);
       }

       // You can decide to either delete the user or mark them as rejected.
       $entityManager->remove($user);
       $entityManager->flush();

       return new JsonResponse(['message' => 'Utilisateur rejeté avec succès.']);
    }
    
    #[Route('/api/admin/randos/{id}', name: 'admin_update_rando', methods: ['PUT'])]
    #[IsGranted('ROLE_ADMIN')]
    public function updateRando(int $id, Request $request, RandoRepository $randoRepository, EntityManagerInterface $entityManager): JsonResponse
    {
        $rando = $randoRepository->find($id);

        if (!$rando) {
            return new JsonResponse(['error' => 'Randonnée non trouvée.'], JsonResponse::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent(), true);
        $rando->setTitle($data['title']);
        $rando->setDescription($data['description']);
        $rando->setLocation($data['location']);
        $rando->setDistance($data['distance']);
        $rando->setDuration($data['duration']);
        $rando->setDifficulty($data['difficulty']);
        $rando->setImage($data['image']);

        $entityManager->flush();

        return new JsonResponse(['message' => 'Randonnée mise à jour avec succès.']);
    }

    #[Route('/api/admin/randos/{id}', name: 'admin_delete_rando', methods: ['DELETE'])]
    #[IsGranted('ROLE_ADMIN')]
    public function deleteRando(int $id, RandoRepository $randoRepository, EntityManagerInterface $entityManager): JsonResponse
    {
        $rando = $randoRepository->find($id);
    
        if (!$rando) {
            return new JsonResponse(['error' => 'Randonnée non trouvée.'], JsonResponse::HTTP_NOT_FOUND);
        }
    
        $entityManager->remove($rando);
        $entityManager->flush();
    
        return new JsonResponse(['message' => 'Randonnée supprimée avec succès.']);
    }
    

    #[Route('/api/admin/avis', name: 'admin_get_avis_to_moderate', methods: ['GET'])]
    #[IsGranted('ROLE_ADMIN')]
    public function getAvisToModerate(AvisRepository $avisRepository): JsonResponse
    {
        $avisList = $avisRepository->findBy(['approved' => false]);
        return $this->json($avisList, JsonResponse::HTTP_OK, [], ['groups' => 'avis:read']);
    }

    #[Route('/api/admin/avis/approve/{id}', name: 'admin_approve_avis', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function approveAvis(int $id, AvisRepository $avisRepository, EntityManagerInterface $entityManager): JsonResponse
    {
        $avis = $avisRepository->find($id);

        if (!$avis) {
            return new JsonResponse(['error' => 'Avis non trouvé.'], JsonResponse::HTTP_NOT_FOUND);
        }

        $avis->setApproved(true); // Marquer l'avis comme approuvé
        $entityManager->flush();

        return new JsonResponse(['message' => 'Avis approuvé avec succès.']);
    }

    #[Route('/api/admin/avis/reject/{id}', name: 'admin_reject_avis', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function rejectAvis(int $id, AvisRepository $avisRepository, EntityManagerInterface $entityManager): JsonResponse
    {
        $avis = $avisRepository->find($id);

        if (!$avis) {
            return new JsonResponse(['error' => 'Avis non trouvé.'], JsonResponse::HTTP_NOT_FOUND);
        }

        $entityManager->remove($avis);
        $entityManager->flush();

        return new JsonResponse(['message' => 'Avis rejeté avec succès.']);
    }

    #[Route('/api/avis/{randoId}', name: 'api_get_avis', methods: ['GET'])]
    public function getAvisForRando(int $randoId, RandoRepository $randoRepository): JsonResponse
    {
        $rando = $randoRepository->find($randoId);

        if (!$rando) {
            return new JsonResponse(['error' => 'Rando non trouvée.'], JsonResponse::HTTP_NOT_FOUND);
        }

        $avisList = $rando->getAvis()->filter(function ($avis) {
            return $avis->getApproved(); // Filtrer seulement les avis approuvés
        });

        return $this->json($avisList->toArray());
    }

}
