<?php

namespace App\Controller;

use App\Entity\Program;
use App\Form\ProgramType;
use App\Repository\ExerciseRepository;
use App\Repository\ProgramRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

/**
 * @Route("/api/program")
 */
class ProgramController extends AbstractController
{
    /**
     * @Route("/", name="program_list", methods={"GET"})
     */
    public function getPrograms(ProgramRepository $programRepository): Response
    {
        $programs = $programRepository->findAll();

        return $this->json($programs, Response::HTTP_OK, [], ['groups' => 'program']);
    }

    /**
     * @Route("/", name="program_new", methods={"POST"})
     */
    public function postProgram(Request $request, ExerciseRepository $exerciseRepository): Response
    {
        /*
            {
                "title": "program test",
                "description": "description program 1",
                "time": 10,
                "img_path": "image_program_1.png",
                "exercise_ids":[1, 20]
            }
        */


        // start of payload validation
        $keyList = ["title",
                    "description",
                    "time",
                    "img_path",
                    "exercise_ids"
                    ];

        $validationsErrors = [];

        $jsonContent = $request->getContent();
        if (json_decode($jsonContent) === null) {
            return $this->json([
                'error' => 'Format de données érroné.'
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        // get payload content and convert it to object, so we can acess it's properties
        $contentObject = json_decode($request->getContent());
        $contentArray = get_object_vars($contentObject);

        foreach($keyList as $key){
            if(!array_key_exists($key, $contentArray)){
                $validationsErrors[] = [
                                        $key => "Requiered, but not provided"
                                        ];
            }
        }

        if (count($validationsErrors) !== 0) {
            return $this->json($validationsErrors, Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        // end of payload validation


        // get payload content and convert it to object, so we can acess it's properties
        $programTitle = $contentObject->title;
        $programTime = $contentObject->time; // type integer
        $programImgPath = $contentObject->img_path;
        $programDescription = $contentObject->description;
        $programExercises = $contentObject->exercise_ids; // array of ids of exercises


        if($programTime === ""){
            $programTime = 0;
        }

        if(gettype($programExercises) !== "array"){
            $programExercises = [];
        }

        foreach($programExercises as $key => $id){
            if(gettype($id) !== "integer"){
                $programExercises[$key] = "";
            }
        }
        
        // payload validation
        $validationsErrors = [];
        
        if($programTitle === ""){
            $validationsErrors[] = "title, blank";
        }

        if(strlen($programTitle) > 64){
            $validationsErrors[] = "title, length, max, 64";
        }

        if(gettype($programTime) !== "integer"){
            $validationsErrors[] = "time, not integer";
        }

        if($programTime < 0){
            $validationsErrors[] = "time, value, min, 0";
        }

        if($programTime > 999){
            $validationsErrors[] = "time, value, max, 999";
        }

        if(strlen($programImgPath) > 64){
            $validationsErrors[] = "imgPath, length, max, 64";
        }

        if($programDescription === ""){
            $validationsErrors[] = "description, blank";
        }

        if(strlen($programDescription) > 999){
            $validationsErrors[] = "description, length, max, 999";
        }

        if (count($validationsErrors) !== 0) {
            return $this->json($validationsErrors, Response::HTTP_UNPROCESSABLE_ENTITY);
        }


        $program = new Program();
        $program->setTitle($programTitle);
        $program->setCreatedAt(new \DateTime());
        $program->setTime($programTime);

        if($programImgPath === ""){
            $programImgPath = "default_program.png";
        }

        $program->setImgPath($programImgPath);
        $program->setDescription($programDescription);

        // identify the most frequent mastery level among exercises related to this program
        $masteryLevelIdList = []; // list of mastery ids
        $masteryLevelList = []; // list of mastery objects

        foreach($programExercises as $id){
            $exercise = $exerciseRepository->find($id);
            if($exercise){ // checking if exercise id, provided from front, exists in bdd
                $program->addExercise($exercise);
                $masteryLevelId = $exercise->getMasteryLevel()->getId();
                $masteryLevelIdList[] = $masteryLevelId;
                $masteryLevelList[$masteryLevelId] = $exercise->getMasteryLevel(); // storing mastery objects by id for later use
            }
        }
        
        if(!empty($masteryLevelIdList)){ // checking if there were at least 1 bdd match for mastery
            $idFrequencies = array_count_values($masteryLevelIdList); // getting a list with ids as keys and number of id as value
            $mostFrequentMasteryId = $masteryLevelIdList[0]; // assuming that the most frequent id is the first one
            foreach($idFrequencies as $id => $frequencie){
                if($idFrequencies[$mostFrequentMasteryId] < $frequencie){ // checking if assumption was right, if not updating the id
                    $mostFrequentMasteryId = $id;
                }
            }
    
            $program->setMasteryLevel($masteryLevelList[$mostFrequentMasteryId]); // pulling mastery object by most frequent id and adding it to program
        }
        // end of mastery level treatment

        $em = $this->getDoctrine()->getManager();
        $em->persist($program);
        $em->flush();
        return $this->redirectToRoute('program_show', ['id' => $program->getId()], Response::HTTP_CREATED);
    }

    /**
     * @Route("/{id}", name="program_show", methods={"GET"})
     */
    public function getProgram($id, ProgramRepository $programRepository): Response
    {
        $program = $programRepository->find($id);
        if (!$program) {
            
            return new JsonResponse(['error' => '404 not found.'], 404);
        }
        return $this->json($program, Response::HTTP_OK, [], ['groups' => 'program']);
    }

    /**
     * @Route("/{id}", name="program_edit", methods={"PUT"})
     */
    public function putProgram(Request $request, $id, ProgramRepository $programRepository, ExerciseRepository $exerciseRepository): Response
    {
        /*
            {
                "title": "program test",
                "description": "description program 1",
                "time": 10,
                "img_path": "image_program_1.png",
                "exercise_ids":[1, 20]
            }
        */

        
        $program = $programRepository->find($id);
        if (!$program) {
            
            return new JsonResponse(['error' => '404 not found.'], 404);
        }

        // start of payload validation
        $keyList = ["title", "description", "time", "img_path", "exercise_ids"];

        $validationsErrors = [];

        $jsonContent = $request->getContent();
        if (json_decode($jsonContent) === null) {
            return $this->json([
                'error' => 'Format de données érroné.'
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        // get payload content and convert it to object, so we can acess it's properties
        $contentObject = json_decode($request->getContent());
        $contentArray = get_object_vars($contentObject);

        foreach($keyList as $key){
            if(!array_key_exists($key, $contentArray)){
                $validationsErrors[] = [
                                        $key => "Requiered, but not provided"
                                        ];
            }
        }

        if (count($validationsErrors) !== 0) {
            return $this->json($validationsErrors, Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        // end of payload validation


        // get payload content and convert it to object, so we can acess it's properties
        $programTitle = $contentObject->title;
        $programTime = $contentObject->time; // type integer
        $programImgPath = $contentObject->img_path;
        $programDescription = $contentObject->description;
        $programExercises = $contentObject->exercise_ids; // array of ids of exercises


        if($programTime === ""){
            $programTime = 0;
        }

        if(gettype($programExercises) !== "array"){
            $programExercises = [];
        }

        foreach($programExercises as $key => $id){
            if(gettype($id) !== "integer"){
                $programExercises[$key] = "";
            }
        }
        
        // payload validation
        $validationsErrors = [];
        
        if($programTitle === ""){
            $validationsErrors[] = "title, blank";
        }

        if(strlen($programTitle) > 64){
            $validationsErrors[] = "title, length, max, 64";
        }

        if(gettype($programTime) !== "integer"){
            $validationsErrors[] = "time, not integer";
        }

        if($programTime < 0){
            $validationsErrors[] = "time, value, min, 0";
        }

        if($programTime > 999){
            $validationsErrors[] = "time, value, max, 999";
        }

        if(strlen($programImgPath) > 64){
            $validationsErrors[] = "imgPath, length, max, 64";
        }

        if($programDescription === ""){
            $validationsErrors[] = "description, blank";
        }

        if(strlen($programDescription) > 999){
            $validationsErrors[] = "description, length, max, 999";
        }

        if (count($validationsErrors) !== 0) {
            return $this->json($validationsErrors, Response::HTTP_UNPROCESSABLE_ENTITY);
        }


        $program->setTitle($programTitle);
        $program->setUpdatedAt(new \DateTime());
        $program->setTime($programTime);

        if($programImgPath === ""){
            $programImgPath = "default_program.png";
        }

        $program->setImgPath($programImgPath);
        $program->setDescription($programDescription);

        // identify the most frequent mastery level among exercises related to this program
        $masteryLevelIdList = []; // list of mastery ids
        $masteryLevelList = []; // list of mastery objects

        foreach($programExercises as $id){
            $exercise = $exerciseRepository->find($id);
            if($exercise){ // checking if exercise id, provided from front, exists in bdd
                $program->addExercise($exercise);
                $masteryLevelId = $exercise->getMasteryLevel()->getId();
                $masteryLevelIdList[] = $masteryLevelId;
                $masteryLevelList[$masteryLevelId] = $exercise->getMasteryLevel(); // storing mastery objects by id for later use
            }
        }
        
        if(!empty($masteryLevelIdList)){ // checking if there were at least 1 bdd match for mastery
            $idFrequencies = array_count_values($masteryLevelIdList); // getting a list with ids as keys and number of id as value
            $mostFrequentMasteryId = $masteryLevelIdList[0]; // assuming that the most frequent id is the first one
            foreach($idFrequencies as $id => $frequencie){
                if($idFrequencies[$mostFrequentMasteryId] < $frequencie){ // checking if assumption was right, if not updating the id
                    $mostFrequentMasteryId = $id;
                }
            }
    
            $program->setMasteryLevel($masteryLevelList[$mostFrequentMasteryId]); // pulling mastery object by most frequent id and adding it to program
        }
        // end of mastery level treatment

        $em = $this->getDoctrine()->getManager();
        $em->persist($program);
        $em->flush();
        return $this->redirectToRoute('program_show', ['id' => $program->getId()], Response::HTTP_CREATED);
    }

    /**
     * @Route("/{id}", name="program_delete", methods={"DELETE"})
     */
    public function deleteProgram(Request $request, $id, ProgramRepository $programRepository): Response
    {
        $program = $programRepository->find($id);
        if (!$program) {
            return new JsonResponse(['error' => '404 not found.'], 404);
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($program);
        $em->flush();

        $programs = $programRepository->findAll();
        return $this->json($programs, Response::HTTP_OK, [], ['groups' => 'program']);
    }
}
