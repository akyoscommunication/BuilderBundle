<?php

namespace Akyos\BuilderBundle\Components\Text;

use Akyos\BuilderBundle\Service\FixturesHelpers;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class TextFixtures extends Fixture implements FixtureGroupInterface
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
        return ['component', 'builder-components', 'component-text'];
    }

    public function load(ObjectManager $manager): void
    {
        $slug = "text";
        $name = "Bloc de texte";
        $shortDescription = "Insérez un bloc de texte";
        $prototype = "default";
        $componentFields = [['name' => 'Contenu', 'slug' => 'content', 'desc' => 'Insérez un contenu', 'type' => 'textarea_html', 'option' => [], 'group' => 'Général',],];

        $this->fixturesHelpers->updateBdd($slug, $name, $shortDescription, false, $prototype, $componentFields);
    }
}
