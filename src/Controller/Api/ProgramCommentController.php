<?php

namespace App\Controller\Api;

use App\Entity\ProgramComment;
use App\Repository\ProgramCommentRepository;
use App\Repository\ProgramRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/api/program-comment")
 */
class ProgramCommentController extends AbstractController
{
    /**
     * @Route("/", name="program_comment_list", methods={"GET"})
     */
    public function getProgramComments(ProgramCommentRepository $programCommentRepository): Response
    {
        $programComments = $programCommentRepository->findAll();

        return $this->json($programComments, Response::HTTP_OK, [], ['groups' => 'program_comment']);
    }

    /**
     * @Route("/", name="program_comment_new", methods={"POST"})
     */
    public function postProgramComment(Request $request, ProgramRepository $programRepository, UserRepository $userRepository): Response
    {

        /*
            {
                "text": string,
                "user_id": integer,
                "Program_id": integer
            }
        */

        $keyList = ["text",
                    "user_id",
                    "Program_id"];

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

        $programCommentText = $contentObject->text;
        $programCommentUserId = $contentObject->user_id;
        $programCommentProgramId = $contentObject->Program_id;


        // payload validation
        
        if($programCommentText === ""){
            $validationsErrors[] = "text, blank";
        }

        if(strlen($programCommentText) > 999){
            $validationsErrors[] = "text, length, max, 999";
        }

        if(gettype($programCommentUserId) !== "integer"){
            $validationsErrors[] = "user_id, not integer";
        }

        if(gettype($programCommentProgramId) !== "integer"){
            $validationsErrors[] = "Program_id, not integer";
        }

        if (count($validationsErrors) !== 0) {
            return $this->json($validationsErrors, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $program = $programRepository->find($programCommentProgramId);

        if(!$program){
            $validationsErrors[] = "Program, not found";
        }

        $user = $userRepository->find($programCommentUserId);

        if(!$user){
            $validationsErrors[] = "user, not found";
        }

        if (count($validationsErrors) !== 0) {
            return $this->json($validationsErrors, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $programComment = new ProgramComment();
        $programComment->setCreatedAt(new \DateTime());
        $programComment->setText($programCommentText);
        $programComment->setProgram($program);
        $programComment->setUser($user);

        $em = $this->getDoctrine()->getManager();
        $em->persist($programComment);
        $em->flush();
        return $this->json($programComment, Response::HTTP_CREATED, [], ['groups' => 'program_comment']);
    }

    /**
     * @Route("/{id<\d+>}", name="program_comment_show", methods={"GET"})
     */
    public function getProgramComment($id, ProgramCommentRepository $programCommentRepository): Response
    {
        $programComment = $programCommentRepository->find($id);
        if (!$programComment) {
            
            return new JsonResponse(['error' => '404 not found.'], 404);
        }
        return $this->json($programComment, Response::HTTP_OK, [], ['groups' => 'program_comment']);
    }

    /**
     * @Route("/{id<\d+>}", name="program_comment_edit", methods={"PUT"})
     */
    public function putProgramComment(Request $request, $id, ProgramCommentRepository $programCommentRepository): Response
    {
        /*
            {
                "text": string
            }
        */

        $programComment = $programCommentRepository->find($id);

         // L'User est-il le même ?
         $user = $this->getUser();

        if ($user !== $programComment->getUser()) {
            if(!in_array("ROLE_MODERATOR", $user->getRoles())){
                throw $this->createAccessDeniedException('Non autorisé.');
            }
         }
        
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

        $programCommentText = $contentObject->text;


        // payload validation

        if($programCommentText === ""){
            $validationsErrors[] = "text, blank";
        }

        if(strlen($programCommentText) > 999){
            $validationsErrors[] = "text, length, max, 999";
        }

        if (count($validationsErrors) !== 0) {
            return $this->json($validationsErrors, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $programComment->setText($programCommentText);
        $programComment->setUpdatedAt(new \DateTime());

        $em = $this->getDoctrine()->getManager();
        $em->flush();
        return $this->json($programComment, Response::HTTP_OK, [], ['groups' => 'program_comment']);
    }

    /**
     * @Route("/{id<\d+>}", name="program_comment_delete", methods={"DELETE"})
     */
    public function deleteProgramComment(Request $request, $id, ProgramCommentRepository $programCommentRepository): Response
    {
        $programComment = $programCommentRepository->find($id);

         // L'User est-il le même ?
         $user = $this->getUser();

        if ($user !== $programComment->getUser()) {
            if(!in_array("ROLE_MODERATOR", $user->getRoles())){
                throw $this->createAccessDeniedException('Non autorisé.');
            }
         }

        if (!$programComment) {
            
            return new JsonResponse(['error' => '404 not found.'], 404);
        }
        $em = $this->getDoctrine()->getManager();
        $em->remove($programComment);
        $em->flush();

        // return $this->json([], Response::HTTP_OK);

        $programComments = $programCommentRepository->findAll();
        return $this->json($programComments, Response::HTTP_OK, [], ['groups' => 'program_comment']);
    }

}
