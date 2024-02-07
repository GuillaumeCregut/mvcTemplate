<?php
namespace App\Router;

class Routing
{
    private static array $routes=[
        ''=>['HomeController','index',['id']],
        'detail'=>['controllerName','methodName',['id',]],
    ];

    public static function getRoute(string $url): array|false
    {
        if (!key_exists( $url,self::$routes)) {
            return false;
        }
        return self::$routes[$url];
        
    }

}