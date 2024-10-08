<?php

namespace Akyos\BuilderBundle\Entity;

use Akyos\BuilderBundle\Repository\ComponentValueRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Gedmo\Translatable\Translatable;

#[Gedmo\TranslationEntity(class: ComponentValueTranslation::class)]
#[ORM\Entity(repositoryClass: ComponentValueRepository::class)]
class ComponentValue implements Translatable
{
    use TimestampableEntity;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[Gedmo\Translatable]
    #[ORM\Column(type: 'text', nullable: true)]
    private $value;

    #[ORM\ManyToOne(targetEntity: Component::class, inversedBy: 'componentValues')]
    #[ORM\JoinColumn(nullable: false)]
    private $component;

    #[ORM\ManyToOne(targetEntity: ComponentField::class)]
    #[ORM\JoinColumn(nullable: false)]
    private $componentField;

    #[Gedmo\Locale]
    private $locale;

    #[ORM\OneToMany(targetEntity: ComponentValueTranslation::class, mappedBy: 'object', cascade: ['persist', 'remove'])]
    private $translations;

    public function __construct()
    {
        $this->translations = new ArrayCollection();
    }

    public function getValue()
    {
        $decoded = json_decode($this->value);
        if (is_array($decoded)) {
            return $decoded;
        }

        return $this->value;
    }

    public function setValue($value): self
    {
        if (is_array($value)) {
            $value = json_encode($value);
        }
        if (is_object($value)) {
            $value = $value->getId();
        }
        $this->value = $value;

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getComponent(): ?Component
    {
        return $this->component;
    }

    public function setComponent(?Component $component): self
    {
        $this->component = $component;

        return $this;
    }

    public function getComponentField(): ?ComponentField
    {
        return $this->componentField;
    }

    public function setComponentField(?ComponentField $componentField): self
    {
        $this->componentField = $componentField;

        return $this;
    }

    public function setTranslatableLocale($locale)
    {
        $this->locale = $locale;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTranslations(): Collection
    {
        return $this->translations;
    }

    public function addTranslation(ComponentValueTranslation $translation): self
    {
        if (!$this->translations->contains($translation)) {
            $this->translations[] = $translation;
            $translation->setObject($this);
        }

        return $this;
    }

    public function removeTranslation(ComponentValueTranslation $translation): self
    {
        if ($this->translations->contains($translation)) {
            $this->translations->removeElement($translation);
            // set the owning side to null (unless already changed)
            if ($translation->getObject() === $this) {
                $translation->setObject(null);
            }
        }

        return $this;
    }
}
