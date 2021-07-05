<?php

namespace Model;

use Entity\Post;
use OCFram\PaginatedQuery;
use Pagerfanta\Pagerfanta;

class PostManagerPDO extends PostManager
{
    public function getList($debut = -1, $limite = -1)
    {
        $sql = 'SELECT * FROM post ORDER BY id DESC';

        if ($debut != -1 || $limite != -1) {
            $sql .= ' LIMIT ' . (int) $limite . ' OFFSET ' . (int) $debut;
        }

        $requete = $this->dao->query($sql);
        $requete->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Entity\Post');

        $listePosts = $requete->fetchAll();

        foreach ($listePosts as $post) {
            $post->setDateCreation(new \DateTime($post->dateCreation()));
            $post->setDateModif(new \DateTime($post->dateModif()));
        }

        $requete->closeCursor();

        return $listePosts;
    }

    public function getUnique($id)
    {
        $requete = $this->dao->prepare('SELECT * FROM post WHERE id = :id');
        $requete->bindValue(':id', (int) $id, \PDO::PARAM_INT);
        $requete->execute();

        $requete->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Entity\Post');

        if ($post = $requete->fetch()) {
            $post->setDateCreation(new \DateTime($post->dateCreation()));
            $post->setDateModif(new \DateTime($post->dateModif()));

            return $post;
        }

        return null;
    }

    public function count()
    {
        return $this->dao->query('SELECT COUNT(*) FROM post')->fetchColumn();
    }

    protected function add(Post $post)
    {
        $requete = $this->dao->prepare('INSERT INTO post SET idAuteur = :idAuteur, titre = :titre, image = :image, 
                                       chapo = :chapo, contenu = :contenu, slug = :slug, dateCreation = NOW(), 
                                       dateModif = NOW()');

        $requete->bindValue(':idAuteur', $post->idAuteur());
        $requete->bindValue(':titre', $post->titre());
        $requete->bindValue(':image', $post->image());
        $requete->bindValue(':chapo', $post->chapo());
        $requete->bindValue(':contenu', $post->contenu());
        $requete->bindValue(':slug', $post->slug());

        $requete->execute();
    }

    protected function modify(Post $post)
    {
        $requete = $this->dao->prepare('UPDATE post SET idAuteur = :idAuteur, titre = :titre, image = :image,
         chapo = :chapo, contenu = :contenu, slug = :slug, dateModif = NOW() WHERE id = :id');

        $requete->bindValue(':idAuteur', ($post->idAuteur()!=0) ? $post->idAuteur() : null, \PDO::PARAM_INT);
        $requete->bindValue(':titre', $post->titre());
        $requete->bindValue(':image', $post->image());
        $requete->bindValue(':chapo', $post->chapo());
        $requete->bindValue(':contenu', $post->contenu());
        $requete->bindValue(':slug', $post->slug());
        $requete->bindValue(':id', $post->id(), \PDO::PARAM_INT);

        $requete->execute();
    }

    public function delete($id)
    {
        $this->dao->exec('DELETE FROM post WHERE id = ' . (int) $id);
    }

    public function findPaginated(int $perPage, int $currentPage): Pagerfanta
    {
        $query = new PaginatedQuery(
            $this->dao,
            'SELECT * FROM post ORDER BY dateModif DESC',
            'SELECT COUNT(id) FROM post',
            Post::class
        );
        return (new Pagerfanta($query))
            ->setMaxPerPage($perPage)
            ->setCurrentPage($currentPage);
    }

    public function maxId(): int
    {
        return (int) $this->dao->query('SELECT MAX(id) FROM post')->fetchColumn();
    }
}
