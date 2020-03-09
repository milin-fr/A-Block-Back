<?php

namespace App\Controller;

use App\Entity\ExerciseComment;
use App\Repository\ExerciseCommentRepository;
use App\Repository\ExerciseRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/api/exercise-comment")
 */
class ExerciseCommentController extends AbstractController
{
    /**
     * @Route("/", name="exercise_comment_list", methods={"GET"})
     */
    public function getexerciseComments(ExerciseCommentRepository $exerciseCommentRepository): Response
    {
        $exerciseComments = $exerciseCommentRepository->findAll();

        return $this->json($exerciseComments, Response::HTTP_OK, [], ['groups' => 'exercise_comment']);
    }

    /**
     * @Route("/", name="exercise_comment_new", methods={"POST"})
     */
    public function postExerciseComment(Request $request, ExerciseRepository $exerciseRepository, UserRepository $userRepository): Response
    {

        /*
            {
                "text": string,
                "user_id": integer,
                "exercise_id": integer
            }
        */

        $keyList = ["text",
                    "user_id",
                    "exercise_id"];

        $validationsErrors = [];

        $jsonContent = $request->getContent();
        if (json_decode($jsonContent) === null) {
            return $this->json([
                'error' => 'Format de données érroné.'
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        // get payload content and convert it to object, so we can acess it's properties
        $contentObject = json_decode($request->getContent());
        $contentArray = get_object_vars($contentObject);

        foreach($keyList as $key){
            if(!array_key_exists($key, $contentArray)){
                $validationsErrors[] = [
                                        $key => "Requiered, but not provided"
                                        ];
            }
        }

        if (count($validationsErrors) !== 0) {
            return $this->json($validationsErrors, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $exerciseCommentText = $contentObject->text;
        $exerciseCommentUserId = $contentObject->user_id;
        $exerciseCommentExerciseId = $contentObject->exercise_id;


        // payload validation
        
        if($exerciseCommentText === ""){
            $validationsErrors[] = "text, blank";
        }

        if(strlen($exerciseCommentText) > 999){
            $validationsErrors[] = "text, length, max, 999";
        }

        if(gettype($exerciseCommentUserId) !== "integer"){
            $validationsErrors[] = "user_id, not integer";
        }

        if(gettype($exerciseCommentExerciseId) !== "integer"){
            $validationsErrors[] = "exercise_id, not integer";
        }

        if (count($validationsErrors) !== 0) {
            return $this->json($validationsErrors, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $exercise = $exerciseRepository->find($exerciseCommentExerciseId);

        if(!$exercise){
            $validationsErrors[] = "exercise, not found";
        }

        $user = $userRepository->find($exerciseCommentUserId);

        if(!$user){
            $validationsErrors[] = "user, not found";
        }

        if (count($validationsErrors) !== 0) {
            return $this->json($validationsErrors, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $exerciseComment = new ExerciseComment();
        $exerciseComment->setCreatedAt(new \DateTime());
        $exerciseComment->setText($exerciseCommentText);
        $exerciseComment->setExercise($exercise);
        $exerciseComment->setUser($user);

        $em = $this->getDoctrine()->getManager();
        $em->persist($exerciseComment);
        $em->flush();
        return $this->json($exerciseComment, Response::HTTP_CREATED, [], ['groups' => 'exercise_comment']);
    }

    /**
     * @Route("/{id}", name="exercise_comment_show", methods={"GET"})
     */
    public function getExerciseComment($id, exerciseCommentRepository $exerciseCommentRepository): Response
    {
        $exerciseComment = $exerciseCommentRepository->find($id);
        if (!$exerciseComment) {
            
            return new JsonResponse(['error' => '404 not found.'], 404);
        }
        return $this->json($exerciseComment, Response::HTTP_OK, [], ['groups' => 'exercise_comment']);
    }

    /**
     * @Route("/{id}", name="exercise_comment_edit", methods={"PUT"})
     */
    public function putExerciseComment(Request $request, $id, exerciseCommentRepository $exerciseCommentRepository): Response
    {
        /*
            {
                "text": string
            }
        */

        $exerciseComment = $exerciseCommentRepository->find($id);
        
        $keyList = ["text"];

        $validationsErrors = [];

        $jsonContent = $request->getContent();
        if (json_decode($jsonContent) === null) {
            return $this->json([
                'error' => 'Format de données érroné.'
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        // get payload content and convert it to object, so we can acess it's properties
        $contentObject = json_decode($request->getContent());
        $contentArray = get_object_vars($contentObject);

        foreach($keyList as $key){
            if(!array_key_exists($key, $contentArray)){
                $validationsErrors[] = [
                                        $key => "Requiered, but not provided"
                                        ];
            }
        }

        if (count($validationsErrors) !== 0) {
            return $this->json($validationsErrors, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $exerciseCommentText = $contentObject->text;


        // payload validation

        if($exerciseCommentText === ""){
            $validationsErrors[] = "text, blank";
        }

        if(strlen($exerciseCommentText) > 999){
            $validationsErrors[] = "text, length, max, 999";
        }

        if (count($validationsErrors) !== 0) {
            return $this->json($validationsErrors, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $exerciseComment->setText($exerciseCommentText);
        $exerciseComment->setUpdatedAt(new \DateTime());

        $em = $this->getDoctrine()->getManager();
        $em->flush();
        return $this->json($exerciseComment, Response::HTTP_OK, [], ['groups' => 'exercise_comment']);
    }

    /**
     * @Route("/{id}", name="exercise_comment_delete", methods={"DELETE"})
     */
    public function deleteExerciseComment(Request $request, $id, exerciseCommentRepository $exerciseCommentRepository): Response
    {
        $exerciseComment = $exerciseCommentRepository->find($id);
        if (!$exerciseComment) {
            
            return new JsonResponse(['error' => '404 not found.'], 404);
        }
        $em = $this->getDoctrine()->getManager();
        $em->remove($exerciseComment);
        $em->flush();

        // return $this->json([], Response::HTTP_OK);

        $exerciseComments = $exerciseCommentRepository->findAll();
        return $this->json($exerciseComments, Response::HTTP_OK, [], ['groups' => 'exercise_comment']);
    }

}
