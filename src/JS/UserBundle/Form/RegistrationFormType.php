<?php

namespace JS\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options) {
        //$builder
            //->remove('plainPassword')
            //->add('password', PasswordType::class, array('label' => 'form.password', 'translation_domain' => 'FOSUserBundle'))
        //;
    }
    
    public function getParent() {
        return 'FOS\UserBundle\Form\Type\RegistrationFormType';
    }
    
    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'js_user_registration';
    }


}
