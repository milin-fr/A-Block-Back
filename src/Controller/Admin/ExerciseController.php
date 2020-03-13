<?php

namespace App\Controller\Admin;

use Exception;
use App\Entity\Exercise;
use App\Form\ExerciseType;
use App\Entity\ExerciseComment;
use App\Service\MessageGenerator;
use App\Repository\HintRepository;
use App\Repository\ProgramRepository;
use App\Repository\ExerciseRepository;
use App\Repository\MasteryLevelRepository;
use App\Repository\PrerequisiteRepository;
use App\Repository\ProgramCommentRepository;
use Symfony\Component\Serializer\Serializer;
use App\Repository\ExerciseCommentRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

/**
 * @Route("/exercise")
 */
class ExerciseController extends AbstractController
{
    /**
     * @Route("/", name="exercise_index", methods={"GET"})
     */
    public function index(ExerciseRepository $exerciseRepository): Response
    {
        return $this->render('exercise/index.html.twig', [
            'exercises' => $exerciseRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="exercise_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $exercise = new Exercise();
        $form = $this->createForm(ExerciseType::class, $exercise);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $someNewFilename = ''; // mettre le nom du fichier
            $file = $form['img_path']->getData(); // a verifier si recupere le nom ou le fichier en en entier
            $file->move($directory, $someNewFilename);
            $entityManager = $this->getDoctrine()->getManager();
            // + gestion des entites filles
            $entityManager->persist($exercise);
            $entityManager->flush();

            return $this->redirectToRoute('exercise_back_list');
        }

        return $this->render('back/exercise/add.html.twig', [
            'exercise' => $exercise,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="exercise_show", methods={"GET"})
     */
    public function show(Exercise $exercise): Response
    {
        return $this->render('exercise/show.html.twig', [
            'exercise' => $exercise,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="exercise_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Exercise $exercise): Response
    {

        $form = $this->createForm(ExerciseType::class, $exercise);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
             /** @var UploadedFile $imgPath */
             $exerciseImage = $form->get('img_path')->getData();

             // this condition is needed because the 'brochure' field is not required
             // so the PDF file must be processed only when a file is uploaded
             if ($exerciseImage) {
                 $exerciseImageFilename = pathinfo($exerciseImage->getClientOriginalName(), PATHINFO_FILENAME);
                 // this is needed to safely include the file name as part of the URL
                 $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
                 $newFilename = $safeFilename.'-'.uniqid().'.'.$exerciseImage->guessExtension();
 
                 // Move the file to the directory where brochures are stored
                 try {
                     $exerciseImage->move(
                         $this->getParameter('brochures_directory'),
                         $newFilename
                     );
                 } catch (FileException $e) {
                     // ... handle exception if something happens during file upload
                 }
 
                 // updates the 'brochureFilename' property to store the PDF file name
                 // instead of its contents
                 $exercise->setImgPath(
                    new File($this->getParameter('brochures_directory').'/'.$product->getBrochureFilename())
                );
             }
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('exercise_back_list');
        }

        return $this->render('back/exercise/edit.html.twig', [
            'exercise' => $exercise,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="exercise_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Exercise $exercise): Response
    {
        if ($this->isCsrfTokenValid('delete'.$exercise->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($exercise);
            $entityManager->flush();
        }

        // Un flash message aléatoire
        $this->addFlash('success', $messageGenerator->getHappyMessage());

        return $this->redirectToRoute('exercise_back_list');
    }
}
