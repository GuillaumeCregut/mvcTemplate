<?php

namespace Editiel98\Chore\Routing;

class Routing
{
    /**
     * Get controller for route
     * @param string $url
     * 
     * @return array
     */
    public static function getRoute(string $url, array $routes): array|false
    {
        if (!key_exists($url, $routes)) {
            return false;
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
    public static function decodeURI($uri): array
    {
        $url = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '');
        $routes=RegisterController::getControllers();
        var_dump($routes);
        //Check slug
        $route = self::getRoute($url, $routes);
        if (!$route) {
            return [];
        }
        $parameters = [];
        foreach ($route[3] ?? [] as $parameter) {
            if (isset($_GET[$parameter])) {
                $parameters[$parameter] =  $_GET[$parameter];
            }
        }
        return [$route[0], $route[1], '',$parameters];
    }
}
