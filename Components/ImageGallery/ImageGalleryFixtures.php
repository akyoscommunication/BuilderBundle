<?php

namespace Akyos\BuilderBundle\Components\ImageGallery;

use Akyos\BuilderBundle\Service\FixturesHelpers;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class ImageGalleryFixtures extends Fixture implements FixtureGroupInterface
{
    private FixturesHelpers $fixturesHelpers;

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
                "entity" => "App\Entity\Platform\AG\AG",
                "option" => ["Remplit tout son conteneur:cover","Visible en entier:contain"],
                "group" => "Général",
            ],[
                "name" => "Images",
                "slug" => "images",
                "desc" => "Liste des images à afficher",
                "type" => "gallery",
                "entity" => "App\Entity\Platform\AG\AG",
                "option" => [],
                "group" => "Général",
            ],[
				"name" => "Afficher sous forme de slider ?",
				"slug" => "isSlider",
				"desc" => "Afficher la gallerie sous forme de slider ? Si oui, remplissez les informations ci-dessous",
				"type" => "bool",
				"entity" => "App\Entity\Platform\AG\AG",
				"option" => [],
				"group" => "Slider",
			],[
				"name" => "Flèches défilement ?",
				"slug" => "navigation",
				"desc" => "Afficher les flèches ? (Oui par défaut)",
				"type" => "bool",
				"entity" => "App\Entity\Platform\AG\AG",
				"option" => [],
				"group" => "Slider",
			],[
				"name" => "Pagination ?",
				"slug" => "pagination",
				"desc" => "Afficher la pagination ? (Non par défaut)",
				"type" => "bool",
				"entity" => "App\Entity\Platform\AG\AG",
				"option" => [],
				"group" => "Slider",
			],[
				"name" => "Scrollbar ?",
				"slug" => "scrollbar",
				"desc" => "Afficher la scrollbar ? (Non par défaut)",
				"type" => "bool",
				"entity" => "App\Entity\Platform\AG\AG",
				"option" => [],
				"group" => "Slider",
			],[
				"name" => "Sens de défilement",
				"slug" => "direction",
				"desc" => "Sens de défilement des slides (Horizontal par défaut)",
				"type" => "select",
				"entity" => "App\Entity\Platform\AG\AG",
				"option" => ["Horizontal:horizontal","Vertical:vertical"],
				"group" => "Slider",
			],[
				"name" => "Hauteur du slider",
				"slug" => "height",
				"desc" => "Hauteur du slider",
				"type" => "text",
				"entity" => "App\Entity\Platform\AG\AG",
				"option" => [],
				"group" => "Slider",
			],[
				"name" => "Vitesse de défilement",
				"slug" => "speed",
				"desc" => "Vitesse de défilement des slides en millisecondes (5000 par défaut)",
				"type" => "int",
				"entity" => "App\Entity\Platform\AG\AG",
				"option" => [],
				"group" => "Slider",
			],[
				"name" => "Défilement automatique ?",
				"slug" => "autoplay",
				"desc" => "Est-ce que les slides défilent automatiquement ? (Oui par défaut)",
				"type" => "bool",
				"entity" => "App\Entity\Platform\AG\AG",
				"option" => [],
				"group" => "Slider",
			],[
				"name" => "Boucle ?",
				"slug" => "loop",
				"desc" => "Est-ce que les slides défilent à l'infini ? (Oui par défaut)",
				"type" => "bool",
				"entity" => "App\Entity\Platform\AG\AG",
				"option" => [],
				"group" => "Slider",
			],[
				"name" => "Nombre de slides par vue",
				"slug" => "slides_per_view",
				"desc" => "Nombre de slides visibles en même temps (1 par défaut)",
				"type" => "int",
				"entity" => "App\Entity\Platform\AG\AG",
				"option" => [],
				"group" => "Slider",
			],[
				"name" => "Nombre de slides par vue (< 991px)",
				"slug" => "slides_per_view_991",
				"desc" => "Nombre de slides visibles en même temps, format tablette (1 par défaut)",
				"type" => "int",
				"entity" => "App\Entity\Platform\AG\AG",
				"option" => [],
				"group" => "Slider",
			],[
				"name" => "Nombre de slides par vue (< 767px)",
				"slug" => "slides_per_view_767",
				"desc" => "Nombre de slides visibles en même temps, format smartphone (1 par défaut)",
				"type" => "int",
				"entity" => "App\Entity\Platform\AG\AG",
				"option" => [],
				"group" => "Slider",
			]
        ];

        $this->fixturesHelpers->updateBdd($slug, $name, $shortDescription, $isContainer, $prototype, $componentFields);
    }

    /**
     * @inheritDoc
     */
    public static function getGroups(): array
    {
        return ['component', 'builder-components', 'image-gallery-component'];
    }
}