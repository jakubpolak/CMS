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

        //vycucnut menu, ak nejake existuje a je aktivne, tak zobrazit menus do twigu
        $parentsMenus = $this->get('app.service.menu')->getParents(true);
        $childsMenus = $this->get('app.service.menu')->getChilds(true);
        $menus = $this->get('app.service.menu')->getAllByIsActive(true);

        return [
            'parentsMenus' => $parentsMenus,
            'childsMenus' => $childsMenus,
            'menus' => $menus
        ];
    }
}