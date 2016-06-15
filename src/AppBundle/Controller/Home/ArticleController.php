<?php

namespace AppBundle\Controller\Home;

use AppBundle\Entity\Article;
use AppBundle\Entity\Slug;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Article controller.
 *
 * @author Jakub Polák, Jana Poláková
 * @Route("/{_locale}", defaults={"_locale" = "%locale%"})
 */
class ArticleController extends Controller {
    /**
     * List action.
     *
     * @Route("/articles", name="home_article_index")
     * @Template("@App/home/article/index.html.twig")
     * @Method("GET")
     */
    public function indexAction() : array {
        return [
            'articleList' => $this->get('app.service.article')->getByIsPublished(true)
        ];
    }

    /**
     * Show action.
     *
     * @Route("/articles/{slugOrId}", name="home_article_show")
     * @Template("@App/home/article/article.html.twig")
     */
    public function showAction(string $slugOrId) : array {
        return [
            'article' => $this->get('app.service.slug')
                ->getEntityBySlugTypeAndSlugOrId(Slug::ARTICLE, $slugOrId)
        ];
    }
}
