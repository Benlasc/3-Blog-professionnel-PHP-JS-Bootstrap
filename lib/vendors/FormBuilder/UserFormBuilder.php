<?php

namespace FormBuilder;

use OCFram\BrowserField;
use \OCFram\FormBuilder;
use OCFram\ImageValidator;
use \OCFram\StringField;
use \OCFram\MaxLengthValidator;
use \OCFram\NotNullValidator;
use OCFram\MailField;
use OCFram\MinLengthValidator;
use OCFram\PasswordField;

class UserFormBuilder extends FormBuilder
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
        ->add(new StringField([
            'label' => 'Nom',
            'name' => 'nom',
            'maxLength' => 50,
            'validators' => [
                new MaxLengthValidator('Le nom spécifié est trop long (50 caractères maximum)', 50),
                new NotNullValidator('Merci de spécifier votre nom'),
            ],
            'required' => true,
        ]))
        ->add(new StringField([
            'label' => 'Prénom',
            'name' => 'prenom',
            'maxLength' => 50,
            'validators' => [
                new MaxLengthValidator('Le prénom spécifié est trop long (50 caractères maximum)', 50),
                new NotNullValidator('Merci de spécifier votre prénom'),
            ],
            'required' => true,
        ]))
        ->add(new BrowserField([
            'label' => 'Avatar',
            'name' => 'avatar',
            'validators' => [
                new ImageValidator('Le fichier téléchargé  n\'est pas une image.', ['image/jpeg','image/png'])
            ],
        ]))
        ->add(new MailField([
            'label' => 'Mail',
            'name' => 'mail',
            'maxLength' => 50,
            'validators' => [
                new MaxLengthValidator('Le mail spécifié est trop long (50 caractères maximum)', 50),
                new NotNullValidator('Merci de spécifier votre adresse mail'),
            ],
            'required' => true,
        ]))
        ->add(new PasswordField([
            'label' => 'Password (5 caractères minimum)',
            'name' => 'password',
            'minLength' => 5,
            'maxLength' => 50,
            'validators' => [
                new MinLengthValidator('Le mot de passe spécifié est trop court (5 caractères minimum)', 5),
                new MaxLengthValidator('Le mot de passe spécifié est trop long (50 caractères maximum)', 50),
                new NotNullValidator('Merci de spécifier un mot de passe'),
            ],
            'required' => true,
        ]))
        ->add(new PasswordField([
            'label' => 'Confirmez votre password',
            'name' => 'passwordCheck',
            'validators' => [
                new NotNullValidator('Merci de respécifier votre mot de passe'),
            ],
            'required' => true,
        ]));
    }
}
