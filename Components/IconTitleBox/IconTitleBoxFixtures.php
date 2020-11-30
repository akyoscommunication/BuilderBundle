<?php

namespace Akyos\BuilderBundle\Components\IconTitleBox;

use Akyos\BuilderBundle\Entity\ComponentField;
use Akyos\BuilderBundle\Entity\ComponentTemplate;
use Akyos\BuilderBundle\Service\FixturesHelpers;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class IconTitleBoxFixtures extends Fixture implements FixtureGroupInterface
{
    private $fixturesHelpers;

    public function __construct(FixturesHelpers $fixturesHelpers)
    {
        $this->fixturesHelpers = $fixturesHelpers;
    }

    public function load(ObjectManager $manager): void
    {
        $slug = "icon_title_box";
        $name = "Box icône + titre";
        $shortDescription = "Icône, titre et texte";
        $isContainer = false;
        $prototype = "default";
        $componentFields = [
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
            ],[
                "name" => "Icone en class",
                "slug" => "icon_class",
                "desc" => "Icone en class",
                "type" => "text",
                "entity" => "App\Entity\Platform\AG\AG",
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
        return ['component', 'component-icon-title-box'];
    }
}