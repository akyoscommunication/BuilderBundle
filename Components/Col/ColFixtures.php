<?php

namespace Akyos\BuilderBundle\Components\Col;

use Akyos\BuilderBundle\Entity\ComponentField;
use Akyos\BuilderBundle\Entity\ComponentTemplate;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class ColFixtures extends Fixture implements FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $component = new ComponentTemplate();
        $component->setName('Colonne');
        $component->setSlug('col');
        $component->setShortDescription('Composant colonne');
        $component->setIsContainer(true);
        $component->setPrototype('col');

        $componentFieldArray = [
            [
                'name' => 'Colonnes',
                'slug' => 'col',
                'desc' => 'Nombre de colonne (sur 12)',
                'type' => 'text',
                'option' => [],
                'group' => 'général',
            ],
            [
                'name' => 'Image de fond',
                'slug' => 'background_image',
                'desc' => 'Image de fond de la colonne',
                'type' => 'image',
                'option' => [],
                'group' => 'général',
            ],
            [
                'name' => 'Background-size',
                'slug' => 'background_size',
                'desc' => 'Taille de l\'image de fond',
                'type' => 'select',
                'option' => [
                    'Cover (remplit tout l\'espace):cover',
                    'Contain (rentre entièrement dans le conteneur):contain',
                    'Auto:auto',
                ],
                'group' => 'général',
            ],
            [
                'name' => 'Padding',
                'slug' => 'padding',
                'desc' => 'Marge interne (ex: 15px 20px 15px 20)',
                'type' => 'text',
                'option' => [],
                'group' => 'général',
            ],
            [
                'name' => 'Margin',
                'slug' => 'margin',
                'desc' => 'Marge externe (ex: 15px 20px 15px 20)',
                'type' => 'text',
                'option' => [],
                'group' => 'général',
            ],
            [
                'name' => 'Display',
                'slug' => 'display',
                'desc' => 'Comportement du block et de ses enfants',
                'type' => 'select',
                'option' => [
                    'Block:block',
                    'Flex:flex',
                ],
                'group' => 'général',
            ],
            [
                'name' => 'Flex direction',
                'slug' => 'flex_direction',
                'desc' => 'Orientation des éléments',
                'type' => 'select',
                'option' => [
                    'Horizontal:row',
                    'Vertical:column',
                ],
                'group' => 'général',
            ],
            [
                'name' => 'Flex-wrap',
                'slug' => 'flex_wrap',
                'desc' => 'Les éléments peuvent-ils passer "à la ligne" ?',
                'type' => 'select',
                'option' => [
                    'Oui:wrap',
                    'Non:nowrap',
                ],
                'group' => 'général',
            ],
            [
                'name' => 'Justify-content',
                'slug' => 'justify_content',
                'desc' => 'Alignement des éléments (dans la même direction que flex_direction)',
                'type' => 'select',
                'option' => [
                    'Centrés:center',
                    'Au début:start',
                    'À la fin:end',
                    'Autant d\'espace entre chaque élément:space-b',
                    'Autant d\'espace autour et entre chaque élément:space-a',
                ],
                'group' => 'général',
            ],
            [
                'name' => 'Align-items',
                'slug' => 'align_items',
                'desc' => 'Alignement des éléments (dans la direction contraire à flex_direction)',
                'type' => 'select',
                'option' => [
                    'Centré:center',
                    'Au début:start',
                    'À la fin:end',
                    'Hauteur égale:stretch',
                ],
                'group' => 'général',
            ],
            [
                'name' => 'Background position',
                'slug' => 'backgroundPosition',
                'desc' => 'Position du background',
                'type' => 'select',
                'option' => [
                    'Right:right',
                    'Center:center',
                    'Left:left',
                ],
                'group' => 'général',
            ],
            [
                'name' => 'Break Point',
                'slug' => 'break',
                'desc' => 'Point de rupture',
                'type' => 'text',
                'option' => [],
                'group' => 'général',
            ],
        ];

        foreach ($componentFieldArray as $componentField)
        {
            $newComponentField = new ComponentField();

            $newComponentField->setComponentTemplate($component);

            $newComponentField->setName($componentField['name']);
            $newComponentField->setSlug($componentField['slug']);
            $newComponentField->setShortDescription($componentField['desc']);
            $newComponentField->setType($componentField['type']);
            $newComponentField->setFieldValues($componentField['option']);
            $newComponentField->setGroups($componentField['group']);

            $manager->persist($newComponentField);
        }

         $manager->persist($component);

        $manager->flush();
    }

    /**
     * @inheritDoc
     */
    public static function getGroups(): array
    {
        return ['component'];
    }
}
