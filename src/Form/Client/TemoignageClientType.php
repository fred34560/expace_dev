<?php

namespace App\Form\Client;

use App\Entity\TemoignageClient;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\RangeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TemoignageClientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('message', TextareaType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Votre commentaire ne peut être vide',
                    ]),
                    new Length([
                        'min' => 25,
                        'max' => 205,
                    ]),
                ],
                'label' => 'Votre Commentaire'
            ])
            ->add('note', RangeType::class, [
                'attr' => [
                    'class' => 'custom-range',
                    'min' => '0',
                    'max' => '10',
                    'step' => '1',
                    'value' => '0',
                    'oninput' => 'result4.value=parseInt(temoignage_client_note.value)'
                ],
                'label' => 'Notez la prestation'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TemoignageClient::class,
        ]);
    }
}
