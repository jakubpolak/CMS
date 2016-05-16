<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Helper\MessageHelper;
use Doctrine\DBAL\DBALException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
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
            'translations' => $translationService->getPaginationPage($page, $resultsPerPage),
            'pagesCount' => $translationService->getPaginationPagesCount($resultsPerPage),
            'page' => $page,
        ];
    }

    /**
     * Update action.
     *
     * @Route("/{entity}/{entityId}/{page}/update", name="admin_translation_update")
     * @Template("@App/admin/translation/update.html.twig")
     * @Method("GET")
     */
    public function updateAction(string $entity, int $entityId, int $page): array {
        $languageEntityGroups = $this->get('app.service.translation')->getTranslations($entity, $entityId);

        return [
            'languageEntityGroups' => $languageEntityGroups,
            'entity' => $entity,
            'entityId' => $entityId,
            'page' => $page
        ];
    }

    /**
     * Update process action.
     *
     * @Route("/{entity}/{entityId}/{page}/update", name="admin_translation_updateProcess")
     * @Template("@App/admin/translation/update.html.twig")
     * @Method("POST")
     */
    public function updateProcessAction(string $entity, int $entityId, int $page, Request $request): RedirectResponse {
        $flashBag = $this->get('session')->getFlashBag();

        try {
            $this->get('app.service.translation')->updateTranslations($request);
            $flashBag->add(MessageHelper::TYPE_SUCCESS, 'Preklad bol uložený.');
        } catch (DBALException $e) {
            $flashBag->add(MessageHelper::TYPE_DANGER, 'Preklad sa nepodarilo uložiť.');
        }

        return $this->redirect($this->generateUrl(
            'admin_translation_update', [
                'entity' => $entity,
                'entityId' => $entityId,
                'page' => $page,
            ]
        ));
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
