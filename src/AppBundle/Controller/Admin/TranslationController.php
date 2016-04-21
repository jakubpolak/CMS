<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Helper\Message;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
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
     * @Route("/{page}", defaults={"page" = 1}, name="admin_translation_index")
     * @Template("@App/admin/translation/index.html.twig")
     * @Method("GET")
     */
    public function indexAction(int $page): array {
        $resultsPerPage = $this->get('service_container')->getParameter('results_per_page');
        $translationService = $this->get('app.service.translation');

        return [
            'translations' => $translationService->getPagination($page, $resultsPerPage),
            'pagesCount' => $translationService->getPagesCount($resultsPerPage),
            'page' => $page,
        ];
    }

    /**
     * Update action.
     *
     * @Route("/{entity}/{entityId}/update", name="admin_translation_update")
     * @Template("@App/admin/translation/update.html.twig")
     * @Method("GET")
     */
    public function updateAction(string $entity, int $entityId) {
        $entityGroups = $this->get('app.service.translation')->getTranslationMapperGroups($entity, $entityId);

        if ($entityGroups === null) {
            $this->get('session')->getFlashBag()->add(Message::TYPE_DANGER, 'Preklad neexistuje.');
            return $this->redirect($this->generateUrl('admin_translation_index'));
        }

        return [
            'entityGroups' => $entityGroups,
            'languages' => $this->get('app.service.language')->getAll(),
        ];
    }

    /**
     * Update process action.
     *
     * @Route("/update", name="admin_translation_updateProcess")
     * @Template("@App/admin/translation/update.html.twig")
     * @Method("POST")
     */
    public function updateProcessAction(string $entity, int $entityId, Request $request) {
        return [];
    }

    /**
     * Synchronize action.
     *
     * @Route("/{page}/synchronize", defaults={"page" = 1}, name="admin_translation_synchronize")
     * @Method("GET")
     */
    public function synchronizeAction(int $page) {
        $this->get('app.service.translation')->synchronize();
        return $this->redirect($this->generateUrl('admin_translation_index', ['page' => $page]));
    }
}
