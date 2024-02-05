<?php

namespace App\Form;

use App\Entity\Event;
use App\Entity\Category;
use Doctrine\ORM\EntityRepository;
use Doctrine\DBAL\Query\QueryBuilder;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Titre',
                'required' => true,
                'attr' => [
                    'placeholder' => 'Conférence de ...'
                ]
            ])
            ->add('categories', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'titre',
                'multiple' => true,
                'expanded' => true
            ])
            ->add('date', DateTimeType::class, [
                'label' => 'Date d\'evenement', 
                'required' => true,
                'widget' => 'single_text'
            ])
            ->add('location', TextType::class, [
                'label' => 'Lieu de l\'evenement ',
                'required' => true,
                'attr' => [
                    'placeholder' => 'Lieu...'
                ]
            ])

            ->add('description', TextType::class, [
                'label' => 'Description',
                'required' => true,
                'attr' => [
                    'placeholder' => 'description...',
                    'type'=>'text'
                ]
            ])
            ->add('participate', CheckboxType::class, [
                'label' => 'Participer',
                'required' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}