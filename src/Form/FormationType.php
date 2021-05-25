<?php

namespace App\Form;

use App\Entity\Formation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class FormationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('nom', TextType::class, [
            'label' => 'Nom',
            'required' => true,
            'attr' => ['class' => 'uk-input'],
        ])
        ->add('descriptif', TextareaType::class, [
            'label' => 'Descriptif',
            'attr' => ['class' => 'uk-textarea'],
        ])
        ->add('envoyer', SubmitType::class, [
            'attr' => ['class' => 'uk-button uk-button-secondary uk-margin-top'],
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Formation::class,
        ]);
    }
}
