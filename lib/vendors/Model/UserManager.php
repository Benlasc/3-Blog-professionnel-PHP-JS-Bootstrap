<?php

namespace Model;

use Entity\User;
use \OCFram\Manager;

abstract class UserManager extends Manager
{
    /**
     * Méthode retournant une liste de users demandée.
     * @param $debut int Le premier user à sélectionner
     * @param $limite int Le nombre de users à sélectionner
     * @return array La liste des users. Chaque entrée est une instance de User.
     */
    abstract public function getList($debut = -1, $limite = -1);

    /**
     * Méthode retournant un user précis.
     * @param $id int L'identifiant du user à récupérer
     * @return User Le user demandé
     */
    abstract public function getUnique($id);

    /**
     * Méthode renvoyant le nombre de users total.
     * @return int
     */
    abstract public function count();

    /**
     * Méthode permettant d'ajouter un user.
     * @param User $user Le user à ajouter
     * @return void
     */
    abstract protected function add(User $user);

    /**
     * Méthode permettant d'enregistrer un user.
     * @param User $user le user à enregistrer
     * @see self::add()
     * @see self::modify()
     * @return void
     */
    public function save(User $user, bool $passwordUpdate = true)
    {
        if ($user->isValid()) {
            $user->isNew() ? $this->add($user) : $this->modify($user, $passwordUpdate);
        } else {
            throw new \RuntimeException('L\'utilisateur doit être valide pour être enregistré');
        }
    }

    /**
     * Méthode permettant de modifier un user.
     * @param $user le user à modifier
     * @return void
     */
    abstract protected function modify(User $user, bool $passwordUpdate);

    /**
     * Méthode permettant de supprimer un user.
     * @param $id int L'identifiant du user à supprimer
     * @return void
     */
    abstract public function delete($id);
}
