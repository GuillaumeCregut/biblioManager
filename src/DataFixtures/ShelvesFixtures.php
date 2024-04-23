<?php

namespace App\DataFixtures;

use App\Entity\Shelf;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ShelvesFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < 30; $i++) {
            $shelf = new Shelf();
            $shelf->setName('Etagère #' . $i);
            $libraryNumber = rand(0, 9);
            $libraryRef = 'library_Armoire' . $libraryNumber;
            $ref = $this->getReference($libraryRef);
            $shelf->setLibrary($ref);
            $manager->persist($shelf);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            LibraryFixtures::class,
        ];
    }
}
