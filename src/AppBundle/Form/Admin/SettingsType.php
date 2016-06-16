<?php

namespace AppBundle\Form\Admin;

use AppBundle\Entity\Settings;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
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
        $builder
            ->add('isAdvancedMenuEnabled', null, ['label' => 'Povoliť pokročilé menu'])
            ->add('isTranslationsEnabled', null, ['label' => 'Povoliť preklady'])
            ->add('isLanguagesEnabled', null, ['label' => 'Povoliť jazyky'])
            ->add('isSlugsEnabled', null, ['label' => 'Povoliť slugy'])
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
            'data_class' => Settings::class,
        ]);
    }
}
