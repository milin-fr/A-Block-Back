<?php

namespace App\Controller;

use App\Entity\Exercise;
use App\Form\ExerciseType;
use App\Repository\ExerciseCommentRepository;
use App\Repository\ExerciseRepository;
use App\Repository\HintRepository;
use App\Repository\PrerequisiteRepository;
use App\Repository\ProgramCommentRepository;
use App\Repository\ProgramRepository;
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
 * @Route("/api/exercise")
 */
class ExerciseController extends AbstractController
{
    /**
     * @Route("/", name="exercise_list", methods={"GET"})
     */
    public function getExercises(ExerciseRepository $exerciseRepository): Response
    {
        $exercises = $exerciseRepository->findAll();

        return $this->json($exercises, Response::HTTP_OK, [], ['groups' => 'exercise']);
    }

    /**
     * @Route("/", name="exercise_new", methods={"POST"})
     */
    public function postExercise(Request $request, HintRepository $hintRepository, PrerequisiteRepository $prerequisiteRepository, ProgramRepository $programRepository): Response
    {

        /*
            {
                "title": "exo test",
                "time": 10,
                "imgPath": "image_exo_1.png",
                "description": "description exo 1",
                "score": 10,
                "hints": [6],
                "prerequisites": [11, 12, 1000],
                "programs": [6, 7]
            }
        */

        // get payload content and convert it to object, so we can acess it's properties
        $contentObject = json_decode($request->getContent());
        $exerciseTitle = $contentObject->title;
        $exerciseTime = $contentObject->time; // type integer
        $exerciseImgPath = $contentObject->imgPath;
        $exerciseDescription = $contentObject->description;
        $exerciseScore = $contentObject->score;
        $exerciseHints = $contentObject->hints; // id of hint
        $exercisePrerequisites = $contentObject->prerequisites; // array of ids of prerequisite
        $exercisePrograms = $contentObject->programs; // array of ids of programs


        if($exerciseTime === ""){
            $exerciseTime = 0;
        }

        if($exerciseScore === ""){
            $exerciseScore = 0;
        }

        if(gettype($exerciseHints) !== "array"){
            $exerciseHints = [];
        }

        if(gettype($exercisePrerequisites) !== "array"){
            $exercisePrerequisites = [];
        }

        if(gettype($exercisePrograms) !== "array"){
            $exercisePrograms = [];
        }

        foreach($exerciseHints as $key => $id){
            if(gettype($id) !== "integer"){
                $exerciseHints[$key] = "";
            }
        }

        foreach($exercisePrerequisites as $key => $id){
            if(gettype($id) !== "integer"){
                $exercisePrerequisites[$key] = "";
            }
        }

        foreach($exercisePrograms as $key => $id){
            if(gettype($id) !== "integer"){
                $exercisePrograms[$key] = "";
            }
        }
        
        // payload validation
        $validationsErrors = [];
        
        if($exerciseTitle === ""){
            $validationsErrors[] = "title, blank";
        }

        if(strlen($exerciseTitle) > 64){
            $validationsErrors[] = "title, length, max, 64";
        }

        if(gettype($exerciseTime) !== "integer"){
            $validationsErrors[] = "time, not integer";
        }

        if($exerciseTime < 0){
            $validationsErrors[] = "time, value, min, 0";
        }

        if($exerciseTime > 999){
            $validationsErrors[] = "time, value, max, 999";
        }

        if(strlen($exerciseImgPath) > 64){
            $validationsErrors[] = "imgPath, length, max, 64";
        }

        if($exerciseDescription === ""){
            $validationsErrors[] = "description, blank";
        }

        if(strlen($exerciseDescription) > 999){
            $validationsErrors[] = "description, length, max, 999";
        }

        if(gettype($exerciseScore) !== "integer"){
            $validationsErrors[] = "score, not integer";
        }

        if($exerciseScore < 0){
            $validationsErrors[] = "score, value, min, 0";
        }

        if($exerciseScore > 9999){
            $validationsErrors[] = "score, value, max, 9999";
        }

        if (count($validationsErrors) !== 0) {
            return $this->json($validationsErrors, Response::HTTP_UNPROCESSABLE_ENTITY);
        }


        $exercise = new Exercise();
        $exercise->setTitle($exerciseTitle);
        $exercise->setCreatedAt(new \DateTime());
        $exercise->setTime($exerciseTime);

        if($exerciseImgPath === ""){
            $exerciseImgPath = "default_exercise.png";
        }

        $exercise->setImgPath($exerciseImgPath);
        $exercise->setDescription($exerciseDescription);
        $exercise->setScore($exerciseScore);

        foreach($exerciseHints as $id){
            $hint = $hintRepository->find($id);
            if($hint){
                $exercise->addHint($hint);
            }
        }

        foreach($exercisePrerequisites as $id){
            $prerequisite = $prerequisiteRepository->find($id);
            if($prerequisite){
                $exercise->addprerequisite($prerequisite);
            }
        }

        foreach($exercisePrograms as $id){
            $program = $programRepository->find($id);
            if($program){
                $exercise->addProgram($program);
            }
        }
        

        $em = $this->getDoctrine()->getManager();
        $em->persist($exercise);
        $em->flush();
        return $this->redirectToRoute('exercise_show', ['id' => $exercise->getId()], Response::HTTP_CREATED);
    }

    /**
     * @Route("/{id}", name="exercise_show", methods={"GET"})
     */
    public function getExercise($id, ExerciseRepository $exerciseRepository): Response
    {
        $exercise = $exerciseRepository->find($id);
        if (!$exercise) {
            
            return new JsonResponse(['error' => '404 not found.'], 404);
        }
        return $this->json($exercise, Response::HTTP_OK, [], ['groups' => 'exercise']);
    }

    /**
     * @Route("/{id}/edit", name="exercise_edit", methods={"PUT"})
     */
    public function putExercise(Request $request, Exercise $exercise): Response
    {
        $form = $this->createForm(ExerciseType::class, $exercise);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('exercise_index');
        }

        return $this->render('exercise/edit.html.twig', [
            'exercise' => $exercise,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="exercise_delete", methods={"DELETE"})
     */
    public function deleteExercise(Request $request, Exercise $exercise): Response
    {
        if ($this->isCsrfTokenValid('delete'.$exercise->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($exercise);
            $entityManager->flush();
        }

        return $this->redirectToRoute('exercise_index');
    }
}
