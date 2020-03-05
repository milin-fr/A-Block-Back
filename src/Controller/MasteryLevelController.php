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
 * @Route("/api/mastery_level")
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
                "levelIndex": integer
            }
        */

        // get payload content and convert it to object, so we can acess it's properties
        $contentObject = json_decode($request->getContent());
        $masteryLevelTitle = $contentObject->title;
        $masteryLevelIndex = $contentObject->levelIndex;
        
        if($masteryLevelIndex === ""){
            $masteryLevelIndex = 0;
        }

        // payload validation
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
     * @Route("/{id}/edit", name="mastery_level_edit", methods={"PUT"})
     */
    public function putMasterLevel(Request $request, MasteryLevel $masteryLevel): Response
    {
        $form = $this->createForm(MasteryLevelType::class, $masteryLevel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('mastery_level_index');
        }

        return $this->render('mastery_level/edit.html.twig', [
            'mastery_level' => $masteryLevel,
            'form' => $form->createView(),
        ]);
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
