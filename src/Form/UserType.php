<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Validator\Constraints\File;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            // Get User and Form data
            $user = $event->getData();
            $form = $event->getForm();

            // Start create form
            $form->add('nickname', TextType::class, [
                'label' => 'Pseudo *',
                'required' => true,
            ])
            ->add('firstname', TextType::class, [
                'label' => 'Prénom *',
                'required' => true,
            ])
            ->add('lastname', TextType::class, [
                'label' => 'Nom *',
                'required' => true,
            ])
            ->add('avatar', FileType::class, [
                'label' => 'Image de profil (jpg ou png max 500Ko)',

                // unmapped means that this field is not associated to any entity property
                'mapped' => false,

                // make it optional so we don't have to re-upload the file
                // every time you edit the User
                'required' => false,

                // unmapped fields can't define their validation using annotations
                // in the associated entity, so you can use the PHP constraint classes
                'constraints' => [
                    new File([
                        'maxSize' => '500k',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                        ],
                        'mimeTypesMessage' => 'Ce format n\'est pas pris en charge',
                    ])
                ],
            ])
            ->add('city', TextType::class, [
                'label' => 'Ville *',
                'required' => true,
            ])
            ->add('phone', TextType::class, [
                'label' => 'Téléphone',
                'required' => false,
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description *',
                'required' => true,
            ])
            ->add('birthday', BirthdayType::class, [
                'label' => 'Date de naissance *',
                'required' => true,
            ])
            ->add('isActive', HiddenType::class, [
                'data' => 1,
            ])
            ->add('isVerified', HiddenType::class, [
                'data' => 0,
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email *',
                'required' => true,
            ]);
            // If USER is new, the password is mapped to USER
            // It inherits the constraints defined in the entity
            if (!$user || null === $user->getId()) {
                $form->add('password', RepeatedType::class, [
                    'type' => PasswordType::class,
                    'invalid_message' => 'Les mots de passes sont différents',
                    'first_options' => [
                        'label' => 'Mot de passe',
                        'help' => 'Pas de contraintes pour le moment'
                    ],
                    'second_options' => ['label' => 'Répéter le mot de passe'],
                ]);
            } else {
                // Otherwise we add mapped = false to isolate it from the entity
                // We need to specify the constraints manually
                // As well as empty_data + placeholder
                $form->add('password', RepeatedType::class, [
                    'type' => PasswordType::class,
                    'invalid_message' => 'Les mots de passes sont différents',
                    'empty_data' => '',
                    'mapped' => false,
                    'first_options' => [
                        /*'constraints' => [
                            new Length([
                                'min' => 4,
                                'minMessage' => "Le mot de passe doit contenir au moins {{ limit }} caractères",
                            ]),
                        ], */
                        'attr' => [
                            'placeholder' => 'Laissez vide si inchangé...',
                        ],
                        'label' => 'Mot de passe',
                        'help' => 'Pas de contraintes pour le moment'
                    ],
                    'second_options' => ['label' => 'Répéter le mot de passe'],
                ]);
            };
        });
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
