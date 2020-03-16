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
use Doctrine\Common\Collections\ArrayCollection;
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
 * @Route("/admin/exercise")
 */
class ExerciseController extends AbstractController
{
    /**
     * @Route("/", name="admin_exercise_index", methods={"GET"})
     */
    public function index(ExerciseRepository $exerciseRepository): Response
    {
        return $this->render('admin/exercise/index.html.twig', [
            'exercises' => $exerciseRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="admin_exercise_new", methods={"GET","POST"})
     */
    public function new(Request $request, HintRepository $hintRepository, PrerequisiteRepository $prerequisiteRepository, ProgramRepository $programRepository, MasteryLevelRepository $masteryLevelRepository): Response
    {
        $exercise = new Exercise();
        $form = $this->createForm(ExerciseType::class, $exercise);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imgFile = $form->get('img_path')->getData();
            if ($imgFile) {
                $originalFilename = pathinfo($imgFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = iconv('UTF-8', 'ASCII//IGNORE', $originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$imgFile->guessExtension(); // former le nom avec id d'exercise et remplacer l'image deja existante, supprimer a la suppression de l'exercise

                // Move the file to the directory where brochures are stored
                try {
                    $imgFile->move(
                        $this->getParameter('exercise_img_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                    $newFilename = "exercise_image_default.png"; // if something goes wrong, assign default value
                }

                // updates the 'imgFilename' property to store the PDF file name
                // instead of its contents
                $exercise->setImgPath($newFilename);
            }else{
                $exercise->setImgPath("exercise_image_default.png");
            }

            $entityManager = $this->getDoctrine()->getManager();

            foreach($form->get("programs")->getData() as $id){
                $program = $programRepository->find($id);
                if($program){
                    $program->addExercise($exercise);
                }
            }

            $entityManager->persist($exercise);
            $entityManager->flush();
            $this->addFlash('success', 'Exercise Created!');
            return $this->redirectToRoute('admin_exercise_index');
        }
        return $this->render('admin/exercise/new.html.twig', [
            'exercise' => $exercise,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id<\d+>}", name="admin_exercise_show", methods={"GET"})
     */
    public function show(Exercise $exercise): Response
    {
        return $this->render('admin\exercise/show.html.twig', [
            'exercise' => $exercise,
        ]);
    }

    /**
     * @Route("/{id<\d+>}/edit", name="admin_exercise_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, $id, ExerciseRepository $exerciseRepository, HintRepository $hintRepository, PrerequisiteRepository $prerequisiteRepository, ProgramRepository $programRepository, MasteryLevelRepository $masteryLevelRepository): Response
    {
        $exercise = $exerciseRepository->find($id);
        $originalPrograms = new ArrayCollection();
        foreach($exercise->getPrograms() as $program) {
            $originalPrograms->add($program);
        }
        $form = $this->createForm(ExerciseType::class, $exercise);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imgFile = $form->get('img_path')->getData(); // a verifier si recupere le nom ou le fichier en en entier
            if ($imgFile) {
                $originalFilename = pathinfo($imgFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = iconv('UTF-8', 'ASCII//IGNORE', $originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$imgFile->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $imgFile->move(
                        $this->getParameter('exercise_img_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                    $newFilename = "exercise_image_default.png"; // if something goes wrong, assign default value
                }

                // updates the 'imgFilename' property to store the PDF file name
                // instead of its contents
                $exercise->setImgPath($newFilename);
            }

            $exercise->setUpdatedAt(new \DateTime());

            $newPrograms = [];
            foreach($form->get("programs")->getData() as $id){
                $program = $programRepository->find($id);
                if($program){
                    $newPrograms[] = $program;
                }
            }
            foreach($originalPrograms as $oldProgram){
                if(!in_array($oldProgram, $newPrograms)){
                    $oldProgram->removeExercise($exercise);
                }
            }
            foreach($form->get("programs")->getData() as $id){
                $program = $programRepository->find($id);
                if($program){
                    $program->addExercise($exercise);
                }
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();
            $this->addFlash('success', 'Exercise Edited!');
            return $this->redirectToRoute('admin_exercise_index');
        }

        return $this->render('admin/exercise/new.html.twig', [
            'exercise' => $exercise,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id<\d+>}", name="admin_exercise_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Exercise $exercise): Response
    {
        if ($this->isCsrfTokenValid('delete'.$exercise->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($exercise);
            $entityManager->flush();
        }
        $this->addFlash('success', 'Exercise Deleted!');
        return $this->redirectToRoute('admin_exercise_index');
    }
}
