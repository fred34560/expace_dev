<?php

namespace App\Controller\Client;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/client/messagerie")
 */
class MessagerieController extends AbstractController
{
    /**
     * @Route("/", name="messagerie_home")
     */
    public function index()
    {
        return $this->render('client/messagerie/index.html.twig', [
            'controller_name' => 'MessagerieController',
        ]);
    }
}
