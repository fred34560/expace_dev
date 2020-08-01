<?php

namespace App\Controller;

use App\Entity\Articles;
use App\Entity\Categories;
use App\Entity\Commentaires;
use App\Entity\CommentLike;
use App\Entity\PostLike;
use App\Form\CommenterArticleType;
use App\Repository\ArticlesRepository;
use App\Repository\CommentLikeRepository;
use App\Repository\PostLikeRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class BlogController extends AbstractController
{

    /**
     * @Route("/tutoriels/article/{slug}", name="tutoriels_article")
     *
     * @return void
     */
    public function post($slug, Request $request, EntityManagerInterface $manager) {

        $derniersArticles = $this->getDoctrine()->getRepository(Articles::class)->getDerniersPosts(2);

        $article = $this->getDoctrine()->getRepository(Articles::class)->findOneBy(['slug' => $slug]);
        $derniersPosts = $this->getDoctrine()->getRepository(Articles::class)->getDerniersPosts(3);
        $articlesRecents = $this->getDoctrine()->getRepository(Articles::class)->getDerniersPosts(2);
        $categories = $this->getDoctrine()->getRepository(Categories::class)->findAll();

        $totalArticles = $this->getDoctrine()->getRepository(Articles::class)->getNb();

        $commentaire = new Commentaires();
        $form = $this->createForm(CommenterArticleType::class, $commentaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $parent_id = $_POST['parent_id'];

            $user = $this->getUser();
            $date = new \DateTime();

            //$commentaire->setParentId($parent_id);


            $reponse = $this->getDoctrine()->getRepository(Commentaires::class)->findOneBy(['id' => $_POST['parent_id']]);

            $commentaire->setUsers($user)
                        ->setPseudo($user->getEmail())
                        ->setEmail($user->getEmail())
                        ->setActif(true)
                        ->setRgpd(true)
                        ->setCreatedAt($date)
                        ->setArticles($article)
                        ->setParentId($reponse);

            //dd($commentaire);
            $manager->persist($commentaire);

            $manager->flush();

        }




        return $this->render('tutoriels/posts.html.twig', [
            'article' => $article,
            'derniersPosts' => $derniersPosts,
            'totalArticles' => $totalArticles,
            'categories' => $categories,
            'articlesRecents' => $articlesRecents,
            'derniersArticles' => $derniersArticles,
            'form' => $form->createView()
        ]);

    }
    
    /**
     * @Route("/tutoriels/{slug?}", name="tutoriels_home")
     */
    public function index(PaginatorInterface $paginator, Request $request, $slug)
    {

        $derniersArticles = $this->getDoctrine()->getRepository(Articles::class)->getDerniersPosts(2);
        


        if (!$slug) {
           $donnees = $this->getDoctrine()->getRepository(Articles::class)->findBy([], [
            'createdAt' => 'desc',
            ]); 
        }
        else {
            $idCat = $this->getDoctrine()->getRepository(Categories::class)->findOneBy(['slug' => $slug]);

            $donnees = $this->getDoctrine()->getRepository(Articles::class)->findBy(['categories' => $idCat->getId()], [
                'createdAt' => 'desc',
                ]); 
        }
        
        $categories = $this->getDoctrine()->getRepository(Categories::class)->findAll();

        $totalArticles = $this->getDoctrine()->getRepository(Articles::class)->getNb();
        $derniersPosts = $this->getDoctrine()->getRepository(Articles::class)->getDerniersPosts(3);

        //dd($totalArticles);

        $pagination = $paginator->paginate(
            $donnees, // Requête contenant les données à paginer (ici nos articles)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            3 // Nombre de résultats par page
        );
        $pagination->setCustomParameters([
            'align' => 'center', # center|right (for template: twitter_bootstrap_v4_pagination)
        ]);
        $pagination->setTemplate( 'partials/paginator.html.twig');

        return $this->render('tutoriels/index.html.twig', [
            'pagination' => $pagination,
            'categories' => $categories,
            'totalArticles' => $totalArticles,
            'derniersPosts' => $derniersPosts,
            'derniersArticles' => $derniersArticles
        ]);
    }

    /**
     * Permet d'ajouter ou supprimer des likes aux articles
     * 
     * @Route("/tutoriels/articles/{id}/like", name="tutoriels_article_like")
     *
     * @param Articles $article
     * @param EntityManagerInterface $manager
     * @param PostLikeRepository $postLike
     * @return void
     */
    public function postLike(Articles $article, EntityManagerInterface $manager, PostLikeRepository $postLike) {

        if (!$this->getUser()) return $this->json([
            'code' => 403,
            'message' => 'Unauthorized'
        ], 403);

        if ($article->isLikedArticleByUser($this->getUser())) {
            
            $like = $postLike->findOneBy([
                'article' => $article,
                'user' => $this->getUser()
            ]);

            $manager->remove($like);
            $manager->flush();

            return $this->json([
                'code' => 200,
                'message' => 'suppression',
                'likes' => $postLike->count(['article' => $article])
            ], 200);
        }

        $like = new PostLike();
        $like->setArticle($article)
             ->setUser($this->getUser());

        $manager->persist($like);
        $manager->flush();

        return $this->json([
            'code' => 200,
            'message' => 'ajout',
            'likes' => $postLike->count(['article' => $article])
        ], 200);

    }


    /**
     * Permet d'ajouter ou supprimer des likes aux commentaires
     * 
     * @Route("/tutoriels/commentaire/{id}/like", name="tutoriels_commentaire_like")
     *
     * @param Commentaires $commentaire
     * @param EntityManagerInterface $manager
     * @param CommentLikeRepository $commentLike
     * @return void
     */
    public function CommentLike(Commentaires $commentaire , EntityManagerInterface $manager, CommentLikeRepository $commentLike) {

        if (!$this->getUser()) return $this->json([
            'code' => 403,
            'message' => 'Unauthorized'
        ], 403);

        if ($commentaire->isLikedCommentaireByUser($this->getUser())) {
            
            $like = $commentLike->findOneBy([
                'commentaire' => $commentaire,
                'user' => $this->getUser()
            ]);

            $manager->remove($like);
            $manager->flush();

            return $this->json([
                'code' => 200,
                'message' => 'suppression',
                'likes' => $commentLike->count(['commentaire' => $commentaire])
            ], 200);
        }

        $like = new CommentLike();
        $like->setCommentaire($commentaire)
             ->setUser($this->getUser());

        $manager->persist($like);
        $manager->flush();

        return $this->json([
            'code' => 200,
            'message' => 'ajout',
            'likes' => $commentLike->count(['commentaire' => $commentaire])
        ], 200);

    }

    
}
