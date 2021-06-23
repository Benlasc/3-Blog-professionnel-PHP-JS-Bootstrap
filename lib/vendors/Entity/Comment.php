<?php
namespace Entity;

use \OCFram\Entity;

class Comment extends Entity
{
    protected $idAuteur;
    protected $idArticle;
    protected $contenu;
    protected $dateCreation;
    protected $valid;
    protected $auteur;

    const CONTENU_INVALIDE = 1;

    public function isValid()
    {
        return !(empty($this->contenu));
    }

    // SETTERS //

    public function setIdAuteur(int $idAuteur)
    {
        $this->idAuteur = $idAuteur;
    }

    public function setIdArticle(int $idArticle)
    {
        $this->idArticle = $idArticle;
    }

    public function setContenu($contenu)
    {
        if (!is_string($contenu) || empty($contenu)) {
            $this->erreurs[] = self::CONTENU_INVALIDE;
        }

        $this->contenu = $contenu;
    }

    public function setDateCreation(\DateTime $dateCreation)
    {
        $this->dateCreation = $dateCreation;
    }

    public function setValid(bool $valid)
    {
        $this->valid = $valid;
    }

    public function setAuteur(User $auteur)
    {
        $this->auteur = $auteur;
    }
    // GETTERS //

    public function idAuteur()
    {
        return $this->idAuteur;
    }

    public function idArticle()
    {
        return $this->idArticle;
    }

    public function contenu()
    {
        return $this->contenu;
    }

    public function dateCreation()
    {
        return $this->dateCreation;
    }
  
    public function valid()
    {
        return $this->valid;
    }

    public function auteur()
    {
        return $this->auteur;
    }
}
