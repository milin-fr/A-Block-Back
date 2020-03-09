<?php

namespace App\DataFixtures\Provider;

use Faker\Provider\Base;

class AblocProvider extends Base
{
    protected static $firstNames = [
        "martin",
        "bernard",
        "thomas",
        "petit",
        "robert",
    ];
    protected static $lastNames = [
        "durand",
        "dubois",
        "leroy",
        "bonnet",
        "picard",
    ];
    protected static $exercises = [
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
    protected static $programs = [
        'Endurance',
        'Technique',
        'Force',
        'Resistance',
        'Complet',
    ];
    protected static $exerciseComments = [
        'J\'en ai bavé sur cet exo, mais c\'était super',
        'Exercice beaucoup trop compliqué pour le niveau annoncé',
        'J\'ai bien aimé cet exercice',
        'Je tenterai le niveau suivant pour cet exo! Top!',
        'J\'ai eu du mal à suivre le rythme sur cet exercice',

    ];
    protected static $programComments = [
        'J\'en ai bavé tout le long du programme, mais c\'était super',
        'Programme bien trop compliqué pour le niveau annoncé',
        'J\'ai bien aimé ce programme',
        'J\'ai enfin terminé le programme!!!',
        'J\'ai eu du mal à suivre le rythme sur ce programme',
    ];
    protected static $hints = [
        'Echauffez-vous 15mn',
        'Ne forcez pas, le but c\'est de terminer',
        'Pensez à bien respirer entre chaque reprise',
        'Buvez de l\'eau',
        'Prenez deux jours de repos après cela',
    ];
    protected static $masteryLevels = [
        'Aucune expérience',
        'Débutant',
        'Intermédiaire',
        'Confirmé',
        'Expert',
    ];
    protected static $prerequisites = [
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

    public static function firstName()
    {
        return static::randomElement(static::$firstNames);
    }
    public static function lastName()
    {
        return static::randomElement(static::$lastNames);
    }
    public static function exerciseTitle()
    {
        return static::randomElement(static::$exercises);
    }
    public static function programTitle()
    {
        return static::randomElement(static::$programs);
    }
    public static function exerciseCommentText()
    {
        return static::randomElement(static::$exerciseComments);
    }
    public static function programCommentText()
    {
        return static::randomElement(static::$programComments);
    }
    public static function hintText()
    {
        return static::randomElement(static::$hints);
    }
    public static function masteryLevelTitle()
    {
        return static::randomElement(static::$masteryLevels);
    }
    public static function prerequisiteDescription()
    {
        return static::randomElement(static::$prerequisites);
    }

}