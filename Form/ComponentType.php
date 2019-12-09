<?php

namespace Akyos\BuilderBundle\Form;

use Akyos\BuilderBundle\Entity\Component;
use Akyos\BuilderBundle\Entity\ComponentValue;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ComponentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $component = $builder->getData();

        if ($component instanceof Component) {
            foreach ($component->getComponentTemplate()->getComponentFields() as $field) {
                $hasShortcodeValue = 0;
                foreach($component->getComponentValues() as $componentValue) {
                    if($componentValue->getComponentField() == $field) {
                        $hasShortcodeValue = 1;
                    }
                }
                if(!$hasShortcodeValue) {
                    $newComponentValue = new ComponentValue();
                    $newComponentValue->setComponentField($field);
                    $component->addComponentValue($newComponentValue);
                }
            }
        }

        $builder
            ->add('customId')
            ->add('customClasses')
//            ->add('position')
            ->add('visibilityXS')
            ->add('visibilityS')
            ->add('visibilityM')
            ->add('visibilityL')
            ->add('visibilityXL')
            ->add('componentValues', CollectionType::class, [
                'entry_type'    => ComponentValueType::class,
                'entry_options' => array(
                    'label' => false
                ),
                'label' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Component::class,
        ]);
    }
}
