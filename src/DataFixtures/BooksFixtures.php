<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class BooksFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        for ($i = 0; $i < 100; $i++) {
            $book = new \App\Entity\Book();
            $book->setTitle('Book ' . $i);
            $nbAuthor = rand(1, 3);
            for ($j = 0; $j < $nbAuthor; $j++) {
                $authorId = rand(0, 19);
                $ref = $this->getReference('author_' . $authorId);
                $book->addAuthor($ref);
            }
            $book->setTitle($faker->sentence(rand(1, 5)));
            $book->setISBN($faker->isbn13());
            $isPicture = rand(0, 100) > 50;
            if ($isPicture) {
                $book->setPicture($faker->imageUrl(640, 480));
            } else {
                $book->setPicture(null);
            }
            $book->setEditor($this->getReference('editor_' . rand(0, 9)));
            $book->setPosition($this->getReference('shelf_' . rand(0, 29)));
            $book->setTheme($this->getReference('theme_' . rand(0, 9)));
            $manager->persist($book);
            $this->addReference('book_' . $i, $book);
        }
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            AuthorFixtures::class,
            ThemeFixtures::class,
            ShelvesFixtures::class,
            EditorFixtures::class,
        ];
    }
}
