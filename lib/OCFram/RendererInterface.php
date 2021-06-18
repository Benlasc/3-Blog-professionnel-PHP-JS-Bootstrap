<?php

namespace OCFram;

interface RendererInterface
{

    /**
     * Permet de rajouter un chamin pour charger les vues
     * @param string $path
     * @param null|string $namespace
     */
    public function addPath(string $path, ?string $namespace = null): void;

    /**
     * Permet de rendre une vue
     * Le chemin peut être précisé avec des namespace rajoutés via addPath()
     * $this->render('@blog/view');
     * $this->render('view');
     * @param string $view
     * @param array $params
     * @return string
     */
    public function render(string $view, array $params = []): string;

    /**
     * Permet de rajouter des variables globales à toutes les vues
     *
     * @param string $key
     * @param mixed $value
     */
    public function addGlobal(string $key, $value): void;
}
