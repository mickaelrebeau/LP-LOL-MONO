<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('pseudo', TextType::class, [
            'attr' => [
                'class' => 'form-control mb-3',
                'minlenght' => '2',
                'maxlength' =>'45',
            ],
            'label' => 'Pseudo',
            'label_attr' => [
                'class' => 'form_label'
            ],
            'constraints' => [
                new Assert\Length(['min' => 2, 'max'=> 45])
            ]
        ])
        ->add('email', TextType::class, [
            'attr' => [
                'class' => 'form-control mb-3',
                'minlength' => '2',
                'maxlength' =>'45',
            ],
            'label' => 'Email',
            'label_attr' => [
                'class' => 'form_label'
            ],
            'constraints' => [
                new Assert\Email(),
                new Assert\Length(['min' => 2, 'max'=> 45])
            ]
        ])
        ->add('password', RepeatedType::class, [
            'constraints' => [
                new Assert\Regex(
                    pattern: '^(?=.[A-Za-z])(?=.\d)(?=.[@$!%#?&])[A-Za-z\d@$!%*#?&]{8,}$',
                    match: false,
                    message: 'Ton password doit contenir un nombre, un caractere special', 
                )
            ],
            'type' => PasswordType::class,
            'first_options' => [
                'attr' => [
                    'class' => 'form-control mb-3'
                    ],
                'label' => 'Mot de passe',
                'label_attr' => [
                    'class' => 'form-label'
                    ]
            ],
            'second_options' => [
                'attr' => [
                    'class' => 'form-control'
                    ],
                'label' => 'Confirmation du mot de passe',
                'label_attr' => [
                    'class' => 'form-label'
                    ]
            ],
            'invalid_message' => 'Les mots de passe ne correspondent pas'
        ])
        ->add('agreeTerms', CheckboxType::class, [
            'mapped' => false,
            'constraints' => [
                new Assert\IsTrue([
                    'message' => 'Veuillez accepter les CGU !',
                ])
            ],
            'attr' => [
                'class'=> 'm-4'
            ]
        ])
        ->add('Envoyer', SubmitType::class, [
            'attr' => [
                'class' => 'btn btn-secondary'
            ]
        ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}

