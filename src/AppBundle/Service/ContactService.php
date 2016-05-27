<?php

namespace AppBundle\Service;

use AppBundle\Form\Home\Model\Contact;
use Doctrine\ORM\EntityManager;

/**
 * Contact service.
 *
 * @author Jakub Polák, Jana Poláková
 */
class ContactService {
    /**
     * @var string
     */
    private $contactAddress;

    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    /**
     * @var \Twig_Environment
     */
    private $twig;

    /**
     * Constructor.
     * 
     * ContactService constructor.
     * @param EntityManager $entityManager
     * @param string $contactAddress
     * @param \Swift_Mailer $mailer
     * @param \Twig_Environment $twig
     */
    public function __construct(
        EntityManager $entityManager,
        string $contactAddress,
        \Swift_Mailer $mailer,
        \Twig_Environment $twig
    ) {
        $this->em = $entityManager;
        $this->contactAddress = $contactAddress;
        $this->mailer = $mailer;
        $this->twig = $twig;
    }

    /**
     * Send contact form.
     * 
     * @param Contact $contact
     * @return int
     */
    public function sendContactForm(Contact $contact) {
        $body = $this->twig->render('AppBundle:home/contact:message.html.twig', ['contact' => $contact]);
        
        $message = \Swift_Message::newInstance();
        $message->setFrom($contact->getEmail())
            ->setTo($this->contactAddress)
            ->setCc($contact->getEmail())
            ->setSubject('Nová správa z kontaktného formuláru')
            ->setBody($body)
            ->setContentType('text/html')
        ;

        return $this->mailer->send($message);
    }
}