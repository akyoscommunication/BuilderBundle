<?php

namespace Akyos\BuilderBundle\Entity;

use Akyos\BuilderBundle\Repository\ComponentValueTranslationRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Translatable\Entity\MappedSuperclass\AbstractPersonalTranslation;

/**
 * @ORM\Entity
 * @ORM\Table(name="component_value_translations",
 *     uniqueConstraints={@ORM\UniqueConstraint(name="lookup_unique_idx", columns={
 *         "locale", "object_id", "field"
 *     })}
 * )
 */
class ComponentValueTranslation extends AbstractPersonalTranslation
{
    /**
     * Convinient constructor
     *
     * @param string|null $locale
     * @param string|null $field
     * @param string|null $value
     */
    public function __construct(string $locale = NULL, string $field = NULL, string $value = NULL)
    {
        $this->setLocale($locale);
        $this->setField($field);
        $this->setContent($value);
    }

    /**
     * @ORM\ManyToOne(targetEntity=ComponentValue::class, inversedBy="translations")
     * @ORM\JoinColumn(name="object_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $object;
}
