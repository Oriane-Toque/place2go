<?php

namespace App\Form;

use App\Entity\Event;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre de la sortie *'
            ])
            ->add('description')
            ->add('event_date', DateType::class, [
                'label' => 'Date de la sortie *'
            ])
            ->add('address', TextType::class, [
                'label' => 'Adresse'
            ])
            ->add('city', TextType::class, [
                'label' => 'Ville *'
            ])
            ->add('lat')
            ->add('lon')
            ->add('maxAttendants', ChoiceType::class, [
                'label' => 'Nbre max de participants *'
            ])
            ->add('isActive')
            ->add('createdAt')
            ->add('updatedAt')
            ->add('author')
            ->add('categories')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
