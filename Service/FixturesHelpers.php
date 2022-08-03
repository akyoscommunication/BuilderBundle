<?php

namespace Akyos\BuilderBundle\Service;

use Akyos\BuilderBundle\Entity\ComponentField;
use Akyos\BuilderBundle\Entity\ComponentTemplate;
use Akyos\BuilderBundle\Repository\ComponentFieldRepository;
use Akyos\BuilderBundle\Repository\ComponentTemplateRepository;
use Doctrine\ORM\EntityManagerInterface;

class FixturesHelpers
{
    private ComponentTemplateRepository $componentTemplateRepository;

    private ComponentFieldRepository $componentFieldRepository;

    private EntityManagerInterface $entityManager;

    public function __construct(ComponentTemplateRepository $componentTemplateRepository, ComponentFieldRepository $componentFieldRepository, EntityManagerInterface $entityManager)
    {
        $this->componentTemplateRepository = $componentTemplateRepository;
        $this->componentFieldRepository = $componentFieldRepository;
        $this->entityManager = $entityManager;
    }

    /**
     * @param $slug
     * @param $name
     * @param $shortDescription
     * @param $isContainer
     * @param $prototype
     * @param $componentFieldsValues
     * @return bool
     */
    public function updateBdd($slug, $name, $shortDescription, $isContainer, $prototype, $componentFieldsValues): bool
    {
        $componentExists = $this->componentTemplateRepository->findOneBy(['slug' => $slug]);
        $component = $componentExists ?: new ComponentTemplate();

        $component->setName($name);
        $component->setSlug($slug);
        $component->setShortDescription($shortDescription);
        $component->setIsContainer($isContainer);
        $component->setPrototype($prototype);

        foreach ($componentFieldsValues as $componentFieldValues) {
            $componentFieldExists = $this->componentFieldRepository->findOneBy(['slug' => $componentFieldValues['slug'], 'componentTemplate' => $component]);
            $componentField = $componentFieldExists ?: new ComponentField();

            $componentField->setComponentTemplate($component);

            $componentField->setName($componentFieldValues['name']);
            $componentField->setSlug($componentFieldValues['slug']);
            $componentField->setShortDescription($componentFieldValues['desc']);
            $componentField->setType($componentFieldValues['type']);
            $componentField->setFieldValues($componentFieldValues['option']);
            $componentField->setGroups($componentFieldValues['group']);

            if (!$componentFieldExists) {
                $this->entityManager->persist($componentField);
            }
        }

        if (!$componentExists) {
            $this->entityManager->persist($component);
        }

        $this->entityManager->flush();

        return true;
    }
}