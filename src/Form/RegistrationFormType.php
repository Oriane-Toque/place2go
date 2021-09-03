<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Validator\Constraints\NotCompromisedPassword;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // register form creation
        // adding constraints
        $builder
            ->add('firstname', TextType::class, [
                'label' => 'Prénom *',
                'required' => true,
                'attr' => [
                    'placeholder' => 'ex : Jean'
                ]
            ])
            ->add('lastname', TextType::class, [
                'label' => 'Nom de famille *',
                'required' => true,
                'attr' => [
                    'placeholder' => 'ex : Dupont'
                ]
            ])
            ->add('nickname', TextType::class, [
                'label' => 'Pseudo *',
                'required' => true,
                'attr' => [
                    'placeholder' => 'ex : jd'
                ]
            ])
            ->add('avatar', HiddenType::class, [
                'required' => false,
            ])
            // ajout d'un évènement sur le formulaire pour différencier l'édition de la création d'un utilisateur
            // mapped false pour le password afin de ne pas relier à l'entité en cas de modification de password
            ->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {

                // je récupère mon formulaire
                $form = $event->getForm();

                // je récupère l'utilisateur courant
                $user = $event->getData();

                // si le user n'a pas d'id, c'est qu'il n'a jamais été persisté
                if (null === $user->getId()) {
                    // si nouveau user on demande de renseigner tous ces champs
                    $form
                        ->add('birthday', BirthdayType::class, [
                            'label' => 'Date de naissance',
                            'widget' => 'choice',
                            'days' => range(1, 31),
                            'months' => range(1, 12),
                            'years' => range(date('Y'), date('Y') - 90),
                            'format' => 'ddMMMMyyyy'
                        ])
                        ->add('city', TextType::class, [
                            'label' => 'Ville *',
                            'required' => true,
                            'attr' => [
                                'placeholder' => 'ex : Paris'
                            ]
                        ])
                        ->add('email', EmailType::class, [
                            'label' => 'Adresse email *',
                            'required' => true,
                            'attr' => [
                                'placeholder' => 'ex : email@exemple.fr'
                            ]
                        ])
                        ->add('password', RepeatedType::class, [
                            'type' => PasswordType::class,
                            'required' => true,
                            'first_options'  => [
                                'constraints' => [
                                    new Regex('/^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*#?&-\/])[A-Za-z\d@$!%*#?&-\/]{8,}$/', 'Ce mot de passe ne convient pas.'),
                                    new NotCompromisedPassword(),
                                    new NotBlank(),
                                ],
                                'label' => 'Mot de passe *',
                                'help' => 'Minimum huit caractères, une lettre, un chiffre et un caractère spécial.'
                            ],
                            'second_options' => [
                                'label' => 'Répéter le mot de passe *',
                                'constraints' => [
                                    new NotBlank(),
                                ],
                            ],
                        ]);
                } else {
                    // si user déjà existant donc mode Edition
                    $form
                        ->add('city', TextType::class, [
                            'label' => 'Ville',
                        ])
                        ->add('description', TextareaType::class, [
                            'label' => 'Description',
                        ])
                        ->add('email', EmailType::class)
                        ->add('oldpassword', PasswordType::class, [
                            'label' => 'Ancien mot de passe',
                            'mapped' => false,
                            'help' => 'Si vous désirez changer de mot de passe, veuillez renseigner votre ancien mot de passe au préalable !',
                            'attr' => [
                                "autocomplete" => 'new-password'
                            ]
                        ])
                        ->add('password', RepeatedType::class, [
                            'type' => PasswordType::class,
                            'invalid_message' => 'Les mots de passe ne correspondent pas.',
                            'mapped' => false,
                            'required' => false,
                            'first_options'  => [
                                'constraints' => [
                                    new Regex('/^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*#?&-\/])[A-Za-z\d@$!%*#?&-\/]{8,}$/'),
                                    new NotCompromisedPassword(),
                                ],
                                'label' => 'Nouveau mot de passe',
                                'help' => 'Minimum huit caractères, une lettre, un chiffre et un caractère spécial.'
                            ],
                            'second_options' => [
                                'label' => 'Répéter le mot de passe',
                            ],
                        ]);
                }
            });
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
