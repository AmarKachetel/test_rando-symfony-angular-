<?php

namespace App\Controller\Api;

use App\Entity\Reservation;
use App\Entity\User;
use App\Entity\Rando;
use App\Repository\ReservationRepository;
use App\Repository\RandoRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/api/reservations', name: 'api_reservation_')]
class ReservationController extends AbstractController
{
    private $entityManager;
    private $serializer;

    public function __construct(EntityManagerInterface $entityManager, SerializerInterface $serializer)
    {
        $this->entityManager = $entityManager;
        $this->serializer = $serializer;
    }

    #[Route('/', name: 'create', methods: ['POST'])]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function create(Request $request, RandoRepository $randoRepository, ReservationRepository $reservationRepository): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $user = $this->getUser();

        if (!$user instanceof User) {
            return $this->json(['message' => 'User not found.'], 404);
        }

        $rando = $randoRepository->find($data['rando_id']);

        if (!$rando) {
            return $this->json(['message' => 'Rando not found.'], 404);
        }

        $reservation = new Reservation();
        $reservation->setUser($user);
        $reservation->setRando($rando);
        $reservation->setReservationDate(new \DateTime());
        $reservation->setStatus('pending');

        $this->entityManager->persist($reservation);
        $this->entityManager->flush();

        return $this->json(['message' => 'Reservation created successfully'], 201);
    }

    #[Route('/my', name: 'list_user', methods: ['GET'])]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function listUserReservations(ReservationRepository $reservationRepository): JsonResponse
    {
       $user = $this->getUser();

       if (!$user instanceof User) {
           return $this->json(['message' => 'User not found.'], 404);
       }

       // Récupérer les réservations de l'utilisateur
       $reservations = $reservationRepository->findBy(['user' => $user]);

       // Personnaliser la sérialisation pour inclure les détails de la randonnée
       $reservationsData = array_map(function (Reservation $reservation) {
           return [
               'id' => $reservation->getId(),
               'reservationDate' => $reservation->getReservationDate()->format('Y-m-d H:i:s'),
               'status' => $reservation->getStatus(),
              'rando' => [
                  'id' => $reservation->getRando()->getId(),
                  'title' => $reservation->getRando()->getTitle(),
                  'description' => $reservation->getRando()->getDescription(),
                  'location' => $reservation->getRando()->getLocation(),
                  'distance' => $reservation->getRando()->getDistance(),
                  'duration' => $reservation->getRando()->getDuration(),
                  'difficulty' => $reservation->getRando()->getDifficulty(),
                  'image' => $reservation->getRando()->getImage(),
              ]
          ];
      }, $reservations);

       return $this->json($reservationsData);
    }


    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function delete(int $id, ReservationRepository $reservationRepository): JsonResponse
    {
        // Rechercher la réservation par son identifiant
        $reservation = $reservationRepository->find($id);
    
        if (!$reservation) {
            return $this->json(['message' => 'Reservation not found.'], 404);
        }
    
        // Vérifier si la réservation appartient à l'utilisateur connecté
        if ($reservation->getUser() !== $this->getUser()) {
            return $this->json(['message' => 'Unauthorized'], 403);
        }
    
        // Supprimer la réservation
        $this->entityManager->remove($reservation);
        $this->entityManager->flush();
    
        return $this->json(['message' => 'Reservation deleted successfully'], 200);
    }
    
}
