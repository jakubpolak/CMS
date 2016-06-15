<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @author Jakub Polák, Jana Poláková
 *
 * @ORM\Table(name="settings")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\SettingsRepository")
 */
class Settings implements Entity {
    const ID = 1;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_advanced_menu_shown", type="boolean")
     */
    private $isAdvancedMenuShown;

    /**
     * Constructor.
     */
    public function __construct() {
        $this->id = self::ID;
        $this->isAdvancedMenuShown = false;
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
     * Get isAdvancedMenuShown.
     *
     * @return boolean
     */
    public function getIsAdvancedMenuShown() : bool {
        return $this->isAdvancedMenuShown;
    }

    /**
     * Set isAdvancedMenuShown.
     *
     * @param boolean $isAdvancedMenuShown
     */
    public function setIsAdvancedMenuShown(bool $isAdvancedMenuShown) {
        $this->isAdvancedMenuShown = $isAdvancedMenuShown;
    }
}
