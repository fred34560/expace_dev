<?php

namespace App\Form\Admin;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class ServicesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('type', TextType::class, [
            'label' => 'Type d\'évènement'
        ])
        ->add('info', TextType::class, [
            'label' => 'Info complémentaire'
        ])
        ->add('quantite', IntegerType::class, [
            'label' => 'Quantité',
            'attr' => [
                'step' => '0.5',
                'min' =>'0'
            ]
        ])
        ->add('tarif', MoneyType::class, [
            'label' => 'Prix unitaire',
            'attr' => [
                'step' => '0.5'
            ]
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
