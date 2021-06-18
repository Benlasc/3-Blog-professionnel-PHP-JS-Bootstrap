<?php

namespace App\Backend;

use \OCFram\Application;
use OCFram\TwigRenderer;

class BackendApplication extends Application
{
    public function __construct()
    {
        parent::__construct();
        $this->name = 'Backend';
        $this->renderer = new TwigRenderer($this, "../App/Backend/Templates");
    }

    public function run()
    {
        if ($this->user->isAuthenticated()) {
            $controller = $this->getController();
        } else {
            $controller = new Modules\Connexion\ConnexionController($this, 'Connexion', 'index');
        }

        $controller->execute();

        $this->httpResponse->setPage($controller->page());
        $this->httpResponse->send();
    }
}
