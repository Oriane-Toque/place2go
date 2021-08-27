<?php

namespace App\Form;

use App\Entity\Report;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReportType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('type', ChoiceType::class, [
							'label' => 'Signalement',
							'expanded' => false,
							'multiple' => false,
							'placeholder' => 'Raison du signalement',
							'choices' => [
								'violence_physique' => 'Violence physique',
								'violence_verbale' => 'Violence verbale',
								'harcelement' => 'Harcèlement (spams, contacts abusifs)',
								'comportements_haineux' => 'Comportements Haineux',
								'autre' => 'Autre',
							]
						])
            ->add('message', TextType::class, [
							'label' => 'Préciser la situation'
						])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Report::class,
        ]);
    }
}
