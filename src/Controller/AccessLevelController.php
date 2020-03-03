<?php

namespace App\Controller;

use App\Entity\AccessLevel;
use App\Form\AccessLevelType;
use App\Repository\AccessLevelRepository;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/api/access_level")
 */
class AccessLevelController extends AbstractController
{
    /**
     * @Route("/", name="access_level_list", methods={"GET"})
     */
    public function getAcessLevels(AccessLevelRepository $accessLevelRepository): Response
    {
        $access_levels = $accessLevelRepository->findAll();

        $encoders = [new JsonEncoder()];

        $normalizers = array(new DateTimeNormalizer(), new ObjectNormalizer());

        $serializer = new Serializer($normalizers, $encoders);

        $jsonContent = $serializer->serialize($access_levels, 'json', [
            'circular_reference_handler' => function($objet){
                return $objet->getId();
            }
        ]);
        $response = new Response($jsonContent);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * @Route("/", name="access_level_new", methods={"POST"})
     */
    public function postAccessLevel(Request $request): Response
    {
        $accessLevel = new AccessLevel();
        $form = $this->createForm(AccessLevelType::class, $accessLevel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($accessLevel);
            $entityManager->flush();

            return $this->redirectToRoute('access_level_index');
        }

        return $this->render('access_level/new.html.twig', [
            'access_level' => $accessLevel,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="access_level_show", methods={"GET"})
     */
    public function getAccessLevel(AccessLevel $accessLevel): Response
    {
        return $this->render('access_level/show.html.twig', [
            'access_level' => $accessLevel,
        ]);
    }

    /**
     * @Route("/{id}", name="access_level_edit", methods={"PUT"})
     */
    public function putAccessLevel(Request $request, AccessLevel $accessLevel): Response
    {
        $form = $this->createForm(AccessLevelType::class, $accessLevel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('access_level_index');
        }

        return $this->render('access_level/edit.html.twig', [
            'access_level' => $accessLevel,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="access_level_delete", methods={"DELETE"})
     */
    public function deleteAccessLevel(Request $request, AccessLevel $accessLevel): Response
    {
        if ($this->isCsrfTokenValid('delete'.$accessLevel->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($accessLevel);
            $entityManager->flush();
        }

        return $this->redirectToRoute('access_level_index');
    }
}
