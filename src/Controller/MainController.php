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
            [
                "api/access_level/",
                "GET/",
                "list access_levels/",
                ""
            ],
            [
                "api/access_level/",
                "POST/",
                "add access_level/",
                "
                {
                    \"title\": \"string, not blank\"
                }
                "
            ],
            [
                "api/access_level/id/",
                "GET/",
                "access_level by id",
                ""
            ],
            [
                "api/exercise/",
                "GET/",
                "list exercises/",
                ""
            ],
            [
                "api/exercise/",
                "POST/",
                "add exercise/",
                "
                {
                    \"title\": \"string, not blank\",
                    \"time\": integer,
                    \"imgPath\": \"string\",
                    \"description\": \"string, not blank\",
                    \"score\": integer,
                    \"hints\": [array of integers],
                    \"prerequisites\": [array of integers],
                    \"programs\": [array of integers],
                    \"masteryLevel\": integer
                }
                "
            ],
            [
                "api/exercise/id/",
                "GET/",
                "exercise by id",
                ""
            ],
            [
                "api/hint/",
                "GET/",
                "list hints/",
                ""
            ],
            [
                "api/hint/",
                "POST/",
                "add hint/",
                "
                {
                    \"text\": \"string, not blank\"
                }
                "
            ],
            [
                "api/hint/id/",
                "GET/",
                "hint by id",
                ""
            ],
            [
                "api/mastery_level/",
                "GET/",
                "list mastery_levels/",
                ""
            ],
            [
                "api/mastery_level/",
                "POST/",
                "add mastery_level/",
                "
                {
                    \"title\": \"string, not blank\"
                }
                "
            ],
            [
                "api/mastery_level/id/",
                "GET/",
                "mastery_level by id",
                ""
            ],
            [
                "api/prerequisite/",
                "GET/",
                "list prerequisites/",
                ""
            ],
            [
                "api/prerequisite/",
                "POST/",
                "add prerequisite/",
                "
                {
                    \"description\": \"string, not blank\"
                }
                "
            ],
            [
                "api/prerequisite/id/",
                "GET/",
                "prerequisite by id",
                ""
            ],
            [
                "api/program/",
                "GET/",
                "list programs/",
                ""
            ],
            [
                "api/program/",
                "POST/",
                "add program/",
                "
                {
                    \"title\": \"string, not blank\",
                    \"time\": integer,
                    \"imgPath\": \"string\",
                    \"description\": \"string, not blank\",
                    \"exercises\":[array of integers]
                }
                "
            ],
            [
                "api/program/id/",
                "GET/",
                "program by id",
                ""
            ],
            [
                "api/user/",
                "GET/",
                "list users/",
                ""
            ],
            [
                "api/user/",
                "POST/",
                "add user/",
                "
                {
                    \"email\": \"email, not blank\",
                    \"password\": \"string, not blank\",
                    \"accountName\": \"string, not blank\",
                    \"imgPath\": \"string\",
                    \"availableTime\": integer,
                    \"masteryLevel\": integer
                }
                "
            ],
            [
                "api/user/id/",
                "GET/",
                "user by id",
                ""
            ],
        ];

        return $this->render('home\api_data.html.twig', [
            'api_data' => $apiData,
        ]);
    }
}
