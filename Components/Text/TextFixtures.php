<?php

namespace Akyos\BuilderBundle\Components\Text;

use Akyos\BuilderBundle\Entity\ComponentField;
use Akyos\BuilderBundle\Entity\ComponentTemplate;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class TextFixtures extends Fixture implements FixtureGroupInterface
{
    public function load(ObjectManager $manager)
    {
        $component = new ComponentTemplate();
        $component->setName('Bloc de texte');
        $component->setSlug('text');
        $component->setShortDescription('Insérez un bloc de texte');
        $component->setIsContainer(false);
        $component->setPrototype('default');

        $componentFieldArray = [
            [
                'name' => 'Contenu',
                'slug' => 'content',
                'desc' => 'Insérez un contenu',
                'type' => 'textarea_html',
                'option' => [],
                'group' => 'général',
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
