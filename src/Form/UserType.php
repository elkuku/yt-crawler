<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email')
            // ->add('role')
            ->add(
                'role',
                ChoiceType::class,
                [
                    'choices'  =>
                        User::ROLES
                    //     [
                    //     'Admin'       => 'ROLE_ADMIN',
                    //     'Editor'      => 'ROLE_EDITOR',
                    //     'Agent'       => 'ROLE_AGENT',
                    //     'Intro Agent' => 'ROLE_INTRO_AGENT',
                    //     'User'        => 'ROLE_USER',
                    // ]
        ,
                    // 'multiple' => true,
                    'attr'     => [
                        'class'      => 'selectpicker',
                        'data-style' => 'btn-success',
                    ],
                ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
