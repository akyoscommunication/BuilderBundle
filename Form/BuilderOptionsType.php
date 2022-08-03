<?php

namespace Akyos\BuilderBundle\Form;

use Akyos\BuilderBundle\Entity\BuilderOptions;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BuilderOptionsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('hasBuilderEntities', ChoiceType::class, ['label' => 'Activer le page builder sur les entités :', 'choices' => $options['entities'], 'choice_label' => function ($choice, $key, $value) {
                return $value;
            }, 'multiple' => true, 'expanded' => true]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['data_class' => BuilderOptions::class, 'entities' => []]);
    }
}
