<?php

namespace Editiel98\Kernel\Routing;

use Editiel98\Kernel\ClassFinder;
use ReflectionClass;

class RoutesDisplay
{

    private array $routes = [];

    public function __construct()
    {
       $this->routes= RegisterController::getRoutes();
    }

    /**
     * Get the value of routes
     */
    public function getRoutes(): array
    {
        return $this->routes;
    }

    /**
     * @param string $route
     * 
     * @return string
     */
    public function getRoute(string $route):string |null
    {
        if(key_exists($route,$this->routes)){
            return $this->routes[$route];
        } else return null;
    }
    
}
