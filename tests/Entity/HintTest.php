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
        $errors = self::$container->get('validator')->validate($hint);
        //affichage des erreurs
        $messages = [];
        foreach ($errors as $error) {
            $messages[] = $error->getPropertyPath() . '->' .$error->getMessage();
        }
        $this->assertCount($number, $errors, implode(',', $messages));
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