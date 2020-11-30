<?php

namespace Akyos\BuilderBundle\Components\Title;

use Akyos\BuilderBundle\Entity\ComponentField;
use Akyos\BuilderBundle\Entity\ComponentTemplate;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class TitleFixtures extends Fixture implements FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $component = new ComponentTemplate();
        $component->setName('Titre');
        $component->setSlug('title');
        $component->setShortDescription('Titre');
        $component->setIsContainer(false);
        $component->setPrototype('default');

        $componentFieldArray = [
            [
                "name" => "Contenu texte",
                "slug" => "text_content",
                "desc" => "Quel est le contenu du titre ?",
                "type" => "text",
                "entity" => "App\Entity\Platform\Administrator",
                "option" => [],
                "group" => "Général",
            ],[
                "name" => "Font-size",
                "slug" => "font_size",
                "desc" => "Taille de la police en px",
                "type" => "text",
                "entity" => "App\Entity\Platform\Administrator",
                "option" => [],
                "group" => "Style",
            ],[
                "name" => "Balise",
                "slug" => "tag",
                "desc" => "Nom de la balise utilisée (h1, h2, h3, div, ...)",
                "type" => "select",
                "entity" => "App\Entity\Platform\Administrator",
                "option" => ["h1:h1","h2:h2","h3:h3","h4:h4","h5:h5","h6:h6","div:div","p:p","span:span"],
                "group" => "Général",
            ],[
                "name" => "Couleur du texte",
                "slug" => "color",
                "desc" => "Choisissez la couleur du texte",
                "type" => "select",
                "entity" => "App\Entity\Platform\Administrator",
                "option" => ["Couleur principale:primary","Couleur secondaire:secondary","Blanc:white","Noir:black"],
                "group" => "Style",
            ],[
                "name" => "Soulignement",
                "slug" => "has_decoration",
                "desc" => "Afficher un trait de décoration sous le titre ?",
                "type" => "bool",
                "entity" => "App\Entity\Platform\Administrator",
                "option" => [],
                "group" => "Général",
            ],[
                "name" => "Alignement",
                "slug" => "position",
                "desc" => "Alignement du texte et du soulignement si présent",
                "type" => "select",
                "entity" => "App\Entity\Platform\Administrator",
                "option" => ["Gauche:left","Centre:center","Droite:right"],
                "group" => "Général",
            ],[
                "name" => "Épaisseur du texte",
                "slug" => "font_weight",
                "desc" => "Quelle est l'épaisseur du texte ?",
                "type" => "select",
                "entity" => "App\Entity\Platform\Administrator",
                "option" => ["Normal:default","Gras:bold","Fin:light"],
                "group" => "Style",
            ],[
                "name" => "Transformation du texte",
                "slug" => "text_transform",
                "desc" => "Quelle le style du texte ?",
                "type" => "select",
                "entity" => "App\Entity\Platform\Administrator",
                "option" => ["Normal:normal","minuscule:lowercase","MAJUSCULE:uppercase"],
                "group" => "Style",
            ],[
                "name" => "Police",
                "slug" => "font",
                "desc" => "Renseignez le nom de la police à utiliser, si différente de la police par défaut du site.",
                "type" => "text",
                "entity" => "App\Entity\Platform\Administrator",
                "option" => [],
                "group" => "Style",
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
        return ['component', 'component-title'];
    }
}