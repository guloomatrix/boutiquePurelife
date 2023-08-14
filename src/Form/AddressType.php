<?php

namespace App\Form;

use App\Entity\Address;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class,[
                'label' => 'Quel nom souhaitez-vous donner à votre chemin ?',
                'attr' => [
                    'placeholder' => 'Nommez votre adresse'
                ]
            ])
            ->add('firstname',TextType::class, [
                'label' => 'Votre prénom ',
                'attr' => [
                    'placeholder' => 'Entrez Votre prénom'
                ]
            ])
            ->add('lastname', TextType::class,[
                'label' => 'Votre nom ',
                'attr' => [
                    'placeholder' => 'Entrez Votre nom'
                ]
            ])
            ->add('compagny',TextType::class, [
                'label' => 'Votre société',
                'required' => false,
                'attr' => [
                    'placeholder' => '(facultatif) Entrez le nom de votre société'
                ]
            ])
            ->add('address',TextType::class, [
                'label' => 'Votre adresse ',
                'attr' => [
                    'placeholder' => 'Entrez votre adresse'
                ]
            ])
            ->add('postal',TextType::class, [
                'label' => 'votre code postal',
                'attr' => [
                    'placeholder' => 'Entrez votre code postal'
                ]
            ])
            ->add('city',TextType::class, [
                'label' => 'Votre ville',
                'attr' => [
                    'placeholder' => 'Entrez votre ville'
                ]
            ])
            ->add('country',CountryType::class, [
                'label' => 'Votre pays',
                'attr' => [
                    'placeholder' => 'Choisissez votre pays'
                ]
            ])
            ->add('phone',TextType::class, [
                'label' => 'Votre téléphone',
                'attr' => [
                    'placeholder' => 'Entrez votre numéro de téléphone'
                ]
            ])
            ->add('submit', SubmitType::class,[
                'label' => 'Valider',
                'attr' => [
                    'class' => 'btn-block btn-info'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Address::class,
        ]);
    }
}
