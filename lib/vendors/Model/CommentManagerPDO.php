<?php

namespace Model;

use \Entity\Comment;

class CommentManagerPDO extends CommentManager
{
    protected function add(Comment $comment)
    {
        $q = $this->dao->prepare('INSERT INTO comment SET idAuteur = :idAuteur, idArticle = :idArticle,
        idParent = :idParent, contenu = :contenu, dateCreation = NOW(), valid = :valid, depth = :depth');

        $q->bindValue(':idAuteur', $comment->idAuteur(), \PDO::PARAM_INT);
        $q->bindValue(':idArticle', $comment->idArticle(), \PDO::PARAM_INT);
        $q->bindValue(':idParent', ($comment->idParent() != 0) ? $comment->idParent() : null);
        $q->bindValue(':contenu', $comment->contenu());
        $q->bindValue(':valid', $comment->auteurAdmin, \PDO::PARAM_BOOL);
        $q->bindValue(':depth', $comment->depth(), \PDO::PARAM_INT);

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

    public function getListOfValid($idArticle)
    {
        if (!ctype_digit($idArticle)) {
            throw new \InvalidArgumentException('L\'identifiant de l\'article passé doit être un nombre entier valide');
        }

        $q = $this->dao->prepare('SELECT * FROM comment WHERE idArticle = :idArticle AND valid = 1');
        $q->bindValue(':idArticle', $idArticle, \PDO::PARAM_INT);
        $q->execute();

        $q->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Entity\Comment');

        $comments = $q->fetchAll();

        foreach ($comments as $comment) {
            $comment->setDateCreation(new \DateTime($comment->dateCreation()));
        }

        return $comments;
    }

    public function getListOfInvalid()
    {

        $q = $this->dao->query('SELECT * FROM comment WHERE valid = 0');
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

    public function getIdArticle($id)
    {
        $req = $this->dao->prepare('SELECT idArticle FROM comment WHERE id = ?');
        $req->execute([$id]);
        return $req->fetchColumn();
    }
    
    public function commentExist($id)
    {
        $q = $this->dao->prepare('SELECT id FROM comment WHERE id = :id');
        $q->bindValue(':id', (int) $id, \PDO::PARAM_INT);
        $q->execute();

        $q->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Entity\Comment');

        return $q->fetch();
    }

    public function getIdChildren($id): array
    {
        $idChildren = [];
        $req =  $this->dao->prepare('SELECT id FROM comment WHERE idParent = ?');
        $req->execute([$id]);
        while ($child = $req->fetch()) {
            $idChildren[] = $child[0];
        }
        return $idChildren;
    }

    public function delete($id)
    {
        $this->dao->exec('DELETE FROM comment WHERE id = ' . (int) $id);
    }

    public function deleteWithChildren($id, ?int $idArticle = null)
    {
        if (!isset($idArticle)) {
            $idArticle = $this->getIdArticle($id);
        }

        $idChildren=$this->getIdChildren($id);

        foreach ($idChildren as $idChild) {
            $this->deleteWithChildren($idChild, $idArticle);
        }

        $req = $this->dao->prepare('DELETE FROM comment WHERE idParent = :idParent AND idArticle = :idArticle');

        $req->execute(array(
            'idParent' => $id,
            'idArticle' => $idArticle
            ));

        $this->delete($id);
    }

    public function deleteFromNews($news)
    {
        $this->dao->exec('DELETE FROM comment WHERE idArticle = ' . (int) $news);
    }

    public function countInvalidComments()
    {
        return $this->dao->query('SELECT COUNT(*) FROM comment WHERE valid = 0')->fetchColumn();
    }

    public function validComment(int $id)
    {
        $req = $this->dao->prepare('UPDATE comment SET valid = true WHERE id = :id');
        $req->execute(array(
            'id' => $id
            ));
    }
}
