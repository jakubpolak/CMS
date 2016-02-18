<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @author Jakub Polák, Jana Poláková
 *
 * @ORM\Table(name="menu")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\MenuRepository")
 */
class Menu implements Entity {
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
     * @ORM\Column(name="content", type="text", nullable=true)
     */
    private $content;

    /**
     * @var bool
     *
     * @ORM\Column(name="isActive", type="boolean")
     */
    private $isActive;

    /**
     * @var integer
     *
     * @ORM\Column(name="position", type="integer")
     */
    private $position;

    /**
     * @var Menu
     *
     * @ORM\ManyToOne(targetEntity="Menu", inversedBy="menus")
     * @ORM\JoinColumn(referencedColumnName="id", name="menu_id")
     */
    private $menu;

    /**
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="Menu", mappedBy="menu")
     */
    private $menus;

    /**
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="Slug", mappedBy="menu")
     */
    private $slugs;

    /**
     * Constructor.
     */
    public function __construct() {
        $this->slugs = new ArrayCollection();
        $this->menus = new ArrayCollection();
        $this->isActive = false;
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
     * Set isActive.
     *
     * @param boolean $isActive
     * @return self
     */
    public function setIsActive($isActive) {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * Get isActive.
     *
     * @return bool
     */
    public function getIsActive() {
        return $this->isActive;
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
     * Set position.
     *
     * @param int $position
     * @return self
     */
    public function setPosition($position) {
        $this->position = $position;

        return $this;
    }

    /**
     * Get menu.
     *
     * @return self
     */
    public function getMenu() {
        return $this->menu;
    }

    /**
     * Set menu.
     *
     * @param Menu $menu
     * @return self
     */
    public function setMenu(Menu $menu) {
        $this->menu = $menu;

        return $this;
    }

    /**
     * Get menus.
     *
     * @return Collection
     */
    public function getMenus() {
        return $this->menus;
    }

    /**
     * Set menus.
     *
     * @param Collection $menus
     * @return self
     */
    public function setMenus(Collection $menus){
        $this->menus = $menus;

        return $this;
    }

    /**
     * Get slugs.
     *
     * @return Collection
     */
    public function getSlugs() {
        return $this->slugs;
    }

    /**
     * Set slugs.
     *
     * @param Collection $slugs
     * @return self
     */
    public function setSlugs(Collection $slugs) {
        $this->slugs = $slugs;

        return $this;
    }
}

