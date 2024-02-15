<?php

namespace Editiel98\Kernel\Routing;

use Editiel98\Kernel\ClassFinder;
use ReflectionClass;

class RegisterController
{
    /**
     * @var array<mixed>
     */
    private static array $displayRoutes;

    /**
     * @param mixed $controller
     *
     * @return array<mixed>
     */
    private static function registerContoller(mixed $controller): array |false
    {
        $routes = [];
        $class = new ReflectionClass($controller);
        $prefix = '';
        $classRouteName = '';
        $classAttributes = $class->getAttributes(\Editiel98\Kernel\Attribute\RouteAttribute::class);
        if (!empty($classAttributes)) {
            $classRouteAttribute = $classAttributes[0];
            $classRoute = $classRouteAttribute->newInstance();
            $classRouteName = $classRoute->getName();
            $prefix = $classRoute->getPath();
        }
        $className = $class->getName();
        foreach ($class->getMethods() as $method) {
            if (empty($method->getAttributes(\Editiel98\Kernel\Attribute\RouteAttribute::class))) {
                continue;
            }
            $methodName = $method->getName();
            $routeAttributes[$methodName] =
                $method->getAttributes(\Editiel98\Kernel\Attribute\RouteAttribute::class)[0];
        }
        if (empty($routeAttributes)) {
            return false;
        }
        foreach ($routeAttributes as $key => $routeAttribute) {
            $route = $routeAttribute->newInstance();
            $routeName = $classRouteName . $route->getName();
            $routes[$className][] = array(
                'prefix' => $prefix,
                'path' => $route->getPath(),
                'method' => $key,
                'datas' => $route->getDatas(),
                'name' => $routeName
            );
            self::$displayRoutes[] = array('prefix' => $prefix, 'path' => $route->getPath(), 'name' => $routeName);
        }
        return $routes;
    }


    /**
     * @return array<mixed> named array with controller name, methods and params
     */
    public static function getControllers(): array
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
        return $routes;
    }


    /**
     * @return array<mixed>
     */
    public static function getRoutes(): array
    {
        $routes = [];
        foreach (self::$displayRoutes as $key => $route) {
            $slugs = self::checkUrlVars($route['path']);
            $name = $route['name'];
            $path = $route['path'];
            if ($slugs) {
                $path = $slugs['path'];
            }
            $prefix = $route['prefix'];
            $routes[$name] = $prefix . $path;
        }
        return $routes;
    }

    /**
     * @param array<mixed> $routes
     * @param array<mixed> $controller
     *
     * @return void
     */
    private static function storePaths(array &$routes, array $controller): void
    {
        foreach ($controller as $key => $controllerRoutes) {
            $controllerName = $key;
            foreach ($controllerRoutes as $route) {
                $slugPresent = self::checkUrlVars($route['path']);
                $path = $route['path'];
                if ($slugPresent) {
                    $path = $slugPresent['path'];
                    $slug = $slugPresent['slug'];
                } else {
                    $slug = '';
                }
                $path = $route['prefix'] . $path;
                $method = $route['method'];
                if (empty($route['datas'])) {
                    $routes[$path] = array($controllerName, $method, $slug);
                } else {
                    $routes[$path] = array($controllerName, $method, $slug, $route['datas']);
                }
            }
        }
    }

    /**
     * @param string $url
     *
     * @return array<mixed>
     */
    public static function checkUrlVars(string $url): array|false
    {
        $slug = '';
        if ($start = strpos($url, '{')) {
            if (strpos($url, '}')) {
                $slug = substr($url, $start + 1, -1);
                $length = strlen($url) - $start;
                $path = substr($url, 0, $length - 1);
                return array('slug' => $slug, 'path' => $path);
            }
        }
        return false;
    }
}
