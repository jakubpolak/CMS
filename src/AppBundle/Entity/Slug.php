<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @author Jakub Polák, Jana Poláková
 *
 * @ORM\Table(name="slug", uniqueConstraints={
 *     @ORM\UniqueConstraint(columns={"content", "language_id"})
 * })
 * @ORM\Entity(repositoryClass="AppBundle\Repository\SlugRepository")
 */
class Slug implements Entity {
    const MENU = 'Menu';
    const ARTICLE = 'Article';
    const STATIC_PAGE = 'StaticPage';
    
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
     * @ORM\Column(name="content", type="string", length=255)
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
     * @var StaticPage
     *
     * @ORM\ManyToOne(targetEntity="StaticPage", inversedBy="slugs")
     * @ORM\JoinColumn(referencedColumnName="id", name="static_page_id")
     */
    private $staticPage;

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
     * Constructor.
     */
    public function __construct() {
        $this->content = '';
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
     * Set content.
     *
     * @param string $content content
     * @return Slug
     */
    public function setContent(string $content) : self {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content.
     *
     * @return string
     */
    public function getContent() : string {
        return $this->content;
    }

    /**
     * Get menu.
     *
     * @return Menu
     */
    public function getMenu() : Menu {
        return $this->menu;
    }

    /**
     * Set menu.
     *
     * @param Menu $menu menu
     * @return Slug
     */
    public function setMenu(Menu $menu) : self {
        $this->menu = $menu;

        return $this;
    }

    /**
     * Get language.
     *
     * @return null|Language
     */
    public function getLanguage() {
        return $this->language;
    }

    /**
     * Set language.
     *
     * @param Language $language language
     * @return Slug
     */
    public function setLanguage(Language $language) : self {
        $this->language = $language;

        return $this;
    }

    /**
     * Get article.
     *
     * @return Article
     */
    public function getArticle() : Article {
        return $this->article;
    }

    /**
     * Set article.
     *
     * @param Article $article article
     * @return Slug
     */
    public function setArticle(Article $article) : self {
        $this->article = $article;

        return $this;
    }

    /**
     * Get staticPage.
     *
     * @return StaticPage
     */
    public function getStaticPage() {
        return $this->staticPage;
    }

    /**
     * Set staticPage.
     *
     * @param StaticPage $staticPage
     * @return Slug
     */
    public function setStaticPage(StaticPage $staticPage) : self {
        $this->staticPage = $staticPage;

        return $this;
    }
}

