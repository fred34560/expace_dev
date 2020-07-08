<?php

namespace App\Controller\Admin;

use App\Entity\Devis;
use App\Form\Admin\DevisType;
use App\Repository\DevisRepository;
use Konekt\PdfInvoice\InvoicePrinter;
use App\Repository\IdentiteSocieteRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/admin/devis")
 */
class DevisController extends AbstractController
{
    /**
     * @Route("/", name="admin_devis_index", methods={"GET"})
     */
    public function index(DevisRepository $devisRepository): Response
    {
        return $this->render('admin/devis/index.html.twig', [
            'devis' => $devisRepository->findAll(),
        ]);
    }

    /**
     * @Route("/delete/{id}", name="admin_devis_delete")
     */
    public function delete(Request $request, Devis $devi): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($devi);
        $entityManager->flush();

        return $this->redirectToRoute('admin_devis_index');
    }

        /**
     * Permet d'afficher une facture
     * 
     * @Route("/{id}/view", name="admin_devis_view")
     *
     * @param Devis $devi
     * @return void
     */
    public function view(Devis $devi, IdentiteSocieteRepository $info_societe) {
        
        $societe = $info_societe->findOneBy(['id' => '1']);


        setlocale(LC_TIME, 'fr','fr_FR','fr_FR@euro','fr_FR.utf8','fr-FR','fra');
        

        $invoice = new InvoicePrinter('A4', '€', 'devis_fr');

        

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

        

        $nom_client = strtoupper($devi->getClient()['nom']);
        $prenom_client = mb_strtolower($devi->getClient()['prenom']);
        $prenom_client = ucwords($prenom_client);
        $client = $nom_client. ' '  .$prenom_client;
        $adresse_client = $devi->getClient()['adresse'];
        $postalCode_client = $devi->getClient()['codePostal'];
        $ville_client = $devi->getClient()['ville'];
        $adresse2 = $postalCode_client. ' ' .$ville_client;
        $pays_client = strtoupper($devi->getClient()['pays']);


        if (strlen($devi->getId()) == 1) {
            $fact = 'DEV0000' .$devi->getId();
        }
        elseif (strlen($devi->getId()) == 2) {
            $fact = 'DEV000' .$devi->getId();
        }
        elseif (strlen($devi->getId()) == 3) {
            $fact = 'DEV00' .$devi->getId();
        }
        elseif (strlen($devi->getId()) == 4) {
            $fact = 'DEV0' .$devi->getId();
        }
        elseif (strlen($devi->getId()) == 5) {
            $fact = 'DEV' .$devi->getId();
        }


        
        /* Header settings */
        $invoice->setLogo("images/logo_doc.png");   //logo image path
        $invoice->setColor("#2780e3");      // pdf color scheme
        $invoice->setType("Devis");    // Invoice Type
        $invoice->setReference($fact);   // Reference
        $invoice->setDate(strftime("%d %B %Y", $devi->getCreatedAt()));   //Billing Date
        $invoice->setDue(strftime("%d %B %Y", $devi->getCreatedAt() + 2592000));    // Due Date
        
        
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

         if ($devi->getClient()['societe'] === null) {
             $invoice->setTo([
                $client,
                $adresse_client,
                $adresse2,
                $pays_client,
            ]);
         }
         else {
            $invoice->setTo([
                strtoupper($devi->getClient()['societe']),
                $client,
                $adresse_client,
                $adresse2,
                $pays_client,
            ]);
         }

        


             $tableau = $devi->getServices();
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
