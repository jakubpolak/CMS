<?php

namespace AppBundle\Form\Admin;

use AppBundle\Entity\Role;
use AppBundle\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * User type
 *
 * @author Jakub Polák, Jana Poláková
 */
class UserType extends AbstractType {
    /**
     * Build form.
     *
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('username', null, ['label' => 'Email'])
            ->add('password', PasswordType::class, ['label' => 'Heslo'])
            ->add('roles', EntityType::class, [
                'label' => 'Rola',
                'class' => 'AppBundle:Role',
                'choice_label' => 'name',
                'multiple' => true,
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
            'data_class' => User::class
        ));
    }
}
