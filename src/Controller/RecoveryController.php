<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class RecoveryController extends AbstractController
{
/**
     * @Route("/password-recovery", name="password_recovery", methods={"GET","POST"})
     */
    public function passwordRecovery(Request $request, UserPasswordEncoderInterface $encoder): Response
    {
        $user = new User();
        $form = $this->createForm(UserNewType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $user->setImgPath("user_image_default.png");
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            if($user->getAccountName() == ""){
                $user->setAccountName("Anonyme");
            }
            if($user->getAvailableTime() == ""){
                $user->setAvailableTime(0);
            }
            if($user->getScore() == ""){
                $user->setScore(0);
            }
            $entityManager->flush();
            $imgFile = $form->get('img_path')->getData();
            if ($imgFile) {
                $safeFilename = 'user-'.$user->getId();
                $newFilename = $safeFilename.'.'.($imgFile->guessExtension());

                // Move the file to the directory where brochures are stored
                try {
                    $imgFile->move(
                        $this->getParameter('user_img_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                    $newFilename = "user_image_default.png"; // if something goes wrong, assign default value
                }

                // updates the 'imgFilename' property to store the PDF file name
                // instead of its contents
                $user->setImgPath($newFilename);
            }
            $entityManager->flush();
            $this->addFlash('success', 'User Created!');
            return $this->redirectToRoute('admin_user_index');
        }

        return $this->render('admin/user/new.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }
}
