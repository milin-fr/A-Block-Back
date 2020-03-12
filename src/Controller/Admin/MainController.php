<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin")
 */
class MainController extends AbstractController
{
    /**
     * @Route("/", name="admin_home")
     */
    public function adminHome()
    {
            return $this->render('admin\main\home.html.twig', [
            'data' => ["admin home"],
        ]);
    }


     /**
     * @Route("/admin", name="admin")
     */
    public function admin()
    {
        return $this->json([
            "page admin"
        ]);
    }
}
