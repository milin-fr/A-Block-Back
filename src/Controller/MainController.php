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
                "api/exercise/",
                "GET",
                "list exercises/",
                ""
            ],
            [
                "api/exercise/",
                "POST",
                "add exercise",
                "
                {
                    \"title\": \"string, not blank\",
                    \"time\": integer,
                    \"img_path\": \"string\",
                    \"description\": \"string, not blank\",
                    \"score\": integer,
                    \"hint_ids\": [array of integers],
                    \"prerequisite_ids\": [array of integers],
                    \"program_ids\": [array of integers],
                    \"mastery_level_id\": integer
                }
                "
            ],
            [
                "api/exercise/id",
                "PUT",
                "edit exercise by id",
                "
                {
                    \"title\": \"string, not blank\",
                    \"time\": integer,
                    \"img_path\": \"string\",
                    \"description\": \"string, not blank\",
                    \"score\": integer,
                    \"hint_ids\": [array of integers],
                    \"prerequisite_ids\": [array of integers],
                    \"program_ids\": [array of integers],
                    \"mastery_level_id\": integer
                }
                "
            ],
            [
                "api/exercise/id",
                "GET",
                "exercise by id",
                ""
            ],
            [
                "api/exercise/id",
                "DELETE",
                "delete exercise by id",
                ""
            ],
            [
                "api/hint/",
                "GET",
                "list hints/",
                ""
            ],
            [
                "api/hint/",
                "POST",
                "add hint",
                "
                {
                    \"text\": \"string, not blank\"
                }
                "
            ],
            [
                "api/hint/id",
                "PUT",
                "edit hint by id",
                "
                {
                    \"text\": \"string, not blank\"
                }
                "
            ],
            [
                "api/hint/id",
                "GET",
                "hint by id",
                ""
            ],
            [
                "api/hint/id",
                "DELETE",
                "delete hint by id",
                ""
            ],
            [
                "api/mastery_level/",
                "GET",
                "list mastery_levels",
                ""
            ],
            [
                "api/mastery-level/",
                "POST",
                "add mastery_level",
                "
                {
                    \"title\": \"string, not blank\",
                    \"level_index\": integer
                }
                "
            ],
            [
                "api/mastery-level/id",
                "PUT",
                "edit mastery_level",
                "
                {
                    \"title\": \"string, not blank\",
                    \"level_index\": integer
                }
                "
            ],
            [
                "api/mastery-level/id",
                "GET",
                "mastery_level by id",
                ""
            ],
            [
                "api/mastery-level/id",
                "DELETE",
                "delete mastery_level by id",
                ""
            ],
            [
                "api/prerequisite/",
                "GET",
                "list prerequisites/",
                ""
            ],
            [
                "api/prerequisite/",
                "POST",
                "add prerequisite",
                "
                {
                    \"description\": \"string, not blank\"
                }
                "
            ],
            [
                "api/prerequisite/id",
                "PUT",
                "edit prerequisite by id",
                "
                {
                    \"description\": \"string, not blank\"
                }
                "
            ],
            [
                "api/prerequisite/id",
                "GET",
                "prerequisite by id",
                ""
            ],
            [
                "api/prerequisite/id",
                "DELETE",
                "delete prerequisite by id",
                ""
            ],
            [
                "api/program/",
                "GET",
                "list programs",
                ""
            ],
            [
                "api/program/",
                "POST",
                "add program",
                "
                {
                    \"title\": \"string, not blank\",
                    \"description\": \"string, not blank\",
                    \"time\": integer,
                    \"img_path\": \"string\",
                    \"exercise_ids\": [array of integers]
                }
                "
            ],
            [
                "api/program/id",
                "PUT",
                "edit program by id",
                "
                {
                    \"title\": \"string, not blank\",
                    \"description\": \"string, not blank\",
                    \"time\": integer,
                    \"img_path\": \"string\",
                    \"exercise_ids\": [array of integers]
                }
                "
            ],
            [
                "api/program/id",
                "GET",
                "program by id",
                ""
            ],
            [
                "api/program/id",
                "DELETE",
                "delete program by id",
                ""
            ],
            [
                "api/user/",
                "GET",
                "list users",
                ""
            ],
            [
                "api/user/",
                "POST",
                "add user",
                "
                {
                    \"email\": \"email, not blank\",
                    \"password\": \"string, not blank\",
                    \"account_name\": \"string, not blank\",
                    \"img_path\": \"string\",
                    \"available_time\": integer,
                    \"mastery_level\": integer
                }
                "
            ],
            [
                "api/user/id",
                "PUT",
                "edit user by id",
                "
                {
                    \"email\": \"email, not blank\",
                    \"password\": \"string, not blank\",
                    \"account_name\": \"string, not blank\",
                    \"img_path\": \"string\",
                    \"available_time\": integer,
                    \"mastery_level\": integer
                }
                "
            ],
            [
                "api/user/id",
                "GET",
                "user by id",
                ""
            ],
            [
                "api/user/id",
                "DELETE",
                "delete user by id",
                ""
            ],
            [
                "api/exercise-comment/",
                "GET",
                "list exercise_comments",
                ""
            ],
            [
                "api/exercise-comment/",
                "POST",
                "add exercise_comment",
                "
                {
                    \"text\": \"string, not blank\",
                    \"user_id\": \"integer, not blank\",
                    \"exercise_id\": \"integer, not blank\"
                }
                "
            ],
            [
                "api/exercise-comment/id",
                "PUT",
                "edit exercise_comment by id",
                "
                {
                    \"text\": \"string, not blank\"
                }
                "
            ],
            [
                "api/exercise-comment/id",
                "GET",
                "exercise-comment by id",
                ""
            ],
            [
                "api/exercise-comment/id",
                "DELETE",
                "delete exercise_comment by id",
                ""
            ],
            [
                "api/program-comment/",
                "GET",
                "list program_comments",
                ""
            ],
            [
                "api/program-comment/",
                "POST",
                "add program_comment",
                "
                {
                    \"text\": \"string, not blank\",
                    \"user_id\": \"integer, not blank\",
                    \"program_id\": \"integer, not blank\"
                }
                "
            ],
            [
                "api/program-comment/id",
                "PUT",
                "edit program_comment by id",
                "
                {
                    \"text\": \"string, not blank\"
                }
                "
            ],
            [
                "api/program-comment/id",
                "GET",
                "program-comment by id",
                ""
            ],
            [
                "api/program-comment/id",
                "DELETE",
                "delete program_comment by id",
                ""
            ],
        ];

        return $this->render('home\api_data.html.twig', [
            'api_data' => $apiData,
        ]);
    }
}
