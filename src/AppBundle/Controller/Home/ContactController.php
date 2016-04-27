<?php

namespace AppBundle\Controller\Home;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Contact controller.
 *
 * @author Jakub Polák, Jana Poláková
 *
 * @Route("/contact")
 */
class ContactController extends Controller {
    /**
     * Contact action.
     *
     * @Route("/", name="home_default_contact")
     * @Template("@App/home/contact/contact.html.twig")
     * @Method("GET")
     */
    public function contactAction() {
        $menuTree = $this->get('app.service.menu')->getMenu(true);

        return ['menuTree' => $menuTree];
    }
}