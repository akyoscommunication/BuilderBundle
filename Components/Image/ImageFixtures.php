<?php

namespace Akyos\BuilderBundle\Components\Image;

use Akyos\BuilderBundle\Entity\ComponentField;
use Akyos\BuilderBundle\Entity\ComponentTemplate;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class ImageFixtures extends Fixture implements FixtureGroupInterface
{
    public function load(ObjectManager $manager)
    {
        $component = new ComponentTemplate();
        $component->setName('Image');
        $component->setSlug('image');
        $component->setShortDescription('Une image simple');
        $component->setIsContainer(false);
        $component->setPrototype('default');

        $componentFieldArray = [
            [
                'name' => 'image',
                'slug' => 'image',
                'desc' => 'Source de l\'image',
                'type' => 'image',
                'option' => [],
                'group' => '',
            ],[
                'name' => 'alt',
                'slug' => 'alt',
                'desc' => 'Description de l\'image',
                'type' => 'text',
                'option' => [],
                'group' => '',
            ],[
                'name' => 'taille',
                'slug' => 'width',
                'desc' => 'la largeur de l\'image',
                'type' => 'text',
                'option' => [],
                'group' => '',
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