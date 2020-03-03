<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function home()
    {
        $apiData = [
            "/api/access_level" => [
                "GET" => "la liste des access_level"
            ],
            "/api/access_level/id" => [
                "GET" => "l'access_level trouvé par id"
            ],
            "/api/exercise" => [
                "GET" => "la liste des exercise"
            ],
            "/api/exercise/id" => [
                "GET" => "l'exercise trouvé par id"
            ],
            "/api/hint" => [
                "GET" => "la liste des hint"
            ],
            "/api/hint/id" => [
                "GET" => "l'hint trouvé par id"
            ],
            "/api/mastery_level" => [
                "GET" => "la liste des mastery_level"
            ],
            "/api/mastery_level/id" => [
                "GET" => "l'mastery_level trouvé par id"
            ],
            "/api/prerequisite" => [
                "GET" => "la liste des prerequisite"
            ],
            "/api/prerequisite/id" => [
                "GET" => "l'prerequisite trouvé par id"
            ],
            "/api/access_level" => [
                "GET" => "la liste des access_level"
            ],
            "/api/access_level/id" => [
                "GET" => "l'access_level trouvé par id"
            ],
            "/api/access_level" => [
                "GET" => "la liste des access_level"
            ],
            "/api/access_level/id" => [
                "GET" => "l'access_level trouvé par id"
            ],
        ];
        return $this->render('base.html.twig', [
            'api_data' => $apiData,
        ]);
    }
}
