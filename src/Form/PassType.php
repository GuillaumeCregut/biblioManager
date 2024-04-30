<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Validator\Constraints\Length;

class PassType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('old_password', PasswordType::class, [
                'label' => 'Mot de passe actuel',
                'attr' => [
                    'class' => 'form-control',
                ]
            ])
            ->add('new_password', PasswordType::class, [
                'label' => 'Nouveau mot de passe',
                'attr' => [
                    'class' => 'form-control',
                ],
                'constraints' => [
                    new Length(['min' => 6])
                ]
            ])
            ->add('confirm_password', PasswordType::class, [
                'label' => 'Confirmez le mot de passe',
                'attr' => [
                    'class' => 'form-control',
                ],
                'constraints' => [
                    new Length(['min' => 6])
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
