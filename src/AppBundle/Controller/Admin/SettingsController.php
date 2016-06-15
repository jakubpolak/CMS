<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Settings;
use AppBundle\Form\Admin\SettingsType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Settings controller.
 * 
 * @author Jakub Polák, Jana Poláková
 * @Route("/{_locale}/admin/settings", defaults={"_locale" = "%locale%"})
 */
class SettingsController  extends Controller {
    /**
     * Index action.
     *
     * @Route("", name="admin_settings_update")
     * @Template("@App/admin/settings/update.html.twig")
     * @Method("GET")
     */
    public function indexAction() : array {
        return [];
    }

    /**
     * Index process action.
     *
     * @Route("", name="admin_settings_updateProcess")
     * @Template("@App/admin/settings/update.html.twig")
     * @Method("POST")
     */
    public function indexProcessAction(Request $request) {
        return [];
    }

    /**
     * Create update settings form.
     *
     * @param Settings $settings
     * @return Form
     */
    private function createUpdateForm(Settings $settings) : Form {
        return $this->createForm(SettingsType::class, $settings, [
            'action' => $this->generateUrl('admin_settings_updateProcess'),
            'method' => Request::METHOD_POST
        ]);
    }
}
