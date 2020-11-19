<?php

namespace Akyos\BuilderBundle\Form;

use Akyos\BuilderBundle\Entity\ComponentTemplate;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ComponentTemplateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, [
                'label' => "Nom du composant"
            ])
            ->add('slug', null, [
                'label' => "Slug du composant"
            ])
            ->add('shortDescription', null, [
                'label' => "Courte description"
            ])
            ->add('isContainer', null, [
                'label' => "Est-ce un composant-conteneur ?"
            ])
            ->add('prototype', null, [
                'label' => "Définir un template de prototype",
                'help' => "Si ce champ est vide, celui-ci est rempli par 'default'",
                'empty_data' => "default",
            ])
            ->add('componentFields', CollectionType::class, [
                'entry_type' => ComponentFieldType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'label' => "Paramètres du composant",
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ComponentTemplate::class
        ]);
    }
}
