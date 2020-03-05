<?php

namespace App\Controller;

use App\Entity\Exercise;
use App\Form\ExerciseType;
use App\Repository\ExerciseCommentRepository;
use App\Repository\ExerciseRepository;
use App\Repository\HintRepository;
use App\Repository\MasteryLevelRepository;
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
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

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
    public function postExercise(Request $request, HintRepository $hintRepository, PrerequisiteRepository $prerequisiteRepository, ProgramRepository $programRepository, MasteryLevelRepository $masteryLevelRepository, SerializerInterface $serializer, ValidatorInterface $validator): Response
    {

        /*
            {
                "title": "exo test",
                "time": 10,
                "img_path": "image_exo_1.png",
                "description": "description exo 1",
                "score": 10,
                "hint_ids": [6],
                "prerequisite_ids": [11, 12, 1000],
                "program_ids": [6, 7],
                "mastery_level_id": 1
            }
        */

        $jsonString = $request->getContent();

        if (json_decode($jsonString) === null) {
            return $this->json([
                'error' => 'Format de données érroné.'
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $exercise = $serializer->deserialize($jsonString, Exercise::class, 'json');

        dd($exercise);

        $errors = $validator->validate($exercise);
        if (count($errors) !== 0) {
            $jsonErrors = [];
            foreach ($errors as $error) {
                $jsonErrors[] = [
                    'field' => $error->getPropertyPath(),
                    'message' => $error->getMessage(),
                ];
            }
            return $this->json($jsonErrors, Response::HTTP_UNPROCESSABLE_ENTITY);
        }


        $jsonObject = json_decode($request->getContent());
        $masteryLevelId = $jsonObject->mastery_level_id; // id of masteryLevel
        $validationsErrors = [];
        
        if(gettype($masteryLevelId) === "integer"){
            $masteryLevel = $masteryLevelRepository->find($masteryLevelId);
        }
        
        if(!$masteryLevel){
            $validationsErrors[] = [
                'field' => "mastery_level_id",
                'message' => "Ce niveau de difficulté n'est pas defini.",
            ];;
        }

        if (count($validationsErrors) !== 0) {
            return $this->json($validationsErrors, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $exerciseHints = $jsonObject->hint_ids; // id of hint
        $exercisePrerequisites = $jsonObject->prerequisite_ids; // array of ids of prerequisite
        $exercisePrograms = $jsonObject->program_ids; // array of ids of programs

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

        $exercise->setCreatedAt(new \DateTime());

        if(!$exercise->getImgPath()){
            $exercise->setImgPath("exercise_image_default.png");
        }

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
        
        $exercise->setMasteryLevel($masteryLevel);

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
