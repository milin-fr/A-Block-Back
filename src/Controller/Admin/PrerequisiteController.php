<?php

namespace App\Controller\Admin;

use App\Entity\Prerequisite;
use App\Form\PrerequisiteType;
use App\Repository\PrerequisiteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/prerequisite")
 */
class PrerequisiteController extends AbstractController
{
    /**
     * @Route("/", name="admin_prerequisite_index", methods={"GET"})
     */
    public function index(PrerequisiteRepository $prerequisiteRepository): Response
    {
        return $this->render('admin/prerequisite/index.html.twig', [
            'prerequisites' => $prerequisiteRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="admin_prerequisite_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $prerequisite = new Prerequisite();
        $form = $this->createForm(PrerequisiteType::class, $prerequisite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($prerequisite);
            $entityManager->flush();

            return $this->redirectToRoute('admin_prerequisite_index');
        }

        return $this->render('admin/prerequisite/new.html.twig', [
            'prerequisite' => $prerequisite,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id<\d+>}", name="admin_prerequisite_show", methods={"GET"})
     */
    public function show(Prerequisite $prerequisite): Response
    {
        return $this->render('admin/prerequisite/show.html.twig', [
            'prerequisite' => $prerequisite,
        ]);
    }

    /**
     * @Route("/{id<\d+>}/edit", name="admin_prerequisite_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Prerequisite $prerequisite): Response
    {
        $form = $this->createForm(PrerequisiteType::class, $prerequisite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_prerequisite_index');
        }

        return $this->render('admin/prerequisite/edit.html.twig', [
            'prerequisite' => $prerequisite,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id<\d+>}", name="admin_prerequisite_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Prerequisite $prerequisite): Response
    {
        if ($this->isCsrfTokenValid('delete'.$prerequisite->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($prerequisite);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_prerequisite_index');
    }
}
