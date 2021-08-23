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
                'required' => true,
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description de la sortie *',
                'required' => true,
            ])
            ->add('event_date', DateTimeType::class, [
                'widget' => 'single_text',
                'label' => 'Date de la sortie *',
                'data' => new DateTime(),
                'required' => true,
            ])
            ->add('address', HiddenType::class, [
                'required' => false
            ])
            ->add('city', TextType::class, [
                'label' => 'Ville *',
                'required' => true,
            ])
            ->add('maxAttendants', ChoiceType::class, [
                'label' => 'Nbre max de participants *',
                'required' => true,
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
                'required' => true,
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
