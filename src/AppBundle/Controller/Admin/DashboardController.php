<?php

namespace AppBundle\Controller\Admin;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Dashboard controller.
 *
 * @author Jakub Polák, Jana Poláková
 * @Route("/admin/dashboard")
 */
class DashboardController extends Controller {
    /**
     * @Route("", name="admin_dashboard_index")
     * @Template("@App/admin/dashboard/index.html.twig")
     * @Method("GET")
     */
    public function indexAction(): array {
        return [];
    }
}
