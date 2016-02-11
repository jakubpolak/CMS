<?php

namespace AppBundle\Form\Admin;

use AppBundle\Entity\Article;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Trsteel\CkeditorBundle\Form\Type\CkeditorType;

/**
 * Article type.
 *
 * @author Jakub Polák
 */
class ArticleType extends AbstractType {
    /**
     * Build form.
     *
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('heading',null, ['label' => 'Nadpis'])
            ->add('content', CkeditorType::class, ['label' => 'Obsah'])
            ->add('isPublished', null, ['label' => 'Publikovať'])
            ->add('writtenOn', null, ['label' => 'Napísaný dňa'])
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
            'data_class' => Article::class
        ]);
    }
}
