<?php

namespace AppBundle\Form\Admin;

use AppBundle\Entity\Menu;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Trsteel\CkeditorBundle\Form\Type\CkeditorType;

/**
 * Menu type.
 *
 * @author Jakub Polák
 */
class MenuType extends AbstractType {
    /**
     * Build form.
     *
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('name', null, ['label' => 'Názov'])
            ->add('content', CkeditorType::class, ['label' => 'Obsah'])
            ->add('isActive', null, ['label' => 'Aktívny'])
            ->add('position', null, ['label' => 'Pozícia'])
            ->add('menu', EntityType::class, [
                'label' => 'Nadradené menu',
                'class' => 'AppBundle:Menu',
                'query_builder' => function(EntityRepository $er) use ($builder) {
                    return $er->createQueryBuilder('m')
                        ->orderBy('m.position', 'ASC')
                        ->where('m.menu IS NULL')
                    ;
                },
                'required' => false,
                'choice_label' => 'name',
                'placeholder' => 'Žiadne',
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Uložiť',
                'attr' => ['class' => 'btn btn-primary']
            ])
        ;
    }
    
    /**
     * Configure options.
     *
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => Menu::class
        ));
    }
}
