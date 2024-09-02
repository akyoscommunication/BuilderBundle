<?php

namespace Akyos\BuilderBundle\Components\Slide;

use Akyos\BuilderBundle\Service\FixturesHelpers;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class SlideFixtures extends Fixture implements FixtureGroupInterface
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
        return ['component', 'builder-components', 'component-slide'];
    }

    public function load(ObjectManager $manager): void
    {
        $slug = "slide";
        $name = "Slide";
        $shortDescription = "Slide du slider";
        $prototype = "default";
        $componentFields = [

        ];

        $this->fixturesHelpers->updateBdd($slug, $name, $shortDescription, true, $prototype, $componentFields);
    }
}