<?php

namespace AppBundle\Form\Admin;

use AppBundle\Entity\Settings;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Settings type.
 * 
 * @author Jakub Polák, Jana Poláková
 */
class SettingsType extends AbstractType {
    /**
     * Build form.
     * 
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        
    }

    /**
     * Configure options.
     * 
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults([
            'data_class' => Settings::class,
        ]);
    }
}
