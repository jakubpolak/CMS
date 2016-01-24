<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @author Jakub Polák
 *
 * @ORM\Table(name="slug")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\SlugRepository")
 */
class Slug {
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
     * @ORM\Column(name="content", type="string", length=255, unique=true)
     */
    private $content;

    /**
     * @var Menu
     *
     * @ORM\ManyToOne(targetEntity="Menu", inversedBy="slugs")
     * @ORM\JoinColumn(referencedColumnName="id", name="menu_id")
     */
    private $menu;

    /**
     * @var Language
     *
     * @ORM\ManyToOne(targetEntity="Language", inversedBy="slugs")
     * @ORM\JoinColumn(referencedColumnName="id", name="language_id")
     */
    private $language;

    /**
     * @var Article
     *
     * @ORM\ManyToOne(targetEntity="Article", inversedBy="slugs")
     * @ORM\JoinColumn(referencedColumnName="id", name="article_id")
     */
    private $article;

    /**
     * Get id.
     *
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set content.
     *
     * @param string $content
     * @return Slug
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
     * Get menu.
     *
     * @return Menu
     */
    public function getMenu() {
        return $this->menu;
    }

    /**
     * Set menu.
     *
     * @param Menu $menu
     * @return Slug
     */
    public function setMenu($menu) {
        $this->menu = $menu;

        return $this;
    }

    /**
     * Get language.
     *
     * @return Language
     */
    public function getLanguage() {
        return $this->language;
    }

    /**
     * Set language.
     *
     * @param Language $language
     * @return Slug
     */
    public function setLanguage($language) {
        $this->language = $language;

        return $this;
    }

    /**
     * Get article.
     *
     * @return Article
     */
    public function getArticle() {
        return $this->article;
    }

    /**
     * Set article.
     *
     * @param Article $article
     * @return Slug
     */
    public function setArticle($article) {
        $this->article = $article;

        return $this;
    }
}

