<?php

namespace App\Controller\Client;

use App\Entity\Users;
use App\Form\Client\ProfilType;
use App\Repository\UsersRepository;
use App\Form\Client\ChangePasswordFormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/client/profil")
 */
class ProfilController extends AbstractController
{
    /**
     * @Route("/edit", name="client_profil_edit", methods={"GET","POST"})
     */
    public function edit(Request $request): Response
    {
        $user = $this->getUser();

        $form = $this->createForm(ProfilType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('client_profil_edit');
        }

        return $this->render('client/profil/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/update-password", name="client_profil_update_password", methods={"GET","POST"})
     *
     * @param Request $request
     * @return void
     */
    public function updatefPassword(Request $request, UserPasswordEncoderInterface $encoder) {


        $user = $this->getUser();

        $form = $this->createForm(ChangePasswordFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            $verifiedPassword = $encoder->isPasswordValid(
                $user,
                $form->get('ancienPassword')->getData()
            );

            if ($verifiedPassword === false) {
                $this->addFlash(
                    'danger',
                    "Votre mot de passe est invalide !"
                );

                return $this->redirectToRoute('client_profil_update_password');
            }
            else {

                if ($form->get('plainPassword')->getData() === $form->get('ancienPassword')->getData()) {
                    $this->addFlash(
                        'warning',
                        "Votre mot de passe correspond à celui enregistré !"
                    );
                    return $this->redirectToRoute('client_profil_update_password');
                }

                else {
                    $passwordEncoded = $encoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                    );

                    $user->setPassword($passwordEncoded);
                    $this->getDoctrine()->getManager()->flush();


                    $this->addFlash(
                        'success',
                        "Votre mot de passe a bien été modifié<br>Vous devez vous reconnecter !"
                    );
                    return $this->redirectToRoute('app_logout');
                }

                

                
            }

            

        }

        return $this->render('client/profil/update_pass.html.twig', [
            'form' => $form->createView()
        ]);

    }
}
