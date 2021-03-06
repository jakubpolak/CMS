<?php

namespace AppBundle\Form\Admin;

use AppBundle\Entity\Language;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Language type.
 *
 * @author Jakub Polák, Jana Poláková
 */
class LanguageType extends AbstractType {
    /**
     * Build form.
     *
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('name', null, ['label' => 'Názov'])
            ->add('code', null, ['label' => 'Kód'])
            ->add('isDefault', null, ['label' => 'Východzí'])
            ->add('save', SubmitType::class, ['label' => 'Uložiť', 'attr' => ['class' => 'btn btn-primary']])
        ;
    }
    
    /**
     * Configure options.
     *
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => Language::class
        ));
    }
}
