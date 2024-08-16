<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Rando;
use App\Entity\Photo;
use App\Entity\Post;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager)
    {
        // User A
        $userA = new User();
        $userA->setEmail('usera@example.com');
        $userA->setPassword($this->passwordHasher->hashPassword($userA, 'password123'));
        $manager->persist($userA);

        // Randonnées de User A
        for ($i = 1; $i <= 3; $i++) {
            $rando = new Rando();
            $rando->setTitle("Randonnée $i de User A")
                  ->setDescription("Description de la randonnée $i de User A")
                  ->setLocation("Location $i")
                  ->setDistance(rand(5, 20))
                  ->setDuration(rand(1, 5) . 'h')
                  ->setDifficulty('Moyenne')
                  ->setImage('http://localhost:8000/images/randos/rando' . $i . '.jpg')
                  ->setUser($userA);
            $manager->persist($rando);
        }

        // Photos de User A
        for ($i = 1; $i <= 2; $i++) {
            $photo = new Photo();
            $photo->setUrl('http://localhost:8000/images/photos/photo' . $i . '.jpg')
                  ->setDescription("Photo $i de User A")
                  ->setUser($userA);
            $manager->persist($photo);
        }

        // Post de User A
        $postA = new Post();
        $postA->setTitle('Post de User A')
              ->setContent('Contenu du post de User A')
              ->setUser($userA);
        $manager->persist($postA);

        // User B
        $userB = new User();
        $userB->setEmail('userb@example.com');
        $userB->setPassword($this->passwordHasher->hashPassword($userB, 'password123'));
        $manager->persist($userB);

        // Randonnées de User B
        for ($i = 1; $i <= 3; $i++) {
            $rando = new Rando();
            $rando->setTitle("Randonnée $i de User B")
                  ->setDescription("Description de la randonnée $i de User B")
                  ->setLocation("Location $i")
                  ->setDistance(rand(5, 20))
                  ->setDuration(rand(1, 5) . 'h')
                  ->setDifficulty('Facile')
                  ->setImage('http://localhost:8000/images/randos/rando' . ($i + 3) . '.jpg')
                  ->setUser($userB);
            $manager->persist($rando);
        }

        // Photos de User B
        for ($i = 1; $i <= 2; $i++) {
            $photo = new Photo();
            $photo->setUrl('http://localhost:8000/images/photos/photo' . ($i + 2) . '.jpg')
                  ->setDescription("Photo $i de User B")
                  ->setUser($userB);
            $manager->persist($photo);
        }

        // Post de User B
        $postB = new Post();
        $postB->setTitle('Post de User B')
              ->setContent('Contenu du post de User B')
              ->setUser($userB);
        $manager->persist($postB);

        $manager->flush();
    }
}
