<?php

namespace AppBundle\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @author Jakub Polák, Jana Poláková
 *
 * @ORM\Table(name="article")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ArticleRepository")
 */
class Article implements Entity {
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
     * @ORM\Column(name="is_published", type="boolean")
     */
    private $isPublished;

    /**
     * @var \DateTime
     *
     * @Assert\NotBlank()
     * @ORM\Column(name="written_on", type="date")
     */
    private $writtenOn;

    /**
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="Slug", mappedBy="article")
     */
    private $slugs;

    /**
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="Image", mappedBy="article")
     */
    private $images;

    /**
     * @var Meta
     *
     * @ORM\OneToOne(targetEntity="Meta", mappedBy="article", cascade={"ALL"})
     * @ORM\JoinColumn(referencedColumnName="id", name="meta_id")
     */
    private $meta;

    /**
     * Constructor.
     */
    public function __construct() {
        $this->isPublished = false;
        $this->writtenOn = new DateTime();
        $this->slugs = new ArrayCollection();
        $this->images = new ArrayCollection();
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
     * Set heading.
     *
     * @param string $heading
     * @return self
     */
    public function setHeading(string $heading): self {
        $this->heading = $heading;

        return $this;
    }

    /**
     * Get heading.
     *
     * @return string
     */
    public function getHeading(): string {
        return $this->heading;
    }

    /**
     * Set content.
     *
     * @param string $content
     * @return self
     */
    public function setContent(string $content): self {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content.
     *
     * @return string
     */
    public function getContent(): string {
        return $this->content;
    }

    /**
     * Set isPublished.
     *
     * @param bool $isPublished
     * @return self
     */
    public function setIsPublished(bool $isPublished): self {
        $this->isPublished = $isPublished;

        return $this;
    }

    /**
     * Get isPublished.
     *
     * @return bool
     */
    public function getIsPublished(): bool {
        return $this->isPublished;
    }

    /**
     * Set writtenOn.
     *
     * @param DateTime $writtenOn
     * @return self
     */
    public function setWrittenOn(DateTime $writtenOn) {
        $this->writtenOn = $writtenOn;

        return $this;
    }

    /**
     * Get writtenOn.
     *
     * @return DateTime
     */
    public function getWrittenOn(): DateTime {
        return $this->writtenOn;
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
        $slug->setArticle($this);

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
     * Get images.
     *
     * @return Collection
     */
    public function getImages(): Collection {
        return $this->images;
    }

    /**
     * Set images.
     *
     * @param Collection $images
     * @return self
     */
    public function setImages(Collection $images): self {
        $this->images = $images;

        return $this;
    }

    /**
     * Add image.
     *
     * @param Image $image
     * @return Collection
     */
    public function addImage(Image $image): Collection {
        $this->images->add($image);
        $image->setArticle($this);

        return $this->images;
    }

    /**
     * Remove image.
     *
     * @param Image $image
     * @return Collection
     */
    public function removeImage(Image $image): Collection {
        $this->images->remove($image);

        return $this->images;
    }

    /**
     * Get meta.
     *
     * @return Meta
     */
    public function getMeta(): Meta {
        return $this->meta;
    }

    /**
     * Set meta.
     *
     * @param Meta $meta
     * @return self
     */
    public function setMeta(Meta $meta): self {
        $this->meta = $meta;

        return $this;
    }
}

