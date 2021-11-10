<?php

namespace Akyos\BuilderBundle\Form;

use Akyos\BuilderBundle\Entity\ComponentField;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ComponentFieldType extends AbstractType
{
    private array $entities;

    public function __construct( EntityManagerInterface $entityManager) {
        $this->entities = array();
        $meta = $entityManager->getMetadataFactory()->getAllMetadata();
        foreach ($meta as $m) {
            if(!preg_match('/Component|Option|Menu|ContactForm|User|Gedmo|BuilderTemplate|NewPasswordRequest|seo|Redirect301|File/i', $m->getName())) {
                $this->entities[] = $m->getName();
            }
        }
    }
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
                    'Champ texte'    => 'text',
                    'CKEditor'       => 'textarea_html',
                    'Zone de texte'  => 'textarea',
                    'Image'          => 'image',
                    'Galerie d\images' => 'gallery',
                    'Téléphone'       => 'tel',
                    'Mail'           => 'mail',
                    'Lien interne'   => 'pagelink',
                    'Lien interne article' => 'postlink',
                    'Lien externe'   => 'link',
                    'Nombre'         => 'int',
                    'Select'        => 'select',
                    'Booléen'       => 'bool',
                    'Entité'        => 'entity',
                    'Couleur'        => 'color',
                ],
                'multiple' => false,
                'expanded' => false
            ])
            ->add('entity', ChoiceType::class, [
                'label' => "choisir un élément",
                'choices'=> $this->entities,
                'choice_label' => function ($choice, $key, $value) {
                    return $value;
                }
            ])
            ->add('fieldValues', CollectionType::class, [
                'entry_type' => TextType::class,
                'entry_options' => [
                    'attr' => ['class' => 'option_item'],
                    'label' => false
                ],
                'attr' => ['class' => 'options_collection'],
                'allow_add' => true,
                'allow_delete' => true,
                'label' => "Options"
            ])
            ->add('groups', null, [
                'label' => "Définir un groupe ( Pour les onglets à l'édit )",
                'help' => "Si ce champ est vide, celui-ci est rempli par 'général'",
                'empty_data' => "Général",
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
