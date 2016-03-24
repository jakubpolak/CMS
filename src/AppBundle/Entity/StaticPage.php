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
     * @return $this
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
     * @return $this
     */
    public function setContent($content){
        $this->content = $content;

        return $this;
    }
}