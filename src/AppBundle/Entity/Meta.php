<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Meta.
 *
 * @author Jakub Polák, Jana Poláková
 *
 * @ORM\Table(name="meta")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\MetaRepository")
 */
class Meta implements Entity{
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
     * @ORM\Column(name="meta_keywords", type="string", length=510, nullable=true)
     */
    private $metaKeywords;

    /**
     * @var string
     *
     * @ORM\Column(name="meta_description", type="text", nullable=true)
     */
    private $metaDescription;

    /**
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="Article", mappedBy="meta")
     */
    private $articles;

    /**
     * Meta constructor.
     */
    public function __construct() {
        $this->articles = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set metaKeywords.
     *
     * @param string $metaKeywords
     * @return self
     */
    public function setMetaKeywords($metaKeywords) {
        $this->metaKeywords = $metaKeywords;

        return $this;
    }

    /**
     * Get metaKeywords.
     *
     * @return string
     */
    public function getMetaKeywords() {
        return $this->metaKeywords;
    }

    /**
     * Set metaDescription.
     *
     * @param string $metaDescription
     * @return self
     */
    public function setMetaDescription($metaDescription) {
        $this->metaDescription = $metaDescription;

        return $this;
    }

    /**
     * Get metaDescription.
     *
     * @return string
     */
    public function getMetaDescription() {
        return $this->metaDescription;
    }

    /**
     * Get articles.
     *
     * @return Collection
     */
    public function getArticles() {
        return $this->articles;
    }

    /**
     * Set articles.
     *
     * @param Collection $articles
     * @return self
     */
    public function setArticles(Collection $articles) {
        $this->articles = $articles;

        return $this;
    }
}

