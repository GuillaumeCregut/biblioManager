<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Faker\Factory;

class UserFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;


    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        $user = new User();
        $user
            ->setFirstname('Guillaume')
            ->setLastname('Crégut')
            ->setLogin('gcregut')
            ->setAdmin(true);
        $password = $this->hasher->hashPassword($user, 'MUSTANG');
        $user->setPassword($password);
        $manager->persist($user);
        for ($i = 0; $i < 5; $i++) {
            $user = new User();
            $user
                ->setFirstname($faker->firstName())
                ->setLastname($faker->lastName())
                ->setLogin($faker->userName())
                ->setAdmin(false);
            $password = $this->hasher->hashPassword($user, '123456');
            $user->setPassword($password);
            $manager->persist($user);
        }
        $manager->flush();
    }
}
