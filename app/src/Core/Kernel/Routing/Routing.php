<?php

namespace Editiel98\Kernel\Routing;

use Editiel98\Kernel\WebInterface\RequestHandler;

class Routing
{
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
        $requestHandler = RequestHandler::getInstance();
        $queries = $requestHandler->query;
        $rawUrl = parse_url($urlToFind, PHP_URL_PATH);
        if (!$rawUrl) {
            $rawUrl = '';
        }
        $url = trim($rawUrl);
        $routes = RegisterController::getControllersRoutes();
        $urlMatcher = new URLMatcher();
        $route = $urlMatcher->findRoute($url, $routes);
        foreach ($route['datas'] ?? [] as $parameter) {
            if ($queries->hasKey($parameter)) {
                $route['params'][$parameter] =  $queries->getParam($parameter);
            }
        }
        return $route;
    }
}
