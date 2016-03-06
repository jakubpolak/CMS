<?php

namespace AppBundle\Form\Admin;

use AppBundle\Entity\Meta;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Meta type.
 *
 * @author Jakub Polák, Jana Poláková
 */
class MetaType extends AbstractType {
    /**
     * Build form.
     *
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('meta_keywords', null, ['label' => 'Meta kľúčové slová'])
            ->add('meta_description', TextareaType::class, ['label' => 'Meta popis'])
        ;
    }

    /**
     * Configure options.
     *
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => Meta::class
        ));
    }
}
