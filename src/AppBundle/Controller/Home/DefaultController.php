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
     * @Template("@App/home/default/index.html.twig")
     * @Method("GET")
     */
    public function indexAction(): array {
        $sliderImages = $this->get('app.service.image')->getAllToSliderOrderByPosition();

        return ['sliderImages' => $sliderImages];
    }
}