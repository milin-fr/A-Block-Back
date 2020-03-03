<?php

namespace App\Controller;

use App\Entity\Hint;
use App\Form\HintType;
use App\Repository\HintRepository;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @Route("/api/hint")
 */
class HintController extends AbstractController
{
    /**
     * @Route("/", name="hint_list", methods={"GET"})
     */
    public function getHints(HintRepository $hintRepository): Response
    {
        $hints = $hintRepository->findAll();

        return $this->json($hints, Response::HTTP_OK, [], ['groups' => 'hint']);
    }

    /**
     * @Route("/", name="hint_new", methods={"POST"})
     */
    public function postHint(Request $request): Response
    {
        $hint = new Hint();
        $form = $this->createForm(HintType::class, $hint);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($hint);
            $entityManager->flush();

            return $this->redirectToRoute('hint_index');
        }

        return $this->render('hint/new.html.twig', [
            'hint' => $hint,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="hint_show", methods={"GET"})
     */
    public function getHint($id, HintRepository $hintRepository): Response
    {
        $hint = $hintRepository->find($id);
        if (!$hint) {
            
            return new JsonResponse(['error' => '404 not found.'], 404);
        }
        return $this->json($hint, Response::HTTP_OK, [], ['groups' => 'hint']);
    }

    /**
     * @Route("/{id}/edit", name="hint_edit", methods={"PUT"})
     */
    public function putHint(Request $request, Hint $hint): Response
    {
        $form = $this->createForm(HintType::class, $hint);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('hint_index');
        }

        return $this->render('hint/edit.html.twig', [
            'hint' => $hint,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="hint_delete", methods={"DELETE"})
     */
    public function deleteHint(Request $request, Hint $hint): Response
    {
        if ($this->isCsrfTokenValid('delete'.$hint->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($hint);
            $entityManager->flush();
        }

        return $this->redirectToRoute('hint_index');
    }
}
