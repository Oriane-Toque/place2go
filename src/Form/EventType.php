<?php

namespace App\Form;

use App\Entity\Event;
use App\Entity\Category;
use DateTime;
use App\Repository\CategoryRepository;
use DateTimeZone;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\Count;

class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            // Get Form data
            $form = $event->getForm();
            $event = $event->getData();

            $form->add('title', TextType::class, [
                'label' => 'Titre de la sortie *',
                'required' => true,
                'attr' => [
                    'placeholder' => 'Sortie au cinéma, Aller boire un coup, ...',
                ]
            ])
                ->add('description', TextareaType::class, [
                    'label' => 'Description de la sortie *',
                    'required' => true,
                    'attr' => [
                        'placeholder' => 'Détaillez votre sortie (description, organisation, lieu...)',
                    ]
                ]);

            if ($event->getId() == null) {
                $form
                    ->add('event_date', DateTimeType::class, [
                        'label' => 'Date de la sortie *',
                        'required' => true,
                        'data' => new DateTime('now', new DateTimeZone('Europe/Paris')),
                        'years' => range(date('Y'), date('Y')+5),
                    ]);
            } else {
                $form
                    ->add('event_date', DateTimeType::class, [
                        'label' => 'Date de la sortie *',
                        'required' => true,
                        'data' => $event->getEventDate(),
                        'years' => range(date('Y'), date('Y')+5),
                    ]);
            }

            $form
                ->add('address', HiddenType::class, [
                    'label' => 'Adresse',
                    'required' => false,
                ])
                ->add('lat', HiddenType::class, [
                    'required' => false,
                ])
                ->add('lon', HiddenType::class, [
                    'required' => false,
                ])
                ->add('maxAttendants', ChoiceType::class, [
                    'label' => 'Nbre max de participants *',
                    'required' => true,
                    'choices' => range(1, 20, 1),
                    'choice_label' => function ($value) {
                        return $value;
                    }
                ])
                ->add('categories', EntityType::class, [
                    'class' => Category::class,
                    'label' => 'Catégories *',
                    'required' => true,
                    'multiple' => true,
                    'choice_label' => 'name',
                    'expanded' => true,
                    'query_builder' => function (CategoryRepository $cr) {
                        return $cr->createQueryBuilder('c')
                            ->orderBy('c.name', 'ASC');
                    },
                    'constraints' => [
                        new Count(['min' => 1, 'minMessage' => 'Vous devez choisir au moins une catégorie']),
                        new Count(['max' => 3, 'maxMessage' => 'Vous ne pouvez pas choisir plus de 3 catégories']),
                    ],
                    'attr' => [
                        'style' => 'columns: 2'
                    ]
                ]);
        });
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
