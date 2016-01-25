<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Article;
use AppBundle\Form\Admin\ArticleType;
use AppBundle\Message\Message;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Jakub Polák
 *
 * @Route("/admin/article")
 */
class ArticleController extends Controller {
    /**
     * Index action.
     *
     * @Route("/{page}", defaults={"page" = 1}, name="admin_article_index")
     * @Template(":admin/menu:index.html.twig")
     * @Method("GET")
     */
    public function indexAction(int $page): array {
        return ['entities' => $this->get('service.article')->getForPagination($page)];
    }

    /**
     * Create action.
     *
     * @Route("/{page}/create", defaults={"page" = 1}, name="admin_article_create")
     * @Template(":admin/menu:create.html.twig")
     * @Method("GET")
     */
    public function createAction(int $page): array {
        return ['form' => $this->createCreateForm(new Article(), $page)->createView()];
    }

    /**
     * Create process action.
     *
     * @Route("/{page}/create", defaults={"page" = 1}, name="admin_article_createProcess")
     * @Template(":admin/menu:create.html.twig")
     * @Method("POST")
     */
    public function createProcessAction(int $page, Request $request): array {
        $article = new Article();

        $form = $this->createCreateForm($article, $page);
        $form->handleRequest($request);

        $message = null;
        if ($form->isValid()) {
            try {
                $this->get('service.article')->save($article);
                $this->get('session')->getFlashBag()->add(Message::TYPE_SUCCESS, 'Článok bol uložený.');
                return $this->redirect($this->generateUrl('admin_article_index', ['page' => $page]));
            } catch (\Exception $e) {
                $message = new Message(Message::TYPE_DANGER, 'Článok sa nepodarilo uložiť.');
            }
        }

        return ['form' => $form->createView(), 'message' => $message];
    }

    /**
     * Update action.
     *
     * @Route("/{id}/{page}/update", defaults={"page" = 1}, name="admin_article_update")
     * @Template(":admin/menu:update.html.twig")
     * @Method("GET")
     */
    public function updateAction(int $page, Article $article): array {
        return [];
    }

    /**
     * Update process action.
     *
     * @Route("/{id}/{page}/update", defaults={"page" = 1}, name="admin_article_updateProcess")
     * @Template(":admin/menu:update.html.twig")
     * @Method("POST")
     */
    public function updateProcessAction(int $page, Article $article, Request $request): array {
        return [];
    }

    /**
     * Delete action.
     *
     * @Route("/{id}/{page}/delete", name="admin_article_delete")
     * @Method("GET")
     */
    public function deleteAction(int $page, Article $article): array {
        return [];
    }

    /**
     * Create article create form.
     *
     * @param Article $article
     * @param int $page
     * @return Form
     */
    private function createCreateForm(Article $article, int $page): Form {
        return $this->createForm(new ArticleType(), $article, [
            'action' => $this->generateUrl('admin_article_createProcess', ['page' => $page]),
            'method' => Request::METHOD_POST
        ]);
    }

    /**
     * Create article update form.
     *
     * @param Article $article
     * @param int $page
     * @return Form
     */
    private function createUpdateForm(Article $article, int $page): Form {
        return $this->createForm(new ArticleType(), $article, [
            'action' => $this->generateUrl('admin_article_updateProcess', ['page' => $page]),
            'method' => Request::METHOD_POST
        ]);
    }
}