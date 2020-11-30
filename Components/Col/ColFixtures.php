<?php

namespace Akyos\BuilderBundle\Components\Col;

use Akyos\BuilderBundle\Entity\ComponentField;
use Akyos\BuilderBundle\Entity\ComponentTemplate;
use Akyos\BuilderBundle\Service\FixturesHelpers;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class ColFixtures extends Fixture implements FixtureGroupInterface
{
    private $fixturesHelpers;

    public function __construct(FixturesHelpers $fixturesHelpers)
    {
        $this->fixturesHelpers = $fixturesHelpers;
    }

    public function load(ObjectManager $manager): void
    {
        $slug = "col";
        $name = "Colonne";
        $shortDescription = "Composant colonne";
        $isContainer = true;
        $prototype = "col";
        $componentFields = [
            [
                'name' => 'Colonnes',
                'slug' => 'col',
                'desc' => 'Nombre de colonne (sur 12)',
                'type' => 'text',
                'option' => [],
                'group' => 'Général',
            ],
            [
                'name' => 'Offset',
                'slug' => 'offset',
                'desc' => 'Nombre de colonne offset (md)',
                'type' => 'int',
                'option' => [],
                'group' => 'Général',
            ],
            [
                'name' => 'Image de fond',
                'slug' => 'background_image',
                'desc' => 'Image de fond de la colonne',
                'type' => 'image',
                'option' => [],
                'group' => 'Général',
            ],
            [
                'name' => 'Background-size',
                'slug' => 'background_size',
                'desc' => 'Taille de l\'image de fond',
                'type' => 'select',
                'option' => [
                    'Cover (remplit tout l\'espace):cover',
                    'Contain (rentre entièrement dans le conteneur):contain',
                    'Auto:auto',
                ],
                'group' => 'Général',
            ],
            [
                'name' => 'Padding',
                'slug' => 'padding',
                'desc' => 'Marge interne (ex: 15px 20px 15px 20)',
                'type' => 'text',
                'option' => [],
                'group' => 'Général',
            ],
            [
                'name' => 'Margin',
                'slug' => 'margin',
                'desc' => 'Marge externe (ex: 15px 20px 15px 20)',
                'type' => 'text',
                'option' => [],
                'group' => 'Général',
            ],
            [
                'name' => 'Display',
                'slug' => 'display',
                'desc' => 'Comportement du block et de ses enfants',
                'type' => 'select',
                'option' => [
                    'Block:block',
                    'Flex:flex',
                ],
                'group' => 'Général',
            ],
            [
                'name' => 'Flex direction',
                'slug' => 'flex_direction',
                'desc' => 'Orientation des éléments',
                'type' => 'select',
                'option' => [
                    'Horizontal:row',
                    'Vertical:column',
                ],
                'group' => 'Général',
            ],
            [
                'name' => 'Flex-wrap',
                'slug' => 'flex_wrap',
                'desc' => 'Les éléments peuvent-ils passer "à la ligne" ?',
                'type' => 'select',
                'option' => [
                    'Oui:wrap',
                    'Non:nowrap',
                ],
                'group' => 'Général',
            ],
            [
                'name' => 'Justify-content',
                'slug' => 'justify_content',
                'desc' => 'Alignement des éléments (dans la même direction que flex_direction)',
                'type' => 'select',
                'option' => [
                    'Centrés:center',
                    'Au début:start',
                    'À la fin:end',
                    'Autant d\'espace entre chaque élément:space-b',
                    'Autant d\'espace autour et entre chaque élément:space-a',
                ],
                'group' => 'Général',
            ],
            [
                'name' => 'Align-items',
                'slug' => 'align_items',
                'desc' => 'Alignement des éléments (dans la direction contraire à flex_direction)',
                'type' => 'select',
                'option' => [
                    'Centré:center',
                    'Au début:start',
                    'À la fin:end',
                    'Hauteur égale:stretch',
                ],
                'group' => 'Général',
            ],
            [
                'name' => 'Background position',
                'slug' => 'backgroundPosition',
                'desc' => 'Position du background',
                'type' => 'select',
                'option' => [
                    'Right:right',
                    'Center:center',
                    'Left:left',
                ],
                'group' => 'Général',
            ],
            [
                'name' => 'Break Point',
                'slug' => 'break',
                'desc' => 'Point de rupture',
                'type' => 'text',
                'option' => [],
                'group' => 'Général',
            ],[
                'name' => 'Couleur de texte',
                'slug' => 'text_color',
                'desc' => 'Couleur de texte',
                'type' => 'select',
                'option' => [
                    'Primaire:primary',
                    'Secondaire:secondary',
                    'Noir:dark',
                    'Blanc:light',
                ],
                'group' => 'Style',
            ],[
                'name' => 'Couleur de fond',
                'slug' => 'background_color',
                'desc' => 'Couleur de fond',
                'type' => 'text',
                'option' => [],
                'group' => 'Style',
            ],[
                'name' => "Couleur de fond de l'élément à l'intérieur",
                'slug' => 'background_color_el',
                'desc' => 'Couleur de fond',
                'type' => 'text',
                'option' => [],
                'group' => 'Style',
            ],[
                'name' => "Hauteur de la colonne",
                'slug' => 'height',
                'desc' => 'Hauteur de la colonne',
                'type' => 'text',
                'option' => [],
                'group' => 'Style',
            ],[
                'name' => "Largeur max des éléments à l'intérieur",
                'slug' => 'width_el',
                'desc' => "Largeur max des éléments à l'intérieur",
                'type' => 'text',
                'option' => [],
                'group' => 'Style',
            ],[
                'name' => "Marge de l'élément à l'intérieur",
                'slug' => 'margin_el',
                'desc' => "Marge de l'élément à l'intérieur",
                'type' => 'text',
                'option' => [],
                'group' => 'Style',
            ],
        ];

        $this->fixturesHelpers->updateBdd($slug, $name, $shortDescription, $isContainer, $prototype, $componentFields);
    }

    /**
     * @inheritDoc
     */
    public static function getGroups(): array
    {
        return ['component', 'component-col'];
    }
}
