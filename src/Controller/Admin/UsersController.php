<?php

namespace App\Controller\Admin;

use App\Entity\Users;
use App\Form\Admin\UsersType;
use App\Repository\UsersRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/admin/users")
 */
class UsersController extends AbstractController
{
    /**
     * @Route("/", name="admin_users_index", methods={"GET"})
     */
    public function index(UsersRepository $usersRepository): Response
    {
        return $this->render('admin/users/index.html.twig', [
            'users' => $usersRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="admin_users_new", methods={"GET","POST"})
     */
    public function new(Request $request, UserPasswordEncoderInterface $encoder): Response
    {
        $user = new Users();
        $form = $this->createForm(UsersType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager = $this->getDoctrine()->getManager();

            $plainPassword = $user->getEmail();
            $plainPassword .= $user->getPseudo();
            
            $encoded = $encoder->encodePassword($user, $plainPassword);
            $user->setIsVerified(1);
            $user->setPassword($encoded);
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash(
                'success',
                "Votre client a été créé avec succès !"
            );

            return $this->redirectToRoute('admin_users_index');
        }

        return $this->render('admin/users/new.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="admin_users_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Users $user): Response
    {
        $form = $this->createForm(UsersType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            
            $this->addFlash(
                'success',
                "La fiche client a été modifié avec succès !"
            );

            return $this->redirectToRoute('admin_users_index');

            
        }

        return $this->render('admin/users/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/delete/{id}", name="admin_users_delete")
     */
    public function delete(Request $request, Users $user): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($user);
        $entityManager->flush();

        $this->addFlash(
            'warning',
            "La fiche client a été supprimé avec succès !"
        );

        return $this->redirectToRoute('admin_users_index');
    }
}
