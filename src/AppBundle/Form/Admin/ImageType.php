<?php

namespace AppBundle\Form\Admin;

use AppBundle\Entity\Image;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Image type.
 *
 * @author Jakub Polák, Jana Poláková
 */
class ImageType extends AbstractType {
    /**
     * Build form.
     *
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('file', FileType::class, ['label' => 'Obrázok', 'required' => true])
            ->add('position', null, ['label' => 'Pozícia'])
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
            'data_class' => Image::class
        ));
    }
}
