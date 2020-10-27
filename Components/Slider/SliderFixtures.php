<?php

namespace Akyos\BuilderBundle\Components\Slider;

use Akyos\BuilderBundle\Entity\ComponentField;
use Akyos\BuilderBundle\Entity\ComponentTemplate;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class SliderFixtures extends Fixture implements FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $component = new ComponentTemplate();
        $component->setName('Slider');
        $component->setSlug('slider');
        $component->setShortDescription('Container pour slider');
        $component->setIsContainer(true);
        $component->setPrototype('default');

        $componentFieldArray = [
            [
                "name" => "Flèches défilement ?",
                "slug" => "navigation",
                "desc" => "Afficher les flèches ? (Oui par défaut)",
                "type" => "bool",
                "entity" => "App\Entity\Platform\AG\AG",
                "option" => [],
                "group" => "Navigation",
            ],[
                "name" => "Pagination ?",
                "slug" => "pagination",
                "desc" => "Afficher la pagination ? (Non par défaut)",
                "type" => "bool",
                "entity" => "App\Entity\Platform\AG\AG",
                "option" => [],
                "group" => "Navigation",
            ],[
                "name" => "Scrollbar ?",
                "slug" => "scrollbar",
                "desc" => "Afficher la scrollbar ? (Non par défaut)",
                "type" => "bool",
                "entity" => "App\Entity\Platform\Administrator",
                "option" => [],
                "group" => "Navigation",
            ],[
                "name" => "Sens de défilement",
                "slug" => "direction",
                "desc" => "Sens de défilement des slides (Horizontal par défaut)",
                "type" => "select",
                "entity" => "App\Entity\Platform\Administrator",
                "option" => ["Horizontal:horizontal","Vertical:vertical"],
                "group" => "Général",
            ],[
                 "name" => "Hauteur du slider",
                 "slug" => "height",
                 "desc" => "Hauteur du slider",
                 "type" => "text",
                 "entity" => "App\Entity\Platform\AG\AG",
                 "option" => [],
                 "group" => "Général",
             ],[
                "name" => "Vitesse de défilement",
                "slug" => "speed",
                "desc" => "Vitesse de défilement des slides en millisecondes (5000 par défaut)",
                "type" => "int",
                "entity" => "App\Entity\Platform\Administrator",
                "option" => [],
                "group" => "Général",
            ],[
                "name" => "Défilement automatique ?",
                "slug" => "autoplay",
                "desc" => "Est-ce que les slides défilent automatiquement ? (Oui par défaut)",
                "type" => "bool",
                "entity" => "App\Entity\Platform\Administrator",
                "option" => [],
                "group" => "Général",
            ],[
                "name" => "Boucle ?",
                "slug" => "loop",
                "desc" => "Est-ce que les slides défilent à l'infini ? (Oui par défaut)",
                "type" => "bool",
                "entity" => "App\Entity\Platform\Administrator",
                "option" => [],
                "group" => "Général",
            ],[
                "name" => "Nombre de slides par vue",
                "slug" => "slides_per_view",
                "desc" => "Nombre de slides visibles en même temps (1 par défaut)",
                "type" => "int",
                "entity" => "App\Entity\Platform\Administrator",
                "option" => [],
                "group" => "Slides par vue",
            ],[
                "name" => "Nombre de slides par vue (< 991px)",
                "slug" => "slides_per_view_991",
                "desc" => "Nombre de slides visibles en même temps, format tablette (1 par défaut)",
                "type" => "int",
                "entity" => "App\Entity\Platform\Administrator",
                "option" => [],
                "group" => "Slides par vue",
            ],[
                "name" => "Nombre de slides par vue (< 767px)",
                "slug" => "slides_per_view_767",
                "desc" => "Nombre de slides visibles en même temps, format smartphone (1 par défaut)",
                "type" => "int",
                "entity" => "App\Entity\Platform\Administrator",
                "option" => [],
                "group" => "Slides par vue",
            ],
        ];

        foreach ($componentFieldArray as $componentField)
        {
            $newComponentField = new ComponentField();

            $newComponentField->setComponentTemplate($component);

            $newComponentField->setName($componentField['name']);
            $newComponentField->setSlug($componentField['slug']);
            $newComponentField->setShortDescription($componentField['desc']);
            $newComponentField->setType($componentField['type']);
            $newComponentField->setEntity($componentField['entity']);
            $newComponentField->setFieldValues($componentField['option']);
            $newComponentField->setGroups($componentField['group']);

            $manager->persist($newComponentField);
        }

         $manager->persist($component);

        $manager->flush();
    }

    /**
     * @inheritDoc
     */
    public static function getGroups(): array
    {
        return ['component'];
    }
}