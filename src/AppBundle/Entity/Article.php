<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @author Jakub Polák
 *
 * @ORM\Table(name="article")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ArticleRepository")
 */
class Article {
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
     * @Assert\NotBlank()
     * @Assert\Length(min="1", max="255")
     * @ORM\Column(name="heading", type="string", length=255)
     */
    private $heading;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @Assert\Length(min="1")
     * @ORM\Column(name="content", type="text")
     */
    private $content;

    /**
     * @var bool
     *
     * @ORM\Column(name="isPublished", type="boolean")
     */
    private $isPublished;

    /**
     * @var \DateTime
     *
     * @Assert\NotBlank()
     * @Assert\Date()
     * @ORM\Column(name="writtenOn", type="date")
     */
    private $writtenOn;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Slug", mappedBy="article")
     */
    private $slugs;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Image", mappedBy="article")
     */
    private $images;

    /**
     * Constructor.
     */
    public function __construct() {
        $this->isPublished = false;
        $this->writtenOn = new \DateTime();
        $this->slugs = new ArrayCollection();
        $this->images = new ArrayCollection();
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
     * Set heading.
     *
     * @param string $heading
     * @return self
     */
    public function setHeading($heading) {
        $this->heading = $heading;

        return $this;
    }

    /**
     * Get heading.
     *
     * @return string
     */
    public function getHeading() {
        return $this->heading;
    }

    /**
     * Set content.
     *
     * @param string $content
     * @return self
     */
    public function setContent($content) {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content.
     *
     * @return string
     */
    public function getContent() {
        return $this->content;
    }

    /**
     * Set isPublished.
     *
     * @param boolean $isPublished
     * @return self
     */
    public function setIsPublished($isPublished) {
        $this->isPublished = $isPublished;

        return $this;
    }

    /**
     * Get isPublished.
     *
     * @return bool
     */
    public function getIsPublished() {
        return $this->isPublished;
    }

    /**
     * Set writtenOn.
     *
     * @param \DateTime $writtenOn
     * @return self
     */
    public function setWrittenOn($writtenOn) {
        $this->writtenOn = $writtenOn;

        return $this;
    }

    /**
     * Get writtenOn.
     *
     * @return \DateTime
     */
    public function getWrittenOn() {
        return $this->writtenOn;
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
        $slug->setArticle($this);

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
     * Get images.
     *
     * @return ArrayCollection
     */
    public function getImages() {
        return $this->images;
    }

    /**
     * Set images.
     *
     * @param ArrayCollection $images
     * @return self
     */
    public function setImages(ArrayCollection $images) {
        $this->images = $images;

        return $this;
    }

    /**
     * Add image.
     *
     * @param Image $image
     * @return ArrayCollection
     */
    public function addImage(Image $image) {
        $this->images->add($image);
        $image->setArticle($this);

        return $this->images;
    }

    /**
     * Remove image.
     *
     * @param Image $image
     * @return ArrayCollection
     */
    public function removeImage(Image $image) {
        $this->images->remove($image);

        return $this->images;
    }
}

