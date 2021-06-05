<?php

namespace App\Form;

use App\Entity\Session;
use App\Entity\Stagiaire;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class StagiaireSessionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
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
