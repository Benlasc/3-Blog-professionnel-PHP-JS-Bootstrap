<?php

namespace OCFram\Twig;

use OCFram\Router;
use Pagerfanta\Pagerfanta;
use Pagerfanta\View\TwitterBootstrap5View;

class PagerFantaExtension extends \Twig\Extension\AbstractExtension
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
            new \Twig\TwigFunction('paginate', [$this, 'paginate'], ['is_safe' => ['html']])
        ];
    }

    public function paginate(Pagerfanta $paginatedResults, string $route, array $queryArgs = []): string
    {
        $view = new TwitterBootstrap5View();
        return $view->render($paginatedResults, function (int $page) use ($route, $queryArgs) {
            if ($page > 1) {
                $queryArgs[] = $page;
            }
            return $this->router->generateUri($route, [$page]);
        });
    }
}
