<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    public function __construct(
        private UserPasswordHasherInterface $hasher
    ) {}

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        // Admin
        $admin = new User();
        $admin->setEmail('admin@mail.com');
        $admin->setFirstname('Admin');
        $admin->setLastname('Admin');
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setApiAccess(true);
        $admin->setPassword($this->hasher->hashPassword($admin, 'password'));
        $manager->persist($admin);

        // User fixe
        $user = new User();
        $user->setEmail('user@mail.com');
        $user->setFirstname($faker->firstName());
        $user->setLastname($faker->lastName());
        $user->setRoles(['ROLE_USER']);
        $user->setApiAccess(false);
        $user->setPassword($this->hasher->hashPassword($user, 'password'));
        $manager->persist($user);

        // 3 users aléatoires
        for ($i = 0; $i < 3; $i++) {
            $randomUser = new User();
            $randomUser->setEmail($faker->unique()->safeEmail());
            $randomUser->setFirstname($faker->firstName());
            $randomUser->setLastname($faker->lastName());
            $randomUser->setRoles(['ROLE_USER']);
            $randomUser->setApiAccess(false);
            $randomUser->setPassword($this->hasher->hashPassword($randomUser, 'password'));
            $manager->persist($randomUser);
        }

        $manager->flush();
    }
}