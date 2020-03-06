<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Entity\AccessLevel;
use App\Repository\UserRepository;
use App\Repository\AccessLevelRepository;
use App\Repository\MasteryLevelRepository;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/api/user")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/", name="user_list", methods={"GET"})
     */
    public function getAblocUsers(UserRepository $userRepository): Response
    {
        $ablocUsers = $userRepository->findAll();

        return $this->json($ablocUsers, Response::HTTP_OK, [], ['groups' => 'abloc_user']);
    }

    /**
     * @Route("/", name="user_new", methods={"POST"})
     */
    public function postAblocUser(Request $request, AccessLevelRepository $accessLevelRepository, MasteryLevelRepository $masteryLevelRepository, UserRepository $userRepository): Response
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
        $keyList = ["email", "password", "account_name", "img_path", "available_time", "mastery_level"];

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
        $userAccountName = $contentObject->account_name;
        $userImgPath = $contentObject->img_path;
        $userAvailableTime = $contentObject->available_time;
        $userMasteryLevel = $contentObject->mastery_level;


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

        if(strlen($userAccountName) < 3){
            $validationsErrors[] = "accountName, length, min, 3";
        }

        if(strlen($userImgPath) > 64){
            $validationsErrors[] = "imgPath, length, max, 64";
        }

        if($userAvailableTime < 0){
            $validationsErrors[] = "availableTime, value, min, 0";
        }

        if($userAvailableTime > 999){
            $validationsErrors[] = "availableTime, value, max, 999";
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
        $user->setPassword($userPassword); // a revoir
        $user->setAccountName($userAccountName);
        if($userImgPath === ""){
            $userImgPath = "default_user.png";
        }
        $user->setImgPath($userImgPath);
        $user->setAvailableTime(($userAvailableTime));
        $user->setScore(0);
        $user->setCreatedAt(new \DateTime());
        
        $accesLevel = $accessLevelRepository->findAll();
        $user->setAccessLevel($accesLevel[0]); // a revoir
        
        $user->setMasteryLevel($masteryLevel);
        
        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();
        return $this->redirectToRoute('user_show', ['id' => $user->getId()], Response::HTTP_CREATED);
    }

    /**
     * @Route("/{id}", name="user_show", methods={"GET"})
     */
    public function getAblocUser($id, UserRepository $userRepository): Response
    {
        $ablocUser = $userRepository->find($id);
        if (!$ablocUser) {
            
            return new JsonResponse(['error' => '404 not found.'], 404);
        }
        return $this->json($ablocUser, Response::HTTP_OK, [], ['groups' => 'abloc_user']);
    }

    /**
     * @Route("/{id}", name="user_edit", methods={"PUT"})
     */
    public function putAblocUser(Request $request, User $user): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('user_index');
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="user_delete", methods={"DELETE"})
     */
    public function deleteAblocUser(Request $request, User $user): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('user_index');
    }
}
