<?php

namespace App\Form;

use App\Entity\Session;
use App\Entity\Formation;
use App\Entity\Stagiaire;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class SessionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('dateD', DateType::class, [
                'label' => 'Date de début',
                'widget' => 'single_text',
                'required' => true,
                'attr' => ['class' => 'uk-input'],
            ])
            ->add('dateF', DateType::class, [
                'label' => 'Date de fin',
                'widget' => 'single_text',
                'required' => true,
                'attr' => ['class' => 'uk-input'],
            ])
            ->add('nbPlaces', NumberType::class, [
                'label' => 'Nombre de places', 
                'html5' => true,
                'attr' => ['class' => 'uk-input', 'min' => 1],
            ])
            ->add('formation', EntityType::class, [
                'label' => 'Formation',
                'class' => Formation::class,
                'choice_label' => 'nom',
                'required' => true,
                'attr' => ['class' => 'uk-select'],
            ])
            ->add('stagiaire', CollectionType::class, [
                'entry_type' => EntityType::class,
                'entry_options' => [
                    'label' => "Choisir un stagiaire",
                    'class' => Stagiaire::class,
                    'attr' => ['class' => 'uk-select'],
                ],
                'by_reference' => false,
                'required' => false,
                'allow_add' => true,
                'allow_delete' => true,
                'label' => false,
            ])
            ->add('envoyer', SubmitType::class, [
                'attr' => ['class' => 'uk-button uk-button-secondary uk-margin-top'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Session::class,
        ]);
    }
}
