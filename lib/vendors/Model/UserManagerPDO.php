<?php

namespace Model;

use Entity\User;
use OCFram\Random_str_generator;

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
            $user->setResetAt(($user->resetAt() === null) ? null : new \DateTime($user->resetAt()));
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
            $user->setResetAt(($user->resetAt() === null) ? null : new \DateTime($user->resetAt()));
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
                                        mail = :mail, dateInscription = NOW(), admin = :admin, password = :password, confirmed = 0, confirmationToken = :confirmationToken, avatar = :avatar');

        $requete->bindValue(':pseudo', $user->pseudo());
        $requete->bindValue(':nom', $user->nom());
        $requete->bindValue(':prenom', $user->prenom());
        $requete->bindValue(':mail', $user->mail());
        $requete->bindValue(':admin', 0);
        $requete->bindValue(':password', password_hash($user->password(), PASSWORD_DEFAULT));
        $requete->bindValue(':confirmationToken', $user->confirmationToken());
        $requete->bindValue(':avatar', $user->avatar());
        $requete->execute();

        $user->newId = $this->dao->lastInsertId();
    }

    protected function modify(User $user, bool $passwordUpdate)
    {
        if ($passwordUpdate) {
            $requete = 'UPDATE user SET pseudo = :pseudo, nom = :nom, prenom = :prenom, mail = :mail, password = :password, confirmed = :confirmed, confirmationToken = :confirmationToken, resetToken = :resetToken, resetAt = :resetAt, avatar = :avatar WHERE id = :id';
        } else {
            $requete = 'UPDATE user SET pseudo = :pseudo, nom = :nom, prenom = :prenom, mail = :mail, confirmed = :confirmed, confirmationToken = :confirmationToken, resetToken = :resetToken, resetAt = :resetAt, avatar = :avatar WHERE id = :id';
        }
        
        $requete = $this->dao->prepare($requete);

        $requete->bindValue(':pseudo', $user->pseudo());
        $requete->bindValue(':nom', $user->nom());
        $requete->bindValue(':prenom', $user->prenom());
        $requete->bindValue(':mail', $user->mail());
        if ($passwordUpdate) {
            $requete->bindValue(':password', password_hash($user->password(), PASSWORD_DEFAULT));
        }
        $requete->bindValue(':confirmed', $user->confirmed());
        $requete->bindValue(':confirmationToken', $user->confirmationToken());
        $requete->bindValue(':resetToken', $user->resetToken() ?? null);
        $requete->bindValue(':resetAt', ($user->resetAt() === null) ? null : $user->resetAt()->format('Y-m-d G:i:s'));
        $requete->bindValue(':avatar', $user->avatar());
        $requete->bindValue(':id', $user->id(), \PDO::PARAM_INT);

        $requete->execute();
    }

    public function delete($id)
    {
        $this->dao->exec('DELETE FROM user WHERE id = ' . (int) $id);
    }

    public function getWithPseudo(string $pseudo)
    {
        $requete = $this->dao->prepare('SELECT * FROM user WHERE pseudo = :pseudo');
        $requete->bindValue(':pseudo', $pseudo);
        $requete->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Entity\User');
        $requete->execute();
        
        if ($user = $requete->fetch()) {
            $user->setDateInscription(new \DateTime($user->dateInscription()));
            return $user;
        }

        return null;
    }

    public function getWithMail(string $mail)
    {
        $requete = $this->dao->prepare('SELECT * FROM user WHERE mail = :mail');
        $requete->bindValue(':mail', $mail);
        $requete->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Entity\User');
        $requete->execute();
        
        if ($user = $requete->fetch()) {
            $user->setDateInscription(new \DateTime($user->dateInscription()));
            return $user;
        }

        return null;
    }
}
