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
        $menuTree = $this->get('app.service.menu')->getMenu(true);
        $articleList = $this->get('app.service.article')->getAllByIsPublished(true);

        return [
            'menuTree' => $menuTree,
            'articleList' => $articleList
        ];
    }

    /**
     * Show action.
     *
     * @Route("/{id}/show", name="home_article_show")
     * @Template("@App/home/article/article.html.twig")
     */
    public function showAction(Article $article) {
        $menuTree = $this->get('app.service.menu')->getMenu(true);
        $articlea = $article;

        return [
            'menuTree' => $menuTree,
            'article' => $articlea
        ];
    }
}