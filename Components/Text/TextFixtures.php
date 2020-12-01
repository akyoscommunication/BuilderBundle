<?php

namespace Akyos\BuilderBundle\Components\Text;

use Akyos\BuilderBundle\Service\FixturesHelpers;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class TextFixtures extends Fixture implements FixtureGroupInterface
{
    private $fixturesHelpers;

    public function __construct(FixturesHelpers $fixturesHelpers)
    {
        $this->fixturesHelpers = $fixturesHelpers;
    }

    public function load(ObjectManager $manager): void
    {
        $slug = "text";
        $name = "Bloc de texte";
        $shortDescription = "Insérez un bloc de texte";
        $isContainer = false;
        $prototype = "default";
        $componentFields = [
            [
                'name' => 'Contenu',
                'slug' => 'content',
                'desc' => 'Insérez un contenu',
                'type' => 'textarea_html',
                'option' => [],
                'group' => 'Général',
            ],
        ];

        $this->fixturesHelpers->updateBdd($slug, $name, $shortDescription, $isContainer, $prototype, $componentFields);
    }

    /**
     * @inheritDoc
     */
    public static function getGroups(): array
    {
        return ['component', 'component-text'];
    }
}
