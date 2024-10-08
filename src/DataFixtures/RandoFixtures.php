<?php

namespace App\DataFixtures;

use App\Entity\Rando;
use App\Entity\User;
use App\Entity\Post;
use App\Entity\Photo;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class RandoFixtures extends Fixture
{
    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        // Création du premier utilisateur
        $user1 = new User();
        $user1->setEmail('user1@example.com');
        $user1->setUsername('user1');
        $user1->setPassword($this->passwordHasher->hashPassword($user1, 'password1'));
        $user1->setRoles(['ROLE_USER', 'ROLE_ADMIN']);
        $manager->persist($user1);

        // Création du second utilisateur avec le rôle ADMIN
        $user2 = new User();
        $user2->setEmail('user2@example.com');
        $user2->setUsername('user2');
        $user2->setPassword($this->passwordHasher->hashPassword($user2, 'password2'));
        $user2->setRoles(['ROLE_USER', 'ROLE_ADMIN']);
        $manager->persist($user2);

        // Liste des randonnées pour le premier utilisateur
        $randosUser1 = $this->persistRandos($manager, [
            [
                'title' => 'Randonnée du Mont Blanc',
                'description' => 'Une magnifique randonnée autour du Mont Blanc.',
                'location' => 'Chamonix, France',
                'distance' => 10.5,
                'duration' => '5h30',
                'difficulty' => 'Difficile',
                'image' => '/images/randos/rando1.jpg',
                'coordinates' => [45.832622, 6.865188]
            ],
            [
                'title' => 'Tour du lac d’Annecy',
                'description' => 'Randonnée tranquille autour du lac d’Annecy.',
                'location' => 'Annecy, France',
                'distance' => 14.2,
                'duration' => '4h00',
                'difficulty' => 'Facile',
                'image' => '/images/randos/rando2.jpg',
                'coordinates' => [45.899247, 6.129384]
            ],
            [
                'title' => 'Circuit des 25 bosses',
                'description' => 'Un parcours difficile à travers les forêts de Fontainebleau.',
                'location' => 'Fontainebleau, France',
                'distance' => 16.0,
                'duration' => '7h00',
                'difficulty' => 'Très difficile',
                'image' => '/images/randos/rando2.jpg',
                'coordinates' => [48.404147, 2.697014]
            ],
            [
                'title' => 'Lacs de Plitvice',
                'description' => 'Une randonnée pittoresque autour des lacs de Plitvice.',
                'location' => 'Croatie',
                'distance' => 8.0,
                'duration' => '3h30',
                'difficulty' => 'Moyen',
                'image' => '/images/randos/rando2.jpg',
                'coordinates' => [44.880372, 15.617781]
            ],
            [
                'title' => 'Randonnée de l’Obiou',
                'description' => 'Un parcours exigeant dans les Alpes françaises.',
                'location' => 'Alpes, France',
                'distance' => 12.7,
                'duration' => '6h00',
                'difficulty' => 'Difficile',
                'image' => '/images/randos/rando1.jpg',
                'coordinates' => [44.820297, 5.895989]
            ]
        ], $user1);

        // Liste des randonnées pour le second utilisateur
        $randosUser2 = $this->persistRandos($manager, [
            [
                'title' => 'Sentier des douaniers',
                'description' => 'Un parcours longeant la côte bretonne.',
                'location' => 'Bretagne, France',
                'distance' => 12.3,
                'duration' => '6h00',
                'difficulty' => 'Moyen',
                'image' => '/images/randos/rando2.jpg',
                'coordinates' => [48.831282, -3.458218]
            ],
            [
                'title' => 'GR20',
                'description' => 'Le célèbre GR20 à travers la Corse.',
                'location' => 'Corse, France',
                'distance' => 180.0,
                'duration' => '15 jours',
                'difficulty' => 'Très difficile',
                'image' => '/images/randos/rando1.jpg',
                'coordinates' => [42.258745, 9.187408]
            ],
            [
                'title' => 'Tour des glaciers',
                'description' => 'Randonnée au pied des glaciers des Alpes.',
                'location' => 'Alpes, France',
                'distance' => 45.0,
                'duration' => '3 jours',
                'difficulty' => 'Très difficile',
                'image' => '/images/randos/rando1.jpg',
                'coordinates' => [45.9222, 6.8694]
            ],
            [
                'title' => 'Chemin de Saint-Jacques-de-Compostelle',
                'description' => 'Pèlerinage mythique à travers la France et l’Espagne.',
                'location' => 'France - Espagne',
                'distance' => 800.0,
                'duration' => '35 jours',
                'difficulty' => 'Moyen',
                'image' => '/images/randos/rando2.jpg',
                'coordinates' => [42.877742, -8.544844]
            ],
            [
                'title' => 'Mont Ventoux',
                'description' => 'Ascension du Mont Ventoux, un sommet emblématique de Provence.',
                'location' => 'Provence, France',
                'distance' => 21.0,
                'duration' => '8h00',
                'difficulty' => 'Difficile',
                'image' => '/images/randos/rando1.jpg',
                'coordinates' => [44.172359, 5.278526]
            ]
        ], $user2);

        // Ajouter des photos pour chaque utilisateur
        $this->addPhotosForUser($manager, $user1, [
            ['url' => '/images/randos/rando1.jpg', 'description' => 'Photo de la randonnée du Mont Blanc.'],
            ['url' => '/images/randos/rando2.jpg', 'description' => 'Photo de la randonnée du lac d’Annecy.']
        ], $randosUser1[0]);

        $this->addPhotosForUser($manager, $user2, [
            ['url' => '/images/randos/rando1.jpg', 'description' => 'Photo de la randonnée Sentier des douaniers.'],
            ['url' => '/images/randos/rando2.jpg', 'description' => 'Photo de la randonnée GR20.']
        ], $randosUser2[0]);

        // Ajouter des posts pour chaque utilisateur
        $this->addPostsForUser($manager, $user1, [
            ['title' => 'Mon premier post', 'content' => 'Contenu du premier post de user1.'],
            ['title' => 'Deuxième post', 'content' => 'Contenu du deuxième post de user1.']
        ]);

        $this->addPostsForUser($manager, $user2, [
            ['title' => 'Aventures en Bretagne', 'content' => 'Description des aventures en Bretagne de user2.'],
            ['title' => 'Randonnée en Corse', 'content' => 'Rapport de la randonnée en Corse de user2.']
        ]);

        $manager->flush();
    }

    private function persistRandos(ObjectManager $manager, array $randosData, User $user): array
    {
        $randos = [];

        foreach ($randosData as $randoData) {
            $rando = new Rando();
            $rando->setTitle($randoData['title']);
            $rando->setDescription($randoData['description']);
            $rando->setLocation($randoData['location']);
            $rando->setDistance($randoData['distance']);
            $rando->setDuration($randoData['duration']);
            $rando->setDifficulty($randoData['difficulty']);
            $rando->setImage($randoData['image']);
            $rando->setCoordinates($randoData['coordinates']);
            $rando->setUser($user);

            $manager->persist($rando);
            $randos[] = $rando;
        }

        return $randos;
    }

    private function addPostsForUser(ObjectManager $manager, User $user, array $postsData)
    {
        foreach ($postsData as $postData) {
            $post = new Post();
            $post->setTitle($postData['title']);
            $post->setContent($postData['content']);
            $post->setUser($user);

            $manager->persist($post);
        }
    }

    private function addPhotosForUser(ObjectManager $manager, User $user, array $photosData, Rando $rando = null)
    {
        foreach ($photosData as $photoData) {
            $photo = new Photo();
            $photo->setUrl($photoData['url']);
            $photo->setDescription($photoData['description']);
            $photo->setUser($user);

            if ($rando) {
                $photo->setRando($rando);
            }

            $manager->persist($photo);
        }
    }
    
}
