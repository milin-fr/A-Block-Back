<?php

namespace App\Controller;

use Exception;
use App\Entity\User;
use App\Repository\UserRepository;
use Namshi\JOSE\Signer\OpenSSL\None;
use App\Repository\ProgramRepository;
use App\Repository\ExerciseRepository;
use App\Repository\MasteryLevelRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/api/user")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/", name="user_list", methods={"GET"})
     * @IsGranted("ROLE_ADMIN", statusCode=403, message="Access Denied")
     */
    public function getAblocUsers(UserRepository $userRepository): Response
    {
        $ablocUsers = $userRepository->findAll();

        return $this->json($ablocUsers, Response::HTTP_OK, [], ['groups' => 'abloc_user']);
    }

    /**
     * @Route("/", name="user_new", methods={"POST"})
     */
    public function postAblocUser(Request $request, MasteryLevelRepository $masteryLevelRepository, UserRepository $userRepository, UserPasswordEncoderInterface $passwordEncoder): Response
    {

        /*
            {
                "email": "user@mail.com",
                "password": 1234,
                "account_name": "top velu",
                "img_path": "image_profile_1.png",
                "available_time": 10,
                "mastery_level": 1
            }
        */

        // start of payload validation
        $keyList = [
                    "email",
                    "password"
                    ];

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
        // end of payload validation


        // get payload content and convert it to object, so we can acess it's properties

        // values validation

        $userEmail = $contentObject->email;
        $userPassword = $contentObject->password;
        try{
            $userAccountName = $contentObject->account_name;
        } catch(Exception $e) {
            $userAccountName = "";
        }
        try{
            $userImgPath = $contentObject->img_path;
        } catch(Exception $e) {
            $userImgPath = "";
        }
        try{
            $userAvailableTime = $contentObject->available_time;
        } catch(Exception $e) {
            $userAvailableTime = "";
        }
        try{
            $userMasteryLevel = $contentObject->mastery_level;
        } catch(Exception $e) {
            $masteryLevels = $masteryLevelRepository->findAll();
            if(empty($masteryLevels)){
                $validationsErrors[] = [
                    "mastery_level_id" => "no mastery levels in bdd"
                    ];
            }else{
            $masteryLevel = $masteryLevels[0];
            $userMasteryLevel = $masteryLevel->getId();
            }
        }


        $validationsErrors = [];
        
        if(!filter_var($userEmail, FILTER_VALIDATE_EMAIL)){
            $validationsErrors[] = "email, notvalid";
        }

        $users = $userRepository->findAll();
        $emails = [];
        foreach($users as $user){
            $emails[] = $user->getEmail();
        }

        if(in_array($userEmail, $emails)){
            $validationsErrors[] = "email, exists"; // peut se faire avec validateur * @Assert\Unique
        }

        if(strlen($userPassword) > 32){
            $validationsErrors[] = "password, length, max, 32";
        }

        if(strlen($userPassword) < 3){
            $validationsErrors[] = "password, length, min, 3";
        }

        if(strlen($userAccountName) > 64){
            $validationsErrors[] = "accountName, length, max, 32";
        }

        if(strlen($userImgPath) > 64){
            $validationsErrors[] = "imgPath, length, max, 64";
        }

        if(gettype($userMasteryLevel) !== "integer"){
            $validationsErrors[] = "masteryLevel, not integer";
        }

        if(gettype($userMasteryLevel) === "integer"){
            $masteryLevel = $masteryLevelRepository->find($userMasteryLevel);
            if(!$masteryLevel){
                $validationsErrors[] = "masteryLevel, no match in bdd";
            }
        }

        if (count($validationsErrors) !== 0) {
            return $this->json($validationsErrors, Response::HTTP_UNPROCESSABLE_ENTITY);
        }


        $user = new User();
        $user->setEmail($userEmail);
        $user->setPassword($passwordEncoder->encodePassword(
            $user,
            $userPassword
        ));

        if($userAccountName === ""){
            $userAccountName = "Anonyme";
        }

        $user->setAccountName($userAccountName);
        if($userImgPath === ""){
            $userImgPath = "default_user.png";
        }
        $user->setImgPath($userImgPath);
        if($userAvailableTime === ""){
            $userAvailableTime = 0;
        }
        
        if($userAvailableTime < 0){
            $userAvailableTime = 0;
        }

        if($userAvailableTime > 999){
            $userAvailableTime = 999;
        }
        $user->setAvailableTime(($userAvailableTime));
        $user->setScore(0);
        $user->setCreatedAt(new \DateTime());

        $user->setMasteryLevel($masteryLevel);
        
        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();
        return $this->json($user, Response::HTTP_CREATED, [], ['groups' => 'abloc_user']);
    }

    /**
     * @Route("/{id<\d+>}", name="user_show", methods={"GET"})
     */
    public function getAblocUser($id, UserRepository $userRepository): Response
    {
        $ablocUser = $userRepository->find($id);

         // L'User est-il le même ?
         $user = $this->getUser();
         if ($user !== $ablocUser) {
            if(!in_array("ROLE_ADMIN", $user->getRoles())){
                throw $this->createAccessDeniedException('Non autorisé.');
            }
         }

        if (!$ablocUser) {
            
            return new JsonResponse(['error' => '404 not found.'], 404);
        }
        return $this->json($ablocUser, Response::HTTP_OK, [], ['groups' => 'abloc_user']);
    }

    /**
     * @Route("/{id<\d+>}", name="user_edit", methods={"PUT"})
     */
    public function putAblocUser(Request $request, $id, UserRepository $userRepository, MasteryLevelRepository $masteryLevelRepository, UserPasswordEncoderInterface $passwordEncoder, ProgramRepository $programRepository, ExerciseRepository $exerciseRepository): Response
    {
        /*
            {
                "email": "user@mail.com",
                "password": 1234,
                "account_name": "top velu",
                "img_path": "image_profile_1.png",
                "available_time": 10,
                "mastery_level": 1,
                "program_bookmark_ids": [6, 7],
                "exercise_bookmark_ids": [11, 12, 1000],
                "active_program": 1
            }
        */
        
        $ablocUser = $userRepository->find($id);

         // L'User est-il le même ?
         $user = $this->getUser();
         if ($user !== $ablocUser) {
            if(!in_array("ROLE_ADMIN", $user->getRoles())){
                throw $this->createAccessDeniedException('Non autorisé.');
            }
         }

        
        if (!$ablocUser) {
            
            return new JsonResponse(['error' => '404 not found.'], 404);
        }

        // start of payload validation
        $validationsErrors = [];

        $jsonContent = $request->getContent();
        if (json_decode($jsonContent) === null) {
            return $this->json([
                'error' => 'Format de données érroné.'
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        // get payload content and convert it to object, so we can acess it's properties
        $contentObject = json_decode($request->getContent());
        // end of payload validation

        // get payload content and convert it to object, so we can acess it's properties

        // values validation

        try{
            $userEmail = $contentObject->email;
        } catch(Exception $e) {
            $userEmail = $ablocUser->getEmail();
        }
        try{
            $userPassword = $contentObject->password;
            if(strlen($userPassword) > 32){
                $validationsErrors[] = "password, length, max, 32";
            }
    
            if(strlen($userPassword) < 3){
                $validationsErrors[] = "password, length, min, 3";
            }

            $ablocUser->setPassword($passwordEncoder->encodePassword(
                $ablocUser,
                $userPassword
            ));
        } catch(Exception $e) {
            // no action taken on password if new password was not sent
        }
        try{
            $userAccountName = $contentObject->account_name;
        } catch(Exception $e) {
            $userAccountName = $ablocUser->getAccountName();
        }
        try{
            $userImgPath = $contentObject->img_path;
        } catch(Exception $e) {
            $userImgPath = $ablocUser->getImgPath();
        }
        try{
            $userAvailableTime = $contentObject->available_time;
        } catch(Exception $e) {
            $userAvailableTime = $ablocUser->getAvailableTime();
        }
        try{
            $userMasteryLevel = $contentObject->mastery_level;
        } catch(Exception $e) {
            $userMasteryLevel = $ablocUser->getMasteryLevel()->getId();
        }
        try {
            $userProgramBookmarks = $contentObject->program_bookmark_ids;
            $currentProgramBookmarks = $ablocUser->getProgramBookmarks();
            foreach($currentProgramBookmarks as $programBookmark){
                $ablocUser->removeProgramBookmark($programBookmark);
            }
        } catch(Exception $e) {
            $userProgramBookmarks = [];
            $programBookmarks = $ablocUser->getProgramBookmarks();
            foreach($programBookmarks as $programBookmark){
                $userProgramBookmarks[] = $programBookmark->getId();
            }
        }
        try {
            $userExerciseBookmarks = $contentObject->exercise_bookmark_ids;
            $currentExerciseBookmarks = $ablocUser->getExerciseBookmarks();
            foreach($currentExerciseBookmarks as $exerciseBookmark){
                $ablocUser->removeExerciseBookmark($exerciseBookmark);
            }
        } catch(Exception $e) {
            $userExerciseBookmarks = [];
            $exerciseBookmarks = $ablocUser->getExerciseBookmarks();
            foreach($exerciseBookmarks as $exerciseBookmark){
                $userExerciseBookmarks[] = $exerciseBookmark->getId();
            }
        }
        try {
            $userActiveProgram = $contentObject->active_program;
        } catch(Exception $e) {
            $activeProgram = $ablocUser->getActiveProgram();
            if($activeProgram){
                $userActiveProgram = $ablocUser->getActiveProgram()->getId();
            }
            else{
                $userActiveProgram = "";
            }
        }

        if(gettype($userProgramBookmarks) !== "array"){
            $userProgramBookmarks = [];
        }

        foreach($userProgramBookmarks as $key => $id){
            if(gettype($id) !== "integer"){
                $userProgramBookmarks[$key] = "";
            }
        }

        if(gettype($userExerciseBookmarks) !== "array"){
            $userExerciseBookmarks = [];
        }

        foreach($userExerciseBookmarks as $key => $id){
            if(gettype($id) !== "integer"){
                $userExerciseBookmarks[$key] = "";
            }
        }
        
        if(!filter_var($userEmail, FILTER_VALIDATE_EMAIL)){
            $validationsErrors[] = "email, notvalid";
        }

        $users = $userRepository->findAll();
        $emails = [];
        foreach($users as $user){
            $emails[] = $user->getEmail();
        }

        if(!($ablocUser->getEmail() === $userEmail)){
            if(in_array($userEmail, $emails)){
                $validationsErrors[] = "email, exists"; // peut se faire avec validateur * @Assert\Unique
            }
        }

        if(strlen($userAccountName) > 64){
            $validationsErrors[] = "accountName, length, max, 32";
        }

        if(strlen($userImgPath) > 64){
            $validationsErrors[] = "imgPath, length, max, 64";
        }

        if(gettype($userMasteryLevel) === "integer"){
            $masteryLevel = $masteryLevelRepository->find($userMasteryLevel);
            if(!$masteryLevel){
                $validationsErrors[] = "masteryLevel, no match in bdd";
            }
        }

        if(gettype($userActiveProgram) !== "integer" && $userActiveProgram !== null){
            $userActiveProgram = "";
        }

        if(gettype($userActiveProgram) === "integer"){
            $activeProgram = $programRepository->find($userActiveProgram);
            if(!$activeProgram){
                $validationsErrors[] = "program, no match in bdd";
            }
        }

        if (count($validationsErrors) !== 0) {
            return $this->json($validationsErrors, Response::HTTP_UNPROCESSABLE_ENTITY);
        }


        $ablocUser->setEmail($userEmail);

        if($userAccountName === ""){
            $userAccountName = "Anonyme";
        }

        $ablocUser->setAccountName($userAccountName);
        
        if($userImgPath === ""){
            $userImgPath = "default_user.png";
        }
        $ablocUser->setImgPath($userImgPath);
        if($userAvailableTime === ""){
            $userAvailableTime = 0;
        }
        
        if($userAvailableTime < 0){
            $userAvailableTime = 0;
        }

        if($userAvailableTime > 999){
            $userAvailableTime = 999;
        }
        $ablocUser->setAvailableTime($userAvailableTime);
        $ablocUser->setScore(0);
        $ablocUser->setUpdatedAt(new \DateTime());

        $ablocUser->setMasteryLevel($masteryLevel);
        foreach($userProgramBookmarks as $id){
            $program = $programRepository->find($id);
            if($program){
                $ablocUser->addProgramBookmark($program);
            }
        }
        foreach($userExerciseBookmarks as $id){
            $exercise = $exerciseRepository->find($id);
            if($exercise){
                $ablocUser->addExerciseBookmark($exercise);
            }
        }

        $activeProgram = $programRepository->find($userActiveProgram);
        if($activeProgram){
            $followedProgramIds = [];
            $followedPograms = $ablocUser->getFollowedPrograms();
            foreach($followedPograms as $followedPogramId){
                $followedProgramIds[] = $followedPogramId->getId();
            }
            if(!in_array($userActiveProgram, $followedProgramIds)){
                $ablocUser->addFollowedProgram($activeProgram);
            }
            $ablocUser->setActiveProgram($activeProgram);
        }

        $em = $this->getDoctrine()->getManager();
        $em->flush();
        return $this->json($ablocUser, Response::HTTP_OK, [], ['groups' => 'abloc_user']);
    }

    /**
     * @Route("/{id<\d+>}", name="user_delete", methods={"DELETE"})
     */
    public function deleteAblocUser(Request $request, $id, UserRepository $userRepository): Response
    {
        $ablocUser = $userRepository->find($id);

        // L'User est-il le même ?
        $user = $this->getUser();
        if ($user !== $ablocUser) {
            if(!in_array("ROLE_ADMIN", $user->getRoles())){
                throw $this->createAccessDeniedException('Non autorisé.');
            }
        }
        
        if (!$ablocUser) {
            return new JsonResponse(['error' => '404 not found.'], 404);
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($ablocUser);
        $em->flush();

        $responseJson = ["DELETED"];
        return $this->json($responseJson, Response::HTTP_OK, [], []);
    }

    /**
     * @Route("/profile", name="profile", methods={"GET"})
     */
    public function getProfile(): Response
    {
        $ablocUser = $this->getUser();

        if (!$ablocUser) {
            return new JsonResponse(['error' => '404 not found.'], 404);
        }

        return $this->json($ablocUser, Response::HTTP_OK, [], ['groups' => 'abloc_user']);
    }
}