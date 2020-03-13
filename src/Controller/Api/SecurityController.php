<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api")
 */
class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="api_login")
     */
    public function index()
    {
        return $this->json([
            "username",
            "password"
        ]);
    }
}



