<?php

namespace Entity;

use \OCFram\Entity;

class Post extends Entity
{
    protected $idAuteur;
    protected $titre;
    protected $image;
    protected $chapo;
    protected $contenu;
    protected $slug;
    protected $dateCreation;
    protected $dateModif;
    protected $auteur;

    const TITRE_INVALIDE = 1;
    const IMAGE_INVALIDE = 2;
    const CHAPO_INVALIDE = 3;
    const CONTENU_INVALIDE = 4;
    const SLUG_INVALIDE = 5;

    public function isValid()
    {
        return !(empty($this->titre) || empty($this->image) || empty($this->chapo) || empty($this->contenu)
                                     || empty($this->slug));
    }

    // SETTERS //

    public function setIdAuteur($idAuteur)
    {
        $this->idAuteur = (int) $idAuteur;
    }

    public function setTitre($titre)
    {
        if (!is_string($titre) || empty($titre)) {
            $this->erreurs[] = self::TITRE_INVALIDE;
        }

        $this->titre = $titre;
    }

    public function setImage($image)
    {
        if (!is_string($image) || empty($image)) {
            $this->erreurs[] = self::IMAGE_INVALIDE;
        }

        $this->image = $image;
    }

    public function setChapo($chapo)
    {
        if (!is_string($chapo) || empty($chapo)) {
            $this->erreurs[] = self::CHAPO_INVALIDE;
        }

        $this->chapo = $chapo;
    }

    public function setContenu($contenu)
    {
        if (!is_string($contenu) || empty($contenu)) {
            $this->erreurs[] = self::CONTENU_INVALIDE;
        }

        $this->contenu = $contenu;
    }

    public function setSlug($slug)
    {
        if (!is_string($slug) || empty($slug)) {
            $this->erreurs[] = self::SLUG_INVALIDE;
        }

        $this->slug = $slug;
    }

    public function setDateCreation(\DateTime $dateCreation)
    {
        $this->dateCreation = $dateCreation;
    }

    public function setDateModif(\DateTime $dateModif)
    {
        $this->dateModif = $dateModif;
    }

    public function setAuteur(string|array $auteur)
    {
        $this->auteur = $auteur;
    }

    // GETTERS //

    public function idAuteur()
    {
        return $this->idAuteur;
    }

    public function titre()
    {
        return $this->titre;
    }

    public function image()
    {
        return $this->image;
    }

    public function chapo()
    {
        return $this->chapo;
    }

    public function contenu()
    {
        return $this->contenu;
    }
    
    public function slug()
    {
        return $this->slug;
    }

    public function dateCreation()
    {
        return $this->dateCreation;
    }

    public function dateModif()
    {
        return $this->dateModif;
    }

    public function auteur()
    {
        return $this->auteur;
    }
}
