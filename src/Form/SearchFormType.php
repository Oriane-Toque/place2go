<?php

namespace App\Form;

use App\Data\SearchData;
use App\Entity\Category;
use Doctrine\Common\Collections\Collection;
use PhpParser\ErrorHandler\Collecting;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\Collection as ConstraintsCollection;

/**
 * Form to handle Events search
 */
class SearchFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('q', TextType::class, [
                'required' => false,
                'attr' => [
                    'placeholder' => 'Rechercher une activité par Ville ou adresse',
                ]
            ])
            ->add('categories', EntityType::class, [
                'class' => Category::class,
                'required' => false,
                'expanded' => true,
                'multiple' => true,
            ]);
        
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SearchData::class,
            'method' => 'GET',
            'csrf_protection' => false,
        ]);
    }

    public function getBlockPrefix()
    {
        return '';
    }
}
