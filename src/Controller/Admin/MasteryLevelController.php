<?php

namespace App\Controller\Admin;

use App\Entity\MasteryLevel;
use App\Form\MasteryLevelType;
use App\Repository\MasteryLevelRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/mastery-level")
 */
class MasteryLevelController extends AbstractController
{
    /**
     * @Route("/", name="admin_mastery_level_index", methods={"GET"})
     */
    public function index(MasteryLevelRepository $masteryLevelRepository): Response
    {
        return $this->render('admin/mastery_level/index.html.twig', [
            'mastery_levels' => $masteryLevelRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="admin_mastery_level_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $masteryLevel = new MasteryLevel();
        $form = $this->createForm(MasteryLevelType::class, $masteryLevel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($masteryLevel);
            $entityManager->flush();

            return $this->redirectToRoute('admin_mastery_level_index');
        }

        return $this->render('admin/mastery_level/new.html.twig', [
            'mastery_level' => $masteryLevel,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="admin_mastery_level_show", methods={"GET"})
     */
    public function show(MasteryLevel $masteryLevel): Response
    {
        return $this->render('admin/mastery_level/show.html.twig', [
            'mastery_level' => $masteryLevel,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="admin_mastery_level_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, MasteryLevel $masteryLevel): Response
    {
        $form = $this->createForm(MasteryLevelType::class, $masteryLevel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_mastery_level_index');
        }

        return $this->render('admin/mastery_level/edit.html.twig', [
            'mastery_level' => $masteryLevel,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="admin_mastery_level_delete", methods={"DELETE"})
     */
    public function delete(Request $request, MasteryLevel $masteryLevel): Response
    {
        if ($this->isCsrfTokenValid('delete'.$masteryLevel->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($masteryLevel);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_mastery_level_index');
    }
}
