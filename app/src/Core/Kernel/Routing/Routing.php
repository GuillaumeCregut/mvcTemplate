<?php

namespace Editiel98\Kernel\Routing;

class Routing
{
    const CONTROLLER = 0;
    const METHOD = 1;
    const SLUG = 2;
    const PARAMS = 3;

    /**
     * Get controller for route
     * @param string $url
     * 
     * @return array
     */
    private static function getRoute(string $url, array $routes): array|false
    {

        if (!key_exists($url, $routes)) {
            //Check Slug
            $slugs = self::checkSlug($url);
            $newRoute = $slugs['url'];
            if (!key_exists($newRoute, $routes)) {
                return false;
            }
            $newRouteArray = $routes[$newRoute];
            if ($newRouteArray[self::SLUG] === '')
                return false;
            $slugName = $newRouteArray[self::SLUG];
            $newRouteArray[] = [$slugName => $slugs['slug']];
            return $newRouteArray;
        }
        return $routes[$url];
    }

    /**
     * Get controller and method from routing
     * @return array : array with
     * 0=>controller
     * 1=>method
     * 2=>slug
     * 3=>params [key=>alue]
     */
    public static function decodeURI($url): array
    {
        //$url = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '');
        $routes = RegisterController::getControllers();
        //Check slug
        $route = self::getRoute($url, $routes);
        if (!$route) {
            return [];
        }
        $parameters = [];
        if (!empty($route[self::PARAMS])) {
            foreach ($route[self::PARAMS] as $key => $value) {
                if (gettype($key) === 'integer')
                    continue;
                $parameters[$key] = $value;
            }
        }

        foreach ($route[self::PARAMS] ?? [] as $parameter) {
            if (isset($_GET[$parameter])) {
                $parameters[$parameter] =  $_GET[$parameter];
                unset($route[self::PARAMS][$key]);
            }
        }
        return ['controller' => $route[self::CONTROLLER], 'method' => $route[self::METHOD], 'slug' => '', 'params' => $parameters];
    }

    /**
     * @param string $url
     * 
     * @return array
     */
    private static function checkSlug(string $url): array
    {
        return array('url' => substr($url, 0, strrpos($url, '/')), 'slug' => substr($url, strrpos($url, '/') + 1));
    }
}
