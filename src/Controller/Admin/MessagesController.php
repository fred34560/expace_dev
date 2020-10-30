<?php

namespace App\Controller\Admin;

use App\Entity\Messages;
use App\Form\Admin\MessagesType;
use App\Repository\UsersRepository;
use App\Repository\MessagesRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Exception\AccessDeniedException as ExceptionAccessDeniedException;

/**
 * @Route("/admin/messages")
 */
class MessagesController extends AbstractController
{
    /**
     * @Route("/recu", name="admin_messages_recu", methods={"GET"})
     */
    public function messageRecu(PaginatorInterface $paginator, Request $request, MessagesRepository $messagesRepository): Response
    {

        $donnees = $messagesRepository->findBy([
            'destinataire' => $this->getUser()
        ]);

        $pagination = $paginator->paginate(
            $donnees, // Requête contenant les données à paginer (ici nos articles)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            10 // Nombre de résultats par page
        );
        $pagination->setTemplate( 'partials/pagination_message.html.twig');

        return $this->render('admin/messages/reception.html.twig', [
            'messages' => $pagination,
        ]);
    }

    /**
     * @Route("/envoye", name="admin_messages_envoye", methods={"GET"})
     */
    public function messageEnvoye(PaginatorInterface $paginator, Request $request, MessagesRepository $messagesRepository): Response
    {

        $donnees = $messagesRepository->findBy([
            'expediteur' => $this->getUser()
        ]);

        $pagination = $paginator->paginate(
            $donnees, // Requête contenant les données à paginer (ici nos articles)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            10 // Nombre de résultats par page
        );

        //dd($pagination);
        $pagination->setTemplate( 'partials/pagination_message.html.twig');

        return $this->render('admin/messages/expedition.html.twig', [
            'messages' => $pagination,
        ]);
    }

    /**
     * @Route("/new", name="admin_messages_new", methods={"GET","POST"})
     */
    public function new(Request $request, UsersRepository $repo): Response
    {
        $message = new Messages();
        $form = $this->createForm(MessagesType::class, $message);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            //dd($message->getClient());

            $destinataire = $message->getClient();


            $message->setExpediteur($this->getUser())
                    ->setDestinataire($destinataire)
                    ->setLu(0);
            //dd($message);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($message);
            $entityManager->flush();

            return $this->redirectToRoute('admin_messages_recu');
        }

        return $this->render('admin/messages/new.html.twig', [
            'message' => $message,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="admin_messages_show", methods={"GET"})
     */
    public function show(Messages $message): Response
    {
        if ($this->getUser() != $message->getDestinataire()) {
            throw new ExceptionAccessDeniedException();
        }
        return $this->render('admin/messages/show.html.twig', [
            'message' => $message,
        ]);
    }

    /**
     * @Route("/delete/{id}", name="admin_messages_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Messages $message): Response
    {
        if ($this->getUser() != $message->getDestinataire()) {
            throw new ExceptionAccessDeniedException();
        }

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($message);
        $entityManager->flush();

        return $this->redirectToRoute('admin_messages_index');
    }
}
