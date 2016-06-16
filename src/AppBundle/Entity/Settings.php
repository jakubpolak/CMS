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
     * @ORM\Column(name="is_advanced_menu_enabled", type="boolean")
     */
    private $isAdvancedMenuEnabled;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_translations_enabled", type="boolean")
     */
    private $isTranslationsEnabled;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_slugs_enabled", type="boolean")
     */
    private $isSlugsEnabled;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_languages_enabled", type="boolean")
     */
    private $isLanguagesEnabled;

    /**
     * Constructor.
     */
    public function __construct() {
        $this->id = self::ID;
        $this->isAdvancedMenuEnabled = false;
        $this->isTranslationsEnabled = false;
        $this->isSlugsEnabled = false;
        $this->isLanguagesEnabled = false;
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
     * Get isAdvancedMenuEnabled.
     *
     * @return boolean
     */
    public function getIsAdvancedMenuEnabled() : bool {
        return $this->isAdvancedMenuEnabled;
    }

    /**
     * Set isAdvancedMenuEnabled.
     *
     * @param boolean $isAdvancedMenuEnabled is translations enabled
     * @return Settings
     */
    public function setIsAdvancedMenuEnabled(bool $isAdvancedMenuEnabled) : Settings {
        $this->isAdvancedMenuEnabled = $isAdvancedMenuEnabled;

        return $this;
    }

    /**
     * Get isTranslationsEnabled.
     *
     * @return boolean
     */
    public function getIsTranslationsEnabled() : bool {
        return $this->isTranslationsEnabled;
    }

    /**
     * Set isTranslationsEnabled.
     *
     * @param boolean $isTranslationsEnabled is translations enabled
     * @return Settings
     */
    public function setIsTranslationsEnabled(bool $isTranslationsEnabled) : Settings {
        $this->isTranslationsEnabled = $isTranslationsEnabled;

        return $this;
    }

    /**
     * Get isSlugsEnabled.
     *
     * @return boolean
     */
    public function getIsSlugsEnabled() : bool {
        return $this->isSlugsEnabled;
    }

    /**
     * Set isSlugsEnabled.
     *
     * @param boolean $isSlugsEnabled
     * @return Settings
     */
    public function setIsSlugsEnabled(bool $isSlugsEnabled) : Settings {
        $this->isSlugsEnabled = $isSlugsEnabled;

        return $this;
    }

    /**
     * Get isLanguagesEnabled.
     *
     * @return boolean
     */
    public function getIsLanguagesEnabled() : bool {
        return $this->isLanguagesEnabled;
    }

    /**
     * Set isLanguagesEnabled.
     *
     * @param boolean $isLanguagesEnabled
     * @return Settings
     */
    public function setIsLanguagesEnabled(bool $isLanguagesEnabled) : Settings {
        $this->isLanguagesEnabled = $isLanguagesEnabled;

        return $this;
    }
}
