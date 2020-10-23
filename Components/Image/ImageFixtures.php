<?php

namespace Akyos\BuilderBundle\Components\Image;

use Akyos\BuilderBundle\Entity\ComponentField;
use Akyos\BuilderBundle\Entity\ComponentTemplate;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class ImageFixtures extends Fixture implements FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $component = new ComponentTemplate();
        $component->setName('Image');
        $component->setSlug('image');
        $component->setShortDescription('Une image');
        $component->setIsContainer(false);
        $component->setPrototype('default');

        $componentFieldArray = [
            [
                "name" => "image",
                "slug" => "image",
                "desc" => "mon image",
                "type" => "image",
                "entity" => "App\Entity\Program",
                "option" => [],
                "group" => "Général",
            ],[
                "name" => "Largeur",
                "slug" => "width",
                "desc" => "Largeur de l'image",
                "type" => "text",
                "entity" => "App\Entity\Program",
                "option" => [],
                "group" => "Général",
            ],[
                "name" => "Marge",
                "slug" => "margin",
                "desc" => "Marge de l'image",
                "type" => "text",
                "entity" => "App\Entity\Program",
                "option" => [],
                "group" => "Général",
            ],[
                "name" => "Redirection au click",
                "slug" => "redirection",
                "desc" => "Redirection au click",
                "type" => "entity",
                "entity" => "Akyos\CoreBundle\Entity\Page",
                "option" => [],
                "group" => "Général",
            ],[
                "name" => "Taille",
                "slug" => "size",
                "desc" => "Taille de l'image",
                "type" => "select",
                "entity" => "App\Entity\Agency",
                "option" => ["Contain:contain","Cover:cover"],
                "group" => "Général",
            ],[
                "name" => "Hauteur",
                "slug" => "height",
                "desc" => "Hauteur de l'image",
                "type" => "text",
                "entity" => "App\Entity\Program",
                "option" => [],
                "group" => "Général",
            ],[
                "name" => "Position de l'image",
                "slug" => "position",
                "desc" => "Position de l'image",
                "type" => "select",
                "entity" => "App\Entity\Program",
                "option" => ["En haut à gauche:top-left","En haut au centre:top-center","En haut à droite:top-right","Centré à gauche:center-left","Centré au centre:center-center","Centré à droite:center-right","En bas à gauche:bottom-left","En bas au centre:bottom-center","En bas à droite:bottom-right"],
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