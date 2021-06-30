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
        //echo $this->app()->renderer()->render('404', $vars);

        // ob_start();
        // require $this->contentFile;
        // $content = ob_get_clean();

        // ob_start();
        // require __DIR__ . '/../../App/' . $this->app->name() . '/Templates/layout.php';
        // return ob_get_clean();
    }

    public function setContentFile($contentFile)
    {
        if (!is_string($contentFile) || empty($contentFile)) {
            throw new \InvalidArgumentException('La vue spécifiée est invalide');
        }

        $this->contentFile = $contentFile;
    }
}
