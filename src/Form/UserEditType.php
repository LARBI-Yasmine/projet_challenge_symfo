<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Event;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class UserEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('firstname',TextType::class, [
            'label' => 'Prénom',
            'required' => true,
            'attr' => [
                'placeholder' => "Votre nouveau prénom",
                
            ],
            ])
        ->add('lastname',TextType::class, [
            'label' => 'Nom',
            'required' => true,
            'attr' => [
                'placeholder' => "Votre nouveau nom",
                
            ],
            ])
        ->add('email',EmailType::class) 
        ->add('password', PasswordType::class, [
            'label' => 'Le mot de passe'
        ]); 
        
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}