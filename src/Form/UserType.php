<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email')
            ->add('phone_number')
            ->add('password')
            ->add('firstname')
            ->add('lastname')
            ->add('status')
            ->add('created_at')
            ->add('updated_at')
            ->add('group_id')
            ->add('digicode')
            ->add('address_1')
            ->add('address_2')
            ->add('address_3')
            ->add('fix_number')
            ->add('cb_1')
            ->add('cb_2')
            ->add('pseudo')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
