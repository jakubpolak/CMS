<?php

namespace AppBundle\Controller\Admin;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;

/**
 * Translation controller.
 *
 * @author Jakub Polák, Jana Poláková
 * @Route("/admin/translation")
 */
class TranslationController extends Controller {

    /**
     * Index action.
     *
     * @Route("", name="admin_translation_index")
     * @Template("@App/admin/translation/index.html.twig")
     * @Method("GET")
     */
    public function indexAction() {
        return [];
    }

    /**
     * Update action.
     *
     * @Route("/update", name="admin_translation_update")
     * @Template("@App/admin/translation/update.html.twig")
     * @Method("GET")
     */
    public function updateAction() {
        return [];
    }

    /**
     * Update process action.
     *
     * @Route("/update", name="admin_translation_updateProcess")
     * @Template("@App/admin/translation/update.html.twig")
     * @Method("POST")
     */
    public function updateProcessAction(\HttpRequest $httpRequest) {
        return [];
    }

    /**
     * Synchronize action.
     *
     * @Route("/synchronize", name="admin_translation_synchronize")
     * @Method("GET")
     */
    public function synchronizeAction() {
        return $this->redirect($this->generateUrl('admin_translation_index'));
    }
}