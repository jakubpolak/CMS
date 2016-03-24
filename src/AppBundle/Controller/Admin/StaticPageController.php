<?php

namespace AppBundle\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

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
    public function indexAction(){
        return ['staticPages' => $this->get('app.service.staticPage')->getAll()];
    }

    public function createAction() {

    }

    public function createProcessAction() {

    }

    public function updateAction() {

    }

    public function updateProcessAction() {

    }

    public function deleteAction() {

    }

    private function createCreateForm() {

    }

    private function createUpdateForm() {

    }
}