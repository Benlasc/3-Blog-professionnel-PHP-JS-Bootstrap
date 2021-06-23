<?php

namespace OCFram;

class Router extends ApplicationComponent
{
    protected $routes = [];

    const NO_ROUTE = 1;

    public function addRoute(Route $route)
    {
        if (!in_array($route, $this->routes)) {
            $this->routes[] = $route;
        }
    }

    public function getRoute($url)
    {
        foreach ($this->routes as $route) {
            // Si la route correspond à l'URL
            if (($varsValues = $route->match($url)) !== false) {
                // Si elle a des variables
                if ($route->hasVars()) {
                    $varsNames = $route->varsNames();
                    $listVars = [];

                    // On crée un nouveau tableau clé/valeur
                    // (clé = nom de la variable, valeur = sa valeur)
                    foreach ($varsValues as $key => $match) {
                        // La première valeur contient entièrement la chaine capturée (voir la doc sur preg_match)
                        if ($key !== 0) {
                            $listVars[$varsNames[$key - 1]] = $match;
                        }
                    }

                    // On assigne ce tableau de variables � la route
                    $route->setVars($listVars);
                }
                return $route;
            }
        }

        throw new \RuntimeException('Aucune route ne correspond à l\'URL', self::NO_ROUTE);
    }

    /**
     * @param string $name
     * @param array $vars
     *
     * generateUri("indexPage", [2])
     * generateUri("showPost", [slug-test])
     * generateUri("nomRoute", [5,"test"])
     *
     * @return string|null
     */
    // public function generateUri(string $name, array $vars = []): string|null
    // {
    //     foreach ($this->routes as $route) {
    //         if ($route->name() == $name) {
    //             if (!$vars) {
    //                 return $this->app->httpRequest()->requestURI();
    //             }

    //             if (count($vars) == 1) {
    //                 preg_match_all(
    //                     "/\(.+\)/",
    //                     $route->url(),
    //                     $matches
    //                 );
    //             } else {
    //                 preg_match_all(
    //                     "/\(.+\)/U",
    //                     $route->url(),
    //                     $matches
    //                 );
    //             }

    //             $url = $route->url();
    //             $i = 0;
    //             foreach ($matches[0] as $match) {
    //                 $url = str_replace($match, $vars[$i], $url);
    //                 $i++;
    //             }

    //             return str_replace("\\", "", $url);
    //         }
    //     }
    //     return null;
    // }


    /**
     * @param string $name
     * @param array $vars
     *
     * generateUri("indexPage", [2])
     * generateUri("showPost", [slug-test])
     * generateUri("nomRoute", [5,"test"])
     *
     * @return string|null
     */
    public function generateUri(string $name, array $vars = []): string|null
    {
        foreach ($this->routes as $route) {
            if ($route->name() == $name) {
                $url = $route->url();
                $tab = [];
                while ($p = strpos($url, "(")) {
                    $g = "(";
                    foreach (str_split(substr($url, $p + 1)) as $c) {
                        if (substr_count($g, "(") != substr_count($g, ")")) {
                            $g .= $c;
                        } else {
                            break;
                        }
                    }

                    $tab[] = "/" . $g . "/";

                    $url = str_replace($g, "", $url);
                }

                $url = $route->url();

                foreach (array_combine($tab, $vars) as $regex => $value) {
                    if (preg_match($regex, $value)) {
                        $url = str_replace(substr($regex, 1, -1), $value, $url);
                    } else {
                        return null;
                    }
                }
                return str_replace(["\?","\."], ["?","."], $url);
            }
        }
        return null;
    }
}
