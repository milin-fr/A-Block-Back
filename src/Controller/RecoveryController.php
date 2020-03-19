<?php

namespace App\Controller;

use App\Form\PasswordRecoveryType;
use App\Form\UserNewType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RecoveryController extends AbstractController
{
/**
     * @Route("/password-recovery", name="password_recovery", methods={"GET","POST"})
     */
    public function passwordRecovery(Request $request, UserPasswordEncoderInterface $encoder, UserRepository $userRepository, \Swift_Mailer $mailer): Response
    {
        $defaultData = ['email' => ''];
        $form = $this->createFormBuilder($defaultData)
        ->add('email', EmailType::class)
        ->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $email = $form->get('email')->getData();
            $ablocUser = $userRepository->findOneBy(["email" => $email]);
            
            if(!$ablocUser){ // user was not found in bdd
                $this->addFlash('warning', 'Une erreur est survenue !');
                return $this->redirectToRoute('password_recovery');
            }
            
            // user was found in bdd
            // generating new password
            $newPassword = "";
            $stringOfCharacters = "abcdefghijklmnopqrstuvwxyz0123456789";
            for($i = 0; $i<6; $i++){
                $newPassword = $newPassword . $stringOfCharacters[random_int(0, 35)];
            }
            
            // assigning new password to user

            $ablocUser->setPassword($encoder->encodePassword($ablocUser, $newPassword));
            
            // saving user with new password
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();

            // sending confirmation message start
            $message = (new \Swift_Message('Abloc - Nouveau mot de passe !'))
            ->setFrom("email.delivery.service.fr@gmail.com")
            ->setTo($email)
            ->setBody(
                $this->renderView(
                    "emails/recovery.html.twig",
                    [
                        "accountName" => $ablocUser->getAccountName(),
                        "password" => $newPassword
                    ]
                ),
                "text/html"
            );
            $mailer->send($message);
            // sending confirmation message end

            $this->addFlash('success', 'Vous allez recevoir un nouveau mot de passe sur votre e-mail !');
            return $this->redirectToRoute('password_recovery');
        }

        return $this->render('recovery/recover_password.html.twig', [
            'defaultData' => $defaultData,
            'form' => $form->createView(),
        ]);
    }
}
