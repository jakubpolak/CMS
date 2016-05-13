<?php

namespace AppBundle\Controller\Admin;


use AppBundle\Entity\Language;
use AppBundle\Form\Admin\LanguageType;
use AppBundle\Helper\MessageHelper;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;

/**
 * Language controller.
 *
 * @author Jakub Polák, Jana Poláková
 * @Route("/admin/language")
 */
class LanguageController extends Controller {
    /**
     * Index action.
     *
     * @Route("", name="admin_language_index")
     * @Template("@App/admin/language/index.html.twig")
     * @Method("GET")
     */
    public function indexAction(): array {
        return ['languages' => $this->get('app.service.language')->getAll()];
    }

    /**
     * Create action.
     *
     * @Route("/create", name="admin_language_create")
     * @Template("@App/admin/language/create.html.twig")
     * @Method("GET")
     */
    public function createAction(): array {
        return ['form' => $this->createCreateForm(new Language())->createView()];
    }

    /**
     * Create process action.
     *
     * @Route("/create", name="admin_language_createProcess")
     * @Template("@App/admin/language/create.html.twig")
     * @Method("POST")
     */
    public function createProcessAction(Request $request) {
        $language = new Language();

        $form = $this->createCreateForm($language);
        $form->handleRequest($request);

        $message = null;
        if ($form->isValid()) {
            try {
                $this->get('app.service.language')->save($language);
                $this->get('session')->getFlashBag()->add(MessageHelper::TYPE_SUCCESS, 'Jazyk bol uložený.');
                return $this->redirect($this->generateUrl('admin_language_index'));
            } catch (\Exception $e) {
                $message = new MessageHelper(MessageHelper::TYPE_DANGER, 'Jazyk sa nepodarilo uložiť.');
            }
        }

        return [
            'form' => $form->createView(),
            'message' => $message,
        ];
    }

    /**
     * Update action.
     *
     * @Route("/{id}/update", name="admin_language_update")
     * @Template("@App/admin/language/update.html.twig")
     * @Method("GET")
     */
    public function updateAction(Language $language) {
        if ($language === null) {
            $this->get('session')->getFlashBag()->add(MessageHelper::TYPE_DANGER, 'Jazyk neexistuje.');
            return $this->redirect($this->generateUrl('admin_language_index'));
        }

        $form = $this->createUpdateForm($language);

        return [
            'form' => $form->createView(),
            'language' => $language,
        ];
    }

    /**
     * Update process action.
     *
     * @Route("/{id}/update", name="admin_language_updateProcess")
     * @Template("@App/admin/language/update.html.twig")
     * @Method("POST")
     */
    public function updateProcessAction(Request $request, Language $language) {
        $redirect = $this->redirect($this->generateUrl('admin_language_index'));

        if ($language === null) {
            $this->get('session')->getFlashBag()->add(MessageHelper::TYPE_DANGER, 'Jazyk neexistuje.');
            return $redirect;
        }

        $form = $this->createUpdateForm($language);
        $form->handleRequest($request);

        $message = null;
        if ($form->isValid()) {
            try {
                $this->get('app.service.language')->save($language);
                $this->get('session')->getFlashBag()->add(MessageHelper::TYPE_SUCCESS, 'Jazyk sa nepodarilo uložiť.');
                return $redirect;
            } catch (\Exception $e) {
                $message = new MessageHelper(MessageHelper::TYPE_DANGER, 'Jazyk sa nepodarilo uložiť.');
            }
        }

        return [
            'message' => $message,
            'form' => $form->createView(),
        ];
    }

    /**
     * Delete action.
     *
     * @Route("/{id}/delete", name="admin_language_delete")
     * @Method("GET")
     */
    public function deleteAction(Language $language) {
        $flashBag = $this->get('session')->getFlashBag();
        $redirect = $this->redirect($this->generateUrl('admin_language_index'));

        if ($language === null) {
            $flashBag->add(MessageHelper::TYPE_DANGER, 'Jazyk sa nepodarilo zmazať, pretože neexistuje.');
        } else {
            $this->get('app.service.language')->delete($language);
            $flashBag->add(MessageHelper::TYPE_SUCCESS, 'Jazyk bol zmazaný.');
        }

        return $redirect;
    }

    /**
     * Create language create form.
     *
     * @param Language $language
     * @return Form
     */
    public function createCreateForm(Language $language): Form {
        return $this->createForm(LanguageType::class, $language, [
            'method' => Request::METHOD_POST,
            'action' => $this->generateUrl('admin_language_createProcess')
        ]);
    }

    /**
     * Create language update form.
     *
     * @param Language $language
     * @return Form
     */
    public function createUpdateForm(Language $language): Form {
        return $this->createForm(LanguageType::class, $language, [
            'method' => Request::METHOD_POST,
            'action' => $this->generateUrl('admin_language_updateProcess', ['id' => $language->getId()])
        ]);
    }
}
