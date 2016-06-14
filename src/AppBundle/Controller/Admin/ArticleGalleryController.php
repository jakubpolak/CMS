<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Article;
use AppBundle\Entity\Image;
use AppBundle\Entity\ImageType;
use AppBundle\Form\Admin\ImageType as ImageTypeForm;
use AppBundle\Helper\MessageHelper;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;

/**
 * Article gallery controller.
 *
 * @author Jakub Polák, Jana Poláková
 * @Route("/{_locale}/admin/article/gallery", defaults={"_locale" = "%locale%"})
 */
class ArticleGalleryController extends Controller {
    /**
     * Create action.
     *
     * @Route("/{articleId}", name="admin_articleGallery_create")
     * @Template("@App/admin/article-gallery/create.html.twig")
     * @Method("GET")
     */
    public function createAction(int $articleId) {
        $image = $this->get('app.service.image')->getInstance();
        $form = $this->createCreateForm($image, $articleId);

        return [
            'form' => $form->createView(),
            'articleId' => $articleId,
        ];
    }

    /**
     * Create process action.
     *
     * @Route("/{articleId}/create", name="admin_articleGallery_createProcess")
     * @ParamConverter("article", options={"mapping": {"articleId": "id"}})
     * @Template("@App/admin/article-gallery/create.html.twig")
     * @Method("POST")
     */
    public function createProcessAction(Request $request, Article $article) {
        $articleId = $article->getId();

        $imageService = $this->get('app.service.image');

        $image = $imageService->getInstance();
        $image->setArticle($article);

        $form = $this->createCreateForm($image, $articleId);
        $form->handleRequest($request);

        $message = null;
        if ($form->isValid()) {
            try {
                $imageService->save($image, ImageType::GALLERY);
                $this->get('session')->getFlashBag()->add(MessageHelper::TYPE_SUCCESS, 'Obrázok bol uložený.');
                return $this->redirect($this->generateUrl('admin_article_update', ['id' => $articleId]));
            } catch (\Exception $e) {
                $message = new MessageHelper(MessageHelper::TYPE_DANGER, 'Obrázok sa nepodarilo uložiť.');
            }
        }

        return [
            'form' => $form->createView(),
            'articleId' => $articleId,
            'message' => $message,
        ];
    }

    /**
     * Update action.
     *
     * @Route("/{articleId}/{imageId}/update", name="admin_articleGallery_update")
     * @ParamConverter("image", options={"mapping": {"imageId": "id"}})
     * @Template("@App/admin/article-gallery/update.html.twig")
     * @Method("GET")
     */
    public function updateAction(Image $image, int $articleId) {
        if ($image === null) {
            $this->get('session')->getFlashBag()->add(MessageHelper::TYPE_DANGER, 'Obrázok neexistuje.');
            return $this->redirect($this->generateUrl('admin_article_update', ['id' => $articleId]));
        }

        return [
            'form' => $this->createUpdateForm($image, $articleId)->createView(),
            'articleId' => $articleId,
        ];
    }

    /**
     * Update process action.
     *
     * @Route("/{articleId}/{imageId}/update", name="admin_articleGallery_updateProcess")
     * @ParamConverter("image", options={"mapping": {"imageId": "id"}})
     * @Template("@App/admin/article-gallery/update.html.twig")
     * @Method("POST")
     */
    public function updateProcessAction(Request $request, Image $image, int $articleId) {
        $flashBag = $this->get('session')->getFlashBag();
        $redirect = $this->redirect($this->generateUrl('admin_article_update', ['id' => $articleId]));

        if ($image === null) {
            $flashBag->add(MessageHelper::TYPE_DANGER, 'Obrázok neexistuje.');
            return $redirect;
        }

        $form = $this->createUpdateForm($image, $articleId);
        $form->handleRequest($request);

        $message = null;
        if ($form->isValid()) {
            try {
                $this->get('app.service.image')->save($image);
                $flashBag->add(MessageHelper::TYPE_SUCCESS, 'Obrázok bol uložený.');
                return $redirect;
            } catch (\Exception $e) {
                $message = new MessageHelper(MessageHelper::TYPE_DANGER, 'Obrázok sa nepodarilo uložiť.' . $e->getMessage());
            }
        }

        return [
            'form' => $form->createView(),
            'articleId' => $articleId,
            'message' => $message,
        ];
    }

    /**
     * Delete action.
     *
     * @Route("/{articleId}/{imageId}/delete", name="admin_articleGallery_delete")
     * @ParamConverter("image", options={"mapping": {"imageId": "id"}})
     * @Method("GET")
     */
    public function deleteAction(Image $image, int $articleId) {
        $redirect = $this->redirect($this->generateUrl('admin_article_update', ['id' => $articleId]));
        $flashBag = $this->get('session')->getFlashBag();

        if ($image === null) {
            $flashBag->add(MessageHelper::TYPE_DANGER, 'Obrázok neexistuje.');
            return $redirect;
        }

        $imageService = $this->get('app.service.image');
        try {
            $imageService->delete($image);
            $flashBag->add(MessageHelper::TYPE_SUCCESS, 'Obrázok bol zmazaný.');
        } catch (\Exception $e) {
            $flashBag->add(MessageHelper::TYPE_DANGER, 'Obrázok sa nepodarilo zmazať.');
        }

        return $redirect;
    }

    /**
     * Create image create form.
     *
     * @param Image $image image
     * @param int $articleId article id
     * @return Form
     */
    private function createCreateForm(Image $image, int $articleId): Form {
        return $this->createForm(ImageTypeForm::class, $image, [
            'action' => $this->generateUrl('admin_articleGallery_createProcess', ['articleId' => $articleId]),
            'method' => Request::METHOD_POST
        ]);
    }

    /**
     * Create image update form.
     *
     * @param Image $image image
     * @param int $articleId article id
     * @return Form
     */
    private function createUpdateForm(Image $image, int $articleId): Form {
        return $this->createForm(ImageTypeForm::class, $image, [
            'action' => $this->generateUrl('admin_articleGallery_updateProcess', ['articleId' => $articleId, 'imageId' => $image->getId()]),
            'method' => Request::METHOD_POST
        ]);
    }
}
