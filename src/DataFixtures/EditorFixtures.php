<?php

namespace App\DataFixtures;

use App\Entity\Editor;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class EditorFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        for ($i = 0; $i < 10; $i++) {
            $editor = new Editor();
            $editor->setName($faker->company());
            $manager->persist($editor);
            $this->addReference('editor_' . $i, $editor);
        }
        $manager->flush();
    }
}
