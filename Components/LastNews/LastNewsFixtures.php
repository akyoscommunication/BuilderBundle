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
        $component->setShortDescription('Affiche les dernières actualités');
        $component->setIsContainer(false);
        $component->setPrototype('default');

        $componentFieldArray = [
            [
                "name" => "Nombre d'actualité",
                "slug" => "nb",
                "desc" => "Nombre d'actualité à afficher",
                "type" => "int",
                "entity" => "App\Entity\Back\Team",
                "option" => [],
                "group" => "Général",
            ],[
                "name" => "Catégorie à afficher",
                "slug" => "cat",
                "desc" => "Catégorie à afficher",
                "type" => "entity",
                "entity" => "Akyos\CoreBundle\Entity\PostCategory",
                "option" => [],
                "group" => "Général",
            ],[
                "name" => "Texte si pas de résultats",
                "slug" => "no_item_text",
                "desc" => "Message à afficher si aucune actualité ne correspond à la recherche",
                "type" => "text",
                "entity" => "Akyos\CoreBundle\Entity\PostCategory",
                "option" => [],
                "group" => "Général",
            ],[
                "name" => "Pagination ?",
                "slug" => "paginator",
                "desc" => "Afficher une pagination au-delà d'un certain ombre d'articles ?",
                "type" => "bool",
                "entity" => "Akyos\CoreBundle\Entity\PostCategory",
                "option" => [],
                "group" => "Général",
            ],[
                "name" => "Nombre d'articles par page ?",
                "slug" => "posts_per_page",
                "desc" => "Par défaut, 9 articles par page. À utiliser uniquement si la pagination est activée.",
                "type" => "text",
                "entity" => "Akyos\CoreBundle\Entity\PostCategory",
                "option" => [],
                "group" => "Général",
            ],[
                "name" => "Formulaire de filtre par catégorie ?",
                "slug" => "category_filters",
                "desc" => "Formulaire pour filtrer la liste des articles par catégorie ?",
                "type" => "bool",
                "entity" => "Akyos\CoreBundle\Entity\PostCategory",
                "option" => [],
                "group" => "Général",
            ],[
                "name" => "Formulaire de filtre par étiquette ?",
                "slug" => "tag_filters",
                "desc" => "Formulaire pour filtrer la liste des articles par étiquette ?",
                "type" => "bool",
                "entity" => "Akyos\CoreBundle\Entity\PostCategory",
                "option" => [],
                "group" => "Général",
            ]
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