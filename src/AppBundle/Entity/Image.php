<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @author Jakub Polák, Jana Poláková
 *
 * @ORM\Table(name="image")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ImageRepository")
 */
class Image implements Entity {
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
     * @var int
     *
     * @ORM\Column(name="position", type="integer")
     */
    private $position;

    /**
     * @var Article
     *
     * @ORM\ManyToOne(targetEntity="Article", inversedBy="images")
     * @ORM\JoinColumn(referencedColumnName="id", name="article_id")
     */
    private $article;

    /**
     * @var ImageType
     *
     * @ORM\ManyToOne(targetEntity="ImageType", inversedBy="images")
     * @ORM\JoinColumn(referencedColumnName="id", name="image_type_id")
     */
    private $imageType;

    /**
     * Constructor.
     */
    public function __construct() {
        $this->position = 0;
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
     * Set position.
     *
     * @param integer $position
     * @return self
     */
    public function setPosition($position) {
        $this->position = $position;

        return $this;
    }

    /**
     * Get position.
     *
     * @return int
     */
    public function getPosition() {
        return $this->position;
    }

    /**
     * Get article.
     *
     * @return self
     */
    public function getArticle() {
        return $this->article;
    }

    /**
     * Set article.
     *
     * @param Article $article
     * @return self
     */
    public function setArticle($article) {
        $this->article = $article;

        return $this;
    }

    /**
     * Get imageType.
     *
     * @return ImageType
     */
    public function getImageType() {
        return $this->imageType;
    }

    /**
     * Set imageType.
     *
     * @param ImageType $imageType
     * @return self
     */
    public function setImageType($imageType) {
        $this->imageType = $imageType;

        return $this;
    }
}

