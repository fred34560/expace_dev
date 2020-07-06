<?php

namespace App\Controller;

use App\Entity\IdentiteSociete;
use App\Form\IdentiteSocieteType;
use App\Repository\IdentiteSocieteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/identite/societe")
 */
class IdentiteSocieteController extends AbstractController
{
    /**
     * @Route("/new", name="admin_identite_societe_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $identiteSociete = new IdentiteSociete();
        $form = $this->createForm(IdentiteSocieteType::class, $identiteSociete);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($identiteSociete);
            $entityManager->flush();

            $this->addFlash(
                'success',
                "Votre société a bien été créé !"
            );

            return $this->redirectToRoute('admin_users_index');
        }

        return $this->render('admin/identite_societe/new.html.twig', [
            'identite_societe' => $identiteSociete,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="admin_identite_societe_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, IdentiteSociete $identiteSociete): Response
    {
        $form = $this->createForm(IdentiteSocieteType::class, $identiteSociete);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash(
                'success',
                "Votre société a bien été modifié !"
            );

            return $this->redirectToRoute('admin_users_index');
        }

        return $this->render('admin/identite_societe/edit.html.twig', [
            'identite_societe' => $identiteSociete,
            'form' => $form->createView(),
        ]);
    }
}
