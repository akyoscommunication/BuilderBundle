<?php

namespace Akyos\BuilderBundle\Components\Image;

use Akyos\BuilderBundle\Service\FixturesHelpers;
use Akyos\CoreBundle\Entity\Page;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class ImageFixtures extends Fixture implements FixtureGroupInterface
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
        return ['component', 'builder-components', 'component-image'];
    }

    public function load(ObjectManager $manager): void
    {
        $slug = "image";
        $name = "Image";
        $shortDescription = "Une image";
        $isContainer = false;
        $prototype = "default";
        $componentFields = [["name" => "Image", "slug" => "image", "desc" => "Sélectionnez une image", "type" => "image", "entity" => "App\Entity\Program", "option" => [], "group" => "Général",], ["name" => "Largeur", "slug" => "width", "desc" => "Largeur de l'image", "type" => "text", "entity" => "App\Entity\Program", "option" => [], "group" => "Général",], ["name" => "Marge", "slug" => "margin", "desc" => "Marge de l'image", "type" => "text", "entity" => "App\Entity\Program", "option" => [], "group" => "Général",], ["name" => "Lien interne", "slug" => "redirection", "desc" => "Redirection sur une page interne au clic", "type" => "entity", "entity" => Page::class, "option" => [], "group" => "Général",], ["name" => "Taille", "slug" => "size", "desc" => "Taille de l'image", "type" => "select", "entity" => "App\Entity\Agency", "option" => ["Contain:contain", "Cover:cover", "Iframe 16/9:iframe-16-9"], "group" => "Général",], ["name" => "Hauteur", "slug" => "height", "desc" => "Hauteur de l'image", "type" => "text", "entity" => "App\Entity\Program", "option" => [], "group" => "Général",], ["name" => "Position de l'image", "slug" => "position", "desc" => "Position de l'image", "type" => "select", "entity" => "App\Entity\Program", "option" => ["En haut à gauche:top-left", "En haut au centre:top-center", "En haut à droite:top-right", "Centré à gauche:center-left", "Centré au centre:center-center", "Centré à droite:center-right", "En bas à gauche:bottom-left", "En bas au centre:bottom-center", "En bas à droite:bottom-right"], "group" => "Général",], ["name" => "Désactiver le lazy-loading ?", "slug" => "no_lazy", "desc" => "L'image doit-elle charger directement ou au scroll ?", "type" => "bool", "entity" => "App\Entity\Program", "option" => [], "group" => "Général",], ["name" => "Lien externe", "slug" => "external_link", "desc" => "Redirection sur une page externe au clic", "type" => "link", "entity" => Page::class, "option" => [], "group" => "Général",]];

        $this->fixturesHelpers->updateBdd($slug, $name, $shortDescription, $isContainer, $prototype, $componentFields);
    }
}