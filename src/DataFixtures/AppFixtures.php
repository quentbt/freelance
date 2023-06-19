<?php

namespace App\DataFixtures;

use App\Entity\Service;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(
        private UserPasswordHasherInterface $hasher
    ) {}

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for($i = 0; $i < 50; $i++) {
            $users = new User();

            $users->setName($faker->name);
            $users->setEmail($faker->email);
            $users->setPassword($this->hasher->hashPassword($users, 'admin'));

            $manager->persist($users);

            for($j = 0; $j < 5; $j++) {
                $service = new Service();

                $service->setNom($faker->words(5,true))
                    ->setTitre($faker->title)
                    ->setPrix(random_int(1,500))
                    ->setSecteur('job'.$j)
                    ->setDescription('Ma description')
                    ->setCreatedAt($faker->dateTimeBetween('-1 years', 'now'))
                    ->setUser($users);

                $manager->persist($service);
            }
        }

        $manager->flush();
    }
}
