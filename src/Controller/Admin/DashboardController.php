<?php

namespace App\Controller\Admin;

use App\Entity\StatsDevelop;
use App\Repository\DevisRepository;
use App\Repository\FacturesRepository;
use App\Repository\ProjetsRepository;
use App\Repository\StatsDevelopRepository;
use App\Repository\UsersRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/dashboard")
 */
class DashboardController extends AbstractController
{
    /**
     * @Route("/", name="admin_dashboard_index")
     */
    public function index(UsersRepository $usersRepository, ProjetsRepository $projetsRepository, DevisRepository $devisRepository, FacturesRepository $facturesRepository)
    {

        //dd();

        return $this->render('admin/dashboard/index.html.twig', [
            'users' => $usersRepository->findAll(),
            'projets' => $projetsRepository->findAll(),
            'devis' => $devisRepository->findAll(),
            'factures' => $facturesRepository->findAll(),
            'applications' => $this->getDoctrine()->getRepository(StatsDevelop::class)->nbreApp(),
            'dureeCode' => $this->getDoctrine()->getRepository(StatsDevelop::class)->dureeCode(),
            'recompense' => $this->getDoctrine()->getRepository(StatsDevelop::class)->nbreRecompense()
            
        ]);
    }
}
