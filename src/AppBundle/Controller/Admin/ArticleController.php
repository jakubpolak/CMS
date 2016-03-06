<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Article;
use AppBundle\Form\Admin\ArticleType;
use AppBundle\Helper\Message;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Article controller.
 *
 * @author Jakub Polák, Jana Poláková
 * @Route("/admin/articles")
 */
class ArticleController extends Controller {
    /**
     * Index action.
     *
     * @Route("/{page}", defaults={"page" = 1}, name="admin_article_index")
     * @Template("@App/admin/article/index.html.twig")
     * @Method("GET")
     */
    public function indexAction(int $page): array {
        $resultsPerPage = $this->get('service_container')->getParameter('results_per_page');
        $filter = $this->get('app.service.filter');

        return [
            'articles' => $filter->getPagination(Article::class, $page, $resultsPerPage),
            'pagesCount' => $filter->getPagesCount(Article::class, $resultsPerPage),
        ];
    }

    /**
     * Create action.
     *
     * @Route("/{page}/create", defaults={"page" = 1}, name="admin_article_create")
     * @Template("@App/admin/article/create.html.twig")
     * @Method("GET")
     */
    public function createAction(int $page): array {
        return ['form' => $this->createCreateForm(new Article(), $page)->createView()];
    }

    /**
     * Create process action.
     *
     * @Route("/{page}/create", defaults={"page" = 1}, name="admin_article_createProcess")
     * @Template("@App/admin/article/create.html.twig")
     * @Method("POST")
     */
    public function createProcessAction(Request $request, int $page) {
        $article = new Article();

        $form = $this->createCreateForm($article, $page);
        $form->handleRequest($request);

        $message = null;
        if ($form->isValid()) {
            try {
                $this->get('app.service.article')->save($article);
                $this->get('session')->getFlashBag()->add(Message::TYPE_SUCCESS, 'Článok bol uložený.');
                return $this->redirect($this->generateUrl('admin_article_index'));
            } catch (\Exception $e) {
                $message = new Message(Message::TYPE_DANGER, 'Článok sa nepodarilo uložiť.');
            }
        }

        return [
            'form' => $form->createView(),
            'message' => $message,
        ];
    }

    /**
     * Update action.
     *
     * @Route("/{page}/{id}/update", defaults={"page" = 1}, name="admin_article_update")
     * @Template("@App/admin/article/update.html.twig")
     * @Method("GET")
     */
    public function updateAction(Article $article, int $page) {
        if ($article === null) {
            $this->get('session')->getFlashBag()->add(Message::TYPE_DANGER, 'Článok neexistuje.');
            return $this->redirect($this->generateUrl('admin_article_index'));
        }

        $form = $this->createUpdateForm($article, $page);

        return [
            'form' => $form->createView(),
            'slugs' => $article->getSlugs(),
            'article' => $article,
            'images' => $article->getImages(),
        ];
    }

    /**
     * Update process action.
     *
     * @Route("/{page}/{id}/update", defaults={"page" = 1}, name="admin_article_updateProcess")
     * @Template("@App/admin/article/update.html.twig")
     * @Method("POST")
     */
    public function updateProcessAction(Article $article, int $page, Request $request) {
        $redirect = $this->redirect($this->generateUrl('admin_article_index', ['page' => $page]));

        if ($article === null) {
            $this->get('session')->getFlashBag()->add(Message::TYPE_DANGER, 'Článok neexistuje.');
            return $redirect;
        }

        $form = $this->createUpdateForm($article, $page);
        $form->handleRequest($request);

        $message = null;
        if ($form->isValid()) {
            try {
                $this->get('app.service.article')->save($article);
                $this->get('session')->getFlashBag()->add(Message::TYPE_SUCCESS, 'Článok bol uložený.');
                return $redirect;
            } catch (\Exception $e) {
                $message = new Message(Message::TYPE_DANGER, 'Článok sa nepodarilo uložiť.');
            }
        }

        return [
            'form' => $form->createView(),
            'message' => $message,
            'slugs' => $article->getSlugs(),
            'article' => $article,
            'images' => $article->getImages(),
        ];
    }

    /**
     * Delete action.
     *
     * @Route("/{page}/{id}//delete", defaults={"page" = 1}, name="admin_article_delete")
     * @Method("GET")
     */
    public function deleteAction(Article $article, int $page): RedirectResponse {
        $flashBag = $this->get('session')->getFlashBag();
        $redirect = $this->redirect($this->generateUrl('admin_article_index', ['page' => $page]));

        if ($article === null) {
            $flashBag->add(Message::TYPE_DANGER, 'Článok sa nepodarilo zmazať, pretože neexistuje.');
        } else {
            $this->get('app.service.article')->delete($article);
            $flashBag->add(Message::TYPE_SUCCESS, 'Článok bol zmazaný.');
        }

        return $redirect;
    }

    /**
     * Create article create form.
     *
     * @param Article $article
     * @param int $page
     * @return Form
     */
    private function createCreateForm(Article $article, int $page): Form {
        return $this->createForm(ArticleType::class, $article, [
            'action' => $this->generateUrl('admin_article_createProcess', ['page' => $page]),
            'method' => Request::METHOD_POST,
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
        return $this->createForm(ArticleType::class, $article, [
            'action' => $this->generateUrl('admin_article_updateProcess', ['id' => $article->getId(), 'page' => $page]),
            'method' => Request::METHOD_POST,
        ]);
    }
}
