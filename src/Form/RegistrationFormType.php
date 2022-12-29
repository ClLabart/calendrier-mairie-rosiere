<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'Adresse e-mail',
                'attr' => ['class' => 'form-control', 'placeholder' => 'Votre adresse e-mail'],
            ])
            ->add('firstname', TextType::class, [
                'label' => 'Prénom',
                'attr' => ['class' => 'form-control', 'placeholder' => 'Votre prénom'],
            ])
            ->add('lastname', TextType::class, [
                'label' => 'Nom de famille',
                'attr' => ['class' => 'form-control', 'placeholder' => 'Votre nom de famille'],
            ])
            ->add('telephone', TelType::class, [
                'required' => false,
                'label' => 'Numéro de téléphone',
                'attr' => ['class' => 'form-control', 'placeholder' => 'Votre numéro de téléphone'],
            ])
            ->add('birthdate', DateType::class, [
                'widget' => 'single_text',
                'required' => false,
                'label' => 'Date de naissance',
                'attr' => ['class' => 'form-control', 'placeholder' => 'Votre date de naissance'],
            ])
            ->add('address', TextType::class, [
                'required' => false,
                'label' => 'Adresse (rue, ville)',
                'attr' => ['class' => 'form-control', 'placeholder' => 'Votre adresse (1 rue Général De Gaulle, Rosières-Près-Troyes)'],
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'Vous devez accepter nos conditions.',
                    ]),
                ],
                'label' => 'Accepter les conditions d\'utilisation',
                'label_attr' => ['class' => 'form-control', 'style' => 'cursor: pointer;'],
                'attr' => ['class' => 'form-check-input mt-0', 'style' => 'cursor: pointer;'],
            ])
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password', 'class' => 'form-control', 'placeholder' => 'Votre mot de passe'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer un mot de passe',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Votre mot de passe doit comporter au moins {{ limit }} caractères',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
                'label' => 'Mot de passe',
            ])
            ->add('save', SubmitType::class, [
                'label' => 'S\'inscrire'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
