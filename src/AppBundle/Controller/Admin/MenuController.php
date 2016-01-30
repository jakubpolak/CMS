<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Menu;
use AppBundle\Form\Admin\MenuType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;

/**
 * Menu controller.
 *
 * @author Jakub Polák
 * @Route("/admin/article")
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
        return []; // TODO: Implement.
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
     * @Method("POST)
     */
    public function createProcessAction(Request $request) {
        return [];
    }

    /**
     * Update action.
     *
     * @Route("/{id}/update", name="admin_menu_update")
     * @Template("@App/admin/menu/update.html.twig")
     * @Method("GET")
     */
    public function updateAction(Menu $menu): array {
        return [];
    }

    /**
     * Update process action.
     *
     * @Route("/{id}/update", name="admin_menu_updateProcess")
     * @Template("@App/admin/menu/update.html.twig")
     * @Method("POST")
     */
    public function updateProcessAction(Menu $menu, Request $request) {
        return [];
    }

    /**
     * Delete action.
     *
     * @Route("/{id}/delete", name="admin_menu_delete")
     * @Method("GET")
     */
    public function deleteAction(Menu $menu): array {
        return [];
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
            'action' => $this->generateUrl('admin_menu_updateProcess'),
            'method' => Request::METHOD_POST
        ]);
    }
}
