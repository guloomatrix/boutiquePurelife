<?php

namespace App\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType as TypeTextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('prenom', TypeTextType::class, [
                'label'=>'votre prenom',
                'attr'=> [
                    'placeholder' => 'Merci de saisir votre prénom'
                ]

            ])
            ->add('nom', TypeTextType::class, [
                'label'=>'votre nom',
                'attr'=> [
                    'placeholder' => 'Merci de saisir votre nom'
                ]

            ])
            ->add('email', EmailType::class, [
                'label'=>'votre email',
                'attr'=> [
                    'placeholder' => 'Merci de saisir votre email'
                ]

            ])
            ->add('content', TextareaType::class,  [
                'label'=>'votre message',
                'attr'=> [
                    'placeholder' => 'En quoi pouvons-nous vous aider'
                ]

            ])
            ->add('submit', SubmitType::class,  [
                'label'=>'envoyer',
                'attr' => [
                    'class' => 'btn btn-success col-sm-15 col-md-13 col-lg-11 col-xl-12'
                    
                ]
             

            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
