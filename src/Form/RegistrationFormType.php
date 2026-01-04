<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
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
            ->add('lastName', TextType::class, [
                'label' => 'Nom',
                'attr' => ['class' => 'form-control mt-3'],
                'constraints' => [new NotBlank(['message' => 'Veuillez renseigner votre nom.'])],
            ])
            ->add('firstName', TextType::class, [
                'label' => 'Prénom',
                'attr' => ['class' => 'form-control mt-3'],
                'constraints' => [new NotBlank(['message' => 'Veuillez renseigner votre prénom.'])],
            ])
            ->add('email', EmailType::class, [
                'label' => 'Adresse email',
                'attr' => ['class' => 'form-control mt-3'],
                'constraints' => [new NotBlank(['message' => 'Veuillez renseigner votre email.'])],
            ])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'mapped' => false,
                'error_bubbling' => false,
                'first_options' => [
                    'label' => 'Mot de passe',
                    'attr' => ['class' => 'form-control mt-3'],
                ],
                'second_options' => [
                    'label' => 'Confirmer le mot de passe',
                    'attr' => ['class' => 'form-control mt-3'],
                ],
                'invalid_message' => 'Les mots de passe ne correspondent pas.',
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez entrer un mot de passe.']),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Le mot de passe doit faire au moins {{ limit }} caractères.',
                    ]),
                ],
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'label' => "J'accepte les conditions générales d'utilisation",
                'constraints' => [
                    new IsTrue(['message' => 'Vous devez accepter les CGU.']),
                ],
                'row_attr' => ['class' => 'form-check'],
                'label_attr' => ['class' => 'form-check-label'],
                'attr' => ['class' => 'form-check-input cguInput'],
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
