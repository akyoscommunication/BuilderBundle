<?php

namespace Akyos\BuilderBundle\Service;

use Akyos\BuilderBundle\Entity\ComponentField;
use Akyos\BuilderBundle\Entity\ComponentTemplate;
use Akyos\BuilderBundle\Repository\ComponentFieldRepository;
use Akyos\BuilderBundle\Repository\ComponentTemplateRepository;
use Doctrine\ORM\EntityManagerInterface;

class FixturesHelpers
{
    private readonly EntityManagerInterface $entityManager;

    public function __construct(private readonly ComponentTemplateRepository $componentTemplateRepository, private readonly ComponentFieldRepository $componentFieldRepository, EntityManagerInterface $entityManager)
    {
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
    public function updateBdd(string $slug, string $name, string $shortDescription, bool $isContainer, ?string $prototype, $componentFieldsValues): bool
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