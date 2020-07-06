<?php

namespace App\Controller;

use App\Entity\Devis;
use App\Entity\Projets;
use App\Form\ProjetsType;
use App\Entity\Notifications;
use App\Form\CreateDevisType;
use App\Repository\ProjetsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/admin/projets")
 */
class AdminProjetsController extends AbstractController
{
    /**
     * @Route("/", name="admin_projets_index", methods={"GET"})
     */
    public function index(ProjetsRepository $projetsRepository): Response
    {
        return $this->render('admin/projets/index.html.twig', [
            'projets' => $projetsRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="admin_projets_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $projet = new Projets();
        $form = $this->createForm(ProjetsType::class, $projet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($projet);
            $entityManager->flush();

            $this->addFlash(
                'success',
                "Votre nouveau projet a été créé avec succès !"
            );

            return $this->redirectToRoute('admin_projets_index');
        }

        return $this->render('admin/projets/new.html.twig', [
            'projet' => $projet,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/{id}/edit", name="admin_projets_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Projets $projet, EntityManagerInterface $manager): Response
    {
        $form = $this->createForm(ProjetsType::class, $projet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if ($projet->getPropositionCommercialFile() !== null) {

                $notification = new Notifications();

                $notification->setUser($projet->getClient())
                             ->setCreatedAt(new \DateTime('now'))
                             ->setTitre("Réception d'un nouveau document")
                             ->setMessage("Vous avez reçu une nouvelle proposition commerciale pour votre projet");

                $manager->persist($notification);
                $manager->flush();

                
            }

            if ($projet->getCahierChargeFile() !== null) {

                $notification = new Notifications();

                $notification->setUser($projet->getClient())
                             ->setCreatedAt(new \DateTime('now'))
                             ->setTitre("Réception d'un nouveau document")
                             ->setMessage("Vous avez reçu le cahier des charges pour votre projet");

                $manager->persist($notification);
                $manager->flush();

                
            }




            $this->getDoctrine()->getManager()->flush();

            $this->addFlash(
                'success',
                "Votre projet a été modifié avec succès !"
            );

            return $this->redirectToRoute('admin_projets_index');
        }

        return $this->render('admin/projets/edit.html.twig', [
            'projet' => $projet,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/delete/{id}", name="admin_projets_delete")
     */
    public function delete(Request $request, Projets $projet): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($projet);
        $entityManager->flush();

        $this->addFlash(
            'warning',
            "Votre projet a été supprimé avec succès !"
        );

        return $this->redirectToRoute('admin_projets_index');
    }

    /**
     * @Route("/devis/create/{id}", name="admin_devis_create")
     *
     * @return void
     */
    public function createDev(EntityManagerInterface $manager, Projets $projet, Request $request) {

        $devis = new Devis();
        $form = $this->createForm(CreateDevisType::class, $devis);
        $form->handleRequest($request);

        //$session->set($projet);

        

        if ($form->isSubmitted() && $form->isValid()) {

            $client = [
                'nom' => $projet->getClient()->getNom(),
                'prenom' => $projet->getClient()->getPrenom(),
                'adresse' => $projet->getClient()->getAdresse(),
                'codePostal' => $projet->getClient()->getCodePostal(),
                'ville' => $projet->getClient()->getVille(),
                'pays' => $projet->getClient()->getPays(),
                'societe' => $projet->getClient()->getSociete()
            ];


            $devis->setCreatedAt(\time())
                  ->setClient($client)
                  ->setProjet($projet->getTitre())
                  ->setStatut('en_attente')
                  ;
            //dd($devis);
            $manager->persist($devis);

            $notification = new Notifications();

            $notification->setUser($projet->getClient())
                         ->setCreatedAt(new \DateTime('now'))
                         ->setTitre("Réception d'un nouveau document")
                         ->setMessage("Vous avez reçu le devis pour votre projet");

            $manager->persist($notification);

            $manager->flush();

            $this->addFlash(
                'success',
                "Votre devis a été créé avec succès !"
            );

            return $this->redirectToRoute('admin_projets_index');
        }

        return $this->render('admin/devis/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/devis/update-production/{id}", name="admin_projets_update_prod")
     *
     * @param Projets $projet
     * @return void
     */
    public function updateProd(Projets $projet, EntityManagerInterface $manager) {

        $projet->setStatut('terminé');

        $manager->flush();

        $this->addFlash(
            'success',
            "Votre projet a bien été passé en mode production !"
        );

        return $this->redirectToRoute('admin_projets_index');

    }

    /**
     * @Route("/devis/update-developpement/{id}", name="admin_projets_update_dev")
     *
     * @param Projets $projet
     * @return void
     */
    public function updateDev(Projets $projet, EntityManagerInterface $manager) {

        $projet->setStatut('en_cours');

        $manager->flush();

        $this->addFlash(
            'success',
            "Votre projet a bien été passé en mode developpement !"
        );

        return $this->redirectToRoute('admin_projets_index');

    }
}
