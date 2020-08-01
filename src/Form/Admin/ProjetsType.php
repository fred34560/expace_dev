<?php

namespace App\Form\Admin;

use App\Entity\Users;
use App\Entity\Projets;
use Symfony\Component\Form\AbstractType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Vich\UploaderBundle\Form\Type\VichFileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

class ProjetsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titre', TextType::class, [
                'label' => 'Nom du projet'
            ])
            ->add('statut', HiddenType::class, [
                'data' => 'ouverture'
            ])
            ->add('besoinsClient', CKEditorType::class, [
                'label' => 'Besoin du client',
                'config_name' => 'main_config'
            ])
            ->add('propositionCommercialFile', VichFileType::class, [
                'label' => 'Ajouter la proposition commerciale',
                'required' => false
            ])
            ->add('cahierChargeFile', VichFileType::class, [
                'label' => 'Ajouter le cahier des charges',
                'required' => false
            ])
            /*
            ->add('devis', FileType::class, [
                'label' => 'Ajouter le devis',
                'required' => false
            ])
            */
            ->add('client', EntityType::class, [
                'class' => Users::class,
                'choice_label' => 'nom',
                'label' => 'Choisir le client',
                'placeholder' => 'Sélectionnez une option...',
                'attr' => ['class' => 'custom-select']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Projets::class,
        ]);
    }
}
