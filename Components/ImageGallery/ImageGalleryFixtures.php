<?php

namespace Akyos\BuilderBundle\Components\ImageGallery;

use Akyos\BuilderBundle\Entity\ComponentField;
use Akyos\BuilderBundle\Entity\ComponentTemplate;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class ImageGalleryFixtures extends Fixture implements FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $component = new ComponentTemplate();
        $component->setName('Galerie d\'images');
        $component->setSlug('image_gallery');
        $component->setShortDescription('Galerie d\'images avec zoom au clic');
        $component->setIsContainer(false);
        $component->setPrototype('default');

        $componentFieldArray = [
            [
                "name" => "Style des images",
                "slug" => "background_size",
                "desc" => "Est-ce que l'image remplit tout le bloc ou est-ce qu'elle est visible en entier, peu importe son format ?",
                "type" => "select",
                "entity" => "App\Entity\Back\Job",
                "option" => ["Remplit tout son conteneur:cover","Visible en entier:contain"],
                "group" => "Général",
            ],[
                "name" => "Images",
                "slug" => "images",
                "desc" => "Liste des images à afficher",
                "type" => "gallery",
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
        return ['component', 'image-gallery-component'];
    }
}