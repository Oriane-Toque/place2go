<?php

namespace App\Form;

use App\Entity\Event;
use App\Entity\Category;
use DateTime;
use App\Repository\CategoryRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre de la sortie *',
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description de la sortie',
            ])
            ->add('event_date', DateTimeType::class, [
                'label' => 'Date de la sortie *',
                'data' => new DateTime(),
            ])
            ->add('address', TextType::class, [
                'label' => 'Adresse',
            ])
            ->add('city', TextType::class, [
                'label' => 'Ville *',
            ])
            ->add('maxAttendants', ChoiceType::class, [
                'label' => 'Nbre max de participants *',
                'choices' => range(1,20,1),
                'choice_label' => function ($value) {
                    return $value;
                }
            ])
            ->add('isActive', HiddenType::class, [
                'data' => true,
            ])
            ->add('categories', EntityType::class, [
                'label' => 'Catégories *',
                'class' => Category::class,
                'multiple' => true,
                'choice_label' => 'name',
                'expanded' => true,
                'query_builder' => function (CategoryRepository $cr) {
                    return $cr->createQueryBuilder('c')
                        ->orderBy('c.name', 'ASC');
                },
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
