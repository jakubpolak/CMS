<?php

namespace AppBundle\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @author Jakub Polák, Jana Poláková
 *
 * @ORM\Table(name="translation_mapper")
 * @ORM\Entity()
 */
class TranslationMapper implements Entity {
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="entity", type="string")
     */
    private $entity;

    /**
     * @var int
     *
     * @ORM\Column(name="entity_id", type="integer")
     */
    private $entityId;

    /**
     * @var string
     *
     * @ORM\Column(name="attribute", type="string")
     */
    private $attribute;

    /**
     * @var string
     *
     * @ORM\Column(name="attribute_type", type="string")
     */
    private $attributeType;

    /**
     * @var string
     *
     * @ORM\Column(name="attribute_original_content", type="text", nullable=true)
     */
    private $attributeOriginalContent;

    /**
     * @var string
     *
     * @ORM\Column(name="attribute_current_content", type="text", nullable=true)
     */
    private $attributeCurrentContent;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="updated", type="datetime")
     */
    private $updated;

    /**
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="Translation", mappedBy="translationMapper", cascade={"all"})
     */
    private $translations;

    /**
     * @var Language
     *
     * @ORM\ManyToOne(targetEntity="Language", inversedBy="translationMappers")
     * @ORM\JoinColumn(name="language_id", referencedColumnName="id")
     */
    private $language;

    /**
     * Constructor.
     */
    public function __construct() {
        $this->updated = new DateTime();
        $this->translations = new ArrayCollection();
    }

    /**
     * Get id.
     *
     * @return int
     */
    public function getId(): int {
        return $this->id;
    }

    /**
     * Set id.
     *
     * @param int $id
     * @return self
     */
    public function setId($id): self {
        $this->id = $id;

        return $this;
    }

    /**
     * Get entity.
     *
     * @return string
     */
    public function getEntity(): string {
        return $this->entity;
    }

    /**
     * Set entity.
     *
     * @param string $entity
     * @return self
     */
    public function setEntity(string $entity): self {
        $this->entity = $entity;

        return $this;
    }

    /**
     * Get entity id.
     *
     * @return int
     */
    public function getEntityId(): int {
        return $this->entityId;
    }

    /**
     * Set entity id.
     *
     * @param int $entityId
     * @return self
     */
    public function setEntityId(int $entityId): self {
        $this->entityId = $entityId;

        return $this;
    }

    /**
     * Get attribute.
     *
     * @return string
     */
    public function getAttribute(): string {
        return $this->attribute;
    }

    /**
     * Set attribute.
     *
     * @param string $attribute
     * @return self
     */
    public function setAttribute(string $attribute): self {
        $this->attribute = $attribute;

        return $this;
    }

    /**
     * Get attribute type.
     *
     * @return string
     */
    public function getAttributeType(): string {
        return $this->attributeType;
    }

    /**
     * Set attribute type.
     *
     * @param string $attributeType
     * @return self
     */
    public function setAttributeType(string $attributeType): self {
        $this->attributeType = $attributeType;

        return $this;
    }

    /**
     * Get attribute original content.
     *
     * @return string
     */
    public function getAttributeOriginalContent(): string {
        return $this->attributeOriginalContent;
    }

    /**
     * Set attribute original content.
     *
     * @param string $attributeOriginalContent
     * @return self
     */
    public function setAttributeOriginalContent(string $attributeOriginalContent): self {
        $this->attributeOriginalContent = $attributeOriginalContent;

        return $this;
    }

    /**
     * Get attribute current content.
     *
     * @return string
     */
    public function getAttributeCurrentContent(): string {
        return $this->attributeCurrentContent;
    }

    /**
     * Set attribute current content.
     *
     * @param mixed $attributeCurrentContent
     * @return self
     */
    public function setAttributeCurrentContent(string $attributeCurrentContent): self {
        $this->attributeCurrentContent = $attributeCurrentContent;

        return $this;
    }

    /**
     * Get updated.
     *
     * @return DateTime
     */
    public function getUpdated(): DateTime {
        return $this->updated;
    }

    /**
     * Set updated.
     *
     * @param mixed $updated
     * @return self
     */
    public function setUpdated(DateTime $updated): self {
        $this->updated = $updated;

        return $this;
    }

    /**
     * Get translations.
     *
     * @return Collection
     */
    public function getTranslations(): Collection {
        return $this->translations;
    }

    /**
     * Get translations.
     *
     * @param Collection $translations
     * @return self
     */
    public function setTranslations(Collection $translations): self {
        $this->translations = $translations;

        return $this;
    }

    /**
     * Get language.
     *
     * @return Language
     */
    public function getLanguage(): Language {
        return $this->language;
    }

    /**
     * Set language.
     *
     * @param Language $language
     * @return self
     */
    public function setLanguage(Language $language): self {
        $this->language = $language;

        return $this;
    }
}