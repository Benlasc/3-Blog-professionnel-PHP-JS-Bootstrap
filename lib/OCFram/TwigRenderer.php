<?php

namespace OCFram;

use OCFram\Twig\PagerFantaExtension;
use Twig\Extension\DebugExtension;
use Twig\Extra\Intl\IntlExtension;

class TwigRenderer extends ApplicationComponent implements RendererInterface
{

    private $twig;

    private $loader;

    public function __construct(Application $app, string $path)
    {
        parent::__construct($app);
        $this->loader = new \Twig\Loader\FilesystemLoader($path);
        $this->twig = new \Twig\Environment($this->loader, ['debug' => true]);
        //$this->twig->addExtension(new DebugExtension());
        $this->twig->addExtension(new IntlExtension());
        $this->twig->addExtension(new PagerFantaExtension($this->app->router()));
    }

    /**
     * Permet de rajouter un chamin pour charger les vues
     * @param string $path
     * @param null|string $namespace
     */
    public function addPath(string $path, ?string $namespace = null): void
    {
        if ($namespace) {
            $this->loader->addPath($path, $namespace);
        } else {
            $this->loader->addPath($path);
        }
    }

    /**
     * Permet de rendre une vue
     * Le chemin peut être précisé avec des namespace rajoutés via addPath()
     * $this->render('@blog/view');
     * $this->render('view');
     * @param string $view
     * @param array $params
     * @return string
     */
    public function render(string $view, array $params = []): string
    {
        return $this->twig->render($view . '.twig', $params);
    }

    /**
     * Permet de rajouter des variables globales à toutes les vues
     *
     * @param string $key
     * @param mixed $value
     */
    public function addGlobal(string $key, $value): void
    {
        $this->twig->addGlobal($key, $value);
    }
}
