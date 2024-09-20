<?php

namespace App\Controller\Api;

use App\Entity\Photo;
use App\Entity\Rando;
use App\DTO\PhotoDTO;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class PhotoController extends AbstractController
{
    private $entityManager;
    private $tokenStorage;
    private $logger;

    public function __construct(EntityManagerInterface $entityManager, TokenStorageInterface $tokenStorage, LoggerInterface $logger)
    {
        $this->entityManager = $entityManager;
        $this->tokenStorage = $tokenStorage;
        $this->logger = $logger;
    }

    #[Route('/api/photos', name: 'api_all_photos', methods: ['GET'])]
    public function getAllPhotos(): JsonResponse
    {
        $photos = $this->entityManager->getRepository(Photo::class)->findAll();
        $photoDTOs = array_map(fn($photo) => new PhotoDTO($photo), $photos);

        return $this->json($photoDTOs);
    }

    #[Route('/api/user/photos', name: 'api_user_photos', methods: ['GET'])]
    public function getUserPhotos(): JsonResponse
    {
        $user = $this->getUser();
        if (!$user) {
            return new JsonResponse(['error' => 'User not authenticated'], JsonResponse::HTTP_UNAUTHORIZED);
        }

        $photos = $this->entityManager->getRepository(Photo::class)->findBy(['user' => $user]);
        $photoDTOs = array_map(fn($photo) => new PhotoDTO($photo), $photos);

        return $this->json($photoDTOs);
    }

    #[Route('/api/photos/upload/{randoId}', name: 'api_upload_photo', methods: ['POST'])]
    public function uploadPhoto(Request $request, int $randoId, EntityManagerInterface $entityManager): JsonResponse
    {
        $user = $this->getUser();
        if (!$user) {
            return new JsonResponse(['error' => 'User not authenticated'], JsonResponse::HTTP_UNAUTHORIZED);
        }
    
        $rando = $entityManager->getRepository(Rando::class)->find($randoId);
        if (!$rando) {
            return new JsonResponse(['error' => 'Rando not found'], JsonResponse::HTTP_NOT_FOUND);
        }
    
        $file = $request->files->get('file');
        $description = $request->request->get('description');  // Get the description from the form
    
        if ($file) {
            $filename = md5(uniqid()) . '.' . $file->guessExtension();
            $file->move($this->getParameter('photos_directory'), $filename);
    
            $photo = new Photo();
            $photo->setUser($user);
            $photo->setRando($rando);
            $photo->setUrl('/uploads/photos/' . $filename);
            $photo->setDescription($description); // Set description
            
            $entityManager->persist($photo);
            $entityManager->flush();
    
            $photoDTO = new PhotoDTO($photo);
            return $this->json($photoDTO, JsonResponse::HTTP_CREATED);
        }
    
        return new JsonResponse(['error' => 'No file uploaded'], JsonResponse::HTTP_BAD_REQUEST);
    }
    
    #[Route('/api/photos/delete/{photoId}', name: 'api_delete_photo', methods: ['DELETE'])]
    public function deletePhoto(int $photoId): JsonResponse
    {
        $user = $this->getUser();
        if (!$user) {
            return new JsonResponse(['error' => 'User not authenticated'], JsonResponse::HTTP_UNAUTHORIZED);
        }

        $photo = $this->entityManager->getRepository(Photo::class)->find($photoId);
        if (!$photo || $photo->getUser() !== $user) {
            return new JsonResponse(['error' => 'Photo not found or unauthorized'], JsonResponse::HTTP_NOT_FOUND);
        }

        // Remove the photo entity
        $this->entityManager->remove($photo);
        $this->entityManager->flush();

        return new JsonResponse(['message' => 'Photo deleted successfully'], JsonResponse::HTTP_OK);
    }
}