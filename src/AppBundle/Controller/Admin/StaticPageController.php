<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\StaticPage;
use AppBundle\Form\Admin\StaticPageType;
use AppBundle\Helper\Message;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;

/**
 * Static page controller.
 *
 * @author Jakub Polák, Jana Poláková
 * @Route("/admin/static-page")
 */
class StaticPageController extends Controller {
    /**
     * Index action.
     *
     * @Route("/", name="admin_staticPage_index")
     * @Template("@App/admin/static-page/index.html.twig")
     * @Method("GET")
     */
    public function indexAction(): array {
        return ['staticPages' => $this->get('app.service.staticPage')->getAll()];
    }

    /**
     * Create action.
     *
     * @Route("/create", name="admin_staticPage_create")
     * @Template("@App/admin/static-page/create.html.twig")
     * @Method("GET")
     */
    public function createAction(): array {
        return ['form' => $this->createCreateForm(new StaticPage())->createView()];
    }

    /**
     * Create process action.
     *
     * @Route("/create", name="admin_staticPage_createProcess")
     * @Template("@App/admin/static-page/create.html.twig")
     * @Method("POST")
     */
    public function createProcessAction(Request $request) {
        $staticPage = new StaticPage();

        $form = $this->createCreateForm($staticPage);
        $form->handleRequest($request);

        $message = null;
        if($form->isValid()){
            try {
                $this->get('app.service.staticPage')->save($staticPage);
                $this->get('session')->getFlashBag()->add(Message::TYPE_SUCCESS, 'Statická stránka bola uložená.');
                return $this->redirect($this->generateUrl('admin_staticPage_index'));
            } catch (\Exception $e) {
                $message = new Message(Message::TYPE_DANGER, 'Statickú stránku sa nepodarilo uložiť.');
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
     * @Route("/{id}/update/", name="admin_staticPage_update")
     * @Template("@App/admin/static-page/update.html.twig")
     * @Method("GET")
     */
    public function updateAction(StaticPage $staticPage): array {
        if ($staticPage === null) {
            $this->get('session')->getFlashBag()->add(Message::TYPE_DANGER, 'Statická stránka neexistuje.');
            return $this->redirect($this->generateUrl('admin_staticPage_index'));
        }

        return ['form' => $this->createUpdateForm($staticPage)->createView()];
    }

    /**
     * Update process action.
     *
     * @Route("/{id}/update/", name="admin_staticPage_updateProcess")
     * @Template("@App/admin/static-page/update.html.twig")
     * @Method("POST")
     */
    public function updateProcessAction(StaticPage $staticPage, Request $request) {
        $redirect = $this->redirect($this->generateUrl('admin_staticPage_index'));

        if ($staticPage === null) {
            $this->get('session')->getFlashBag()->add(Message::TYPE_DANGER, 'Statická stránka neexistuje.');
            return $redirect;
        }

        $form = $this->createUpdateForm($staticPage);
        $form->handleRequest($request);

        $message = null;
        if ($form->isValid()) {
            try {
                $this->get('app.service.staticPage')->save($staticPage);
                $this->get('session')->getFlashBag()->add(Message::TYPE_SUCCESS, 'Statická stránka bola uložená.');
                return $redirect;
            } catch (\Exception $e) {
                $message = new Message(Message::TYPE_DANGER, 'Statickú stránku sa nepodarilo uložiť.');
            }
        }

        return [
            'form' => $form,
            'message' => $message
        ];
    }

    /**
     * Delete action.
     *
     * @Route("/{id}/delete", name="admin_staticPage_delete")
     * @Method("GET")
     */
    public function deleteAction(StaticPage $staticPage) {
        $flashBag = $this->get('session')->getFlashBag();
        $redirect = $this->redirect($this->generateUrl('admin_staticPage_index'));

        if ($staticPage === null) {
            $flashBag->add(Message::TYPE_DANGER, 'Statickú stránku sa nepodarilo zmazať, pretože neexistuje.');
        } else {
            $this->get('app.service.staticPage')->delete($staticPage);
            $flashBag->add(Message::TYPE_SUCCESS, 'Statická stránka bola zmazaná.');
        }

        return $redirect;
    }

    /**
     * Create create form.
     *
     * @param StaticPage $staticPage
     * @return Form
     */
    private function createCreateForm(StaticPage $staticPage): Form {
        return $this->createForm(StaticPageType::class, $staticPage, [
            'action' => $this->generateUrl('admin_staticPage_createProcess'),
            'method' => Request::METHOD_POST
        ]);
    }

    /**
     * Create update form.
     *
     * @param StaticPage $staticPage
     * @return Form
     */
    private function createUpdateForm(StaticPage $staticPage): Form {
        return $this->createForm(StaticPageType::class, $staticPage, [
            'action' => $this->generateUrl('admin_staticPage_updateProcess', ['id' => $staticPage->getId()]),
            'method' => Request::METHOD_POST
        ]);
    }
}