<?php

namespace Akyos\BuilderBundle\Form;

use Akyos\BuilderBundle\Entity\ComponentValue;
use Artgris\Bundle\MediaBundle\Form\Type\MediaType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ComponentValueType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $formModifier = function (FormInterface $form, ComponentValue $componentValue = null) {
            if ($componentValue != null) {
                $field = $componentValue->getComponentField();
                switch ($field->getType()) {

                    case 'textarea_html':
                        $form
                            ->add('value', CKEditorType::class, array(
                                'required'    => false,
                                'config'      => array(
                                    'placeholder'    => "Texte",
                                    'height'         => 50,
                                    'entities'       => false,
                                    'basicEntities'  => false,
                                    'entities_greek' => false,
                                    'entities_latin' => false,
                                ),
                                'label'    => $field->getName()
                            ))
                        ;
                        break;

                    case 'textarea':
                        $form
                            ->add('value', TextareaType::class, array(
                                'label'    => $field->getName()
                            ))
                        ;
                        break;

                    case 'tel':
                        $form
                            ->add('value', TelType::class, array(
                                'attr'              => array(
                                    'placeholder'       => "Numéro",
                                ),
                                'label'                 => $field->getName(),
                                'required'              => false
                            ))
                        ;
                        break;

                    case 'mail':
                        $form
                            ->add('value', EmailType::class, array(
                                'attr'              => array(
                                    'placeholder'       => "Email",
                                ),
                                'label'                 => $field->getName(),
                                'required'              => false
                            ))
                        ;
                        break;

                    case 'pagelink':
                        $form
                            ->add('value', ChoiceType::class, array(
                                'choices' => $this->pages,
                                'label'  => $field->getName()
                            ))
                        ;
                        break;

                    case 'link':
                        $form
                            ->add('value', UrlType::class, array(
                                'attr'              => array(
                                    'placeholder'       => "Lien",
                                ),
                                'label'                 => $field->getName(),
                                'required'              => false
                            ))
                        ;
                        break;

                    case 'image':
                        $form->add('value',MediaType::class, [
                            'conf' => 'images',
                            'label' => $field->getName(),

                        ]);
                        break;

                    case 'int':
                        $form
                            ->add('value', IntegerType::class, array(
                                'attr'              => array(
                                    'placeholder'       => "Valeur",
                                ),
                                'label'                 => $field->getName(),
                                'required'              => false
                            ))
                        ;
                        break;

                    default:
                        $form
                            ->add('value', TextType::class, array(
                                'attr'              => array(
                                    'placeholder'       => "Valeur",
                                ),
                                'label'                 => $field->getName(),
                                'required'              => false
                            ))
                        ;
                        break;
                }
            }
        };

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) use ($formModifier) {
                // this would be your entity, i.e. SportMeetup
                $data = $event->getData();
                $formModifier($event->getForm(), $data);
            }
        );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ComponentValue::class,
        ]);
    }
}
