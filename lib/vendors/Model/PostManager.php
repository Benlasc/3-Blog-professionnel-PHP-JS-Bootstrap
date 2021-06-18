<?php

namespace Model;

use Entity\Post;
use \OCFram\Manager;
use Pagerfanta\Pagerfanta;

abstract class PostManager extends Manager
{
    /**
     * Méthode retournant une liste de posts demandée.
     * @param $debut int Le premier post à sélectionner
     * @param $limite int Le nombre de posts à sélectionner
     * @return array La liste des posts. Chaque entrée est une instance de Post.
     */
    abstract public function getList($debut = -1, $limite = -1);

    /**
     * Méthode retournant un post précis.
     * @param $id int L'identifiant du post à récupérer
     * @return Post Le post demandée
     */
    abstract public function getUnique($id);

    /**
     * Méthode renvoyant le nombre de posts total.
     * @return int
     */
    abstract public function count();

    /**
     * Méthode permettant d'ajouter un post.
     * @param Post $post Le post à ajouter
     * @return void
     */
    abstract protected function add(Post $post);

    /**
     * Méthode permettant d'enregistrer une post.
     * @param Post $post le post à enregistrer
     * @see self::add()
     * @see self::modify()
     * @return void
     */
    public function save(Post $post)
    {
        if ($post->isValid()) {
            $post->isNew() ? $this->add($post) : $this->modify($post);
        } else {
            throw new \RuntimeException('L\'article doit être validé pour être enregistré');
        }
    }

    /**
     * Méthode permettant de modifier un post.
     * @param $post le post à modifier
     * @return void
     */
    abstract protected function modify(Post $post);

    /**
     * Méthode permettant de supprimer un post.
     * @param $id int L'identifiant du post à supprimer
     * @return void
     */
    abstract public function delete($id);

    abstract public function findPaginated(int $perPage, int $currentPage);
}
