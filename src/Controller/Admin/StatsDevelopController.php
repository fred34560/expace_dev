<?php

namespace App\Controller\Admin;

use App\Entity\StatsDevelop;
use App\Form\StatsDevelopType;
use App\Repository\StatsDevelopRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/stats/develop")
 */
class StatsDevelopController extends AbstractController
{
    /**
     * @Route("/", name="stats_develop_index", methods={"GET"})
     */
    public function index(StatsDevelopRepository $statsDevelopRepository): Response
    {
        return $this->render('admin/stats_develop/index.html.twig', [
            'stats_develops' => $statsDevelopRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="stats_develop_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $statsDevelop = new StatsDevelop();
        $form = $this->createForm(StatsDevelopType::class, $statsDevelop);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($statsDevelop);
            $entityManager->flush();

            $this->addFlash(
                'success',
                "Votre nouveau projet a été créé avec succès !"
            );

            return $this->redirectToRoute('stats_develop_index');
        }

        return $this->render('admin/stats_develop/new.html.twig', [
            'stats_develop' => $statsDevelop,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="stats_develop_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, StatsDevelop $statsDevelop): Response
    {
        $form = $this->createForm(StatsDevelopType::class, $statsDevelop);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash(
                'success',
                "Votre projet a été modifié avec succès !"
            );

            return $this->redirectToRoute('stats_develop_index');
        }

        return $this->render('admin/stats_develop/edit.html.twig', [
            'stats_develop' => $statsDevelop,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/delete/{id}", name="stats_develop_delete")
     */
    public function delete(Request $request, StatsDevelop $statsDevelop): Response
    {
            
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($statsDevelop);
        $entityManager->flush();

        $this->addFlash(
            'warning',
            "Votre projet a été supprimé avec succès !"
        );

        return $this->redirectToRoute('stats_develop_index');
    }
}
