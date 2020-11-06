<?php

namespace Akyos\BuilderBundle\Components\Row;

use Akyos\BuilderBundle\Entity\ComponentField;
use Akyos\BuilderBundle\Entity\ComponentTemplate;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class RowFixtures extends Fixture implements FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $component = new ComponentTemplate();
        $component->setName('Rangée');
        $component->setSlug('row');
        $component->setShortDescription('Pour placer des éléments de contenu.');
        $component->setIsContainer(true);
        $component->setPrototype('row');

        $componentFieldArray = [
            [
                'name' => 'Image de fond',
                'slug' => 'background_image',
                'desc' => 'Image de fond de rangée',
                'type' => 'image',
                'option' => [],
                'group' => 'Général',
            ],
            [
                'name' => 'Container',
                'slug' => 'row_width',
                'desc' => 'Étirement de la rangée',
                'type' => 'select',
                'option' => [
                    'Par défaut:default',
                    'Étirer le background:full-background',
                    'Étirer la rangée et son contenu:full-width',
                    'Étirer la rangée et son contenu avec padding:full-width-with-padding',
                ],
                'group' => 'Général',
            ],
            [
                'name' => 'Padding',
                'slug' => 'padding',
                'desc' => 'Marge interne (ex: 15px 20px 15px 20)',
                'type' => 'text',
                'option' => [],
                'group' => 'Général',
            ],
            [
                'name' => 'Margin',
                'slug' => 'margin',
                'desc' => 'Marge externe (ex: 15px 20px 15px 20)',
                'type' => 'text',
                'option' => [],
                'group' => 'Général',
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
                'group' => 'Général',
            ],
            [
                'name' => 'Couleur de fond',
                'slug' => 'background_color',
                'desc' => 'Couleur en fond de la rangée',
                'type' => 'select',
                'option' => [
                    'Transparent:transparent',
                    'Gris clair:#f5f5f5',
                    'Rouge:#e3001b',
                ],
                'group' => 'Général',
            ],
            [
                'name' => 'Background',
                'slug' => 'background',
                'desc' => 'Code CSS pour background',
                'type' => 'text',
                'option' => [],
                'group' => 'Général',
            ],[
                'name' => 'Position du background',
                'slug' => 'background_position',
                'desc' => 'Position du background de la rangée',
                'type' => 'select',
                'option' => [
                    'Haut:top',
                    'Droite:right',
                    'Bas:bottom',
                    'Gauche:left',
                ],
                'group' => 'Général',
            ]
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
