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
 * @author Jakub Polák
 * @Route("/admin/article")
 */
class ArticleController extends Controller {
    /**
     * Index action.
     *
     * @Route("/{page}/articles", defaults={"page" = 1}, name="admin_article_index")
     * @Template("@App/admin/article/index.html.twig")
     * @Method("GET")
     */
    public function indexAction(int $page): array {
        $resultsPerPage = $this->get('service_container')->getParameter('results_per_page');
        $filter = $this->get('app.service.filter');

        return [
            'articles' => $filter->getPagination(Article::class, $page, $resultsPerPage),
            'pagesCount' => $filter->getPagesCount(Article::class, $resultsPerPage),
            'currentPage' => $page
        ];
    }

    /**
     * Create action.
     *
     * @Route("/create", name="admin_article_create")
     * @Template("@App/admin/article/create.html.twig")
     * @Method("GET")
     */
    public function createAction(): array {
        return ['form' => $this->createCreateForm(new Article())->createView()];
    }

    /**
     * Create process action.
     *
     * @Route("/create", name="admin_article_createProcess")
     * @Template("@App/admin/article/create.html.twig")
     * @Method("POST")
     */
    public function createProcessAction(Request $request) {
        $article = new Article();

        $form = $this->createCreateForm($article);
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
            'message' => $message
        ];
    }

    /**
     * Update action.
     *
     * @Route("/{id}/update", name="admin_article_update")
     * @Template("@App/admin/article/update.html.twig")
     * @Method("GET")
     */
    public function updateAction(Article $article): array {
        if ($article === null) {
            $this->get('session')->getFlashBag()->add(Message::TYPE_DANGER, 'Článok neexistuje.');
            return $this->redirect($this->generateUrl('admin_article_index'));
        }

        return ['form' => $this->createUpdateForm($article)->createView()];
    }

    /**
     * Update process action.
     *
     * @Route("/{id}/update", name="admin_article_updateProcess")
     * @Template("@App/admin/article/update.html.twig")
     * @Method("POST")
     */
    public function updateProcessAction(Article $article, Request $request) {
        if ($article === null) {
            $this->get('session')->getFlashBag()->add(Message::TYPE_DANGER, 'Článok neexistuje.');
            return $this->redirect($this->generateUrl('admin_article_index'));
        }

        $form = $this->createUpdateForm($article);
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
            'message' => $message
        ];
    }

    /**
     * Delete action.
     *
     * @Route("/{id}//delete", name="admin_article_delete")
     * @Method("GET")
     */
    public function deleteAction(Article $article): RedirectResponse {
        $flashBag = $this->get('session')->getFlashBag();

        if ($article === null) {
            $flashBag->add(Message::TYPE_DANGER, 'Článok sa nepodarilo zmazať, pretože neexistuje.');
            return $this->redirect($this->generateUrl('admin_article_index'));
        }

        $this->get('app.service.article')->delete($article);
        $flashBag->add(Message::TYPE_SUCCESS, 'Článok bol zmazaný.');

        return $this->redirect($this->generateUrl('admin_article_index'));
    }

    /**
     * Create article create form.
     *
     * @param Article $article
     * @return Form
     */
    private function createCreateForm(Article $article): Form {
        return $this->createForm(ArticleType::class, $article, [
            'action' => $this->generateUrl('admin_article_createProcess'),
            'method' => Request::METHOD_POST
        ]);
    }

    /**
     * Create article update form.
     *
     * @param Article $article
     * @return Form
     */
    private function createUpdateForm(Article $article): Form {
        return $this->createForm(ArticleType::class, $article, [
            'action' => $this->generateUrl('admin_article_updateProcess', ['id' => $article->getId()]),
            'method' => Request::METHOD_POST
        ]);
    }
}
