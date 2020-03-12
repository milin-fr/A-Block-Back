<?php

namespace App\Entity;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;


class MasteryLevelTest extends KernelTestCase
{

    public function getEntity(): MasteryLevel 
    {
        return (new MasteryLevel())
            ->setTitle('MasteryLevel Title Test')
            ->setUpdatedAt(new \DateTime())
            ->setCreatedAt(new \DateTime())
            ->addExercise(new Exercise)
            ->addProgram(new Program)
            ->setLevelIndex('1')
            ->setDescription('MasteryLevel description Test')
            ->setImgPath('masterylevel_test.png');
    }

    public function assertHasErrors(MasteryLevel $masteryLevel, int $number = 0)
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

    public function testInvalidBlankTitleEntity () 
    {
        $this->assertHasErrors($this->getEntity()->setTitle(''), 1);
    }
}