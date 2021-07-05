<?php

namespace OCFram;

class Page extends ApplicationComponent
{
    protected $contentFile;
    protected $vars = [];

    public function addVar($var, $value)
    {
        if (!is_string($var) || is_numeric($var) || empty($var)) {
            throw new \InvalidArgumentException('Le nom de la variable doit être une chaine de caractères non nulle');
        }

        $this->vars[$var] = $value;
    }

    public function getGeneratedPage()
    {
        if (!file_exists($this->contentFile)) {
            throw new \RuntimeException('La vue spécifiée n\'existe pas');
        }
     
        $vue = array_pop($this->vars) ?: '404';

        $vars = array_merge(
            ['user'=>$this->app->user()],
            $this->vars
        );

        echo $this->app()->renderer()->render($vue, $vars);
    }

    public function setContentFile($contentFile)
    {
        if (!is_string($contentFile) || empty($contentFile)) {
            throw new \InvalidArgumentException('La vue spécifiée est invalide');
        }

        $this->contentFile = $contentFile;
    }
}
