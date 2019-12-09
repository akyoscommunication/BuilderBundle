<?php

namespace Akyos\BuilderBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Akyos\BuilderBundle\Repository\BuilderOptionsRepository")
 */
class BuilderOptions
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="simple_array", nullable=true)
     */
    private $hasBuilderEntities = [];

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getHasBuilderEntities(): ?array
    {
        return $this->hasBuilderEntities;
    }

    public function setHasBuilderEntities(?array $hasBuilderEntities): self
    {
        $this->hasBuilderEntities = $hasBuilderEntities;

        return $this;
    }
}
