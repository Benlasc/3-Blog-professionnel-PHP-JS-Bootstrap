<?php
namespace Entity;

use DateTime;
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
    protected $passwordCheck;
    protected $confirmed;
    protected $confirmationToken;
    protected $resetToken;
    protected $resetAt;
    protected $avatar;

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

    public function setPasswordCheck($passwordCheck)
    {
        if (!is_string($passwordCheck) || empty($passwordCheck)) {
            $this->erreurs[] = self::PASSWORD_INVALIDE;
        }

        $this->passwordCheck = $passwordCheck;
    }

    public function setConfirmed(bool $confirmed)
    {
        $this->confirmed = $confirmed;
    }

    public function setAvatar(string|null $avatar)
    {
        $this->avatar = $avatar;
    }
    
    public function setConfirmationToken(string|null $confirmationToken)
    {
        $this->confirmationToken = $confirmationToken;
    }

    public function setResetToken(string|null $resetToken)
    {
        $this->resetToken = $resetToken;
    }

    public function setResetAt(\DateTime|null $resetAt)
    {
        $this->resetAt = $resetAt;
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

    public function passwordCheck()
    {
        return $this->passwordCheck;
    }

    public function confirmed()
    {
        return $this->confirmed;
    }

    public function avatar()
    {
        return $this->avatar;
    }

    public function confirmationToken()
    {
        return $this->confirmationToken;
    }

    public function resetToken()
    {
        return $this->resetToken;
    }

    public function resetAt()
    {
        return $this->resetAt;
    }
}
