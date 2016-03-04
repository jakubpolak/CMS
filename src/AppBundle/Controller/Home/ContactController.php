<?php

namespace AppBundle\Controller\Home;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Contact controller.
 *
 * @author Jakub Polák, Jana Poláková
 */
class ContactController extends Controller{
    /**
     * Contact action.
     *
     * @Route("", name="home_contact_contact")
     * @Template("@App/home/contact/contact.html.twig")
     * @Method("GET")
     */
    public function contactAction() {
        return [];
    }

    /**
     * Contact process action.
     *
     * @Route("", name="home_contact_contactProcess")
     * @Template("@App/home/contact/contact.html.twig")
     * @Method("POST")
     */
    public function contactProcessAction() {
        return [];
    }
}
