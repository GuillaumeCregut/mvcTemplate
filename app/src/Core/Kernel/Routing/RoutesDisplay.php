<?php

namespace Editiel98\Kernel\Routing;

class RoutesDisplay
{
    /**
     * @var array<mixed>
     */
    private array $routes = [];

    public function __construct()
    {
        $this->routes = RegisterController::getRoutes();
    }


    /**
     * @return array<mixed>
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
    public function getRoute(string $route): string |null
    {
        if (key_exists($route, $this->routes)) {
            return $this->routes[$route];
        } else {
            return null;
        }
    }
}
