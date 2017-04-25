<?php

namespace UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
         $builder
            ->add('username')
            ->add('firstname')
            ->add('lastname')
            ->add('email', 'email')
            ->add('password','password')
            ->add('role','choice',array('choice' => array('ROLE_ADMIN'=>'Administrador', 'ROLE_USER'=>'Usuario'),'placeholder'=>'Seleccione Rol'))
            ->add('isActive','checkbox')
            ->add('save','submit',array('label' => 'Save'))
            // ->add('createdAt')
            // ->add('updatedAt')
        ;

    }
	
	//corresponde al nombre del formulario que va tomar
	public function getName()
    {
        return 'Usuario';
    }

    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'UserBundle\Entity\User'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'userbundle_user';
    }
}
