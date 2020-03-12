<?php

namespace App\Entity;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;


class HintTest extends KernelTestCase
{

    public function getEntity(): Hint 
    {
        return (new Hint())
            ->setText('Hint Text Test')
            ->setUpdatedAt(new \DateTime())
            ->setCreatedAt(new \DateTime());
    }

    public function assertHasErrors(Hint $hint, int $number = 0)
    {
        self::bootKernel();
        $error = self::$container->get('validator')->validate($hint);
        $this->assertCount($number, $error);
    }

    public function testValidEntity () 
    {
        $this->assertHasErrors($this->getEntity(), 0);
    }

    public function testInvalidBlankTextEntity () 
    {
        $this->assertHasErrors($this->getEntity()->setText(''), 1);
    }
}