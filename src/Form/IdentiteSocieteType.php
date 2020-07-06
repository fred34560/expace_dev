<?php

namespace App\Form;

use App\Entity\IdentiteSociete;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class IdentiteSocieteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class)
            ->add('prenom', TextType::class)
            ->add('adresse', TextType::class)
            ->add('codePostal', TextType::class)
            ->add('ville', TextType::class)
            ->add('pays', TextType::class)
            ->add('telephone', TelType::class)
            ->add('email', EmailType::class, [
                'label' => 'Adresse E-mail'
            ])
            ->add('web', UrlType::class, [
                'label' => 'Url du site'
            ])
            ->add('rcs', TextType::class, [
                'label' => 'Numéro RCS'
            ])
            ->add('siren', TextType::class, [
                'label' => 'Numéro Siren'
            ])
            ->add('ape', TextType::class, [
                'label' => 'Code APE'
            ])
            ->add('societe', TextType::class, [
                'label' => 'Nom de la société'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => IdentiteSociete::class,
        ]);
    }
}
