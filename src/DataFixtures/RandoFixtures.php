<?php

namespace App\DataFixtures;

use App\Entity\Rando;
use App\Entity\User;
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
        // Cr�ation de l'utilisateur 1
        $user1 = new User();
        $user1->setEmail('a@gmail.com');
        $user1->setPassword($this->passwordHasher->hashPassword($user1, 'password1'));
        $manager->persist($user1);

        // Cr�ation de l'utilisateur 2
        $user2 = new User();
        $user2->setEmail('q@gmail.com');
        $user2->setPassword($this->passwordHasher->hashPassword($user2, 'password2'));
        $manager->persist($user2);

        // Cr�ation de randonn�es pour l'utilisateur 1
        $rando1 = new Rando();
        $rando1->setTitle('Randonn�e du Mont Val�rien')
               ->setDescription('Une belle randonn�e offrant une vue magnifique sur Paris.')
               ->setLocation('�le-de-France')
               ->setDistance(10.5)
               ->setDuration('2h30')
               ->setDifficulty('Moyenne')
               ->setImage('http://localhost:8000/images/randos/rando1.jpg')
               ->setUser($user1);  // Association avec l'utilisateur 1
        $manager->persist($rando1);

        $rando2 = new Rando();
        $rando2->setTitle('For�t de Fontainebleau')
               ->setDescription('Explorez la magnifique for�t de Fontainebleau.')
               ->setLocation('�le-de-France')
               ->setDistance(15.0)
               ->setDuration('4h00')
               ->setDifficulty('Difficile')
               ->setImage('http://localhost:8000/images/randos/rando1.jpg')
               ->setUser($user1);  // Association avec l'utilisateur 1
        $manager->persist($rando2);

        // Cr�ation d'une randonn�e pour l'utilisateur 2
        $rando3 = new Rando();
        $rando3->setTitle('Randonn�e du Parc de Saint-Cloud')
               ->setDescription('Une balade relaxante dans le Parc de Saint-Cloud.')
               ->setLocation('�le-de-France')
               ->setDistance(5.0)
               ->setDuration('1h30')
               ->setDifficulty('Facile')
               ->setImage('http://localhost:8000/images/randos/rando1.jpg')
               ->setUser($user2);  // Association avec l'utilisateur 2
        $manager->persist($rando3);

        $manager->flush();
    }
}
