<?php

namespace Model;

use Entity\User;

class UserManagerPDO extends UserManager
{
    public function getList($debut = -1, $limite = -1)
    {
        $sql = 'SELECT * FROM user ORDER BY id DESC';

        if ($debut != -1 || $limite != -1) {
            $sql .= ' LIMIT ' . (int) $limite . ' OFFSET ' . (int) $debut;
        }

        $requete = $this->dao->query($sql);
        $requete->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Entity\User');

        $listeusers = $requete->fetchAll();

        foreach ($listeusers as $user) {
            $user->setDateInscription(new \DateTime($user->dateInscription()));
        }

        $requete->closeCursor();

        return $listeusers;
    }

    public function getUnique($id)
    {
        $requete = $this->dao->prepare('SELECT * FROM user WHERE id = :id');
        $requete->bindValue(':id', (int) $id, \PDO::PARAM_INT);
        $requete->execute();

        $requete->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Entity\User');

        if ($user = $requete->fetch()) {
            $user->setDateInscription(new \DateTime($user->dateInscription()));
            return $user;
        }

        return null;
    }

    public function count()
    {
        return $this->dao->query('SELECT COUNT(*) FROM user')->fetchColumn();
    }

    protected function add(User $user)
    {
        $requete = $this->dao->prepare('INSERT INTO user SET pseudo = :pseudo, nom = :nom, prenom = :prenom, 
                                        mail = :mail, dateInscription = NOW(), admin = :admin, password = :password');

        $requete->bindValue(':pseudo', $user->pseudo());
        $requete->bindValue(':nom', $user->nom());
        $requete->bindValue(':prenom', $user->prenom());
        $requete->bindValue(':mail', $user->mail());
        $requete->bindValue(':admin', $user->admin());
        $requete->bindValue(':password', $user->password());

        $requete->execute();
    }

    protected function modify(User $user)
    {
        $requete = $this->dao->prepare('UPDATE user SET pseudo = :pseudo, nom = :nom, prenom = :prenom, mail = :mail, 
                                        admin = :admin, password = :password WHERE id = :id');

        $requete->bindValue(':pseudo', $user->pseudo());
        $requete->bindValue(':nom', $user->nom());
        $requete->bindValue(':prenom', $user->prenom());
        $requete->bindValue(':mail', $user->mail());
        $requete->bindValue(':admin', $user->admin());
        $requete->bindValue(':password', $user->password());
        $requete->bindValue(':id', $user->id(), \PDO::PARAM_INT);

        $requete->execute();
    }

    public function delete($id)
    {
        $this->dao->exec('DELETE FROM user WHERE id = ' . (int) $id);
    }
}
