<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Image;
use AppBundle\Entity\ImageType;
use AppBundle\Form\Admin\SliderImageType;
use AppBundle\Helper\MessageHelper;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Slider gallery controller.
 *
 * @author Jakub Polák, Jana Poláková
 * @Route("/{_locale}/admin/slider-gallery", defaults={"_locale" = "%locale%"})
 */
class SliderGalleryController extends Controller {
    /**
     * Index action.
     * 
     * @Route("", name="admin_sliderGallery_index")
     * @Template("@App/admin/slider-gallery/index.html.twig")
     * @Method("GET")
     */
    public function indexAction() : array {
        $images = $this->get('app.service.image')->getAllToSliderOrderByPosition();

        return ['images' => $images];
    }

    /**
     * Create action.
     * 
     * @Route("/create", name="admin_sliderGallery_create")
     * @Template("@App/admin/slider-gallery/create.html.twig")
     * @Method("GET")
     */
    public function createAction() : array {
        $image = $this->get('app.service.image')->getInstance();
        $form = $this->createCreateForm($image);
        
        return ['form' => $form->createView()];
    }

    /**
     * Create process action.
     *
     * @Route("/create", name="admin_sliderGallery_createProcess")
     * @Template("@App/admin/slider-gallery/create.html.twig")
     * @Method("POST")
     */
    public function createProcessAction(Request $request) {
        $imageService = $this->get('app.service.image');
        $image = $imageService->getInstance();

        $form = $this->createCreateForm($image);
        $form->handleRequest($request);

        $message = null;
        if ($form->isValid()) {
            try {
                $imageService->save($image, ImageType::SLIDER);
                $this->get('session')->getFlashBag()->add(MessageHelper::TYPE_SUCCESS, 'Obrázok bol uložený.');
                return $this->redirect($this->generateUrl('admin_sliderGallery_index'));
            } catch (\Exception $e) {
                $message = new MessageHelper(MessageHelper::TYPE_DANGER, $e->getMessage() . 'Obrázok sa nepodarilo uložiť.');
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
     * @Route("/{imageId}/update", name="admin_sliderGallery_update")
     * @ParamConverter("image", options={"mapping": {"imageId": "id"}})
     * @Template("@App/admin/slider-gallery/update.html.twig")
     * @Method("GET")
     */
    public function updateAction(Image $image) {
        if ($image === null) {
            $this->get('session')->getFlashBag()->add(MessageHelper::TYPE_DANGER, 'Obrázok neexistuje.');
            return $this->redirect($this->generateUrl('admin_sliderGallery_index'));
        }

        return ['form' => $this->createUpdateForm($image)->createView()];
    }

    /**
     * Update process action.
     *
     * @Route("/{imageId}/update", name="admin_sliderGallery_updateProcess")
     * @ParamConverter("image", options={"mapping": {"imageId": "id"}})
     * @Template("@App/admin/slider-gallery/update.html.twig")
     * @Method("POST")
     */
    public function updateProcessAction(Request $request, Image $image) {
        $redirect = $this->redirect($this->generateUrl('admin_sliderGallery_index'));
        $flashBag = $this->get('session')->getFlashBag();
        
        if ($image === null) {
            $flashBag->add(MessageHelper::TYPE_DANGER, 'Obrázok neexistuje.');
            return $redirect;
        }

        $form = $this->createUpdateForm($image);
        $form->handleRequest($request);

        $message = null;
        if ($form->isValid()) {
            try {
                $this->get('app.service.image')->save($image);
                $flashBag->add(MessageHelper::TYPE_SUCCESS, 'Obrázok bol uložený.');
                return $redirect;
            } catch (\Exception $e) {
                $message = new MessageHelper(MessageHelper::TYPE_DANGER, 'Obrázok sa nepodarilo uložiť.');
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
     * @Route("/{imageId}/delete", name="admin_sliderGallery_delete")
     * @ParamConverter("image", options={"mapping": {"imageId": "id"}})
     * @Method("GET")
     */
    public function deleteAction(Image $image) : RedirectResponse {
        $flashBag = $this->get('session')->getFlashBag();
        $redirect = $this->redirect($this->generateUrl('admin_sliderGallery_index'));

        if ($image === null) {
            $flashBag->add(MessageHelper::TYPE_DANGER, 'Obrázok neexistuje.');
            return $redirect;
        }

        try {
            $this->get('app.service.image')->delete($image);
            $flashBag->add(MessageHelper::TYPE_SUCCESS, 'Obrázok bol zmazaný.');
        } catch (\Exception $e) {
            $flashBag->add(MessageHelper::TYPE_DANGER, 'Obrázok sa nepodarilo zmazať.');
        }

        return $redirect;
    }
    
    /**
     * Create create form.
     * 
     * @param Image $image
     * @return Form
     */
    private function createCreateForm(Image $image) : Form {
        return $this->createForm(SliderImageType::class, $image, [
            'action' => $this->generateUrl('admin_sliderGallery_createProcess'),
            'method' => Request::METHOD_POST
        ]);
    }

    /**
     * Create update form.
     * 
     * @param Image $image
     * @return Form
     */
    private function createUpdateForm(Image $image) : Form {
        return $this->createForm(SliderImageType::class, $image, [
            'action' => $this->generateUrl('admin_sliderGallery_updateProcess', ['imageId' => $image->getId()]),
            'method' => Request::METHOD_POST
        ]);
    }
}
