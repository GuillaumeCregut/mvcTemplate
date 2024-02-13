<?php

namespace Editiel98;

use Editiel98\Chore\ClassFinder;
use ReflectionClass;

class RegisterController
{
    const APP_ROOT = __DIR__ . '/../../';

    public static function registerContoller(string $controller): array |false
    {
        $routes = [];
        $class = new ReflectionClass($controller);
        $className = $class->getName();
        foreach ($class->getMethods() as $method) {
            if (empty($method->getAttributes(\Editiel98\Chore\Attribute\RouteAttribute::class)))
                continue;
            $methodName = $method->getName();
            $routeAttributes[$methodName] = $method->getAttributes(\Editiel98\Chore\Attribute\RouteAttribute::class)[0];
        }
        if (empty($routeAttributes)) {
            return false;
        }
        foreach ($routeAttributes as $key => $routeAttribute) {
            $route = $routeAttribute->newInstance();
            $routes[$className][] = array('path' => $route->getPath(), 'method' => $key, 'datas' => $route->getDatas());
        }
        return $routes;
    }

    public static function getControllers()
    {
        $routes = [];
        $controllers = ClassFinder::getClassesInNamespace('App\\Controller');
        foreach ($controllers as $controller) {
            if (get_parent_class($controller) === 'Editiel98\AbstractController') {
                $controllerRoutes = self::registerContoller($controller);

                if (!$controllerRoutes) {
                    continue;
                }
                self::storePaths($routes, $controllerRoutes);
            }
        }
        var_dump($routes);
    }

    private static function storePaths(array &$routes, array $controller)
    {
        foreach ($controller as $key => $controllerRoutes) {
            $controllerName = $key;
            foreach ($controllerRoutes as $route) {
                $slug = self::checkUrlVars($route['path']);
                $path = $route['path'];
                $method = $route['method'];
                if (empty($route['datas']))
                    $routes[$path] = array($controllerName, $method, $slug);
                else
                    $routes[$path] = array($controllerName, $method, $slug, $route['datas']);
            }
        }
    }

    private static function checkUrlVars(string $url)
    {
        $slug = '';
        if ($start = strpos($url, '{')) {
            if (strpos($url, '}')) {
                $slug = substr($url, $start + 1, -1);
            }
        }
        return $slug;
    }
}
