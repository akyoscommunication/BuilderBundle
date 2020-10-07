<?php

namespace Akyos\BuilderBundle\Form;

use Akyos\BuilderBundle\Entity\ComponentValue;
use Akyos\CoreBundle\Repository\PostRepository;
use Akyos\FileManagerBundle\Form\Type\FileManagerCollectionType;
use Akyos\FileManagerBundle\Form\Type\FileManagerType;
use Doctrine\DBAL\Types\BooleanType;
use Doctrine\ORM\EntityManagerInterface;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Akyos\CoreBundle\Repository\PageRepository;

class ComponentValueType extends AbstractType
{
    private $pages;
    private $posts;

    public function __construct(PageRepository $pageRepository, PostRepository $postRepository) {
        $pages = $pageRepository->findAll();
        foreach($pages as $page) {
            $this->pages[$page->getTitle()] = $page->getId();
        }
        $posts = $postRepository->findAll();
        foreach($posts as $post) {
            $this->posts[$post->getTitle()] = $post->getId();
        }
    }

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
                                    'height'         => 300,
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
                                'required' => false,
                                'label'  => $field->getName()
                            ))
                        ;
                        break;

                    case 'postlink' :
                        $form
                            ->add('value', ChoiceType::class, [
                                'choices' => $this->posts,
                                'required' => false,
                                'label' => $field->getName()
                            ])
                        ;
                        break;
                    case 'entity' :
                        $form
                            ->add('value', EntityType::class, [
                                'class'=> $field->getEntity(),
                                'required' => false,
                                'label'=>$field->getName(),
                                'placeholder'=> "choix de l'entité",
                                'choice_label' => function($choice, $key, $value){
                                    return $choice;
                                },
                            ])
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
                        $form->add('value',FileManagerType::class, [
                            'label' => $field->getName(),
                            'config' => 'full'
                        ]);
                        break;

                    case 'gallery':
                        if (!is_array($componentValue->getValue())) {
                            $componentValue->setValue([$componentValue->getValue()]);
                        }
                        $form->add('value',FileManagerCollectionType::class, [
                            'label' => $field->getName(),
                            'entry_options' => [
                                'config' => 'full'
                            ]
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

                    case 'select':
                        $values = [];
                        foreach($field->getFieldValues() as $fieldValue) {
                            $exploded = explode(':', $fieldValue);
                            $values[$exploded[0]] = $exploded[1];
                        }

                        $form
                            ->add('value', ChoiceType::class, array(
                                'attr'              => array(
                                    'placeholder'       => "Valeur",
                                ),
                                'label'                 => $field->getName(),
                                'required'              => false,
                                'choices'               => $values
                            ))
                        ;
                        break;
                    case 'bool':

                        $componentValue->setValue((bool)$componentValue->getValue());
                        $form
                            ->add('value' , CheckboxType::class, [
                                'attr' => [
                                    'placeholder'       => "Valeur",
                                ],
                                'label'                 => $field->getName(),
                                'required'              => false,
                            ])
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
