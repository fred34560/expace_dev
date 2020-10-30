<?php

namespace App\Controller\Client;

use App\Entity\Factures;
use Konekt\PdfInvoice\InvoicePrinter;
use App\Repository\FacturesRepository;
use App\Repository\IdentiteSocieteRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Exception\AccessDeniedException as ExceptionAccessDeniedException;


/**
 * @Route("/client/factures")
 */
class FacturesController extends AbstractController
{
    /**
     * @Route("/", name="client_factures_index")
     */
    public function index(FacturesRepository $facturesRepository): Response
    {
        return $this->render('client/factures/index.html.twig', [ 
            'factures' => $facturesRepository->findBy(['client' => $this->getUser()]),
        ]);
    }

    /**
     * Permet d'afficher une facture
     * 
     * @Route("/{id}/view", name="client_factures_view")
     *
     * @param Factures $facture
     * @return void
     */
    public function view(Factures $factures, IdentiteSocieteRepository $info_societe) {

        if ($this->getUser()->getId() !== $factures->getClient()->getId()) {
            throw new ExceptionAccessDeniedException();
        }
        
        $societe = $info_societe->findOneBy(['id' => '1']);


        setlocale(LC_TIME, 'fr','fr_FR','fr_FR@euro','fr_FR.utf8','fr-FR','fra');
        

        $invoice = new InvoicePrinter('A4', '€', 'factures_fr');

        

        $nom_presta = strtoupper($societe->getNom()); 
        $prenom_presta = ucwords($societe->getPrenom());
        $full_nom_presta = utf8_decode($nom_presta. ' '  .$prenom_presta);
        $adresse_presta = $societe->getAdresse();
        $codePostal_presta = $societe->getCodePostal();
        $ville_presta =$societe->getVille();
        $full_ville_presta = utf8_decode($codePostal_presta. ' '  .$ville_presta);
        $tel_presta = utf8_decode("Telephone: " . $societe->getTelephone());
        $email_presta = utf8_decode('E-mail: ' . $societe->getEmail());
        $site_presta = utf8_decode('Site web: ' . $societe->getWeb());
        $rcs_presta = utf8_decode('RCS: ' . $societe->getRcs());
        $siren_presta = utf8_decode('Siren: ' . $societe->getSiren());
        $ape_presta = utf8_decode('APE: ' . $societe->getApe());
        $vide = html_entity_decode('&nbsp;');

        $nom_client = strtoupper($factures->getClient()->getNom());
        $prenom_client = mb_strtolower($factures->getClient()->getPrenom());
        $prenom_client = ucwords($prenom_client);
        $client = $nom_client. ' '  .$prenom_client;
        $adresse_client = $factures->getClient()->getAdresse();
        $postalCode_client = $factures->getClient()->getCodePostal();
        $ville_client = $factures->getClient()->getVille();
        $adresse2 = $postalCode_client. ' ' .$ville_client;
        $pays_client = strtoupper($factures->getClient()->getPays());


        if (strlen($factures->getId()) == 1) {
            $fact = 'FA0000' .$factures->getId();
        }
        elseif (strlen($factures->getId()) == 2) {
            $fact = 'FA000' .$factures->getId();
        }
        elseif (strlen($factures->getId()) == 3) {
            $fact = 'FA00' .$factures->getId();
        }
        elseif (strlen($factures->getId()) == 4) {
            $fact = 'FA0' .$factures->getId();
        }
        elseif (strlen($factures->getId()) == 5) {
            $fact = 'FA' .$factures->getId();
        }


        
        /* Header settings */
        $invoice->setLogo("images/logo_doc.png");   //logo image path
        $invoice->setColor("#2780e3");      // pdf color scheme
        $invoice->setType("Facture");    // Invoice Type
        $invoice->setReference($fact);   // Reference
        $invoice->setDate(strftime("%d %B %Y", $factures->getCreatedAt()));   //Billing Date
        $invoice->setDue(strftime("%d %B %Y", $factures->getCreatedAt() + 604800));    // Due Date
        
        $invoice->setFrom([
            $full_nom_presta,
            $adresse_presta,
            $full_ville_presta,
            $tel_presta,
            $email_presta,
            $site_presta,
            $rcs_presta,
            $siren_presta,
            $ape_presta    
        ]);

        if ($factures->getClient()->getSociete() === null) {
            $invoice->setTo([
                $client,
                $adresse_client,
                $adresse2,
                $pays_client,
            ]);
        }
        else {
            $invoice->setTo([
                strtoupper($factures->getClient()->getSociete()),
                $client,
                $adresse_client,
                $adresse2,
                $pays_client,
            ]);
        }

        


        $tableau = $factures->getServices();
        $tarif_total = null;

        foreach($tableau as $values){
                
            $invoice->addItem(
                $values['type'], 
                $values['info'], 
                $values['quantite'], 
                0, 
                $values['tarif'], 
                0, 
                $values['tarif']*$values['quantite']
            );
            //$invoice->addItem("AMD Athlon X2DC-7450","2.4GHz/1GB/160GB/SMP-DVD/VB",6,0,580,0,3480);
            
               
            $tarif_total += $values['tarif']*$values['quantite'];
               //
        }

  
        $invoice->addTotal("Accompte",0);
        $invoice->addTotal("Total", $tarif_total);
        $invoice->addTotal("Reste due", $tarif_total, true);
  
  
  
        $invoice->addTitle("Informations");
        $invoice->addParagraph("TVA non applicable art. 293B du CGI");
  
        $invoice->setFooternote($societe->getSociete());
  
        $invoice->render($fact. '.pdf','I'); 

        return;

        /* I => Display on browser, D => Force Download, F => local path save, S => return document as string */
    }
}
