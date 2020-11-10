<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Entity\Articles;
use App\Form\ContactType;
use App\Entity\StatsDevelop;
use App\Entity\TemoignageClient;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;

class HomeController extends AbstractController
{
    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }
    
    /**
     * @Route("/", name="home")
     */
    public function index()
    {
        $derniersArticles = $this->getDoctrine()->getRepository(Articles::class)->getDerniersPosts(2);

        return $this->render('index.html.twig', [
            'derniersArticles' => $derniersArticles,
            'applications' => $this->getDoctrine()->getRepository(StatsDevelop::class)->nbreApp(),
            'dureeCode' => $this->getDoctrine()->getRepository(StatsDevelop::class)->dureeCode(),
            'recompense' => $this->getDoctrine()->getRepository(StatsDevelop::class)->nbreRecompense(),
            'temoignages' => $this->getDoctrine()->getRepository(TemoignageClient::class)->findAll()
        ]);
    }

    /**
     * @Route("/nous-contacter", name="home_contact")
     *
     * @return void
     */
    public function contact() {

        $derniersArticles = $this->getDoctrine()->getRepository(Articles::class)->getDerniersPosts(2);

        return $this->render('contact.html.twig', [
            'derniersArticles' => $derniersArticles
        ]);
    }

    /**
     * @Route("/nos-services", name="home_services")
     *
     * @return void
     */
    public function services() {

        $derniersArticles = $this->getDoctrine()->getRepository(Articles::class)->getDerniersPosts(2);

        return $this->render('services.html.twig', [
            'derniersArticles' => $derniersArticles
        ]);
    }

    /**
     * @Route("/politique-de-confidentialite", name="home_confidentialite")
     *
     * @return void
     */
    public function confidentialite() {

        $derniersArticles = $this->getDoctrine()->getRepository(Articles::class)->getDerniersPosts(2);

        return $this->render('confidentialite.html.twig', [
            'derniersArticles' => $derniersArticles
        ]);
    }

    
}
