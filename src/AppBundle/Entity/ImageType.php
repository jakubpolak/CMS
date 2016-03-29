<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @author Jakub Polák, Jana Poláková
 *
 * @ORM\Table(name="image_type")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ImageTypeRepository")
 */
class ImageType implements Entity {
    const GALLERY = 'GALLERY';
    const SLIDER = 'SLIDER';

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
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="Image", mappedBy="imageType")
     */
    private $images;

    /**
     * Constructor.
     */
    public function __construct() {
        $this->images = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return int|null
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return self
     */
    public function setName(string $name): self {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName(): string {
        return $this->name;
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
     * @return self
     */
    public function addImage(Image $image): self {
        $this->images->add($image);
        $image->setImageType($this);

        return $this;
    }

    /**
     * Remove image.
     *
     * @param Image $image
     * @return self
     */
    public function removeImage(Image $image): self {
        $this->images->remove($image);

        return $this;
    }
}

