<?php

namespace Akyos\BuilderBundle\Entity;

use Akyos\BuilderBundle\Repository\ComponentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\OrderBy;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Gedmo\Translatable\Translatable;

#[ORM\Entity(repositoryClass: ComponentRepository::class)]
class Component implements Translatable
{
    use TimestampableEntity;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $customId;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $customClasses;

    #[ORM\Column(type: 'string', length: 255)]
    private $type;

    #[ORM\Column(type: 'integer')]
    private $typeId;

    #[ORM\Column(type: 'integer')]
    private $position;

    #[ORM\Column(type: 'boolean')]
    private $visibilityXS;

    #[ORM\Column(type: 'boolean')]
    private $visibilityS;

    #[ORM\Column(type: 'boolean')]
    private $visibilityM;

    #[ORM\Column(type: 'boolean')]
    private $visibilityL;

    #[ORM\Column(type: 'boolean')]
    private $visibilityXL;

    #[ORM\OneToMany(targetEntity: ComponentValue::class, mappedBy: 'component', orphanRemoval: true, cascade: ['persist'])]
    private $componentValues;

    #[ORM\ManyToOne(targetEntity: ComponentTemplate::class)]
    #[ORM\JoinColumn(nullable: false)]
    private $componentTemplate;

    #[ORM\ManyToOne(targetEntity: Component::class, inversedBy: 'childComponents')]
    private $parentComponent;

    #[ORM\OneToMany(targetEntity: Component::class, mappedBy: 'parentComponent', cascade: ['remove'])]
    #[OrderBy(['position' => 'ASC'])]
    private $childComponents;

    #[ORM\Column(type: 'boolean')]
    private $isTemp;

    /**
     * @Gedmo\Locale
     * Used locale to override Translation listener`s locale
     * this is not a mapped field of entity metadata, just a simple property
     */
    private $locale;

    public function __construct()
    {
        $this->componentValues = new ArrayCollection();
        $this->childComponents = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCustomId(): ?string
    {
        return $this->customId;
    }

    public function setCustomId(?string $customId): self
    {
        $this->customId = $customId;

        return $this;
    }

    public function getCustomClasses(): ?string
    {
        return $this->customClasses;
    }

    public function setCustomClasses(?string $customClasses): self
    {
        $this->customClasses = $customClasses;

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

    public function getTypeId(): ?int
    {
        return $this->typeId;
    }

    public function setTypeId(int $typeId): self
    {
        $this->typeId = $typeId;

        return $this;
    }

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function setPosition(int $position): self
    {
        $this->position = $position;

        return $this;
    }

    public function getVisibilityXS(): ?bool
    {
        return $this->visibilityXS;
    }

    public function setVisibilityXS(bool $visibilityXS): self
    {
        $this->visibilityXS = $visibilityXS;

        return $this;
    }

    public function getVisibilityS(): ?bool
    {
        return $this->visibilityS;
    }

    public function setVisibilityS(bool $visibilityS): self
    {
        $this->visibilityS = $visibilityS;

        return $this;
    }

    public function getVisibilityM(): ?bool
    {
        return $this->visibilityM;
    }

    public function setVisibilityM(bool $visibilityM): self
    {
        $this->visibilityM = $visibilityM;

        return $this;
    }

    public function getVisibilityL(): ?bool
    {
        return $this->visibilityL;
    }

    public function setVisibilityL(bool $visibilityL): self
    {
        $this->visibilityL = $visibilityL;

        return $this;
    }

    public function getVisibilityXL(): ?bool
    {
        return $this->visibilityXL;
    }

    public function setVisibilityXL(bool $visibilityXL): self
    {
        $this->visibilityXL = $visibilityXL;

        return $this;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getComponentValues(): Collection
    {
        return $this->componentValues;
    }

    public function addComponentValue(ComponentValue $componentValue): self
    {
        if (!$this->componentValues->contains($componentValue)) {
            $this->componentValues[] = $componentValue;
            $componentValue->setComponent($this);
        }

        return $this;
    }

    public function removeComponentValue(ComponentValue $componentValue): self
    {
        if ($this->componentValues->contains($componentValue)) {
            $this->componentValues->removeElement($componentValue);
            // set the owning side to null (unless already changed)
            if ($componentValue->getComponent() === $this) {
                $componentValue->setComponent(null);
            }
        }

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

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getChildComponents(): Collection
    {
        return $this->childComponents;
    }

    public function addChildComponent(self $childComponent): self
    {
        if (!$this->childComponents->contains($childComponent)) {
            $this->childComponents[] = $childComponent;
            $childComponent->setParentComponent($this);
        }

        return $this;
    }

    public function removeChildComponent(self $childComponent): self
    {
        if ($this->childComponents->contains($childComponent)) {
            $this->childComponents->removeElement($childComponent);
            // set the owning side to null (unless already changed)
            if ($childComponent->getParentComponent() === $this) {
                $childComponent->setParentComponent(null);
            }
        }

        return $this;
    }

    public function getParentComponent(): ?self
    {
        return $this->parentComponent;
    }

    public function setParentComponent(?self $parentComponent): self
    {
        $this->parentComponent = $parentComponent;

        return $this;
    }

    public function getIsTemp(): ?bool
    {
        return $this->isTemp;
    }

    public function setIsTemp(bool $isTemp): self
    {
        $this->isTemp = $isTemp;

        return $this;
    }

    public function setTranslatableLocale($locale)
    {
        $this->locale = $locale;
    }
}
