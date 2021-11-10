<?php

namespace Akyos\BuilderBundle\Components\News;

use Akyos\BuilderBundle\Service\FixturesHelpers;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Akyos\CoreBundle\Entity\Post;

class NewsFixtures extends Fixture implements FixtureGroupInterface
{
    private FixturesHelpers $fixturesHelpers;

    public function __construct(FixturesHelpers $fixturesHelpers)
    {
        $this->fixturesHelpers = $fixturesHelpers;
    }

    public function load(ObjectManager $manager): void
    {
        $slug = "news";
        $name = "Actualité";
        $shortDescription = "Affichage d'une actualité";
        $isContainer = false;
        $prototype = "default";
        $componentFields = [
            [
                "name" => "Actualité",
                "slug" => "post",
                "desc" => "Actualité",
                "type" => "entity",
                "entity" => Post::class,
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
        return ['component', 'builder-components', 'component-news'];
    }
}