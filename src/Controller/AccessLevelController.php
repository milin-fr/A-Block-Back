<?php

namespace App\Controller;

use App\Entity\AccessLevel;
use App\Form\AccessLevelType;
use App\Repository\AccessLevelRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/access/level")
 */
class AccessLevelController extends AbstractController
{
    /**
     * @Route("/", name="access_level_index", methods={"GET"})
     */
    public function index(AccessLevelRepository $accessLevelRepository): Response
    {
        return $this->render('access_level/index.html.twig', [
            'access_levels' => $accessLevelRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="access_level_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
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
    public function show(AccessLevel $accessLevel): Response
    {
        return $this->render('access_level/show.html.twig', [
            'access_level' => $accessLevel,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="access_level_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, AccessLevel $accessLevel): Response
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
    public function delete(Request $request, AccessLevel $accessLevel): Response
    {
        if ($this->isCsrfTokenValid('delete'.$accessLevel->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($accessLevel);
            $entityManager->flush();
        }

        return $this->redirectToRoute('access_level_index');
    }
}
