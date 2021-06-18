<?php

namespace Model;

use \Entity\Comment;

class CommentManagerPDO extends CommentManager
{
    protected function add(Comment $comment)
    {
        $q = $this->dao->prepare('INSERT INTO comment SET idAuteur = :idAuteur, idArticle = :idArticle, contenu = :contenu, 
                                  dateCreation = NOW(), valid = False');

        $q->bindValue(':idAuteur', $comment->idAuteur(), \PDO::PARAM_INT);
        $q->bindValue(':idArticle', $comment->idArticle());
        $q->bindValue(':contenu', $comment->contenu());

        $q->execute();

        $comment->setId($this->dao->lastInsertId());
    }

    public function getListOf($idArticle)
    {
        if (!ctype_digit($idArticle)) {
            throw new \InvalidArgumentException('L\'identifiant de l\'article passé doit être un nombre entier valide');
        }

        $q = $this->dao->prepare('SELECT * FROM comment WHERE idArticle = :idArticle');
        $q->bindValue(':idArticle', $idArticle, \PDO::PARAM_INT);
        $q->execute();

        $q->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Entity\Comment');

        $comments = $q->fetchAll();

        foreach ($comments as $comment) {
            $comment->setDateCreation(new \DateTime($comment->dateCreation()));
        }

        return $comments;
    }

    protected function modify(Comment $comment)
    {
        $q = $this->dao->prepare('UPDATE comment SET contenu = :contenu WHERE id = :id');

        $q->bindValue(':contenu', $comment->contenu());
        $q->bindValue(':id', $comment->id(), \PDO::PARAM_INT);

        $q->execute();
    }

    public function get($id)
    {
        $q = $this->dao->prepare('SELECT * FROM comment WHERE id = :id');
        $q->bindValue(':id', (int) $id, \PDO::PARAM_INT);
        $q->execute();

        $q->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Entity\Comment');

        if ($comment = $q->fetch()) {
            $comment->setDateCreation(new \DateTime($comment->dateCreation()));
            return $comment;
        }
        return null;
    }

    public function delete($id)
    {
        $this->dao->exec('DELETE FROM comment WHERE id = ' . (int) $id);
    }

    public function deleteFromNews($news)
    {
        $this->dao->exec('DELETE FROM comment WHERE idArticle = ' . (int) $news);
    }
}
