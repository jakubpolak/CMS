<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Menu;
use AppBundle\Form\Admin\MenuType;
use AppBundle\Helper\Message;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Menu controller.
 *
 * @author Jakub Polák, Jana Poláková
 * @Route("/admin/menu")
 */
class MenuController extends Controller {
    /**
     * Index action
     *
     * @Route("", name="admin_menu_index")
     * @Template("@App/admin/menu/index.html.twig")
     * @Method("GET")
     */
    public function indexAction(): array {
        return ['menus' => $this->get('app.service.menu')->getParents()];
    }

    /**
     * Create action.
     *
     * @Route("/create", name="admin_menu_create")
     * @Template("@App/admin/menu/create.html.twig")
     * @Method("GET")
     */
    public function createAction(): array {
        return ['form' => $this->createCreateForm(new Menu())->createView()];
    }

    /**
     * Create process action.
     *
     * @Route("/create", name="admin_menu_createProcess")
     * @Template("@App/admin/menu/create.html.twig")
     * @Method("POST")
     */
    public function createProcessAction(Request $request) {
        $menu = new Menu();

        $form = $this->createCreateForm($menu);
        $form->handleRequest($request);

        $message = null;
        if ($form->isValid()) {
            try {
                $this->get('app.service.menu')->save($menu);
                $this->get('session')->getFlashBag()->add(Message::TYPE_SUCCESS, 'Menu bolo uložené.');
                return $this->redirect($this->generateUrl('admin_menu_index'));
            } catch (\Exception $e) {
                $message = new Message(Message::TYPE_DANGER, 'Menu sa nepodarilo uložiť.');
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
     * @Route("/{id}/update", name="admin_menu_update")
     * @Template("@App/admin/menu/update.html.twig")
     * @Method("GET")
     */
    public function updateAction(Menu $menu): array {
        if ($menu === null) {
            $this->get('session')->getFlashBag()->add(Message::TYPE_DANGER, 'Menu neexistuje.');
            return $this->redirect($this->generateUrl('admin_menu_index'));
        }

        return ['form' => $this->createUpdateForm($menu)->createView()];
    }

    /**
     * Update process action.
     *
     * @Route("/{id}/update", name="admin_menu_updateProcess")
     * @Template("@App/admin/menu/update.html.twig")
     * @Method("POST")
     */
    public function updateProcessAction(Menu $menu, Request $request) {
        $redirect = $this->redirect($this->generateUrl('admin_menu_index'));

        if ($menu === null) {
            $this->get('session')->getFlashBag()->add(Message::TYPE_DANGER, 'Menu neexistuje.');
            return $redirect;
        }

        $form = $this->createUpdateForm($menu);
        $form->handleRequest($request);

        $message = null;
        if ($form->isValid()) {
            try {
                $this->get('app.service.menu')->save($menu);
                $this->get('session')->getFlashBag()->add(Message::TYPE_SUCCESS, 'Menu bolo uložené.');
            } catch (\Exception $e) {
                $message = new Message(Message::TYPE_DANGER, 'Menu sa nepodarilo uložiť.');
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
     * @Route("/{id}/delete", name="admin_menu_delete")
     * @Method("GET")
     */
    public function deleteAction(Menu $menu): RedirectResponse {
        $flashBag = $this->get('session')->getFlashBag();
        $redirect = $this->redirect($this->generateUrl('admin_menu_index'));

        if ($menu === null) {
            $flashBag->add(Message::TYPE_DANGER, 'Menu sa nepodarilo zmazať, pretože neexistuje.');
        } else {
            $this->get('app.service.menu')->delete($menu);
            $flashBag->add(Message::TYPE_SUCCESS, 'Menu bolo zmazané.');
        }

        return $redirect;
    }

    /**
     * Create menu create form.
     *
     * @param Menu $menu
     * @return Form
     */
    private function createCreateForm(Menu $menu): Form {
        return $this->createForm(MenuType::class, $menu, [
            'action' => $this->generateUrl('admin_menu_createProcess'),
            'method' => Request::METHOD_POST
        ]);
    }

    /**
     * Create menu update form.
     *
     * @param Menu $menu
     * @return Form
     */
    private function createUpdateForm(Menu $menu): Form {
        return $this->createForm(MenuType::class, $menu, [
            'action' => $this->generateUrl('admin_menu_updateProcess', ['id' => $menu->getId()]),
            'method' => Request::METHOD_POST
        ]);
    }
}
