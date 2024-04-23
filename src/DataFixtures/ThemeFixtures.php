<?php

namespace App\DataFixtures;

use App\Entity\Theme;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class ThemeFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        for ($i = 0; $i < 10; $i++) {
            $theme = new Theme();
            $theme->setName($faker->word());
            $manager->persist($theme);
            $this->addReference('theme_' . $i, $theme);
        }

        $manager->flush();
    }
}
