<?php

namespace Akyos\BuilderBundle\Components\LastNews;

use Akyos\BuilderBundle\Entity\ComponentField;
use Akyos\BuilderBundle\Entity\ComponentTemplate;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class LastNewsFixtures extends Fixture implements FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $component = new ComponentTemplate();
        $component->setName('Dernières actualités');
        $component->setSlug('last_news');
        $component->setShortDescription('Affiche les 3 dernières actualités');
        $component->setIsContainer(false);
        $component->setPrototype('default');

        $componentFieldArray = [
            
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