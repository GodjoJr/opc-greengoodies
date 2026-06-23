<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    public function __construct(
        private UserPasswordHasherInterface $hasher
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        $admin = new User();
        $admin->setEmail('admin@mail.com');
        $admin->setFirstname('Admin');
        $admin->setLastname('Admin');
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setApiAccess(true);
        $admin->setPassword($this->hasher->hashPassword($admin, 'password'));
        $manager->persist($admin);

        $user = new User();
        $user->setEmail('user@mail.com');
        $user->setFirstname($faker->firstName());
        $user->setLastname($faker->lastName());
        $user->setRoles(['ROLE_USER']);
        $user->setApiAccess(false);
        $user->setPassword($this->hasher->hashPassword($user, 'password'));
        $manager->persist($user);

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

        $products = [
            [
                'name' => 'Kit d\'hygiène recyclable',
                'shortDescription' => 'Pour une salle de bain éco-friendly',
                'price' => 24.99,
                'picture' => 'product-image-1.webp',
            ],
            [
                'name' => 'Shot Tropical',
                'shortDescription' => 'Fruits frais, pressés à froid',
                'price' => 4.50,
                'picture' => 'product-image-2.webp',
            ],
            [
                'name' => 'Gourde en bois',
                'shortDescription' => '50cl, bois d\'olivier',
                'price' => 16.90,
                'picture' => 'product-image-3.webp',
            ],
            [
                'name' => 'Disques Démaquillants x3',
                'shortDescription' => 'Solution efficace pour vous démaquiller en douceur',
                'price' => 19.90,
                'picture' => 'product-image-4.webp',
            ],
            [
                'name' => 'Bougie Lavande & Patchouli',
                'shortDescription' => 'Cire naturelle',
                'price' => 32.00,
                'picture' => 'product-image-5.webp',
            ],
            [
                'name' => 'Brosse à dent',
                'shortDescription' => 'Bois de hêtre rouge issu de forêts gérées durablement',
                'price' => 5.40,
                'picture' => 'product-image-6.webp',
            ],
            [
                'name' => 'Kit couvert en bois',
                'shortDescription' => 'Revêtement Bio en olivier & sac de transport',
                'price' => 12.30,
                'picture' => 'product-image-7.webp',
            ],
            [
                'name' => 'Nécessaire, déodorant Bio',
                'shortDescription' => '50ml déodorant à l\'eucalyptus',
                'price' => 8.50,
                'picture' => 'product-image-8.webp',
            ],
            [
                'name' => 'Savon Bio',
                'shortDescription' => 'Thé, Orange & Girofle',
                'price' => 18.90,
                'picture' => 'product-image-9.webp',
            ],
        ];

        foreach ($products as $data) {
            $product = new Product();
            $product->setName($data['name']);
            $product->setShortDescription($data['shortDescription']);
            $product->setFullDescription($faker->paragraphs(2, true));
            $product->setPrice($data['price']);
            $product->setPicture($data['picture']);

            $manager->persist($product);
        }

        $manager->flush();
    }

}