<?php

namespace App\Controller\Admin;

use App\Entity\ProgramComment;
use App\Form\ProgramCommentType;
use App\Repository\ProgramCommentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/program-comment")
 */
class ProgramCommentController extends AbstractController
{
    /**
     * @Route("/", name="admin_program_comment_index", methods={"GET"})
     */
    public function index(ProgramCommentRepository $programCommentRepository): Response
    {
        return $this->render('admin/program_comment/index.html.twig', [
            'program_comments' => $programCommentRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="admin_program_comment_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $programComment = new ProgramComment();
        $form = $this->createForm(ProgramCommentType::class, $programComment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($programComment);
            $entityManager->flush();

            return $this->redirectToRoute('admin_program_comment_index');
        }

        return $this->render('admin/program_comment/new.html.twig', [
            'program_comment' => $programComment,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="admin_program_comment_show", methods={"GET"})
     */
    public function show(ProgramComment $programComment): Response
    {
        return $this->render('admin/program_comment/show.html.twig', [
            'program_comment' => $programComment,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="admin_program_comment_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, ProgramComment $programComment): Response
    {
        $form = $this->createForm(ProgramCommentType::class, $programComment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_program_comment_index');
        }

        return $this->render('admin/program_comment/edit.html.twig', [
            'program_comment' => $programComment,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="admin_program_comment_delete", methods={"DELETE"})
     */
    public function delete(Request $request, ProgramComment $programComment): Response
    {
        if ($this->isCsrfTokenValid('delete'.$programComment->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($programComment);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_program_comment_index');
    }
}
