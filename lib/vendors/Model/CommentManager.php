<?php

namespace Model;

use \OCFram\Manager;
use \Entity\Comment;

abstract class CommentManager extends Manager
{
    /**
     * Méthode permettant d'ajouter un commentaire
     * @param $comment Le commentaire à ajouter
     * @return void
     */
    abstract protected function add(Comment $comment);

    /**
     * Méthode permettant d'enregistrer un commentaire.
     * @param $comment Le commentaire à enregistrer
     * @return void
     */
    public function save(Comment $comment)
    {
        if ($comment->isValid()) {
            $comment->isNew() ? $this->add($comment) : $this->modify($comment);
        } else {
            throw new \RuntimeException('Le commentaire doit être validé pour être enregistré');
        }
    }

    /**
     * Méthode permettant de récupérer une liste de commentaires.
     * @param $news L'article sur lequel on veut récupérer les commentaires
     * @return array
     */
    abstract public function getListOf($news);

    /**
     * Méthode permettant de modifier un commentaire.
     * @param $comment Le commentaire à modifier
     * @return void
     */
    abstract protected function modify(Comment $comment);

    /**
     * Méthode permettant d'obtenir un commentaire spécifique.
     * @param $id L'identifiant du commentaire
     * @return Comment
     */
    abstract public function get($id);

    /**
     * Méthode permettant de supprimer un commentaire.
     * @param $id L'identifiant du commentaire à supprimer
     * @return void
     */
    abstract public function delete($id);

    /**
     * Méthode permettant de supprimer tous les commentaires liés à un article
     * @param $news L'identifiant de l'article dont les commentaires doivent être supprimés
     * @return void
     */
    abstract public function deleteFromNews($news);
}
