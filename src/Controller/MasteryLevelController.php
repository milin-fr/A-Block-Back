<?php

namespace App\Controller;

use App\Entity\MasteryLevel;
use App\Form\MasteryLevelType;
use App\Repository\MasteryLevelRepository;
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
                "level_index": 2
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


        if (count($validationsErrors) !== 0) {
            return $this->json($validationsErrors, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $masteryLevel = new MasteryLevel();
        
        $masteryLevel->setTitle($masteryLevelTitle);
        $masteryLevel->setCreatedAt(new \DateTime());
        $masteryLevel->setLevelIndex($masteryLevelIndex);
        $em = $this->getDoctrine()->getManager();
        $em->persist($masteryLevel);
        $em->flush();
        return $this->redirectToRoute('mastery_level_show', ['id' => $masteryLevel->getId()], Response::HTTP_CREATED);
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
                "level_index": 2
            }
        */
        
        $masteryLevel = $masteryLevelRepository->find($id);
        if (!$masteryLevel) {
            
            return new JsonResponse(['error' => '404 not found.'], 404);
        }
        
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


        if (count($validationsErrors) !== 0) {
            return $this->json($validationsErrors, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $masteryLevel->setTitle($masteryLevelTitle);
        $masteryLevel->setUpdatedAt(new \DateTime());
        $masteryLevel->setLevelIndex($masteryLevelIndex);
        $em = $this->getDoctrine()->getManager();
        $em->persist($masteryLevel);
        $em->flush();
        return $this->redirectToRoute('mastery_level_show', ['id' => $masteryLevel->getId()], Response::HTTP_CREATED);
    }

    /**
     * @Route("/{id}", name="mastery_level_delete", methods={"DELETE"})
     */
    public function deleteMasteryLevel(Request $request, MasteryLevel $masteryLevel): Response
    {
        if ($this->isCsrfTokenValid('delete'.$masteryLevel->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($masteryLevel);
            $entityManager->flush();
        }

        return $this->redirectToRoute('mastery_level_index');
    }
}
