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
                "list exercises",
                "",
                "ROLE_USER"
            ],
            [
                "api/exercise/",
                "POST",
                "add exercise",
                "
                {
                    \"title\": \"string, not blank, requiered\",
                    \"time\": integer,
                    \"img_path\": \"string\",
                    \"description\": \"string\",
                    \"score\": integer,
                    \"hint_ids\": [array of integers],
                    \"prerequisite_ids\": [array of integers],
                    \"program_ids\": [array of integers],
                    \"mastery_level_id\": integer
                }
                ",
                "ROLE_ADMIN"
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
                    \"description\": \"string\",
                    \"score\": integer,
                    \"hint_ids\": [array of integers],
                    \"prerequisite_ids\": [array of integers],
                    \"program_ids\": [array of integers],
                    \"mastery_level_id\": integer
                }
                ",
                "ROLE_ADMIN"
            ],
            [
                "api/exercise/id",
                "GET",
                "exercise by id",
                "ROLE_USER"
            ],
            [
                "api/exercise/id",
                "DELETE",
                "delete exercise by id",
                "",
                "ROLE_ADMIN"
            ],
            [
                "api/hint/",
                "GET",
                "list hints/",
                "",
                "ROLE_USER"
            ],
            [
                "api/hint/",
                "POST",
                "add hint",
                "
                {
                    \"text\": \"string, not blank, requiered\"
                }
                ",
                "ROLE_ADMIN"
            ],
            [
                "api/hint/id",
                "PUT",
                "edit hint by id",
                "
                {
                    \"text\": \"string, not blank, requiered\"
                }
                ",
                "ROLE_ADMIN"
            ],
            [
                "api/hint/id",
                "GET",
                "hint by id",
                "",
                "ROLE_USER"
            ],
            [
                "api/hint/id",
                "DELETE",
                "delete hint by id",
                "",
                "ROLE_ADMIN"
            ],
            [
                "api/mastery_level/",
                "GET",
                "list mastery_levels",
                "",
                "ROLE_USER"
            ],
            [
                "api/mastery-level/",
                "POST",
                "add mastery_level",
                "
                {
                    \"title\": \"string, not blank, requiered\",
                    \"level_index\": integer, requiered
                    \"description\": \"string\",
                    \"img_path\": \"string\"
                }
                ",
                "ROLE_ADMIN"
            ],
            [
                "api/mastery-level/id",
                "PUT",
                "edit mastery_level",
                "
                {
                    \"title\": \"string, not blank\",
                    \"level_index\": integer,
                    \"description\": \"string\",
                    \"img_path\": \"string\"
                }
                ",
                "ROLE_ADMIN"
            ],
            [
                "api/mastery-level/id",
                "GET",
                "mastery_level by id",
                "",
                "ROLE_USER"
            ],
            [
                "api/mastery-level/id",
                "DELETE",
                "delete mastery_level by id",
                "",
                "ROLE_ADMIN"
            ],
            [
                "api/prerequisite/",
                "GET",
                "list prerequisites/",
                "",
                "ROLE_USER"
            ],
            [
                "api/prerequisite/",
                "POST",
                "add prerequisite",
                "
                {
                    \"description\": \"string, not blank, requiered\"
                }
                ",
                "ROLE_ADMIN"
            ],
            [
                "api/prerequisite/id",
                "PUT",
                "edit prerequisite by id",
                "
                {
                    \"description\": \"string, not blank, requiered\"
                }
                ",
                "ROLE_ADMIN"
            ],
            [
                "api/prerequisite/id",
                "GET",
                "prerequisite by id",
                "",
                "ROLE_USER"
            ],
            [
                "api/prerequisite/id",
                "DELETE",
                "delete prerequisite by id",
                "",
                "ROLE_ADMIN"
            ],
            [
                "api/program/",
                "GET",
                "list programs",
                "",
                "ROLE_USER"
            ],
            [
                "api/program/",
                "POST",
                "add program",
                "
                {
                    \"title\": \"string, not blank, requiered\",
                    \"description\": \"string\",
                    \"time\": integer,
                    \"img_path\": \"string\",
                    \"exercise_ids\": [array of integers]
                }
                ",
                "ROLE_ADMIN"
            ],
            [
                "api/program/id",
                "PUT",
                "edit program by id",
                "
                {
                    \"title\": \"string, not blank\",
                    \"description\": \"string\",
                    \"time\": integer,
                    \"img_path\": \"string\",
                    \"exercise_ids\": [array of integers]
                }
                ",
                "ROLE_ADMIN"
            ],
            [
                "api/program/id",
                "GET",
                "program by id",
                "",
                "ROLE_USER"
            ],
            [
                "api/program/id",
                "DELETE",
                "delete program by id",
                "",
                "ROLE_ADMIN"
            ],
            [
                "api/user/",
                "GET",
                "list users",
                "",
                "ROLE_ADMIN"
            ],
            [
                "api/user/",
                "POST",
                "add user",
                "
                {
                    \"email\": \"email, not blank, requiered\",
                    \"password\": \"string, not blank, requiered\",
                    \"account_name\": \"string\",
                    \"img_path\": \"string\",
                    \"available_time\": integer,
                    \"mastery_level\": integer
                }
                ",
                "ROLE_USER"
            ],
            [
                "api/user/id",
                "PUT",
                "edit user by id",
                "
                {
                    \"email\": \"email, not blank\",
                    \"password\": \"string, not blank\",
                    \"account_name\": \"string\",
                    \"img_path\": \"string\",
                    \"available_time\": integer,
                    \"mastery_level\": integer,
                    \"program_bookmark_ids\": [array of integers],
                    \"exercise_bookmark_ids\": [array of integers],
                    \"active_program\": integer
                }
                ",
                "ROLE_USER"
            ],
            [
                "api/user/id",
                "GET",
                "user by id",
                "",
                "ROLE_USER"
            ],
            [
                "api/user/id",
                "DELETE",
                "delete user by id",
                "",
                "ROLE_USER"
            ],
            [
                "api/exercise-comment/",
                "GET",
                "list exercise_comments",
                "",
                "ROLE_USER"
            ],
            [
                "api/exercise-comment/",
                "POST",
                "add exercise_comment",
                "
                {
                    \"text\": \"string, not blank, requiered\",
                    \"user_id\": \"integer, requiered\",
                    \"exercise_id\": \"integer, requiered\"
                }
                ",
                "ROLE_USER"
            ],
            [
                "api/exercise-comment/id",
                "PUT",
                "edit exercise_comment by id",
                "
                {
                    \"text\": \"string, not blank, requiered\"
                }
                ",
                "ROLE_USER"
            ],
            [
                "api/exercise-comment/id",
                "GET",
                "exercise-comment by id",
                "",
                "ROLE_USER"
            ],
            [
                "api/exercise-comment/id",
                "DELETE",
                "delete exercise_comment by id",
                "",
                "ROLE_USER"
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
                    \"text\": \"string, not blank, requiered\",
                    \"user_id\": \"integer, requiered\",
                    \"program_id\": \"integer, requiered\"
                }
                ",
                "ROLE_USER"
            ],
            [
                "api/program-comment/id",
                "PUT",
                "edit program_comment by id",
                "
                {
                    \"text\": \"string, not blank, requiered\"
                }
                ",
                "ROLE_USER"
            ],
            [
                "api/program-comment/id",
                "GET",
                "program-comment by id",
                "",
                "ROLE_USER"
            ],
            [
                "api/program-comment/id",
                "DELETE",
                "delete program_comment by id",
                "",
                "ROLE_USER"
            ],
            [
                "api/login",
                "POST",
                "recieve bearer token",
                "{
                    \"username\": \"email, not blank, requiered\",
                    \"password\": \"string, not blank, requiered\"
                }",
                "anonyme"
            ],
            [
                "api/user/profile",
                "GET",
                "recieve current user info",
                "",
                "ROLE_USER"
            ],
        ];

        return $this->render('home\api_data.html.twig', [
            'api_data' => $apiData,
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
