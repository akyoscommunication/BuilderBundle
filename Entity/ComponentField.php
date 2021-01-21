<?php

namespace Akyos\BuilderBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Doctrine\ORM\Mapping\UniqueConstraint;

/**
 * @ORM\Entity(repositoryClass="Akyos\BuilderBundle\Repository\ComponentFieldRepository")
 * @ORM\Table(uniqueConstraints={@UniqueConstraint(name="update_constraint", columns={"name", "component_template_id"})})
 */
class ComponentField
{
    use TimestampableEntity;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $shortDescription;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $type;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    /**
     * @ORM\ManyToOne(targetEntity="Akyos\BuilderBundle\Entity\ComponentTemplate", inversedBy="componentFields")
     * @ORM\JoinColumn(nullable=false)
     */
    private $componentTemplate;

    /**
     * @ORM\Column(type="simple_array", nullable=true)
     */
    private $fieldValues = [];

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $groups;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $entity;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getShortDescription(): ?string
    {
        return $this->shortDescription;
    }

    public function setShortDescription(string $shortDescription): self
    {
        $this->shortDescription = $shortDescription;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getComponentTemplate(): ?ComponentTemplate
    {
        return $this->componentTemplate;
    }

    public function setComponentTemplate(?ComponentTemplate $componentTemplate): self
    {
        $this->componentTemplate = $componentTemplate;

        return $this;
    }

    public function getFieldValues(): ?array
    {
        return $this->fieldValues;
    }

    public function setFieldValues(?array $fieldValues): self
    {
        $this->fieldValues = $fieldValues;

        return $this;
    }

    public function getGroups(): ?string
    {
        return $this->groups;
    }

    public function setGroups(?string $groups): self
    {
        $this->groups = $groups;

        return $this;
    }

    public function getEntity(): ?string
    {
        return $this->entity;
    }

    public function setEntity(?string $entity): self
    {
        $this->entity = $entity;

        return $this;
    }
}
