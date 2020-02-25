<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("/home", name="home")
     */
    public function home()
    {
        return $this->json([
            [
            'message' => 'Nous allons faire la liste des routes et des requetes possible ici pour commencer',
            'path' => 'homeController.php'
            ],
            [
                'route' => 'public/users/',
                'use' => 'recuperer les utilisateurs en JSON',
                'path' => 'UserController.php'
            ],
        ]);
    }
}
