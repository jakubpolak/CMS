<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @author Jakub Polák, Jana Poláková
 *
 * @ORM\Table(name="language")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\LanguageRepository")
 */
class Language implements Entity {
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
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="code", type="string", length=255, unique=true)
     */
    private $code;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_default", type="boolean")
     */
    private $isDefault;

    /**
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="Slug", mappedBy="language")
     */
    private $slugs;

    /**
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="TranslationMapper", mappedBy="language")
     */
    private $translationMappers;

    /**
     * Constructor.
     */
    public function __construct() {
        $this->slugs = new ArrayCollection();
        $this->translationMappers = new ArrayCollection();
        $this->isDefault = false;
    }

    /**
     * Get id.
     *
     * @return int|null
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set name.
     *
     * @param string $name
     * @return self
     */
    public function setName(string $name): self {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name.
     *
     * @return string
     */
    public function getName(): string {
        return $this->name;
    }

    /**
     * Set code.
     *
     * @param string $code
     * @return self
     */
    public function setCode(string $code) {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code.
     *
     * @return string
     */
    public function getCode(): string {
        return $this->code;
    }

    /**
     * Get slugs.
     *
     * @return Collection
     */
    public function getSlugs(): Collection {
        return $this->slugs;
    }

    /**
     * Set slugs.
     *
     * @param Collection $slugs
     * @return self
     */
    public function setSlugs(Collection $slugs): self {
        $this->slugs = $slugs;

        return $this;
    }

    /**
     * Add slug.
     *
     * @param Slug $slug
     * @return self
     */
    public function addSlug(Slug $slug): self {
        $this->slugs->add($slug);
        $slug->setLanguage($this);

        return $this;
    }

    /**
     * Remove slug.
     *
     * @param Slug $slug
     * @return self
     */
    public function removeSlug(Slug $slug): self {
        $this->slugs->remove($slug);

        return $this;
    }

    /**
     * Get isDefault.
     *
     * @return bool
     */
    public function getIsDefault(): bool {
        return $this->isDefault;
    }

    /**
     * Set isLanguage.
     *
     * @param bool $isDefault
     * @return self
     */
    public function setIsDefault(bool $isDefault): self {
        $this->isDefault = $isDefault;

        return $this;
    }
}

