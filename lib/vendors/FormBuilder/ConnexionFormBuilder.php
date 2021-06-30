<?php

namespace FormBuilder;

use \OCFram\FormBuilder;
use \OCFram\StringField;
use \OCFram\MaxLengthValidator;
use \OCFram\NotNullValidator;
use OCFram\PasswordField;

class ConnexionFormBuilder extends FormBuilder
{
    public function build()
    {
        $this->form->add(new StringField([
            'label' => 'Pseudo',
            'name' => 'pseudo',
            'maxLength' => 100,
            'validators' => [
                new MaxLengthValidator('Le pseudo spécifié est trop long (100 caractères maximum)', 100),
                new NotNullValidator('Merci de spécifier votre pseudo'),
            ],
            'required' => true,
        ]))
        ->add(new PasswordField([
            'label' => 'Password',
            'name' => 'password',
            'maxLength' => 50,
            'validators' => [
                new MaxLengthValidator('Le mot de passe spécifié est trop long (50 caractères maximum)', 50),
                new NotNullValidator('Merci de spécifier un mot de passe'),
            ],
            'required' => true,
            'passwordForget' => true,
        ]));
    }
}
