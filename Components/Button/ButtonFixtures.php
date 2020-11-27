<?php

namespace Akyos\BuilderBundle\Components\Button;

use Akyos\BuilderBundle\Entity\ComponentField;
use Akyos\BuilderBundle\Entity\ComponentTemplate;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class ButtonFixtures extends Fixture implements FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $component = new ComponentTemplate();
        $component->setName('Button');
        $component->setSlug('button');
        $component->setShortDescription('Un simple bouton');
        $component->setIsContainer(false);
        $component->setPrototype('default');

        $componentFieldArray = [
            [
                'name' => 'Lien',
                'slug' => 'link',
                'desc' => 'Lien du button',
                'type' => 'text',
                'option' => [],
                'group' => 'Général',
            ],[
                'name' => 'Texte',
                'slug' => 'button_text',
                'desc' => 'Texte du bouton',
                'type' => 'text',
                'option' => [],
                'group' => 'Général',
            ],[
                'name' => 'Nouvel onglet ?',
                'slug' => 'target',
                'desc' => 'Ouvrir dans un nouvel onglet ?',
                'type' => 'select',
                'option' => ["Oui:1","Non:0"],
                'group' => 'Général',
            ],[
                'name' => 'Ancre ?',
                'slug' => 'anchor',
                'desc' => 'Lien vers une ancre sur la page ?',
                'type' => 'bool',
                'option' => [],
                'group' => 'Général',
            ],[
                'name' => 'Couleur de fond',
                'slug' => 'color',
                'desc' => 'Couleur de fond du bouton',
                'type' => 'select',
                'option' => ["Couleur principale:primary","Couleur secondaire:secondary","Blanc:white","Noir:black","Transparent:transparent"],
                'group' => 'Style',
            ],[
                'name' => 'Couleur du texte',
                'slug' => 'text_color',
                'desc' => 'Couleur du texte du bouton',
                'type' => 'select',
                'option' => ["Couleur principale:primary","Couleur secondaire:secondary","Blanc:white","Noir:black"],
                'group' => 'Style',
            ],[
                'name' => 'Margin',
                'slug' => 'margin',
                'desc' => 'Marges externes',
                'type' => 'text',
                'option' => [],
                'group' => 'Style',
            ],[
                'name' => 'Padding',
                'slug' => 'padding',
                'desc' => 'Marges internes',
                'type' => 'text',
                'option' => [],
                'group' => 'Style',
            ],[
                'name' => 'Border-radius',
                'slug' => 'border_radius',
                'desc' => 'Coins arrondis',
                'type' => 'text',
                'option' => [],
                'group' => 'Style',
            ],[
                'name' => 'Position',
                'slug' => 'position',
                'desc' => 'Position',
                'type' => 'select',
                'option' => [
                    "Droite:end",
                    "Gauche:start",
                    "Centré:center",
                    "Toute la largeur:full",
                ],
                'group' => 'Style',
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