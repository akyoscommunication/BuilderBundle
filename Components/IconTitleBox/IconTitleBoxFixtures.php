<?php

namespace Akyos\BuilderBundle\Components\IconTitleBox;

use Akyos\BuilderBundle\Service\FixturesHelpers;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class IconTitleBoxFixtures extends Fixture implements FixtureGroupInterface
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
        return ['component', 'builder-components', 'component-icon-title-box'];
    }

    public function load(ObjectManager $manager): void
    {
        $slug = "icon_title_box";
        $name = "Box icône + titre";
        $shortDescription = "Icône, titre et texte";
        $prototype = "default";
        $componentFields = [["name" => "Icône", "slug" => "icon", "desc" => "Choisissez l'icône", "type" => "image", "entity" => "App\Entity\Platform\AG\AG", "option" => [], "group" => "Général",], ["name" => "Titre", "slug" => "title", "desc" => "Contenu du titre", "type" => "text", "entity" => "App\Entity\Platform\AG\AG", "option" => [], "group" => "Général",], ["name" => "Description", "slug" => "description", "desc" => "Contenu de la description", "type" => "text", "entity" => "App\Entity\Platform\AG\AG", "option" => [], "group" => "Général",], ["name" => "Icone en class", "slug" => "icon_class", "desc" => "Icone en class", "type" => "text", "entity" => "App\Entity\Platform\AG\AG", "option" => [], "group" => "Général",],];

        $this->fixturesHelpers->updateBdd($slug, $name, $shortDescription, false, $prototype, $componentFields);
    }
}