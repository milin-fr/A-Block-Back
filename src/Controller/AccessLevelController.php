<?php

namespace App\Controller;

use App\Entity\AccessLevel;
use App\Form\AccessLevelType;
use App\Repository\AccessLevelRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/api/access-level")
 */
class AccessLevelController extends AbstractController
{
    /**
     * @Route("/", name="access_level_list", methods={"GET"})
     */
    public function getAccessLevels(AccessLevelRepository $accessLevelRepository): Response
    {
        $accessLevels = $accessLevelRepository->findAll();

        return $this->json($accessLevels, Response::HTTP_OK, [], ['groups' => 'access_level']);
    }

    /**
     * @Route("/", name="access_level_new", methods={"POST"})
     */
    public function postAccessLevel(Request $request, SerializerInterface $serializer, ValidatorInterface $validator): Response
    {

        /*
            {
                "title": "access level test"
            }
        */

        $keyList = ["title"];

        $validationsErrors = [];

        $jsonContent = $request->getContent();
        if (json_decode($jsonContent) === null) {
            return $this->json([
                'error' => 'Format de données érroné.'
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        // get payload content and convert it to object, so we can access it's properties
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


        $accessLevelTitle = $contentObject->title;


        // payload validation
        
        if($accessLevelTitle === ""){
            $validationsErrors[] = "Title, blank";
        }

        if(strlen($accessLevelTitle) > 64){
            $validationsErrors[] = "title, length, max, 64";
        }

        if (count($validationsErrors) !== 0) {
            return $this->json($validationsErrors, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $accessLevel = new AccessLevel();
        
        $accessLevel->setTitle($accessLevelTitle);
        $accessLevel->setCreatedAt(new \DateTime());

        $em = $this->getDoctrine()->getManager();
        $em->persist($accessLevel);
        $em->flush();
        return $this->redirectToRoute('access_level_show', ['id' => $accessLevel->getId()], Response::HTTP_CREATED);
    }

    /**
     * @Route("/{id}", name="access_level_show", methods={"GET"})
     */
    public function getAccessLevel($id, AccessLevelRepository $accessLevelRepository): Response
    {
        $accessLevel = $accessLevelRepository->find($id);
        if (!$accessLevel) {
            
            return new JsonResponse(['error' => '404 not found.'], 404);
        }
        return $this->json($accessLevel, Response::HTTP_OK, [], ['groups' => 'access_level']);
    }

    /**
     * @Route("/{id}", name="access_level_edit", methods={"PUT"})
     */
    public function putAccessLevel(Request $request, $id, AccessLevelRepository $accessLevelRepository): Response
    {
        /*
            {
                "title": "access level test"
            }
        */
        
        $accessLevel = $accessLevelRepository->find($id);
        
        $keyList = ["title"];

        $validationsErrors = [];

        $jsonContent = $request->getContent();
        if (json_decode($jsonContent) === null) {
            return $this->json([
                'error' => 'Format de données érroné.'
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        // get payload content and convert it to object, so we can access it's properties
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

        $accessLevelTitle = $contentObject->title;


        // payload validation
        $validationsErrors = [];
        
        if($accessLevelTitle === ""){
            $validationsErrors[] = "Title, blank";
        }

        if(strlen($accessLevelTitle) > 64){
            $validationsErrors[] = "title, length, max, 64";
        }

        if (count($validationsErrors) !== 0) {
            return $this->json($validationsErrors, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $accessLevel->setTitle($accessLevelTitle);
        $accessLevel->setUpdatedAt(new \DateTime());

        $em = $this->getDoctrine()->getManager();
        $em->flush();
        return $this->redirectToRoute('access_level_show', ['id' => $accessLevel->getId()], Response::HTTP_CREATED);
    }

    /**
     * @Route("/{id}", name="access_level_delete", methods={"DELETE"})
     */
    public function deleteAccessLevel(Request $request, $id, AccessLevelRepository $accessLevelRepository): Response
    {
        $accessLevel = $accessLevelRepository->find($id);
        if (!$accessLevel) {
            
            return new JsonResponse(['error' => '404 not found.'], 404);
        }
        $em = $this->getDoctrine()->getManager();
        $em->remove($accessLevel);
        $em->flush();

        // return $this->json([], Response::HTTP_OK);

        $accessLevels = $accessLevelRepository->findAll();
        return $this->json($accessLevels, Response::HTTP_OK, [], ['groups' => 'access_level']);
    }
}