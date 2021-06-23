<?php

namespace FormBuilder;

use \OCFram\FormBuilder;
use \OCFram\StringField;
use \OCFram\TextField;
use \OCFram\MaxLengthValidator;
use \OCFram\NotNullValidator;
use OCFram\ImageValidator;
use OCFram\SelectField;
use OCFram\BrowserField;

class NewsFormBuilder extends FormBuilder
{
    public function build()
    {
        $this->form->add(new SelectField([
            'label' => 'Auteur',
            'name' => 'auteur',
        ]))
        ->add(new StringField([
            'label' => 'Titre',
            'name' => 'titre',
            'maxLength' => 100,
            'validators' => [
                new MaxLengthValidator('Le titre spécifié est trop long (100 caractères maximum)', 100),
                new NotNullValidator('Merci de spécifier le titre de l\'article'),
            ],
        ]))
        ->add(new StringField([
            'label' => 'Chapô',
            'name' => 'chapo',
            'maxLength' => 100,
            'validators' => [
                new MaxLengthValidator('Le chapô spécifié est trop long (100 caractères maximum)', 100),
                new NotNullValidator('Merci de spécifier le chapô de l\'article'),
            ],
        ]))
            ->add(new BrowserField([
                'label' => 'Image',
                'name' => 'image',
                'validators' => [
                    new NotNullValidator('Merci de spécifier l\'image de l\'article'),
                    new ImageValidator('Le fichier téléchargé  n\'est pas une image.', ['image/jpeg','image/png'])
                ],
            ]))
            ->add(new TextField([
                'label' => 'Contenu',
                'name' => 'contenu',
                'heightBootstrap' => 200,
                'validators' => [
                    new NotNullValidator('Merci de spécifier le contenu de l\'article'),
                ],
            ]))
            ->add(new StringField([
                'label' => 'Slug',
                'name' => 'slug',
                'maxLength' => 100,
                'validators' => [
                    new MaxLengthValidator('Le slug spécifié est trop long (100 caractères maximum)', 100),
                    new NotNullValidator('Merci de spécifier le slug de l\'article'),
                ],
            ]));
    }
}
