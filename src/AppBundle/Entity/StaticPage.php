<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @author Jakub Polák, Jana Poláková
 *
 * @ORM\Table(name="static_page")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\StaticPageRepository")
 */
class StaticPage implements Entity {
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
     * @ORM\Column(name="is_active", type="boolean")
     */
    private $isActive;

    /**
     * @var Meta
     *
     * @ORM\OneToOne(targetEntity="Meta", mappedBy="staticPage", cascade={"ALL"})
     * @ORM\JoinColumn(referencedColumnName="id", name="meta_id")
     */
    private $meta;

    /**
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="Slug", mappedBy="staticPage")
     */
    private $slugs;

    /**
     * Constructor.
     */
    public function __construct() {
        $this->isActive = true;
        $this->slugs = new ArrayCollection();
    }

    /**
     * Get id.
     *
     * @return null|int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Get heading.
     *
     * @return null|string
     */
    public function getHeading() {
        return $this->heading;
    }

    /**
     * Set heading.
     *
     * @param string $heading
     * @return StaticPage
     */
    public function setHeading(string $heading) : self {
        $this->heading = $heading;

        return $this;
    }

    /**
     * Get content.
     *
     * @return null|string
     */
    public function getContent() {
        return $this->content;
    }

    /**
     * Set content.
     *
     * @param string $content content
     * @return StaticPage
     */
    public function setContent(string $content) : self {
        $this->content = $content;

        return $this;
    }

    /**
     * Get isActive.
     *
     * @return bool
     */
    public function getIsActive() : bool {
        return $this->isActive;
    }

    /**
     * Set isActive.
     *
     * @param bool $isActive is active
     * @return StaticPage
     */
    public function setIsActive(bool $isActive) : self {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * Get meta.
     *
     * @return Meta
     */
    public function getMeta() {
        return $this->meta;
    }

    /**
     * Set meta.
     *
     * @param Meta $meta meta
     * @return StaticPage
     */
    public function setMeta(Meta $meta) : self {
        $this->meta = $meta;

        return $this;
    }

    /**
     * Get slugs.
     * 
     * @return Collection
     */
    public function getSlugs() : Collection {
        return $this->slugs;
    }

    /**
     * Set slugs.
     * 
     * @param Collection $slugs slugs
     * @return StaticPage
     */
    public function setSlugs(Collection $slugs) : self {
        $this->slugs = $slugs;
        
        return $this;
    }
}
