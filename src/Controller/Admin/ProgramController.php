<?php

namespace App\Controller\Admin;

use App\Entity\Program;
use App\Form\ProgramType;
use App\Repository\ExerciseRepository;
use App\Repository\ProgramRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/program")
 */
class ProgramController extends AbstractController
{
    /**
     * @Route("/", name="admin_program_index", methods={"GET"})
     */
    public function index(ProgramRepository $programRepository): Response
    {
        return $this->render('admin/program/index.html.twig', [
            'programs' => $programRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="admin_program_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $program = new Program();
        $form = $this->createForm(ProgramType::class, $program);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imgFile = $form->get('img_path')->getData();
            if ($imgFile) {
                $safeFilename = 'program-'.$program->getId();
                $newFilename = $safeFilename.'.'.$imgFile->guessExtension();
                try {
                    $imgFile->move(
                        $this->getParameter('program_img_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                    $newFilename = "program_image_default.png"; // if something goes wrong, assign default value
                }

                // updates the 'imgFilename' property to store the PDF file name
                // instead of its contents
                $program->setImgPath($newFilename);
            }else{
                $program->setImgPath("program_image_default.png");
            }
            // identify the most frequent mastery level among exercises related to this program
            $masteryLevelIdList = []; // list of mastery ids
            $masteryLevelList = []; // list of mastery objects

            $programExercises = $form->get("exercises")->getData();
            foreach($programExercises as $exercise){
                if($exercise){ // checking if exercise id, provided from front, exists in bdd
                    $program->addExercise($exercise);
                    $masteryLevelId = $exercise->getMasteryLevel()->getId();
                    $masteryLevelIdList[] = $masteryLevelId;
                    $masteryLevelList[$masteryLevelId] = $exercise->getMasteryLevel(); // storing mastery objects by id for later use
                }
            }
        
            if(!empty($masteryLevelIdList)){ // checking if there were at least 1 bdd match for mastery
                $idFrequencies = array_count_values($masteryLevelIdList); // getting a list with ids as keys and number of id as value
                $mostFrequentMasteryId = $masteryLevelIdList[0]; // assuming that the most frequent id is the first one
                foreach($idFrequencies as $id => $frequencie){
                    if($idFrequencies[$mostFrequentMasteryId] < $frequencie){ // checking if assumption was right, if not updating the id
                        $mostFrequentMasteryId = $id;
                    }
                }
            
                $program->setMasteryLevel($masteryLevelList[$mostFrequentMasteryId]); // pulling mastery object by most frequent id and adding it to program
            }
            // end of mastery level treatment

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($program);
            $entityManager->flush();
            $this->addFlash('success', 'Program Created!');
            return $this->redirectToRoute('admin_program_index');
        }

        return $this->render('admin/program/new.html.twig', [
            'program' => $program,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id<\d+>}", name="admin_program_show", methods={"GET"})
     */
    public function show(Program $program): Response
    {
        return $this->render('admin/program/show.html.twig', [
            'program' => $program,
        ]);
    }

    /**
     * @Route("/{id<\d+>}/edit", name="admin_program_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Program $program): Response
    {
        $form = $this->createForm(ProgramType::class, $program);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imgFile = $form->get('img_path')->getData();
            if ($imgFile) {
                $safeFilename = 'program-'.$program->getId();
                $newFilename = $safeFilename.'.'.$imgFile->guessExtension();
                try {
                    $imgFile->move(
                        $this->getParameter('program_img_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                    $newFilename = "program_image_default.png"; // if something goes wrong, assign default value
                }
                // updates the 'imgFilename' property to store the PDF file name
                // instead of its contents
                $program->setImgPath($newFilename);
            }
            // identify the most frequent mastery level among exercises related to this program
            $masteryLevelIdList = []; // list of mastery ids
            $masteryLevelList = []; // list of mastery objects

            $programExercises = $form->get("exercises")->getData();
            foreach($programExercises as $exercise){
                if($exercise){ // checking if exercise id, provided from front, exists in bdd
                    $program->addExercise($exercise);
                    $masteryLevelId = $exercise->getMasteryLevel()->getId();
                    $masteryLevelIdList[] = $masteryLevelId;
                    $masteryLevelList[$masteryLevelId] = $exercise->getMasteryLevel(); // storing mastery objects by id for later use
                }
            }
        
            if(!empty($masteryLevelIdList)){ // checking if there were at least 1 bdd match for mastery
                $idFrequencies = array_count_values($masteryLevelIdList); // getting a list with ids as keys and number of id as value
                $mostFrequentMasteryId = $masteryLevelIdList[0]; // assuming that the most frequent id is the first one
                foreach($idFrequencies as $id => $frequencie){
                    if($idFrequencies[$mostFrequentMasteryId] < $frequencie){ // checking if assumption was right, if not updating the id
                        $mostFrequentMasteryId = $id;
                    }
                }
            
                $program->setMasteryLevel($masteryLevelList[$mostFrequentMasteryId]); // pulling mastery object by most frequent id and adding it to program
            }
            // end of mastery level treatment
            
            $program->setUpdatedAt(new \DateTime());

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();
            $this->addFlash('success', 'Program Edited!');
            return $this->redirectToRoute('admin_program_index');
        }

        return $this->render('admin/program/edit.html.twig', [
            'program' => $program,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id<\d+>}", name="admin_program_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Program $program): Response
    {
        if ($this->isCsrfTokenValid('delete'.$program->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($program);
            $entityManager->flush();
        }
        $this->addFlash('success', 'Program Deleted!');
        return $this->redirectToRoute('admin_program_index');
    }
}
