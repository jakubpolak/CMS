<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @author Jakub Polák
 *
 * @ORM\Table(name="language")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\LanguageRepository")
 */
class Language {
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
     * @ORM\Column(name="is_default", type="boolean", nullable=false)
     */
    private $isDefault;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Slug", mappedBy="language")
     */
    private $slugs;

    /**
     * Constructor.
     */
    public function __construct() {
        $this->slugs = new ArrayCollection();
        $this->isDefault = false;
    }

    /**
     * Get id.
     *
     * @return int
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
    public function setName($name) {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name.
     *
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Set code.
     *
     * @param string $code
     * @return self
     */
    public function setCode($code) {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code.
     *
     * @return string
     */
    public function getCode() {
        return $this->code;
    }

    /**
     * Get slugs.
     *
     * @return ArrayCollection
     */
    public function getSlugs() {
        return $this->slugs;
    }

    /**
     * Set slugs.
     *
     * @param ArrayCollection $slugs
     * @return self
     */
    public function setSlugs(ArrayCollection $slugs) {
        $this->slugs = $slugs;

        return $this;
    }

    /**
     * Add slug.
     *
     * @param Slug $slug
     * @return self
     */
    public function addSlug(Slug $slug) {
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
    public function removeSlug(Slug $slug) {
        $this->slugs->remove($slug);

        return $this;
    }

    /**
     * Get isDefault.
     *
     * @return boolean
     */
    public function getIsDefault() {
        return $this->isDefault;
    }

    /**
     * Set isLanguage.
     *
     * @param boolean $isDefault
     * @return self
     */
    public function setIsDefault($isDefault) {
        $this->isDefault = $isDefault;

        return $this;
    }
}

