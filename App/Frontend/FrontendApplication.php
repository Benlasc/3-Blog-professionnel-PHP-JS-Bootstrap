<?php

namespace App\Frontend;

use \OCFram\Application;
use OCFram\TwigRenderer;

class FrontendApplication extends Application
{
    public function __construct()
    {
        parent::__construct();
        $this->name = 'Frontend';
        //$this->renderer = new TwigRenderer($this, "../App/Frontend/Templates");
        $this->renderer->addPath("../App/Frontend/Templates");
    }

    public function run()
    {
        $controller = $this->getController();
        $controller->execute();
        $this->httpResponse->setPage($controller->page());
        $this->httpResponse->send();
    }
}
