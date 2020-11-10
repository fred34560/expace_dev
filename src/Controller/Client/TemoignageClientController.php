<?php

namespace App\Controller\Client;

use App\Entity\TemoignageClient;
use App\Form\Client\TemoignageClientType;
use App\Repository\TemoignageClientRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/client/temoignages")
 */
class TemoignageClientController extends AbstractController
{
    /**
     * @Route("/", name="temoignage_client_index", methods={"GET"})
     */
    public function index(TemoignageClientRepository $temoignageClientRepository): Response
    {
        return $this->render('client/temoignage/index.html.twig', [
            'temoignages' => $temoignageClientRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="temoignage_client_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $temoignageClient = new TemoignageClient();
        $form = $this->createForm(TemoignageClientType::class, $temoignageClient);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            
            $temoignageClient->setClient($this->getUser());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($temoignageClient);
            $entityManager->flush();

            return $this->redirectToRoute('temoignage_client_index');
        }

        return $this->render('client/temoignage/new.html.twig', [
            'temoignages' => $temoignageClient,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="temoignage_client_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, TemoignageClient $temoignageClient): Response
    {
        $form = $this->createForm(TemoignageClientType::class, $temoignageClient);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('temoignage_client_index');
        }

        return $this->render('client/temoignage/edit.html.twig', [
            'temoignages' => $temoignageClient,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="temoignage_client_delete", methods={"DELETE"})
     */
    public function delete(Request $request, TemoignageClient $temoignageClient): Response
    {
        if ($this->isCsrfTokenValid('delete'.$temoignageClient->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($temoignageClient);
            $entityManager->flush();
        }

        return $this->redirectToRoute('temoignage_client_index');
    }
}
