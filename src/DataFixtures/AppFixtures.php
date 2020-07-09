<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Users;
use App\Entity\Articles;
use App\Entity\PostLike;
use App\Entity\Categories;
use App\Entity\CommentLike;
use App\Entity\Commentaires;
use App\Entity\Projets;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
        
    }
    
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr-FR');
        


        // Nous gérons les utilisateurs

        $users = [];
        $genres = ['male', 'female'];

        for ($i=1; $i<=100; $i++) {
            
            $user = new Users();

            $genre = $faker->randomElement($genres);
            $picture = 'https://randomuser.me/api/portraits/';
            $pictureId = $faker->numberBetween(1, 99) . '.jpg';
            $picture .= ($genre == 'male' ? 'men/' : 'women/') . $pictureId;

            $hash = $this->encoder->encodePassword($user, 'password');

            $user->setEmail($faker->email)
                 ->setPassword($hash)
                 ->setIsVerified(1)
                 ->setAvatar($picture)
                 ->setPseudo($faker->userName)
                 ->setNom($faker->lastName)
                 ->setPrenom($faker->firstName($genre))
                 ->setAdresse($faker->streetAddress)
                 ->setCodePostal($faker->postcode)
                 ->setVille($faker->city)
                 ->setPays($faker->country)
                 ->setSociete($faker->company)
                 ->setCreatedAt($faker->dateTime('now'))
                 ->setUpdatedAt($faker->dateTime('now'));
        
        $manager->persist($user);
        $users[] = $user;        
        }

        //Nous gérons les catégories

        $categories = [];

        for ($i=1; $i<=5; $i++) {
            
            $categorie = new Categories();

            $categorie->setNom($faker->lastName);
            $manager->persist($categorie);
            $categories[] = $categorie;
        }

        // Nous gérons les articles
        $articles = [];

        for ($i=1; $i<=200; $i++) {
            
            $article = new Articles();

            $titre = $faker->sentence();
            $contenu = '<p>' . join ('</p><p>', $faker->paragraphs(5)) . '</p>';
            $image = $faker->imageUrl(715,417);

            $user = $users[mt_rand(0, count($users) -1)];
            $categorie = $categories[mt_rand(0, count($categories) -1)];

            $article->setUsers($user)
                    ->setTitre($titre)
                    ->setContenu($contenu)
                    ->setFeaturedImage($image)
                    ->setCategories($categorie);

            $manager->persist($article);
            $articles[] = $article;

            $commentaires = [];

            for ($j = 1; $j <= mt_rand(0, 10); $j++) {

                $commentaire = new Commentaires();

                $article = $articles[mt_rand(0, count($articles) -1)];
                $user = $users[mt_rand(0, count($users) -1)];
                $content = '<p>' . join ('</p><p>', $faker->paragraphs(1)) . '</p>';

                $commentaire->setArticles($article)
                            ->setPseudo($user->getEmail())
                            ->setEmail($user->getEmail())
                            ->setContenu($content)
                            ->setActif(true)
                            ->setRgpd(true)
                            ->setCreatedAt($faker->dateTime('now'))
                            ->setUsers($user);

                $manager->persist($commentaire);
                $commentaires[] = $commentaire;


                for ($k = 1; $k <= mt_rand(0, 3); $k++) {

                    $reponse = new Commentaires();
    
                    $article = $articles[mt_rand(0, count($articles) -1)];
                    $id = $commentaires[mt_rand(0, count($commentaires) -1)];
                    $user = $users[mt_rand(0, count($users) -1)];
                    $content = '<p>' . join ('</p><p>', $faker->paragraphs(1)) . '</p>';
    
                    $commentaire->setArticles($article)
                                ->setPseudo($user->getEmail())
                                ->setEmail($user->getEmail())
                                ->setContenu($content)
                                ->setActif(true)
                                ->setRgpd(true)
                                ->setCreatedAt($faker->dateTime('now'))
                                ->setParentId($id)
                                ->setUsers($user);
    
                    $manager->persist($commentaire);

                    for ($l = 1; $l <= mt_rand(0, 10); $l++) { 
                        $postLike = new PostLike();
                        $postLike->setArticle($article)
                             ->setUser($faker->randomElement($users));

                        $manager->persist($postLike);

                    

                    }
    
                    for ($m = 1; $m <= mt_rand(0, 10); $m++) {
                        $commentLike = new CommentLike();
                        $commentLike->setCommentaires($commentaire)
                                    ->setUser($faker->randomElement($users));

                        $manager->persist($commentLike);
                    }
    
    
                
                }




            
            }

            $projet = new Projets();
        
            for ($n=1; $n<=10; $n++) {

                $statut = ['en_cours', 'terminé', 'ouverture'];

                $projet->setClient($faker->randomElement($users))
                       ->setTitre($faker->sentence())
                       ->setStatut($faker->randomElement($statut))
                       ->setBesoinsClient('<p>' . join ('</p><p>', $faker->paragraphs(5)) . '</p>')
                       ->setCreatedAt($faker->dateTime('now'))
                       ->setUpdatedAt($faker->dateTime('now'));
                       

                $manager->persist($projet);
            }

        }
        

        //Nous gérons les commentaires
        
        $manager->flush();
    }
}
