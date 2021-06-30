<?php

namespace OCFram;

session_start();

if (!isset($_SESSION['token'])) {
    $_SESSION['token'] = bin2hex(openssl_random_pseudo_bytes(6));
}

class User extends ApplicationComponent
{
    public function getAttribute($attr)
    {
        return isset($_SESSION[$attr]) ? $_SESSION[$attr] : null;
    }

    public function getFlash(): array
    {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);

        return $flash;
    }

    public function hasFlash()
    {
        return isset($_SESSION['flash']);
    }

    public function isAuthenticated()
    {
        return isset($_SESSION['user_id']);
    }

    public function isAdmin()
    {
        return isset($_SESSION['admin']) && $_SESSION['admin'] === true;
    }

    public function setAttribute($attr, $value)
    {
        $_SESSION[$attr] = $value;
    }

    public function setAuthenticated($userId)
    {
        if (!is_int($userId)) {
            throw new \InvalidArgumentException('La valeur spécifiée à la méthode User::setAuthenticated() 
                                                 doit être un entier naturel.');
        }

        $_SESSION['user_id'] = $userId;
    }

    public function setAdmin($admin = true)
    {
        if (!is_bool($admin)) {
            throw new \InvalidArgumentException('La valeur spécifiée à la méthode User::setAuthenticated() 
                                                 doit être un boolean');
        }

        $_SESSION['admin'] = $admin;
    }

    /**
     * @param string $message : alert message
     * @param string $class : alert bootstrap class
     *
     * @return void
     */
    public function setFlash(string $message, string $class): void
    {
        $_SESSION['flash'] = [$message,$class];
    }

    public function deleteAttribute($attr)
    {
        unset($_SESSION[$attr]);
    }
}
