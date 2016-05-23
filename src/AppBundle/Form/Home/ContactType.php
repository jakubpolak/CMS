<?php

namespace AppBundle\Form\Home;

use AppBundle\Form\Home\Model\Contact;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Trsteel\CkeditorBundle\Form\Type\CkeditorType;

/**
 * Contact type
 *
 * @author Jakub Polák, Jana Poláková
 */
class ContactType extends AbstractType {
    /**
     * Build form.
     *
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('name',null, ['label' => 'Meno a priezvisko'])
            ->add('phone',null, ['label' => 'Telefónne číslo'])
            ->add('email', null, ['label' => 'Email'])
            ->add('message', CkeditorType::class, ['label' => 'Správa'])
            ->add('save', SubmitType::class, ['label' => 'Uložiť', 'attr' => ['class' => 'btn btn-primary']])
        ;
    }

    /**
     * Configure options.
     *
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults([
            'data_class' => Contact::class
        ]);
    }
}
