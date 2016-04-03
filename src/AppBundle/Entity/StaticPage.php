<?php

namespace AppBundle\Entity;

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
     * Get id.
     *
     * @return int
     */
    public function getId(){
        return $this->id;
    }

    /**
     * Get heading.
     *
     * @return string
     */
    public function getHeading(){
        return $this->heading;
    }

    /**
     * Set heading.
     *
     * @param string $heading
     * @return self
     */
    public function setHeading($heading){
        $this->heading = $heading;

        return $this;
    }

    /**
     * Get content.
     *
     * @return string
     */
    public function getContent(){
        return $this->content;
    }

    /**
     * Set content.
     *
     * @param string $content
     * @return self
     */
    public function setContent($content){
        $this->content = $content;

        return $this;
    }

    /**
     * Get is active.
     *
     * @return boolean
     */
    public function getIsActive(){
        return $this->isActive;
    }

    /**
     * Set is active.
     *
     * @param boolean $isActive
     * @return self
     */
    public function setIsActive($isActive){
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * Get meta.
     *
     * @return Meta
     */
    public function getMeta(){
        return $this->meta;
    }

    /**
     * Set meta.
     *
     * @param Meta $meta
     * @return self
     */
    public function setMeta($meta){
        $this->meta = $meta;

        return $this;
    }
}