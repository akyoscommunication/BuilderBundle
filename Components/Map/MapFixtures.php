<?php

namespace Akyos\BuilderBundle\Components\Map;

use Akyos\BuilderBundle\Entity\ComponentField;
use Akyos\BuilderBundle\Entity\ComponentTemplate;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class MapFixtures extends Fixture implements FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $component = new ComponentTemplate();
        $component->setName('Map');
        $component->setSlug('map');
        $component->setShortDescription('Affiche une carte avec Leaflet');
        $component->setIsContainer(false);
        $component->setPrototype('default');

        $componentFieldArray = [
            [
                "name" => "Latitude",
                "slug" => "latitude",
                "desc" => "Coordonnées GPS",
                "type" => "text",
                "entity" => "App\Entity\Back\Job",
                "option" => [],
                "group" => "Général",
            ],[
                "name" => "Longitude",
                "slug" => "longitude",
                "desc" => "Coordonnées GPS",
                "type" => "text",
                "entity" => "App\Entity\Back\Job",
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