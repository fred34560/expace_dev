<?php

namespace App\Form\Admin;

use App\Entity\Users;
use App\Entity\Messages;
use Symfony\Component\Form\AbstractType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class MessagesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titre', TextType::class, [
                'label' => 'Sujet'
            ])
            ->add('message', CKEditorType::class, [
                'label' => 'Mon message',
                'config_name' => 'default'
            ])
            ->add('client', EntityType::class, [
                'class' => Users::class,
                'choice_label' => 'email',
                'label' => 'Choisir le destinataire',
                'placeholder' => 'Sélectionnez une option...',
                'attr' => ['class' => 'custom-select']

            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Messages::class,
        ]);
    }
}
