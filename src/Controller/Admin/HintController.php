<?php

namespace App\Controller\Admin;

use App\Entity\Hint;
use App\Form\HintType;
use App\Repository\HintRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/hint")
 */
class HintController extends AbstractController
{
    /**
     * @Route("/", name="admin_hint_index", methods={"GET"})
     */
    public function index(HintRepository $hintRepository): Response
    {
        return $this->render('admin/hint/index.html.twig', [
            'hints' => $hintRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="admin_hint_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $hint = new Hint();
        $form = $this->createForm(HintType::class, $hint);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($hint);
            $entityManager->flush();
            $this->addFlash('success', 'Hint Created!');
            return $this->redirectToRoute('admin_hint_index');
        }
        return $this->render('admin/hint/new.html.twig', [
            'hint' => $hint,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id<\d+>}", name="admin_hint_show", methods={"GET"})
     */
    public function show(Hint $hint): Response
    {
        return $this->render('admin/hint/show.html.twig', [
            'hint' => $hint,
        ]);
    }

    /**
     * @Route("/{id<\d+>}/edit", name="admin_hint_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Hint $hint): Response
    {
        $form = $this->createForm(HintType::class, $hint);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'Hint Edited!');
            return $this->redirectToRoute('admin_hint_index');
        }

        return $this->render('admin/hint/edit.html.twig', [
            'hint' => $hint,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id<\d+>}", name="admin_hint_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Hint $hint): Response
    {
        if ($this->isCsrfTokenValid('delete'.$hint->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($hint);
            $entityManager->flush();
        }
        $this->addFlash('success', 'Hint Deleted!');

        return $this->redirectToRoute('admin_hint_index');
    }
}
