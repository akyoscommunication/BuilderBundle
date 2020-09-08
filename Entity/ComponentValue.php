<?php

namespace Akyos\BuilderBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Gedmo\Translatable\Translatable;

/**
 * @ORM\Entity(repositoryClass="Akyos\BuilderBundle\Repository\ComponentValueRepository")
 * @Gedmo\TranslationEntity(class="Akyos\BuilderBundle\Entity\ComponentValueTranslation")
 */
class ComponentValue implements Translatable
{
    use TimestampableEntity;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Gedmo\Translatable
     */
    private $value;

    /**
     * @ORM\ManyToOne(targetEntity="Akyos\BuilderBundle\Entity\Component", inversedBy="componentValues")
     * @ORM\JoinColumn(nullable=false)
     */
    private $component;

    /**
     * @ORM\ManyToOne(targetEntity="Akyos\BuilderBundle\Entity\ComponentField")
     * @ORM\JoinColumn(nullable=false)
     */
    private $componentField;

    /**
     * @Gedmo\Locale
     * Used locale to override Translation listener`s locale
     * this is not a mapped field of entity metadata, just a simple property
     */
    private $locale;

    /**
     * @ORM\OneToMany(targetEntity=ComponentValueTranslation::class, mappedBy="object", cascade={"persist", "remove"})
     */
    private $translations;

    public function __construct()
    {
        $this->translations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getValue()
    {
        $decoded = json_decode($this->value);
        if(is_array($decoded)) {
            return $decoded;
        }

        return $this->value;
    }

    public function setValue($value): self
    {
        if(is_array($value)) {
            $value = json_encode($value);
        }
        $this->value = $value;

        return $this;
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
     * @return Collection|ComponentValueTranslation[]
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
