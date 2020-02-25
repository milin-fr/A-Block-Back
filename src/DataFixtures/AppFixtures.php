<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $firstNames = [
            "martin",
            "bernard",
            "thomas",
            "petit",
            "robert",
        ];
        $lastNames = [
            "durand",
            "dubois",
            "leroy",
            "bonnet",
            "picard",
        ];
        foreach ($firstNames as $index => $firstName) {
            $user = new User();
            $user->setEmail($firstName . "-" . $lastNames[$index] . "@mail.fr");
            $user->setPassword("123");
            $manager->persist($user);
        }
        
        $manager->flush();
    }
}
