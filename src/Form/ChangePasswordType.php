<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class ChangePasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options' => [
                    'label' => 'Nouveau mot de passe',
                    'attr' => ['class' => 'uk-input'],
                ],
                'second_options' => [
                    'label' => 'Confirmez le nouveau mot de passe',
                    'attr' => ['class' => 'uk-input'],
                ],
                'invalid_message' => 'Les mots de passe ne corespondent pas',
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Entrez un mot de passe',
                    ]),
                    new Length([
                        'min' => 8,
                        'minMessage' => 'Votre mot de passe doit comporter au moins {{ limit }} caractères',
                        // max length allowed by Symfony for security reasons
                        'max' => 32,
                        'minMessage' => 'Votre mot de passe doit comporter moins de {{ limit }} caractères',
                    ]),
                    new Regex([
                        'pattern' => "/^(?=.*\d)(?=.*[A-Z])(?=.*[@#$%-])(?!.*(.)\1{2}).*[a-z]/m",
                        'match' => true,
                        'message' => "Attention : Votre mot de passe doit comporter au moins huit caractères, dont des lettres majuscules et minuscules, un chiffre et un symbole."
                    ]),
                ],
            ])
            ->add('envoyer', SubmitType::class, [
                'attr' => ['class' => 'uk-button uk-button-secondary uk-margin-top'],
            ]);
            
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
