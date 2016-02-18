<?php

namespace AppBundle\Form\Admin;

use AppBundle\Entity\Slug;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Slug type
 *
 * @author Jakub Polák, Jana Poláková
 */
class SlugType extends AbstractType {
    /**
     * Build form.
     *
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('content', null, ['label' => 'URL'])
            ->add('language', EntityType::class, [
                'label' => 'Jazyk',
                'class' => 'AppBundle:Language',
                'choice_label' => 'code',
                'multiple' => false,
            ])
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
            'data_class' => Slug::class
        ));
    }
}
