<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class EditProfilType extends AbstractType
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
        ->add('firstname', TextType::class, [
            'attr' => [
                'class' => 'form-control mb-3',
                'minlength' => '2',
                'maxlength' =>'45',
            ],
            'label' => 'Prenom',
            'label_attr' => [
                'class' => 'form_label'
            ],
            'constraints' => [
                new Assert\Length(['min' => 2, 'max'=> 45])
            ]
        ])
        ->add('lastname', TextType::class, [
            'attr' => [
                'class' => 'form-control mb-3',
                'minlength' => '2',
                'maxlength' =>'45',
            ],
            'label' => 'Nom',
            'label_attr' => [
                'class' => 'form_label'
            ],
            'constraints' => [
                new Assert\Length(['min' => 2, 'max'=> 45])
            ]
        ])
        ->add('fix_number', TextType::class, [
            'attr' => [
                'class' => 'form-control mb-3',
                'minlength' => '2',
                'maxlength' =>'15',
            ],
            'label' => 'Telephone Fixe',
            'label_attr' => [
                'class' => 'form_label'
            ],
            'constraints' => [
                new Assert\Length(['min' => 2, 'max'=> 45])
            ]
        ])
        ->add('digicode', TextType::class, [
            'attr' => [
                'class' => 'form-control mb-3',
                'minlength' => '2',
                'maxlength' =>'15',
            ],
            'label' => 'Digicode',
            'label_attr' => [
                'class' => 'form_label'
            ],
            'constraints' => [
                new Assert\Length(['min' => 2, 'max'=> 45])
            ]
        ])
        ->add('address_1', TextType::class, [
            'attr' => [
                'class' => 'form-control mb-3',
                'minlength' => '2',
                'maxlength' =>'45',
            ],
            'label' => 'Adresse 1',
            'label_attr' => [
                'class' => 'form_label'
            ],
            'constraints' => [
                new Assert\Length(['min' => 2, 'max'=> 45])
            ]
        ])
        ->add('address_2', TextType::class, [
            'attr' => [
                'class' => 'form-control mb-3',
                'minlength' => '2',
                'maxlength' =>'45',
            ],
            'label' => 'Adresse 2',
            'label_attr' => [
                'class' => 'form_label'
            ],
            'constraints' => [
                new Assert\Length(['min' => 2, 'max'=> 45])
            ]
        ])
        ->add('address_3', TextType::class, [
            'attr' => [
                'class' => 'form-control mb-3',
                'minlength' => '2',
                'maxlength' =>'45',
            ],
            'label' => 'Adresse 3',
            'label_attr' => [
                'class' => 'form_label'
            ],
            'constraints' => [
                new Assert\Length(['min' => 2, 'max'=> 45])
            ]
        ])
        ->add('cb_1', TextType::class, [
            'attr' => [
                'class' => 'form-control mb-3',
                'minlength' => '2',
                'maxlength' =>'45',
            ],
            'label' => 'N de Carte Banquaire 1',
            'label_attr' => [
                'class' => 'form_label'
            ],
            'constraints' => [
                new Assert\Length(['min' => 2, 'max'=> 45])
            ]
        ])
        ->add('cb_2', TextType::class, [
            'attr' => [
                'class' => 'form-control mb-3',
                'minlength' => '2',
                'maxlength' =>'45',
            ],
            'label' => 'N de Carte Banquaire 2',
            'label_attr' => [
                'class' => 'form_label'
            ],
            'constraints' => [
                new Assert\Length(['min' => 2, 'max'=> 45])
            ]
        ])
        ->add('Editer', SubmitType::class, [
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