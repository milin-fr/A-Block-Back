<?php

namespace App\Entity;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;


class PrerequisiteTest extends KernelTestCase
{

    public function getEntity(): Prerequisite
    {
        return (new Prerequisite())
            ->setDescription('Prerequisite Description Test')
            ->setUpdatedAt(new \DateTime())
            ->setCreatedAt(new \DateTime())
            ->addExercise(new Exercise);
            
            self::bootKernel();
            $error = self::$container->get('validator')->validate($prerequisite);
            $this->assertCount(0, $error);
    }
    public function assertHasErrors(Prerequisite $prerequisite, int $number = 0)
    {
        self::bootKernel();
        $error = self::$container->get('validator')->validate($prerequisite);
        $this->assertCount($number, $error);
    }

    public function testValidEntity ()
    {
        $this->assertHasErrors($this->getEntity(), 0);
    }

    public function testInvalidBlankDescriptionEntity () 
    {
        $this->assertHasErrors($this->getEntity()->setDescription(''), 1);
    }
}