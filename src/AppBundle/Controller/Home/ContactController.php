<?php

namespace AppBundle\Controller\Home;

use AppBundle\Form\Home\ContactType;
use AppBundle\Form\Home\Model\Contact;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

/**
 * Contact controller.
 *
 * @author Jakub Polák, Jana Poláková
 *
 * @Route("/contact")
 */
class ContactController extends Controller {
    /**
     * Contact action.
     *
     * @Route("/", name="home_contact_contact")
     * @Template("@App/home/contact/contact.html.twig")
     * @Method("GET")
     */
    public function contactAction() {
        return ['form' => $this->createContactForm(new Contact())->createView()];
    }

    /**
     * Contact process action.
     *
     * @Route("/", name="home_contact_contactProcess")
     * @Template("@App/home/contact/contact.html.twig")
     * @Method("GET")
     */
    public function contactProcessAction(Request $request) {
        $contact = new Contact();

        $form = $this->createContactForm($contact);
        $form->handleRequest($request);

        $message = [];
        if ($form->isValid()){
            try {
                $this->get('app.service.contact')->sendContactForm($contact);
                $this->get('session')->getFlashBag()->add('success', 'Vaša správa bola odoslaná.');

                return $this->redirect($this->generateUrl('home_contact_contact'));
            } catch (Exception $e){
                $message = ['type' => 'danger', 'message' => 'Správu sa nepodarilo odoslať.'];
            }
        }

        return ['form' => $form->createView(), 'message' => $message];
    }


    public function createContactForm(Contact $contact) {
        return $this->createForm(ContactType::class, $contact, [
            'action' => $this->generateUrl('home_contact_contactProcess'),
            'method' => 'POST',
        ]);
    }
}