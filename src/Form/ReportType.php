<?php

namespace App\Form;

use App\Entity\Report;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
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
								'Violence physique' => 'violence_physique',
								'Violence verbale' => 'violence_verbale',
								'Harcèlement (spams, contacts abusifs)' => 'harcelement',
								'Comportements Haineux' => 'comportements_haineux',
								'Autre' => 'autre',
							]
						])
            ->add('message', TextareaType::class, [
							'label' => 'Préciser la situation',
							'help' => 'Détaillez au maximum, circonstances (en ligne, au cours d\'une sortie), quand ? ...',
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
