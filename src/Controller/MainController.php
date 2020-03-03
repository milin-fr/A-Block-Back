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
            "api/access_level" => [
                "GET" => "les access_levels"
            ],
            "api/access_level/id" => [
                "GET" => "l'access_level par id"
            ],
            "api/exercise" => [
                "GET" => "les exercises"
            ],
            "api/exercise/id" => [
                "GET" => "l'exercise par id"
            ],
            "api/hint" => [
                "GET" => "les hints"
            ],
            "api/hint/id" => [
                "GET" => "le hint par id"
            ],
            "api/mastery_level" => [
                "GET" => "les mastery_levels"
            ],
            "api/mastery_level/id" => [
                "GET" => "le mastery_level par id"
            ],
            "api/prerequisite" => [
                "GET" => "les prerequisites"
            ],
            "api/prerequisite/id" => [
                "GET" => "le prerequisite par id"
            ],
            "api/program" => [
                "GET" => "les programs"
            ],
            "api/program/id" => [
                "GET" => "le program par id"
            ],
            "api/user" => [
                "GET" => "les users"
            ],
            "api/user/id" => [
                "GET" => "l'user par id"
            ],
        ];

        return $this->render('home\api_data.html.twig', [
            'api_data' => $apiData,
        ]);
    }
}
