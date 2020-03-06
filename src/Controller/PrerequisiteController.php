<?php

namespace App\Controller;

use App\Entity\Prerequisite;
use App\Form\PrerequisiteType;
use App\Repository\PrerequisiteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

/**
 * @Route("/api/prerequisite")
 */
class PrerequisiteController extends AbstractController
{
    /**
     * @Route("/", name="prerequisite_list", methods={"GET"})
     */
    public function getPrerequisites(PrerequisiteRepository $prerequisiteRepository): Response
    {
        $prerequisite = $prerequisiteRepository->findAll();

        return $this->json($prerequisite, Response::HTTP_OK, [], ['groups' => 'prerequisite']);
    }

    /**
     * @Route("/", name="prerequisite_new", methods={"POST"})
     */
    public function postPrerequisite(Request $request): Response
    {
        /*
            {
                "description": "hint test",
            }
        */

        // start of payload validation
        $keyList = ["description"];

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
        $prerequisiteDescription = $contentObject->description;


        $validationsErrors = [];
        
        if($prerequisiteDescription === ""){
            $validationsErrors[] = "description, blank";
        }

        if(strlen($prerequisiteDescription) > 999){
            $validationsErrors[] = "description, length, max, 999";
        }

        if (count($validationsErrors) !== 0) {
            return $this->json($validationsErrors, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $prerequisite = new Prerequisite();
        
        $prerequisite->setDescription($prerequisiteDescription);
        $prerequisite->setCreatedAt(new \DateTime());

        $em = $this->getDoctrine()->getManager();
        $em->persist($prerequisite);
        $em->flush();
        return $this->redirectToRoute('prerequisite_show', ['id' => $prerequisite->getId()], Response::HTTP_CREATED);
    }

    /**
     * @Route("/{id}", name="prerequisite_show", methods={"GET"})
     */
    public function getPrerequisite($id, PrerequisiteRepository $prerequisiteRepository): Response
    {
        $prerequisite = $prerequisiteRepository->find($id);
        if (!$prerequisite) {
            
            return new JsonResponse(['error' => '404 not found.'], 404);
        }
        return $this->json($prerequisite, Response::HTTP_OK, [], ['groups' => 'prerequisite']);
    }

    /**
     * @Route("/{id}/edit", name="prerequisite_edit", methods={"PUT"})
     */
    public function putPrerequisite(Request $request, Prerequisite $prerequisite): Response
    {
        $form = $this->createForm(PrerequisiteType::class, $prerequisite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('prerequisite_index');
        }

        return $this->render('prerequisite/edit.html.twig', [
            'prerequisite' => $prerequisite,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="prerequisite_delete", methods={"DELETE"})
     */
    public function deletePrerequisite(Request $request, Prerequisite $prerequisite): Response
    {
        if ($this->isCsrfTokenValid('delete'.$prerequisite->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($prerequisite);
            $entityManager->flush();
        }

        return $this->redirectToRoute('prerequisite_index');
    }
}
