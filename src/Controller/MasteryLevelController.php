<?php

namespace App\Controller;

use App\Entity\MasteryLevel;
use App\Form\MasteryLevelType;
use App\Repository\MasteryLevelRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
        return $this->render('mastery_level/index.html.twig', [
            'mastery_levels' => $masteryLevelRepository->findAll(),
        ]);
    }

    /**
     * @Route("/", name="mastery_level_new", methods={"POST"})
     */
    public function postMasteryLevel(Request $request): Response
    {
        $masteryLevel = new MasteryLevel();
        $form = $this->createForm(MasteryLevelType::class, $masteryLevel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($masteryLevel);
            $entityManager->flush();

            return $this->redirectToRoute('mastery_level_index');
        }

        return $this->render('mastery_level/new.html.twig', [
            'mastery_level' => $masteryLevel,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="mastery_level_show", methods={"GET"})
     */
    public function getMasterLevel(MasteryLevel $masteryLevel): Response
    {
        return $this->render('mastery_level/show.html.twig', [
            'mastery_level' => $masteryLevel,
        ]);
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
