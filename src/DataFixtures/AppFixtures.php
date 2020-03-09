<?php

namespace App\DataFixtures;

use App\DataFixtures\Provider\AblocProvider;
use App\Entity\Hint;
use App\Entity\User;
use App\Entity\Program;
use App\Entity\Exercise;
use App\Entity\MasteryLevel;
use App\Entity\Prerequisite;
use App\Entity\ProgramComment;
use App\Entity\ExerciseComment;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{

    public function load(ObjectManager $manager)
    {

        $masteryLevels = [
            'Aucune expérience',
            'Débutant',
            'Intermédiaire',
            'Confirmé',
            'Expert',
        ];

        // Liste des MasteryLevel
        $masteryLevelsList = [];
        //Création de 5 MasteryLevel
        for ($i = 0; $i < 5; $i++) {
            $masteryLevel = new MasteryLevel();
            $masteryLevel->setTitle($masteryLevels[$i]);
            $masteryLevel->setLevelIndex($i);
            $masteryLevel->setCreatedAt(new \DateTime);
            $masteryLevelsList[] = $masteryLevel;
            $manager->persist($masteryLevel);
        }


        $hints = [
            'Echauffez-vous 15mn',
            'Ne forcez pas, le but c\'est de terminer',
            'Pensez à bien respirer entre chaque reprise',
            'Buvez de l\'eau',
            'Prenez deux jours de repos après cela',
        ];

        // Liste des Hints
        $HintsList = [];
        //Création de 5 Hints
        for ($i = 0; $i < 5; $i++) {
            $hint = new Hint();
            $hint->setText($hints[$i]);
            $hint->setCreatedAt(new \DateTime);
            $HintsList[] = $hint;
            $manager->persist($hint);
        }


        $prerequisites = [
            'Une paire de chaussre de sport',
            'Un casque',
            'Un corde',
            'Une poutre d\'entraienemnt',
            'Du fingertape',
            'Des gants',
            'Des lunettes',
            'Un tapis de sport',
            'Du grip',
            'Un chronomètre',
        ];

        // Liste des Prérequis
        $PrerequisitesList = [];
        //Création de 10 Prerequisites
        for ($i = 0; $i < 10; $i++) {
            $prerequisite = new Prerequisite();
            $prerequisite->setDescription($prerequisites[$i]);
            $prerequisite->setCreatedAt(new \DateTime);
            $PrerequisitesList[] = $prerequisite;
            $manager->persist($prerequisite);
        } 



        $exercises = [
            'Les pieds précis', 
            'Le plantage de pied',
            'Le clignement de pied',
            'Les petites prises seulement',
            'Dés-escalade',
            'Les pieds collés',
            'L’observation',
            'Relais Bloc ',
            'Prete moi tes yeux',
            'Moins que toi',
            'Le petit Poucet',
            'La cordée',
            'Saute Mouton',
            '1,2,3 Grimper',
            'A la recherche de l\'anneau',
         ];  


        // Liste des exercices
        $ExercisesList = [];
        //Création de 15 exercices
        for ($i = 0; $i < 15; $i++) {
            $exercise = new Exercise();
            $exercise->setTitle($exercises[$i]);
            $exercise->setTime(random_int(10, 30));
            $exercise->setCreatedAt(new \DateTime);
            $exercise->addHint($HintsList[0]);
            $exercise->addHint($HintsList[1]);
            $exercise->addHint($HintsList[2]);
            $exercise->addPrerequisite($PrerequisitesList[0]);
            $exercise->addPrerequisite($PrerequisitesList[1]);
            $exercise->addPrerequisite($PrerequisitesList[2]);
            $exercise->setMasteryLevel($masteryLevelsList[$i % 5]);
            $exercise->setImgPath('exercise_image_default.png');
            $ExercisesList[] = $exercise;
            $manager->persist($exercise);
        }

        $programs = [
            'Endurance',
            'Technique',
            'Force',
            'Resistance',
            'Complet',
        ];

        // Liste des programmes
        $ProgramsList = [];
        //Création de 5 programmes
        for ($i = 0; $i < 5; $i++) {
            $program = new Program();
            $program->setTitle($programs[$i]);
            $program->setTime(random_int(10, 30));
            $program->setCreatedAt(new \DateTime);
            shuffle($ExercisesList);
            shuffle($masteryLevelsList);
            $program->setMasteryLevel($masteryLevelsList[0]);
            $program->addExercise($ExercisesList[0]);
            $program->addExercise($ExercisesList[1]);
            $program->addExercise($ExercisesList[2]);
            $program->setImgPath('program_image_default.png');
            $ProgramsList[] = $program;
            $manager->persist($program);
        }


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

        // Liste de Users
        $UsersList = [];
        // Création de 5 Users
        for ($i = 0; $i < 5; $i++) {
            $user = new User();
            $user->setPassword('123');
            $user->setAccountName($firstNames[$i]);
            $user->setEmail($firstNames[$i].$lastNames[$i]."@mail.fr");
            $user->setScore("0");
            $user->setCreatedAt(new \DateTime);
            shuffle($masteryLevelsList);
            $user->setMasteryLevel($masteryLevelsList[0]);
            $user->addExerciseBookmark($ExercisesList[0]);
            $user->addProgramBookmark($ProgramsList[0]);
            $user->setImgPath('user_image_default.png');
            $UsersList[] = $user;
            $manager->persist($user);
        }


        $exerciseComments = [
            'J\'en ai bavé sur cet exo, mais c\'était super',
            'Exercice beaucoup trop compliqué pour le niveau annoncé',
            'J\'ai bien aimé cet exercice',
            'Je tenterai le niveau suivant pour cet exo! Top!',
            'J\'ai eu du mal à suivre le rythme sur cet exercice',
    
        ];
        // Liste des Commentaires sur Exercices
        $exerciseCommentsList = [];
        //Création de 20 commentaires sur Exercices
        for ($i = 0; $i < 20; $i++) {
            $exerciseComment = new ExerciseComment();
            $exerciseComment->setText($exerciseComments[$i % 5]);
            shuffle($UsersList);
            shuffle($ExercisesList);
            $exerciseComment->setUser($UsersList[0]);
            $exerciseComment->setCreatedAt(new \DateTime);
            $exerciseComment->setExercise($ExercisesList[0]);
            $manager->persist($exerciseComment);
        }

        $programComments = [
            'J\'en ai bavé tout le long du programme, mais c\'était super',
            'Programme bien trop compliqué pour le niveau annoncé',
            'J\'ai bien aimé ce programme',
            'J\'ai enfin terminé le programme!!!',
            'J\'ai eu du mal à suivre le rythme sur ce programme',
        ];

        // Liste des Commentaires sur Programmes
        $programCommentsList = [];
        //Création de 10 commentaires sur Programmes
        for ($i = 0; $i < 10; $i++) {
            $programComment = new ProgramComment();
            $programComment->setText($programComments[$i % 5]);
            shuffle($UsersList);
            shuffle($ProgramsList);
            $programComment->setUser($UsersList[0]);
            $programComment->setCreatedAt(new \DateTime);
            $programComment->setProgram($ProgramsList[0]);
            $manager->persist($programComment);
        }

        $manager->flush();
        
    }
}