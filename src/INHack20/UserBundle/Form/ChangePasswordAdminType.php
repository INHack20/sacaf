<?php

namespace INHack20\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class ChangePasswordAdminType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder->add('PlainPassword', 'repeated', array('type' => 'password'));
       
    }

    public function getName()
    {
        return 'fos_user_change_password';
    }
}
