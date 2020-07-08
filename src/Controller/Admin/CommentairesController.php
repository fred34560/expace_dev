<?php

namespace App\Controller\Admin;

use App\Entity\Commentaires;
use App\Form\Admin\CommentairesType;
use App\Repository\CommentairesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/commentaires")
 */
class CommentairesController extends AbstractController
{
    /**
     * @Route("/", name="admin_commentaires_index", methods={"GET"})
     */
    public function index(CommentairesRepository $commentairesRepository): Response
    {
        return $this->render('admin/commentaires/index.html.twig', [
            'commentaires' => $commentairesRepository->findAll(),
        ]);
    }


    /**
     * @Route("/{id}/edit", name="admin_commentaires_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Commentaires $commentaire): Response
    {
        $form = $this->createForm(CommentairesType::class, $commentaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash(
                'success',
                "Le commentaire a été modifiée avec succès !"
            );

            return $this->redirectToRoute('admin_commentaires_index');
        }

        return $this->render('admin/commentaires/edit.html.twig', [
            'commentaire' => $commentaire,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/delete/{id}", name="admin_commentaires_delete")
     */
    public function delete(Request $request, Commentaires $commentaire): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($commentaire);
        $entityManager->flush();

        $this->addFlash(
            'warning',
            "Le commentaire a été supprimé avec succès !"
        );

        return $this->redirectToRoute('admin_commentaires_index');
    }
}
