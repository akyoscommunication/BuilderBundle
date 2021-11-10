<?php

namespace Akyos\BuilderBundle\Components\Form;

use Akyos\BuilderBundle\Service\FixturesHelpers;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class FormFixtures extends Fixture implements FixtureGroupInterface
{
    private FixturesHelpers $fixturesHelpers;

    public function __construct(FixturesHelpers $fixturesHelpers)
    {
        $this->fixturesHelpers = $fixturesHelpers;
    }
    
    public function load(ObjectManager $manager): void
    {
        $slug = 'form';
        $name = 'Formulaire';
        $shortDescription = 'Formulaire de contact';
        $isContainer = false;
        $prototype = 'default';
        $componentFields = [
            [
                "name" => "Titre du formulaire",
                "slug" => "title",
                "desc" => "Titre du formulaire",
                "type" => "text",
                "entity" => "App\Entity\ProductBooking",
                "option" => [],
                "group" => "Général",
            ], [
                "name" => "Id du formulaire",
                "slug" => "idform",
                "desc" => "Id du formulaire",
                "type" => "int",
                "entity" => "App\Entity\Subscription",
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
        return ['component', 'builder-components', 'component-form'];
    }
}