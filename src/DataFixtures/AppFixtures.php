<?php

namespace App\DataFixtures;

use Faker;
use Faker\Factory;
use App\Entity\Hint;
use App\Entity\User;
use App\Entity\Program;
use App\Entity\Exercise;
use App\Entity\AccessLevel;
use App\Entity\MasteryLevel;
use App\Entity\Prerequisite;
use Faker\Provider\DateTime;
use App\Entity\ProgramComment;
use App\Entity\ExerciseComment;
use Doctrine\Bundle\FixturesBundle\Fixture;
use App\DataFixtures\Provider\AblocProvider;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        //Instance de Faker
        $faker = Faker\Factory::create('fr_FR');
        $faker->seed('Données identiques à chaque load');
        //Pour les randoms
        mt_srand(123456789);
        //Ajout des Providers de base
        $faker->addProvider(new AblocProvider($faker));

        // Liste des AccessLevel
        $AccessLevelsList = [];
        //Création des 3 AccessLevel
        for ($i = 0; $i < 3; $i++) {
            $accessLevel = new AccessLevel();
            $accessLevel->setTitle($faker->unique()->accessLevelTitle());
            $accessLevel->setCreatedAt(new \DateTime);
            $AccessLevelsList[] = $accessLevel;
            $manager->persist($accessLevel);
        }

        // Liste des MasteryLevel
        $masteryLevelsList = [];
        //Création de 5 MasteryLevel
        for ($i = 0; $i < 5; $i++) {
            $masteryLevel = new MasteryLevel();
            $masteryLevel->setTitle($faker->unique()->masteryLevelTitle());
            $masteryLevel->setCreatedAt(new \DateTime);
            $masteryLevelsList[] = $masteryLevel;
            $manager->persist($masteryLevel);
        }

        // Liste de Users
        $UsersList = [];
        // Création de 5 Users
        for ($i = 0; $i < 5; $i++) {
            $user = new User();
            $user->setPassword("123");
            $user->setAccountName($faker->unique()->firstName());
            $user->setEmail("$faker->firstName.$faker->lastName@mail.fr");
            $user->setScore("0");
            $user->setCreatedAt(new \DateTime);
            shuffle($AccessLevelsList);
            $user->setAccessLevel($AccessLevelsList[0]);
            shuffle($masteryLevelsList);
            $user->setMasteryLevel($masteryLevelsList[0]);
            $UsersList[] = $user;
            $manager->persist($user);
        }

        // Liste des Hints
        $HintsList = [];
        //Création de 5 Hints
        for ($i = 0; $i < 5; $i++) {
            $hint = new Hint();
            $hint->setText($faker->unique()->hintText());
            $hint->setCreatedAt(new \DateTime);
            $HintsList[] = $hint;
            $manager->persist($hint);
        }

        // Liste des Prérequis
        $PrerequisitesList = [];
        //Création de 10 Prerequisites
        for ($i = 0; $i < 10; $i++) {
            $prerequisite = new Prerequisite();
            $prerequisite->setDescription($faker->unique()->prerequisiteDescription());
            $prerequisite->setCreatedAt(new \DateTime);
            $PrerequisitesList[] = $prerequisite;
            $manager->persist($prerequisite);
        } 

        // Liste des exercices
        $ExercisesList = [];
        //Création de 15 exercices
        for ($i = 0; $i < 15; $i++) {
            $exercise = new Exercise();
            $exercise->setTitle($faker->exerciseTitle());
            $exercise->setTime(random_int(10, 30));
            $exercise->setCreatedAt(new \DateTime);
            $exercise->addHint($HintsList[0]);
            $exercise->addHint($HintsList[1]);
            $exercise->addHint($HintsList[2]);
            $exercise->addPrerequisite($PrerequisitesList[0]);
            $exercise->addPrerequisite($PrerequisitesList[1]);
            $exercise->addPrerequisite($PrerequisitesList[2]);
            $ExercisesList[] = $exercise;
            $manager->persist($exercise);
        }
        // Liste des programmes
        $ProgramsList = [];
        //Création de 5 programmes
        for ($i = 0; $i < 5; $i++) {
            $program = new Program();
            $program->setTitle($faker->programTitle());
            $program->setTime(random_int(10, 30));
            $program->setCreatedAt(new \DateTime);
            shuffle($ExercisesList);
            $program->addExercise($ExercisesList[0]);
            $program->addExercise($ExercisesList[1]);
            $program->addExercise($ExercisesList[2]);
            $ProgramsList[] = $program;
            $manager->persist($program);
        }
        // Liste des Commentaires sur Exercices
        $exerciseCommentsList = [];
        //Création de 20 commentaires sur Exercices
        for ($i = 0; $i < 20; $i++) {
            $exerciseComment = new ExerciseComment();
            $exerciseComment->setText($faker->exerciseCommentText());
            shuffle($UsersList);
            shuffle($ExercisesList);
            $exerciseComment->setUser($UsersList[0]);
            $exerciseComment->setExercise($ExercisesList[0]);
            $manager->persist($exerciseComment);
        }
        // Liste des Commentaires sur Programmes
        $programCommentsList = [];
        //Création de 10 commentaires sur Programmes
        for ($i = 0; $i < 10; $i++) {
            $programComment = new ProgramComment();
            $programComment->setText($faker->programCommentText());
            shuffle($UsersList);
            shuffle($ProgramsList);
            $programComment->setUser($UsersList[0]);
            $programComment->setProgram($ProgramsList[0]);
            $manager->persist($programComment);
        }  
        $manager->flush();
    }
}