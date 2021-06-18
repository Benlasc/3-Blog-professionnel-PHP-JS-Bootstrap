<?php
namespace Entity;

use \OCFram\Entity;

class User extends Entity
{
    protected $pseudo;
    protected $nom;
    protected $prenom;
    protected $mail;
    protected $dateInscription;
    protected $admin;
    protected $password;

    const PSEUDO_INVALIDE = 1;
    const NOM_INVALIDE = 2;
    const PRENOM_INVALIDE = 3;
    const MAIL_INVALIDE = 4;
    const PASSWORD_INVALIDE = 5;

    public function isValid()
    {
        return !(empty($this->pseudo) || empty($this->nom) || empty($this->prenom) || empty($this->mail)
                                      || empty($this->password));
    }

    // SETTERS //

    public function setPseudo($pseudo)
    {
        if (!is_string($pseudo) || empty($pseudo)) {
            $this->erreurs[] = self::PSEUDO_INVALIDE;
        }

        $this->pseudo = $pseudo;
    }

    public function setNom($nom)
    {
        if (!is_string($nom) || empty($nom)) {
            $this->erreurs[] = self::NOM_INVALIDE;
        }

        $this->nom = $nom;
    }

    public function setPrenom($prenom)
    {
        if (!is_string($prenom) || empty($prenom)) {
            $this->erreurs[] = self::PRENOM_INVALIDE;
        }

        $this->prenom = $prenom;
    }

    public function setMail($mail)
    {
        if (!is_string($mail) || empty($mail)) {
            $this->erreurs[] = self::MAIL_INVALIDE;
        }

        $this->mail = $mail;
    }

    public function setDateInscription(\DateTime $dateInscription)
    {
        $this->dateInscription = $dateInscription;
    }

    public function setAdmin(bool $admin)
    {
        $this->admin = $admin;
    }

    public function setPassword($password)
    {
        if (!is_string($password) || empty($password)) {
            $this->erreurs[] = self::PASSWORD_INVALIDE;
        }

        $this->password = $password;
    }

    // GETTERS //

    public function pseudo()
    {
        return $this->pseudo;
    }

    public function nom()
    {
        return $this->nom;
    }

    public function prenom()
    {
        return $this->prenom;
    }

    public function mail()
    {
        return $this->mail;
    }

    public function dateInscription()
    {
        return $this->dateInscription;
    }
    
    public function admin()
    {
        return $this->admin;
    }

    public function password()
    {
        return $this->password;
    }
}
