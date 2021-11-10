<?php

namespace Akyos\BuilderBundle\Form;

use Akyos\BuilderBundle\Entity\Component;
use Akyos\BuilderBundle\Entity\ComponentTemplate;
use Akyos\BuilderBundle\Entity\ComponentValue;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ComponentType extends AbstractType
{
    private ?Request $request;

    public function __construct(RequestStack $requestStack)
    {
        $this->request = $requestStack->getCurrentRequest();
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $component = $builder->getData();

        if ($component instanceof Component) {
            /** @var ComponentTemplate $componentTemplate */
            $componentTemplate = $component->getComponentTemplate();
            foreach ($componentTemplate->getComponentFields() as $field) {
                $hasShortcodeValue = 0;
                foreach($component->getComponentValues() as $componentValue) {
                    if($componentValue->getComponentField() === $field) {
                        $hasShortcodeValue = 1;
                    }
                }
                if(!$hasShortcodeValue) {
                    $newComponentValue = new ComponentValue();
                    $newComponentValue->setComponentField($field);
                    $newComponentValue->setTranslatableLocale($this->request->getLocale());
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
                'entry_options' => [
                    'label' => false
                ],
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
