<?php
// src/AppBUndle/DataFixtures/LoadTheme.php
namespace AppBundle\DataFixtures;

use AppBundle\Entity\Theme; 
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class LoadTheme extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // create themes
        for ($i = 0; $i < 3; $i++) {
            $theme = new Theme();
            $theme->setintitule('theme '.$i);
            $date = new \Datetime();
            $theme->setDate($date);
            $manager->persist($theme);
        }

        $manager->flush();
    }
}