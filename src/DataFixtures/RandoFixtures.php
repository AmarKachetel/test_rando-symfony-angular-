<?php

namespace App\DataFixtures;

use App\Entity\Rando;
use App\Entity\User;
use App\Entity\Post;
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
        $user1->setPassword($this->passwordHasher->hashPassword($user1, 'password1'));
        $manager->persist($user1);

        // Création du second utilisateur
        $user2 = new User();
        $user2->setEmail('user2@example.com');
        $user2->setPassword($this->passwordHasher->hashPassword($user2, 'password2'));
        $manager->persist($user2);

        // Liste des randonnées pour le premier utilisateur
        $randosUser1 = [
            [
                'title' => 'Randonnée du Mont Blanc',
                'description' => 'Une magnifique randonnée autour du Mont Blanc.',
                'location' => 'Chamonix, France',
                'distance' => 10.5,
                'duration' => '5h30',
                'difficulty' => 'Difficile',
                'image' => 'mont_blanc.jpg',
                'coordinates' => [45.832622, 6.865188]
            ],
            [
                'title' => 'Tour du lac d’Annecy',
                'description' => 'Randonnée tranquille autour du lac d’Annecy.',
                'location' => 'Annecy, France',
                'distance' => 14.2,
                'duration' => '4h00',
                'difficulty' => 'Facile',
                'image' => 'lac_annecy.jpg',
                'coordinates' => [45.899247, 6.129384]
            ],
        ];

        // Liste des randonnées pour le second utilisateur
        $randosUser2 = [
            [
                'title' => 'Sentier des douaniers',
                'description' => 'Un parcours longeant la côte bretonne.',
                'location' => 'Bretagne, France',
                'distance' => 12.3,
                'duration' => '6h00',
                'difficulty' => 'Moyen',
                'image' => 'sentier_douaniers.jpg',
                'coordinates' => [48.831282, -3.458218]
            ],
            [
                'title' => 'GR20',
                'description' => 'Le célèbre GR20 à travers la Corse.',
                'location' => 'Corse, France',
                'distance' => 180.0,
                'duration' => '15 jours',
                'difficulty' => 'Très difficile',
                'image' => 'gr20.jpg',
                'coordinates' => [42.258745, 9.187408]
            ],
        ];

        // Persist les randonnées pour les utilisateurs
        $this->persistRandos($manager, $randosUser1, $user1);
        $this->persistRandos($manager, $randosUser2, $user2);

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

    private function persistRandos(ObjectManager $manager, array $randosData, User $user)
    {
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
            $rando->setUser($user);  // Associer la randonnée à l'utilisateur

            $manager->persist($rando);
        }
    }

    private function addPostsForUser(ObjectManager $manager, User $user, array $postsData)
    {
        foreach ($postsData as $postData) {
            $post = new Post();
            $post->setTitle($postData['title']);
            $post->setContent($postData['content']);
            $post->setUser($user);  // Associer le post à l'utilisateur

            $manager->persist($post);
        }
    }
}
