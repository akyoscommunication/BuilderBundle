<?php

namespace Akyos\BuilderBundle\Components\Button;

use Akyos\BuilderBundle\Entity\ComponentField;
use Akyos\BuilderBundle\Entity\ComponentTemplate;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class ButtonFixtures extends Fixture implements FixtureGroupInterface
{
    public function load(ObjectManager $manager)
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
                'group' => 'général',
            ],[
                'name' => 'Texte',
                'slug' => 'button_text',
                'desc' => 'Texte  du bouton',
                'type' => 'text',
                'option' => [],
                'group' => 'général',
            ],[
                'name' => 'Target',
                'slug' => 'target',
                'desc' => 'Oucrir dans un nouvel onglet?',
                'type' => 'select',
                'option' => ["Non:0"],
                'group' => 'général',
            ],[
                'name' => 'Couleur',
                'slug' => 'color',
                'desc' => 'la couleur du bouton',
                'type' => 'select',
                'option' => ["rouge:red","blanc:white"],
                'group' => 'général',
            ],[
                'name' => 'Margin',
                'slug' => 'margin',
                'desc' => 'margin',
                'type' => 'text',
                'option' => [],
                'group' => 'général',
            ],[
                'name' => 'Padding',
                'slug' => 'padding',
                'desc' => 'padding',
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