<?php

namespace Akyos\BuilderBundle\Components\Map;

use Akyos\BuilderBundle\Service\FixturesHelpers;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class MapFixtures extends Fixture implements FixtureGroupInterface
{
    private $fixturesHelpers;

    public function __construct(FixturesHelpers $fixturesHelpers)
    {
        $this->fixturesHelpers = $fixturesHelpers;
    }

    public function load(ObjectManager $manager): void
    {
        $slug = "map";
        $name = "Map";
        $shortDescription = "Affiche une carte avec Leaflet";
        $isContainer = false;
        $prototype = "default";
        $componentFields = [
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
            ],[
                "name" => "Zoom",
                "slug" => "zoom",
                "desc" => "Zoom (nombre entre 1 et 18)",
                "type" => "int",
                "entity" => "App\Entity\Back\Job",
                "option" => [],
                "group" => "Général",
            ],[
                "name" => "Style",
                "slug" => "layer",
                "desc" => "Type d'affichage de la carte",
                "type" => "select",
                "entity" => "App\Entity\Back\Job",
                "option" => [
                    "Satellite:satellite",
                ],
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
        return ['component','component-map'];
    }
}