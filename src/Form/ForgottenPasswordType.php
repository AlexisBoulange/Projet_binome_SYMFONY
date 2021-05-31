<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ForgottenPasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('emailResetPass', EmailType::class, [
            'label' => 'Email associée à votre compte',
            'required' => true,
            'attr' => ['class' => 'uk-input'],
        ])
        ->add('envoyer', SubmitType::class, [
            'attr' => ['class' => 'uk-button uk-button-secondary uk-margin-top'],
        ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
