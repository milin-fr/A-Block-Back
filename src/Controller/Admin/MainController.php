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
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/admin")
 */
class MainController extends AbstractController
{
    /**
     * @Route("/", name="admin_home", methods={"GET"})
     */
    public function adminHome()
    {
            return $this->render('admin\main\home.html.twig', [
            'data' => ["admin home"],
        ]);
    }
    
    /**
     * @Route("/super-admin", name="super_admin_home", methods={"GET"})
     */
    public function superAdminHome(UserRepository $userRepository)
    {
            return $this->render('admin/main/super_home.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    /**
     * @Route("/super-admin/new", name="super_admin_new", methods={"GET","POST"})
     */
    public function new(Request $request, UserPasswordEncoderInterface $encoder): Response
    {
        $user = new User();
        $form = $this->createForm(UserNewType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword($encoder->encodePassword($user, $user->getPassword()));
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('super_admin_home');
        }

        return $this->render('admin/main/super_new.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/super-admin/{id<\d+>}", name="super_admin_edit", methods={"GET","POST"})
     */
    public function superAdminEdit(Request $request, User $user)
    {
        $form = $this->createForm(UserEditType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setUpdatedAt(new \DateTime());
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('super_admin_home');
        }

        return $this->render('admin/main/super_edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }
}
