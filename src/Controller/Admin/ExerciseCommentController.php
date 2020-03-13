<?php

namespace App\Controller\Admin;

use App\Entity\ExerciseComment;
use App\Form\ExerciseCommentType;
use App\Repository\ExerciseCommentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/exercise/comment")
 */
class ExerciseCommentController extends AbstractController
{
    /**
     * @Route("/", name="admin_exercise_comment_index", methods={"GET"})
     */
    public function index(ExerciseCommentRepository $exerciseCommentRepository): Response
    {
        return $this->render('admin/exercise_comment/index.html.twig', [
            'exercise_comments' => $exerciseCommentRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="admin_exercise_comment_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $exerciseComment = new ExerciseComment();
        $form = $this->createForm(ExerciseCommentType::class, $exerciseComment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($exerciseComment);
            $entityManager->flush();

            return $this->redirectToRoute('admin_exercise_comment_index');
        }

        return $this->render('admin/exercise_comment/new.html.twig', [
            'exercise_comment' => $exerciseComment,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="admin_exercise_comment_show", methods={"GET"})
     */
    public function show(ExerciseComment $exerciseComment): Response
    {
        return $this->render('admin/exercise_comment/show.html.twig', [
            'exercise_comment' => $exerciseComment,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="admin_exercise_comment_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, ExerciseComment $exerciseComment): Response
    {
        $form = $this->createForm(ExerciseCommentType::class, $exerciseComment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_exercise_comment_index');
        }

        return $this->render('admin/exercise_comment/edit.html.twig', [
            'exercise_comment' => $exerciseComment,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="admin_exercise_comment_delete", methods={"DELETE"})
     */
    public function delete(Request $request, ExerciseComment $exerciseComment): Response
    {
        if ($this->isCsrfTokenValid('delete'.$exerciseComment->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($exerciseComment);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_exercise_comment_index');
    }
}
