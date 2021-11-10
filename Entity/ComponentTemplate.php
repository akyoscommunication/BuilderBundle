<?php

namespace Akyos\BuilderBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @ORM\Entity(repositoryClass="Akyos\BuilderBundle\Repository\ComponentTemplateRepository")
 */
class ComponentTemplate
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
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $slug;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $shortDescription;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isContainer;

    /**
     * @ORM\OneToMany(targetEntity="Akyos\BuilderBundle\Entity\ComponentField", mappedBy="componentTemplate", orphanRemoval=true, cascade={"persist"})
     */
    private $componentFields;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $prototype;

    public function __construct()
    {
        $this->componentFields = new ArrayCollection();
    }

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

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

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

    public function getIsContainer(): ?bool
    {
        return $this->isContainer;
    }

    public function setIsContainer(bool $isContainer): self
    {
        $this->isContainer = $isContainer;

        return $this;
    }

    /**
     * @return Collection|ComponentField[]
     */
    public function getComponentFields(): Collection
    {
        return $this->componentFields;
    }

    public function addComponentField(ComponentField $componentField): self
    {
        if (!$this->componentFields->contains($componentField)) {
            $this->componentFields[] = $componentField;
            $componentField->setComponentTemplate($this);
        }

        return $this;
    }

    public function removeComponentField(ComponentField $componentField): self
    {
        if ($this->componentFields->contains($componentField)) {
            $this->componentFields->removeElement($componentField);
            // set the owning side to null (unless already changed)
            if ($componentField->getComponentTemplate() === $this) {
                $componentField->setComponentTemplate(null);
            }
        }

        return $this;
    }

    public function getPrototype(): ?string
    {
        return $this->prototype;
    }

    public function setPrototype(?string $prototype): self
    {
        $this->prototype = $prototype;

        return $this;
    }
}
