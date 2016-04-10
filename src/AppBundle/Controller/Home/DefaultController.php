<?php

namespace AppBundle\Controller\Home;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Default controller.
 *
 * @author Jakub Polák, Jana Poláková
 */
class DefaultController extends Controller {
    /**
     * Index action.
     *
     * @Route("", name="home_default_index")
     * @Template("@App/home/layout.html.twig")
     * @Method("GET")
     */
    public function indexAction() {
        $menuTree = $this->get('app.service.menu')->getMenu(true);

        return ['menuTree' => $menuTree];
    }

    /**
     * Contact action.
     *
     * @Route("/contact", name="home_default_contact")
     * @Template("@App/home/default/contact.html.twig")
     * @Method("GET")
     */
    public function contactAction() {
        $menuTree = $this->get('app.service.menu')->getMenu(true);

        return ['menuTree' => $menuTree];    }
}