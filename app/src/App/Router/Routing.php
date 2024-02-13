<?php
namespace App\Router;

use ReflectionClass;

class Routing
{
    private static array $routes=[
        ''=>['HomeController','index',''],
        '/test/'=>['TestController','index',''],
        'toto'=>['TestController','test','',['id', 'test']],
       // 'detail'=>['controllerName','methodName','slug',['id',]],
    ];

    /**
     * Get controller for route
     * @param string $url
     * 
     * @return array
     */
    public static function getRoute(string $url): array|false
    {
        var_dump(self::$routes);
        if (!key_exists( $url,self::$routes)) {
            return false;
        }
        return self::$routes[$url];
        
    }
}