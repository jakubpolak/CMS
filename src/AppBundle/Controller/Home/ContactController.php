<?php

namespace AppBundle\Controller\Home;

use AppBundle\Form\Home\ContactType;
use AppBundle\Form\Home\Model\Contact;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;

/**
 * Contact controller.
 *
 * @author Jakub Polák, Jana Poláková
 * @Route("/{_locale}/contact", defaults={"_locale" = "%locale%"})
 */
class ContactController extends Controller {
    /**
     * Contact action.
     *
     * @Route("/", name="home_contact_contact")
     * @Template("@App/home/contact/contact.html.twig")
     * @Method("GET")
     */
    public function contactAction() : array {
        return ['form' => $this->createContactForm(new Contact())->createView()];
    }

    /**
     * Contact process action.
     *
     * @Route("/", name="home_contact_contactProcess")
     * @Template("@App/home/contact/contact.html.twig")
     * @Method("POST")
     */
    public function contactProcessAction(Request $request) {
        $contact = new Contact();

        $form = $this->createContactForm($contact);
        $form->handleRequest($request);

        $message = [];
        if ($form->isValid()) {
            try {
                $this->get('app.service.contact')->sendContactForm($contact);
                $this->get('session')->getFlashBag()->add('success', 'Vaša správa bola odoslaná.');

                return $this->redirect($this->generateUrl('home_contact_contact'));
            } catch (\Exception $e) {
                $message = ['type' => 'danger', 'text' => 'Správu sa nepodarilo odoslať.'];
            }
        }

        return [
            'form' => $form->createView(),
            'message' => $message
        ];
    }

    /**
     * Create contact form.
     *
     * @param Contact $contact
     * @return Form
     */
    public function createContactForm(Contact $contact) : Form {
        return $this->createForm(ContactType::class, $contact, [
            'action' => $this->generateUrl('home_contact_contactProcess'),
            'method' => Request::METHOD_POST,
        ]);
    }
}