<?php

namespace AppBundle\Form\Admin;

use AppBundle\Entity\StaticPage;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Trsteel\CkeditorBundle\Form\Type\CkeditorType;

/**
 * Static page type.
 *
 * @author Jana Poláková
 */
class StaticPageType extends AbstractType {
    /**
     * Build form.
     *
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('meta', MetaType::class, ['label' => false])
            ->add('heading',null, ['label' => 'Nadpis'])
            ->add('content', CkeditorType::class, ['label' => 'Obsah'])
            ->add('isActive', null, ['label' => 'Aktívna'])
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
            'data_class' => StaticPage::class
        ]);
    }
}
