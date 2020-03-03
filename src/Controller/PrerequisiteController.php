<?php

namespace App\Controller;

use App\Entity\Prerequisite;
use App\Form\PrerequisiteType;
use App\Repository\PrerequisiteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
        $prerequisites = $prerequisiteRepository->findAll();

        $encoders = [new JsonEncoder()];

        $normalizers = [new ObjectNormalizer()];

        $serializer = new Serializer($normalizers, $encoders);

        $jsonContent = $serializer->serialize($prerequisites, 'json', [
            'circular_reference_handler' => function($objet){
                return $objet->getId();
            }
        ]);
        $response = new Response($jsonContent);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
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
    public function getPrerequisite(Prerequisite $prerequisite): Response
    {
        return $this->render('prerequisite/show.html.twig', [
            'prerequisite' => $prerequisite,
        ]);
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
