<?php

namespace Akyos\BuilderBundle\Components\ImageGallery;

use Akyos\BuilderBundle\Service\FixturesHelpers;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class ImageGalleryFixtures extends Fixture implements FixtureGroupInterface
{
    private $fixturesHelpers;

    public function __construct(FixturesHelpers $fixturesHelpers)
    {
        $this->fixturesHelpers = $fixturesHelpers;
    }

    public function load(ObjectManager $manager): void
    {
        $slug = "image_gallery";
        $name = "Galerie d'images";
        $shortDescription = "Galerie d'images avec zoom au clic";
        $isContainer = false;
        $prototype = "default";
        $componentFields = [
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

        $this->fixturesHelpers->updateBdd($slug, $name, $shortDescription, $isContainer, $prototype, $componentFields);
    }

    /**
     * @inheritDoc
     */
    public static function getGroups(): array
    {
        return ['component', 'image-gallery-component'];
    }
}