<?php

namespace Akyos\BuilderBundle\Form;

use Akyos\BuilderBundle\Entity\ComponentField;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ComponentFieldType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, [
                'label' => "Nom du paramètre"
            ])
            ->add('slug', null, [
                'label' => "Slug du paramètre"
            ])
            ->add('shortDescription', null, [
                'label' => "Courte description"
            ])
            ->add('type', ChoiceType::class, [
                'label' => "Type de champ",
                'choices'  => [
                    'Champ texte'    => "text",
                    'CKEditor'       => "textarea_html",
                    'Zone de texte'  => "textarea",
                    'Image'          => 'image',
                    'Téléphone'          => 'tel',
                    'Mail'          => 'mail',
                    'Lien interne'          => 'pagelink',
                    'Lien externe'          => 'link',
                    'Nombre'          => 'int',
                ],
                'multiple' => false,
                'expanded' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ComponentField::class,
        ]);
    }
}
