<?php

namespace Akyos\BuilderBundle\Components\IconTitleBox;

use Akyos\BuilderBundle\Entity\ComponentField;
use Akyos\BuilderBundle\Entity\ComponentTemplate;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class IconTitleBoxFixtures extends Fixture implements FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $component = new ComponentTemplate();
        $component->setName('Box icône + titre');
        $component->setSlug('icon_title_box');
        $component->setShortDescription('Icône, titre et texte');
        $component->setIsContainer(false);
        $component->setPrototype('default');

        $componentFieldArray = [
            [
                "name" => "Icône",
                "slug" => "icon",
                "desc" => "Choisissez l'icône",
                "type" => "image",
                "entity" => "App\Entity\Platform\Administrator",
                "option" => [],
                "group" => "Général",
            ],[
                "name" => "Titre",
                "slug" => "title",
                "desc" => "Contenu du titre",
                "type" => "text",
                "entity" => "App\Entity\Platform\AG\AG",
                "option" => [],
                "group" => "Général",
            ],[
                "name" => "Description",
                "slug" => "description",
                "desc" => "Contenu de la description",
                "type" => "text",
                "entity" => "App\Entity\Platform\AG\AG",
                "option" => [],
                "group" => "Général",
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
            $newComponentField->setEntity($componentField['entity']);
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