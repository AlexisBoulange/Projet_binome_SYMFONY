<?php

namespace App\Form;

use App\Entity\Module;
use App\Entity\Categorie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ModuleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('libelle', TextType::class, [
            'label' => 'Libellé',
            'required' => true,
            'attr' => ['class' => 'uk-input'],
        ])
        ->add('descriptif', TextareaType::class, [
            'label' => 'Descriptif',
            'attr' => ['class' => 'uk-input'],
        ])
        ->add('categorie', EntityType::class, [
            'label' => 'Categorie',
            'class' => Categorie::class,
            'choice_label' => 'nom',
            'multiple' => true,
            'required' => true,
            'attr' => ['class' => 'uk-select'],
        ])
        ->add('envoyer', SubmitType::class, [
            'attr' => ['class' => 'uk-button uk-button-secondary uk-margin-top'],
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Module::class,
        ]);
    }
}
