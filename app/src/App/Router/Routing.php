<?php
namespace App\Router;

class Routing
{
    private static array $routes=[
        ''=>['HomeController','index'],
        'test'=>['TestController','index'],
       // 'detail'=>['controllerName','methodName',['id',]],
    ];

    /**
     * Get controller for route
     * @param string $url
     * 
     * @return array
     */
    public static function getRoute(string $url): array|false
    {
        if (!key_exists( $url,self::$routes)) {
            return false;
        }
        return self::$routes[$url];
        
    }

}