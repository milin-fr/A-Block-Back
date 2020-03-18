<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class RecoveryController extends AbstractController
{
    /**
     * @Route("/recovery", name="recovery")
     */
    public function index()
    {
        return $this->render('recovery/index.html.twig', [
            'controller_name' => 'RecoveryController',
        ]);
    }
}
