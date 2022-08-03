<?php

namespace Akyos\BuilderBundle\Form;

use Akyos\BuilderBundle\Entity\BuilderTemplate;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChoiceBuilderTemplateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('choice_template', EntityType::class, ['label' => 'Ajouter un template prédéfini sur votre page', 'class' => BuilderTemplate::class, 'choice_label' => 'title', 'placeholder' => 'Choisir un template à ajouter', 'help' => 'Le template se mettra dans la page.',]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([]);
    }
}
