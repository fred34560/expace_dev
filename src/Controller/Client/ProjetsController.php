<?php

namespace App\Controller\Client;

use App\Entity\Projets;
use App\Form\Client\ProjetsType;
use App\Repository\ProjetsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException as ExceptionAccessDeniedException;

/**
 * @Route("/client/projets")
 */
class ProjetsController extends AbstractController
{
    /**
     * @Route("/", name="client_projets_index", methods={"GET"})
     */
    public function index(ProjetsRepository $projetsRepository): Response
    {

        return $this->render('client/projets/index.html.twig', [
            'projets' => $projetsRepository->findBy(['client' => $this->getUser()]),
        ]);
    }

    /**
     * @Route("/new", name="client_projets_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $projet = new Projets();
        $form = $this->createForm(ProjetsType::class, $projet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $projet->setClient($this->getUser());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($projet);
            $entityManager->flush();

            $this->addFlash(
                'success',
                "Votre projet a été créé avec succès !"
            );

            return $this->redirectToRoute('client_projets_index');
        }

        return $this->render('client/projets/new.html.twig', [
            'projet' => $projet,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="client_projets_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Projets $projet): Response
    {
        if ($this->getUser() != $projet->getClient()) {
            throw new ExceptionAccessDeniedException();
        }
        else {
            $form = $this->createForm(ProjetsType::class, $projet);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {

                $this->getDoctrine()->getManager()->flush();

                $this->addFlash(
                    'success',
                    "Votre projet a été modifié avec succès !"
                );

                return $this->redirectToRoute('client_projets_index');
            }

            return $this->render('client/projets/edit.html.twig', [
            'projet' => $projet,
            'form' => $form->createView(),
            ]);
        }
    }

    /**
     * @Route("/delete/{id}", name="client_projets_delete")
     */
    public function delete(Request $request, Projets $projet): Response
    {

        if ($this->getUser() != $projet->getClient()) {
            throw new ExceptionAccessDeniedException();
        }
        elseif ($projet->getStatut() != 'ouverture') {

            $this->addFlash(
                'danger',
                "Vous ne pouvez pas supprimer un projet en developpement ou en production !"
            );

        }
        else {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($projet);
            $entityManager->flush();

            $this->addFlash(
                'warning',
                "Votre projet a été supprimé avec succès !"
            );


           
        }
         return $this->redirectToRoute('client_projets_index');
    }
}
