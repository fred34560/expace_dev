<?php

namespace App\Form;

use App\Entity\Users;
use App\Entity\Factures;
use App\Form\ServicesType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class FacturesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('services', CollectionType::class, [
            'entry_type' => ServicesType::class,
            'allow_add' => true,
            'allow_delete' => true,
            'by_reference' => false,
            'prototype' => true,
            'label' => 'Commentcez par ajouter des taches à cette facture'
        ])
            ->add('client', EntityType::class, [
                'class' => Users::class,
                'choice_label' => 'email',
                'label' => 'Choisir le client',
                'placeholder' => 'Sélectionnez une option...',
                'attr' => ['class' => 'custom-select']

            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Factures::class,
        ]);
    }
}
