<?php

namespace App\Form;

use App\Entity\Group;
use App\Entity\GroupUsers;
use App\Entity\User;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
// use Symfony\Component\Validator\Constraints as Assert;

// class NullableDate extends Assert\DateTime
// {
//     public function __construct($options = null)
//     {
//         parent::__construct($options);

//         $this->nullable = true; // Rendre la date nullable
//     }
// } 

class GroupType extends AbstractType
{
    // Quand un user pourra se connecter, tester : (ça va avec ce qu'il y a plus bas)
    // public function __construct(
    //     private Security $security,
    // ) {
    // }
    
    
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom du groupe :'
            ])
            ->add('is_default', CheckboxType::class, [
                'label' => 'Groupe par défault ?',
                'required' => false
            ])
            // ->add('data', CheckboxType::class, [
            //     'label' => ['Prénom','Nom','Pseudo'] ,
            //     'required' => false,
            //     'multiple' => true
            // ])
            //  ->add('data_sharing')
            ->add('expiration_date', DateType::class, [
                // renders it as a single text box
                'widget' => 'single_text',
                'label' => 'Date d\'expiration du groupe :',
                'required' => false,
                'empty_data' => null,
                'data' => new \DateTime("2049-12-31 00:00:00")
            ])
            // ->add('expiration_date', DateType::class, [
            //     'widget' => 'single_text',
            //     'constraints' => [
            //         new NullableDate(),
            //     ],
            // ])

            ->add('submit', SubmitType::class, [
                'label' => 'Enregistrer'
            ]);
            
        ;

        // $user = $this->security->getUser();
     
       
        
        // if (!$user) {
        //     throw new \LogicException(
        //         'The GroupType cannot be used without an authenticated user!'
        //     );
        // }

        // $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) use ($user) {
        //     if (null !== $event->getData()->getUserId()) {
                
        //         return;
        //     }

        //     $form = $event->getForm();

        //     $formOptions = [
        //         // 'class' => User::class,
        //         'choice_label' => 'user',
        //         // 'query_builder' => function (UserRepository $userRepository) use ($user) {
        //         //    
        //         // },
        //     ];

        //     // create the field, this is similar the $builder->add()
        //     // field name, field type, field options
        //     $form->add('user_id', EntityType::class, $formOptions);
        // });
    }

    


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Group::class,
            'data_sharing'=> null,
            'user' => null,
            'expiration_date' => null
        ]);
    }

    
}
