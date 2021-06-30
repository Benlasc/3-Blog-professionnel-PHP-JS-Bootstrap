<?php
namespace Entity;

use \OCFram\Entity;

class Comment extends Entity
{
    protected $idAuteur;
    protected $idArticle;
    protected $idParent;
    protected $contenu;
    protected $dateCreation;
    protected $valid;
    protected $depth;
    protected $auteur;
    protected $children = [];

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

    public function setIdParent(int $idParent)
    {
        $this->idParent = $idParent;
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

    public function setDepth(int $depth)
    {
        if ($depth > 2) {
            throw new \InvalidArgumentException('La profondeur du commentaire doit être inférieure ou égale à 2');
        }
        $this->depth = $depth;
    }

    public function setAuteur(User $auteur)
    {
        $this->auteur = $auteur;
    }

    public function addChildren(Comment $comment)
    {
        $this->children[] = $comment;
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

    public function idParent()
    {
        return $this->idParent;
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

    public function depth()
    {
        return $this->depth;
    }

    public function auteur()
    {
        return $this->auteur;
    }

    public function children()
    {
        return $this->children;
    }
}
