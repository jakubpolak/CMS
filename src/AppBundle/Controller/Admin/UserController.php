<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\User;
use AppBundle\Form\Admin\UserType;
use AppBundle\Helper\MessageHelper;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * User controller.
 *
 * @author Jakub Polák, Jana Poláková
 * @Route("/{_locale}/admin/users", defaults={"_locale" = "%locale%"})
 */
class UserController extends Controller {
    /**
     * Index action.
     *
     * @Route("", name="admin_user_index")
     * @Template("@App/admin/user/index.html.twig")
     * @Method("GET")
     */
    public function indexAction(): array {
        return ['users' => $this->get('app.service.user')->getAll()];
    }

    /**
     * Create action.
     *
     * @Route("/create", name="admin_user_create")
     * @Template("@App/admin/user/create.html.twig")
     * @Method("GET")
     */
    public function createAction(): array {
        return ['form' => $this->createCreateForm(new User())->createView()];
    }

    /**
     * Create process action.
     *
     * @Route("/create", name="admin_user_createProcess")
     * @Template("@App/admin/user/create.html.twig")
     * @Method("POST")
     */
    public function createProcessAction(Request $request) {
        $user = new User();

        $form = $this->createCreateForm($user);
        $form->handleRequest($request);

        $message = null;
        if ($form->isValid()) {
            try {
                $this->get('app.service.user')->save($user);
                $this->get('session')->getFlashBag()->add(MessageHelper::TYPE_SUCCESS, 'Používateľ bol uložený.');
                return $this->redirect($this->generateUrl('admin_user_index'));
            } catch (\Exception $e) {
                $message = new MessageHelper(MessageHelper::TYPE_DANGER, 'Používateľa sa nepodarilo uložiť.');
            }
        }

        return [
            'form' => $form->createView(),
            'message' => $message
        ];
    }

    /**
     * Update action.
     *
     * @Route("/{id}/update", name="admin_user_update")
     * @Template("@App/admin/user/update.html.twig")
     * @Method("GET")
     */
    public function updateAction(User $user): array {
        if ($user === null) {
            $this->get('session')->getFlashBag()->add(MessageHelper::TYPE_DANGER, 'Používateľ neexistuje.');
            return $this->redirect($this->generateUrl('admin_user_index'));
        }

        return ['form' => $this->createUpdateForm($user)->createView()];
    }

    /**
     * Update process action.
     *
     * @Route("/{id}/update", name="admin_user_updateProcess")
     * @Template("@App/admin/user/create.html.twig")
     * @Method("POST")
     */
    public function updateProcessAction(User $user, Request $request) {
        $redirect = $this->redirect($this->generateUrl('admin_user_index'));

        if ($user === null) {
            $this->get('session')->getFlashBag()->add(MessageHelper::TYPE_DANGER, 'Používateľ neexistuje.');
            return $redirect;
        }

        $form = $this->createUpdateForm($user);
        $form->handleRequest($request);

        $message = null;
        if ($form->isValid()) {
            try {
                $this->get('app.service.user')->save($user);
                $this->get('session')->getFlashBag()->add(MessageHelper::TYPE_SUCCESS, 'Používateľ bol uložený.');
                return $redirect;
            } catch (\Exception $e) {
                $message = new MessageHelper(MessageHelper::TYPE_DANGER, $e->getMessage());
                //$message = new Message(Message::TYPE_DANGER, 'Používateľa sa nepodarilo uložiť.');
            }
        }

        return [
            'form' => $form->createView(),
            'message' => $message,
        ];
    }

    /**
     * Delete action.
     *
     * @Route("/{id}/delete", name="admin_user_delete")
     * @Method("GET")
     */
    public function deleteAction(User $user): RedirectResponse {
        $flashBag = $this->get('session')->getFlashBag();
        $redirect = $this->redirect($this->generateUrl('admin_user_index'));

        if ($user === null) {
            $flashBag->add(MessageHelper::TYPE_DANGER, 'Používateľa sa nepodarilo zmazať, pretože neexistuje.');
        } else {
            $this->get('app.service.user')->delete($user);
            $flashBag->add(MessageHelper::TYPE_SUCCESS, 'Používateľ bol zmazaný.');
        }

        return $redirect;
    }

    /**
     * Create user create form.
     *
     * @param User $user
     * @return Form
     */
    public function createCreateForm(User $user): Form {
        return $this->createForm(UserType::class, $user, [
            'action' => $this->generateUrl('admin_user_createProcess'),
            'method' => Request::METHOD_POST,
        ]);
    }

    /**
     * Create user update form.
     *
     * @param User $user
     * @return Form
     */
    public function createUpdateForm(User $user): Form {
        return $this->createForm(UserType::class, $user, [
            'action' => $this->generateUrl('admin_user_updateProcess', ['id' => $user->getId()]),
            'method' => Request::METHOD_POST,
        ]);
    }
}
