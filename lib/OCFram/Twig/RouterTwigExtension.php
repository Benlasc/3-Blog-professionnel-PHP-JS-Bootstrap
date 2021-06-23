<?php

namespace OCFram\Twig;

use OCFram\Router;

class RouterTwigExtension extends \Twig\Extension\AbstractExtension
{

    /**
     * @var Router
     */
    private $router;

    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    public function getFunctions()
    {
        return [
            new \Twig\TwigFunction('path', [$this, 'pathFor'])
        ];
    }

    /**
     * @param string $name : le nom de la route
     * @param array $params : les paramètres de la route
     *
     * @return string
     */
    public function pathFor(string $name, array $params = []): string
    {
        return $this->router->generateUri($name, $params);
    }
}
