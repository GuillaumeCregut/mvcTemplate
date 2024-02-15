<?php

namespace Editiel98\Kernel\Routing;

class Routing
{
    const CONTROLLER = 0;
    const METHOD = 1;
    const SLUG = 2;
    const PARAMS = 3;

    
    /**
     * @param string $url
     * @param array<mixed> $routes
     * 
     * @return array<mixed>
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
     * Return the controller method and params for specific url
     * @param mixed $urlToFind
     * 
     * @return array<mixed>
     * 0=>controller
     * 1=>method
     * 2=>slug
     * 3=>params [key=>alue]
     */
    public static function decodeURI($urlToFind): array
    {
        $rawUrl=parse_url($urlToFind, PHP_URL_PATH);
        if(!$rawUrl){
            $rawUrl='';
        }
        $url = trim($rawUrl);
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
                if(isset($key))
                    unset($route[self::PARAMS][$key]);
            }
        }
        return ['controller' => $route[self::CONTROLLER], 'method' => $route[self::METHOD], 'slug' => '', 'params' => $parameters];
    }

    /**
     * @param string $url
     * 
     * @return array<mixed>
     */
    private static function checkSlug(string $url): array
    {
        $pos=strrpos($url, '/');
        if(!$pos){
            $pos=null;
        }
        return array('url' => substr($url, 0,  $pos), 'slug' => substr($url,  $pos + 1));
    }
}
