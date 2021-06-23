<?php

namespace OCFram;

session_start();

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
        return isset($_SESSION['auth']) && $_SESSION['auth'] === true;
    }

    public function setAttribute($attr, $value)
    {
        $_SESSION[$attr] = $value;
    }

    public function setAuthenticated($authenticated = true)
    {
        if (!is_bool($authenticated)) {
            throw new \InvalidArgumentException('La valeur spécifiée à la méthode User::setAuthenticated() 
                                                 doit être un boolean');
        }

        $_SESSION['auth'] = $authenticated;
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
}
