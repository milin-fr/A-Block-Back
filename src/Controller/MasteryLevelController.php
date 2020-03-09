<?php

namespace App\Controller;

use App\Entity\MasteryLevel;
use App\Form\MasteryLevelType;
use App\Repository\ExerciseRepository;
use App\Repository\MasteryLevelRepository;
use App\Repository\UserRepository;
use Exception;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @Route("/api/mastery-level")
 */
class MasteryLevelController extends AbstractController
{
    /**
     * @Route("/", name="mastery_level_list", methods={"GET"})
     */
    public function getMasteryLevels(MasteryLevelRepository $masteryLevelRepository): Response
    {
        $masteryLevel = $masteryLevelRepository->findAll();

        return $this->json($masteryLevel, Response::HTTP_OK, [], ['groups' => 'mastery_level']);
    }

    /**
     * @Route("/", name="mastery_level_new", methods={"POST"})
     */
    public function postMasteryLevel(Request $request): Response
    {
    /*
            {
                "title": "mastery level test",
                "level_index": 2,
                "description": "text",
                "img_path": "string"
            }
        */

        // start of payload validation
        $keyList = ["title", "level_index"];

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



        // values validation

        $masteryLevelTitle = $contentObject->title;
        $masteryLevelIndex = $contentObject->level_index;
        try {
            $masteryLevelDescription = $contentObject->description;
        } catch(Exception $e) {
            $masteryLevelDescription = "";
        }
        try {
            $masteryLevelImgPath = $contentObject->img_path;
        } catch(Exception $e) {
            $masteryLevelImgPath = "";
        }
        
        if($masteryLevelIndex === ""){
            $masteryLevelIndex = 0;
        }

        $validationsErrors = [];
        
        if($masteryLevelTitle === ""){
            $validationsErrors[] = "Title, blank";
        }

        if(strlen($masteryLevelTitle) > 64){
            $validationsErrors[] = "title, length, max, 64";
        }

        if(gettype($masteryLevelIndex) !== "integer"){
            $validationsErrors[] = "levelIndex, not integer";
        }

        if($masteryLevelIndex < 0){
            $validationsErrors[] = "levelIndex, value, min, 0";
        }

        if($masteryLevelIndex > 99){
            $validationsErrors[] = "levelIndex, value, max, 99";
        }

        if(strlen($masteryLevelDescription) > 999){
            $validationsErrors[] = "description, length, max, 999";
        }

        if(strlen($masteryLevelImgPath) > 64){
            $validationsErrors[] = "imgPath, length, max, 64";
        }

        if (count($validationsErrors) !== 0) {
            return $this->json($validationsErrors, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $masteryLevel = new MasteryLevel();
        
        $masteryLevel->setTitle($masteryLevelTitle);
        $masteryLevel->setCreatedAt(new \DateTime());
        if($masteryLevelImgPath === ""){
            $masteryLevelImgPath = "default_exercise.png";
        }

        $masteryLevel->setImgPath($masteryLevelImgPath);
        $masteryLevel->setDescription($masteryLevelDescription);
        $masteryLevel->setLevelIndex($masteryLevelIndex);
        $em = $this->getDoctrine()->getManager();
        $em->persist($masteryLevel);
        $em->flush();
        return $this->json($masteryLevel, Response::HTTP_CREATED, [], ['groups' => 'mastery_level']);
    }

    /**
     * @Route("/{id}", name="mastery_level_show", methods={"GET"})
     */
    public function getMasterLevel($id, MasteryLevelRepository $masteryLevelRepository): Response
    {
        $masteryLevel = $masteryLevelRepository->find($id);
        if (!$masteryLevel) {
            
            return new JsonResponse(['error' => '404 not found.'], 404);
        }
        return $this->json($masteryLevel, Response::HTTP_OK, [], ['groups' => 'mastery_level']);
    }

    /**
     * @Route("/{id}", name="mastery_level_edit", methods={"PUT"})
     */
    public function putMasterLevel(Request $request, $id, MasteryLevelRepository $masteryLevelRepository): Response
    {

        /*
            {
                "title": "mastery level test",
                "level_index": 2,
                "description": "text",
                "img_path": "string"
            }
        */

        $masteryLevel = $masteryLevelRepository->find($id);
        if (!$masteryLevel) {
            
            return new JsonResponse(['error' => '404 not found.'], 404);
        }
        
        // start of payload validation

        $validationsErrors = [];

        $jsonContent = $request->getContent();
        if (json_decode($jsonContent) === null) {
            return $this->json([
                'error' => 'Format de données érroné.'
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        // get payload content and convert it to object, so we can acess it's properties
        $contentObject = json_decode($request->getContent());

        if (count($validationsErrors) !== 0) {
            return $this->json($validationsErrors, Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        // end of payload validation



        // values validation

        try {
            $masteryLevelTitle = $contentObject->title;
        } catch(Exception $e) {
            $masteryLevelTitle = $masteryLevel->getTitle();
        }
        try {
            $masteryLevelIndex = $contentObject->level_index;
        } catch(Exception $e) {
            $masteryLevelIndex = $masteryLevel->getLevelIndex();
        }
        try {
            $masteryLevelDescription = $contentObject->description;
        } catch(Exception $e) {
            $masteryLevelDescription = $masteryLevel->getDescription();
        }
        try {
            $masteryLevelImgPath = $contentObject->img_path;
        } catch(Exception $e) {
            $masteryLevelImgPath = $masteryLevel->getImgPath();
        }
        
        if($masteryLevelIndex === ""){
            $masteryLevelIndex = 0;
        }


        $validationsErrors = [];
        
        if($masteryLevelTitle === ""){
            $validationsErrors[] = "Title, blank";
        }

        if(strlen($masteryLevelTitle) > 64){
            $validationsErrors[] = "title, length, max, 64";
        }

        if(gettype($masteryLevelIndex) !== "integer"){
            $validationsErrors[] = "levelIndex, not integer";
        }

        if($masteryLevelIndex < 0){
            $validationsErrors[] = "levelIndex, value, min, 0";
        }

        if($masteryLevelIndex > 99){
            $validationsErrors[] = "levelIndex, value, max, 99";
        }

        if(strlen($masteryLevelDescription) > 999){
            $validationsErrors[] = "description, length, max, 999";
        }

        if(strlen($masteryLevelImgPath) > 64){
            $validationsErrors[] = "imgPath, length, max, 64";
        }

        if (count($validationsErrors) !== 0) {
            return $this->json($validationsErrors, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $masteryLevel->setTitle($masteryLevelTitle);
        $masteryLevel->setUpdatedAt(new \DateTime());
        if($masteryLevelImgPath === ""){
            $masteryLevelImgPath = "default_exercise.png";
        }
        $masteryLevel->setImgPath($masteryLevelImgPath);
        $masteryLevel->setDescription($masteryLevelDescription);
        $masteryLevel->setLevelIndex($masteryLevelIndex);
        $em = $this->getDoctrine()->getManager();
        $em->flush();
        return $this->json($masteryLevel, Response::HTTP_OK, [], ['groups' => 'mastery_level']);
    }

    /**
     * @Route("/{id}", name="mastery_level_delete", methods={"DELETE"})
     */
    public function deleteMasteryLevel(Request $request, $id, MasteryLevelRepository $masteryLevelRepository, UserRepository $userRepository, ExerciseRepository $exerciseRepository): Response
    {
        $masteryLevel = $masteryLevelRepository->find($id);
        if (!$masteryLevel) {
            return new JsonResponse(['error' => '404 not found.'], 404);
        }

        $affectedUser = $userRepository->findBy(["mastery_level"=>$masteryLevel]);
        $affectedExercise = $exerciseRepository->findBy(["mastery_level"=>$masteryLevel]);
        if(!empty($affectedUser)){
            return $this->json([
                'error' => 'Ce niveau de maitrise est affecté a un utilisateur. Impossible de supprimer.'
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        if(!empty($affectedExercise)){
            return $this->json([
                'error' => 'Ce niveau de maitrise est affecté a un exercise. Impossible de supprimer.'
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($masteryLevel);
        $em->flush();

        $masteryLevels = $masteryLevelRepository->findAll();
        return $this->json($masteryLevels, Response::HTTP_OK, [], ['groups' => 'hint']);
    }
}
