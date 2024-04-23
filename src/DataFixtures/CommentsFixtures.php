<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class CommentsFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
        $faker = Factory::create('fr_FR');
        for ($i = 0; $i < 250; $i++) {
            $comment = new \App\Entity\Comment();
            $comment->setUser($this->getReference('user_' . rand(0, 5)));
            $comment->setComment($faker->paragraph());
            $comment->setBook($this->getReference('book_' . rand(0, 99)));
            $manager->persist($comment);
        }
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
            BooksFixtures::class
        ];
    }
}
