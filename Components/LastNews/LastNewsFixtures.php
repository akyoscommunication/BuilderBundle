<?php

namespace Akyos\BuilderBundle\Components\LastNews;

use Akyos\BuilderBundle\Service\FixturesHelpers;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class LastNewsFixtures extends Fixture implements FixtureGroupInterface
{
    private $fixturesHelpers;

    public function __construct(FixturesHelpers $fixturesHelpers)
    {
        $this->fixturesHelpers = $fixturesHelpers;
    }

    public function load(ObjectManager $manager): void
    {
        $slug = "last_news";
        $name = "Dernières actualités";
        $shortDescription = "Affiche les dernières actualités";
        $isContainer = false;
        $prototype = "default";
        $componentFields = [
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

        $this->fixturesHelpers->updateBdd($slug, $name, $shortDescription, $isContainer, $prototype, $componentFields);
    }

    /**
     * @inheritDoc
     */
    public static function getGroups(): array
    {
        return ['component', 'component-last-news'];
    }
}