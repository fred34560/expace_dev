<?php

namespace App\Form;

use App\Entity\StatsDevelop;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class StatsDevelopType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('projet', TextType::class, [
                'label' => 'Nom du projet'
            ])
            ->add('recompense', ChoiceType::class, [
                'label' => 'Obtention d\'un trophée',
                'choices'  => [
                    'Veuillez choisir' => null,
                    'Nous obtenons un trophée' => true,
                    'Nous n\'obtenons aucun trophée' => false,
                ],
            ])
            ->add('Duree', TextType::class, [
                'label' => 'Durée de développement'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => StatsDevelop::class,
        ]);
    }
}
