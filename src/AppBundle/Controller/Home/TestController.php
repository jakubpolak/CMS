<?php

namespace AppBundle\Controller\Home;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Test controller
 *
 * @author Jakub Polák, Jana Poláková
 * @Route("/test")
 */
class TestController extends Controller {
    /**
     * Clear cache action.
     *
     * @Route("/clear-cache", name="home_test_clearCache")
     * @Method("GET")
     */
    public function clearCacheAction() {
        $this->get('app.service.cache')->clearCache();

        return new Response('Done ...');
    }

    /**
     * Translation action.
     *
     * @Route("/translation", name="home_test_translation")
     * @Method("GET")
     */
    public function translationAction() {
        $this->get('app.service.translation')->synchronize();

        return new Response('Done ...');
    }
}