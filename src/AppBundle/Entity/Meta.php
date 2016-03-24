<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Meta.
 *
 * @author Jakub Polák, Jana Poláková
 *
 * @ORM\Table(name="meta")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\MetaRepository")
 */
class Meta implements Entity {
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
     * @var Article
     *
     * @ORM\OneToOne(targetEntity="Article", inversedBy="meta")
     */
    private $article;

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
     * Get article.
     *
     * @return Article
     */
    public function getArticle() {
        return $this->article;
    }

    /**
     * Set article.
     *
     * @param Article $article article
     * @return self
     */
    public function setArticle(Article $article) {
        $this->article = $article;

        return $this;
    }
}

