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
        $prerequisite = new Prerequisite();
        $form = $this->createForm(PrerequisiteType::class, $prerequisite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($prerequisite);
            $entityManager->flush();

            return $this->redirectToRoute('prerequisite_index');
        }

        return $this->render('prerequisite/new.html.twig', [
            'prerequisite' => $prerequisite,
            'form' => $form->createView(),
        ]);
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
