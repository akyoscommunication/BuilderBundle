<?php

namespace Akyos\BuilderBundle\Components\Slider;

use Akyos\BuilderBundle\Service\FixturesHelpers;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class SliderFixtures extends Fixture implements FixtureGroupInterface
{
    private FixturesHelpers $fixturesHelpers;

    public function __construct(FixturesHelpers $fixturesHelpers)
    {
        $this->fixturesHelpers = $fixturesHelpers;
    }

    /**
     * @inheritDoc
     */
    public static function getGroups(): array
    {
        return ['component', 'builder-components', 'component-slider'];
    }

    public function load(ObjectManager $manager): void
    {
        $slug = "slider";
        $name = "Slider";
        $shortDescription = "Container pour slider";
        $prototype = "default";
        $componentFields = [["name" => "Flèches défilement ?", "slug" => "navigation", "desc" => "Afficher les flèches ? (Oui par défaut)", "type" => "bool", "entity" => "App\Entity\Platform\AG\AG", "option" => [], "group" => "Navigation",], ["name" => "Pagination ?", "slug" => "pagination", "desc" => "Afficher la pagination ? (Non par défaut)", "type" => "bool", "entity" => "App\Entity\Platform\AG\AG", "option" => [], "group" => "Navigation",], ["name" => "Scrollbar ?", "slug" => "scrollbar", "desc" => "Afficher la scrollbar ? (Non par défaut)", "type" => "bool", "entity" => "App\Entity\Platform\AG\AG", "option" => [], "group" => "Navigation",], ["name" => "Sens de défilement", "slug" => "direction", "desc" => "Sens de défilement des slides (Horizontal par défaut)", "type" => "select", "entity" => "App\Entity\Platform\AG\AG", "option" => ["Horizontal:horizontal", "Vertical:vertical"], "group" => "Général",], ["name" => "Hauteur du slider", "slug" => "height", "desc" => "Hauteur du slider", "type" => "text", "entity" => "App\Entity\Platform\AG\AG", "option" => [], "group" => "Général",], ["name" => "Vitesse de défilement", "slug" => "speed", "desc" => "Vitesse de défilement des slides en millisecondes (5000 par défaut)", "type" => "int", "entity" => "App\Entity\Platform\AG\AG", "option" => [], "group" => "Général",], ["name" => "Défilement automatique ?", "slug" => "autoplay", "desc" => "Est-ce que les slides défilent automatiquement ? (Oui par défaut)", "type" => "bool", "entity" => "App\Entity\Platform\AG\AG", "option" => [], "group" => "Général",], ["name" => "Boucle ?", "slug" => "loop", "desc" => "Est-ce que les slides défilent à l'infini ? (Oui par défaut)", "type" => "bool", "entity" => "App\Entity\Platform\AG\AG", "option" => [], "group" => "Général",], ["name" => "Nombre de slides par vue", "slug" => "slides_per_view", "desc" => "Nombre de slides visibles en même temps (1 par défaut)", "type" => "int", "entity" => "App\Entity\Platform\AG\AG", "option" => [], "group" => "Slides par vue",], ["name" => "Nombre de slides par vue (< 991px)", "slug" => "slides_per_view_991", "desc" => "Nombre de slides visibles en même temps, format tablette (1 par défaut)", "type" => "int", "entity" => "App\Entity\Platform\AG\AG", "option" => [], "group" => "Slides par vue",], ["name" => "Nombre de slides par vue (< 767px)", "slug" => "slides_per_view_767", "desc" => "Nombre de slides visibles en même temps, format smartphone (1 par défaut)", "type" => "int", "entity" => "App\Entity\Platform\AG\AG", "option" => [], "group" => "Slides par vue",],];

        $this->fixturesHelpers->updateBdd($slug, $name, $shortDescription, true, $prototype, $componentFields);
    }
}