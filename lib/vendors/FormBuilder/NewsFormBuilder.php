<?php

namespace FormBuilder;

use \OCFram\FormBuilder;
use \OCFram\StringField;
use \OCFram\TextField;
use \OCFram\MaxLengthValidator;
use \OCFram\NotNullValidator;

class NewsFormBuilder extends FormBuilder
{
    public function build()
    {
        $this->form->add(new StringField([
            'label' => 'Titre',
            'name' => 'titre',
            'maxLength' => 100,
            'validators' => [
                new MaxLengthValidator('Le titre spécifié est trop long (100 caractères maximum)', 100),
                new NotNullValidator('Merci de spécifier le titre de la news'),
            ],
        ]))
        ->add(new StringField([
            'label' => 'Chapô',
            'name' => 'chapo',
            'maxLength' => 100,
            'validators' => [
                new MaxLengthValidator('Le chapô spécifié est trop long (100 caractères maximum)', 100),
                new NotNullValidator('Merci de spécifier le chapô de la news'),
            ],
        ]))
            ->add(new StringField([
                'label' => 'Image',
                'name' => 'image',
                'maxLength' => 100,
                'validators' => [
                    new MaxLengthValidator('L\'image spécifiée est trop longue (100 caractères maximum)', 100),
                    new NotNullValidator('Merci de spécifier l\'image de la news'),
                ],
            ]))
            ->add(new TextField([
                'label' => 'Contenu',
                'name' => 'contenu',
                'rows' => 8,
                'cols' => 60,
                'validators' => [
                    new NotNullValidator('Merci de spécifier le contenu de la news'),
                ],
            ]))
            ->add(new StringField([
                'label' => 'Slug',
                'name' => 'slug',
                'maxLength' => 100,
                'validators' => [
                    new MaxLengthValidator('Le slug spécifié est trop long (100 caractères maximum)', 100),
                    new NotNullValidator('Merci de spécifier le slug de la news'),
                ],
            ]));
    }
}
