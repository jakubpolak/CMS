<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Slug;
use AppBundle\Form\Admin\SlugType;
use AppBundle\Helper\Message;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;

/**
 * Slug controller.
 *
 * @author Jakub Polák, Jana Poláková
 * @Route("/admin/slug")
 */
class SlugController extends Controller {
    /**
     * Create action.
     *
     * @Route("/create/{entityName}/{entityId}", name="admin_slug_create")
     * @Template("@App/admin/slug/create.html.twig")
     * @Method("GET")
     */
    public function createAction(string $entityName, int $entityId) {
        $flashBag = $this->get('session')->getFlashBag();
        $slugService = $this->get('app.service.slug');
        $entity = $slugService->getEntity($entityName, $entityId);

        if ($entity === null || !$slugService->hasSlugs($entity)) {
            $flashBag->add(Message::TYPE_DANGER, 'Vyskytla sa chybe.');
            return $this->redirect($this->generateUrl('admin_dashboard_index'));
        }

        $form = $this->createCreateForm($entityName, $entityId, new Slug());

        return [
            'form' => $form->createView(),
            'entityName' => $entityName,
            'entityId' => $entityId,
        ];
    }

    /**
     * Create process action.
     *
     * @Route("/create/{entityName}/{entityId}", name="admin_slug_createProcess")
     * @Template("@App/admin/slug/create.html.twig")
     * @Method("POST")
     */
    public function createProcessAction(Request $request, string $entityName, int $entityId) {
        $flashBag = $this->get('session')->getFlashBag();
        $slugService = $this->get('app.service.slug');
        $entity = $slugService->getEntity($entityName, $entityId);

        if ($entity === null || !$slugService->hasSlugs($entity)) {
            $flashBag->add(Message::TYPE_DANGER, 'Došlo k chybe.');
            return $this->redirect($this->generateUrl('admin_dashboard_index'));
        }

        $slug = new Slug();
        $form = $this->createCreateForm($entityName, $entityId, $slug);
        $form->handleRequest($request);

        $message = null;
        if ($form->isValid()) {
            try {
                $slugService->save($slug, $entity);
                $flashBag->add(Message::TYPE_SUCCESS, 'Slug bol uložený.');
                return $this->redirect($this->generateUrl("admin_{$entityName}_update", ['id' => $entityId]));
            } catch (\Exception $e) {
                $message = new Message(Message::TYPE_DANGER, 'Slug sa nepodarilo uložiť.');
            }
        }

        return [
            'form' => $form->createView(),
            'message' => $message,
            'entityName' => $entityName,
            'entityId' => $entityId,
        ];
    }

    /**
     * Update action.
     *
     * @Route("/update/{entityName}/{entityId}/{id}", name="admin_slug_update")
     * @Template("@App/admin/slug/update.html.twig")
     * @Method("GET")
     */
    public function updateAction(string $entityName, int $entityId, Slug $slug) {
        $flashBag = $this->get('session')->getFlashBag();
        $slugService = $this->get('app.service.slug');
        $entity = $slugService->getEntity($entityName, $entityId);

        if ($entity === null || !$slugService->hasSlugs($entity) || $slug === null) {
            $flashBag->add(Message::TYPE_DANGER, 'Došlo k chybe.');
            return $this->redirect($this->generateUrl('admin_dashboard_index'));
        }

        $form = $this->createUpdateForm($entityName, $entityId, $slug);

        return [
            'form' => $form->createView(),
            'entityName' => $entityName,
            'entityId' => $entityId,
        ];
    }

    /**
     * Update process action.
     *
     * @Route("/update/{entityName}/{entityId}/{id}", name="admin_slug_updateProcess")
     * @Template("@App/admin/slug/update.html.twig")
     * @Method("POST")
     */
    public function updateProcessAction(Request $request, string $entityName, int $entityId, Slug $slug) {
        $flashBag = $this->get('session')->getFlashBag();
        $slugService = $this->get('app.service.slug');
        $entity = $slugService->getEntity($entityName, $entityId);

        if ($entity === null || !$slugService->hasSlugs($entity) || $slug === null) {
            $flashBag->add(Message::TYPE_DANGER, 'Došlo k chybe.');
            return $this->redirect($this->generateUrl('admin_dashboard_index'));
        }

        $form = $this->createUpdateForm($entityName, $entityId, $slug);
        $form->handleRequest($request);

        $message = null;
        if ($form->isValid()) {
            try {
                $slugService->save($slug, $entity);
                $flashBag->add(Message::TYPE_SUCCESS, 'Slug bol uložený.');
                return $this->redirect($this->generateUrl("admin_{$entityName}_index", ['id' => $entityId]));
            } catch (\Exception $e) {
                $message = new Message(Message::TYPE_DANGER, 'Pre daný jazyk môže byť najviac jeden slug a zároveň musí byť pre daný jazyk unikátny.');
            }
        }

        return [
            'form' => $form->createView(),
            'message' => $message,
            'entityName' => $entityName,
            'entityId' => $entityId,
        ];
    }

    /**
     * Delete action.
     *
     * @Route("/delete/{entityName}/{entityId}/{id}", name="admin_slug_delete")
     * @Method("GET")
     */
    public function deleteAction(string $entityName, int $entityId, Slug $slug) {
        $flashBag = $this->get('session')->getFlashBag();
        $slugService = $this->get('app.service.slug');
        $entity = $slugService->getEntity($entityName, $entityId);

        if ($entity === null || !$slugService->hasSlugs($entity) || $slug === null) {
            $flashBag->add(Message::TYPE_DANGER, 'Došlo k chybe.');
            return $this->redirect($this->generateUrl('admin_dashboard_index'));
        }

        try {
            $slugService->delete($slug);
        } catch (\Exception $e) {
            $flashBag->add(Message::TYPE_SUCCESS, 'Slug bol zmazaný.');
        }

        return $this->redirect($this->generateUrl("admin_{$entityName}_update", ['id' => $entityId]));
    }

    /**
     * Create slug create form.
     *
     * @param string $entityName entity name
     * @param int $entityId entity id
     * @param Slug $slug
     * @return Form
     */
    public function createCreateForm(string $entityName, int  $entityId, Slug $slug): Form {
        return $this->createForm(SlugType::class, $slug, [
            'method' => Request::METHOD_POST,
            'action' => $this->generateUrl('admin_slug_createProcess', [
                'entityName' => $entityName,
                'entityId' => $entityId,
            ])
        ]);
    }

    /**
     * Create update form.
     *
     * @param string $entityName entity name
     * @param int $entityId entity id
     * @param Slug $slug
     * @return Form
     */
    public function createUpdateForm(string $entityName, int $entityId, Slug $slug): Form {
        return $this->createForm(SlugType::class, $slug, [
            'method' => Request::METHOD_POST,
            'action' => $this->generateUrl('admin_slug_updateProcess', [
                'entityName' => $entityName,
                'entityId' => $entityId,
                'id' => $slug->getId(),
            ])
        ]);
    }
}
