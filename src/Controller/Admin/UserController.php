<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Form\UserType;
use App\Form\UserNewType;
use App\Form\UserEditType;
use App\Form\UserPasswordType;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/admin/user")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/", name="admin_user_index", methods={"GET"})
     */
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('admin/user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="admin_user_new", methods={"GET","POST"})
     */
    public function new(Request $request, UserPasswordEncoderInterface $encoder): Response
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

    /**
     * @Route("/{id<\d+>}", name="admin_user_show", methods={"GET"})
     */
    public function show(User $user): Response
    {
        return $this->render('admin/user/show.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/{id<\d+>}/edit", name="admin_user_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, User $user): Response
    {
        $form = $this->createForm(UserEditType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imgFile = $form->get('img_path')->getData();// a verifier si recupere le nom ou le fichier en en entier
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
            if($user->getAccountName() == ""){
                $user->setAccountName("Anonyme");
            }
            if($user->getAvailableTime() == ""){
                $user->setAvailableTime(0);
            }
            if($user->getScore() == ""){
                $user->setScore(0);
            }
            $user->setUpdatedAt(new \DateTime());
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'User Edited!');
            return $this->redirectToRoute('admin_user_index');
        }

        return $this->render('admin/user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/edit/password/{id<\d+>}", name="admin_user_edit_password")
     */
    public function editPassword($id, Request $request, UserPasswordEncoderInterface $encoder, UserRepository $userRepository)
    {
        $user = $userRepository->find($id);
        $form = $this->createForm(UserPasswordType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $user->setPassword($encoder->encodePassword($user, $user->getPassword()));
            $user->setUpdatedAt(new \DateTime());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();

            $this->addFlash('success', 'User Password Edited!');

            return $this->redirectToRoute('admin_user_index');
        }

        return $this->render('admin/user/edit_password.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id<\d+>}", name="admin_user_delete", methods={"DELETE"})
     */
    public function delete(Request $request, User $user): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_user_index');
    }
}


