<?php

namespace AppBundle\Controller\Home;

use AppBundle\Entity\Article;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Article controller.
 *
 * @author Jakub Polák, Jana Poláková
 *
 * @Route("/article")
 */
class ArticleController extends Controller {
    /**
     * List action.
     *
     * @Route("/", name="home_article_list")
     * @Template("@App/home/article/list.html.twig")
     * @Method("GET")
     */
    public function listAction() {
        $articleList = $this->get('app.service.article')->getAllByIsPublished(true);
        return ['articleList' => $articleList];
    }

    /**
     * Show action.
     *
     * @Route("/{id}/show", name="home_article_show")
     * @Template("@App/home/article/article.html.twig")
     */
    public function showAction(Article $article) {
        return ['article' => $article];
    }
}