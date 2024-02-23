<?php

namespace Editiel98\Kernel\Routing;

use Editiel98\Kernel\ClassFinder;
use Editiel98\Kernel\GetEnv;
use ReflectionClass;

class RegisterController
{
    /**
     * @var array<mixed>
     */
    private static array $displayRoutes;
    /**
     * @var array<mixed>
     */
    public static array $rawRoutes;

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
                'name' => $routeName,
                'params' => []
            );
            self::$displayRoutes[] = array('prefix' => $prefix, 'path' => $route->getPath(), 'name' => $routeName);
        }
        return $routes;
    }


    /**
     * @return array<mixed> named array with controller name, methods and params
     */
    public static function getControllersRoutes(): array
    {
        $routes = [];
        $controllers = ClassFinder::getClassesInNamespace('App\\Controller');
        if (GetEnv::getEnvValue('envMode') === 'DEBUG') {
            $controllers[] = 'Editiel98\\Templates\\DebugController';
        }
        foreach ($controllers as $controller) {
            if (
                (get_parent_class($controller) === 'Editiel98\AbstractController') ||
                (get_parent_class($controller) === 'Editiel98\Kernel\MainAbstractController')
            ) {
                $controllerRoutes = self::registerContoller($controller);

                if (!$controllerRoutes) {
                    continue;
                }
                self::storePaths($routes, $controllerRoutes);
            }
        }
        return self::$rawRoutes;
    }


    /**
     * @return array<mixed>
     */
    public static function getRoutes(): array
    {
        $routes = [];
        if (empty(self::$displayRoutes)) {
            self::getControllersRoutes();
        }
        foreach (self::$displayRoutes as $route) {
            $name = $route['name'];
            $path = $route['path'];
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
                $route['controller'] = $controllerName;
                self::$rawRoutes[] = $route;
                $path = $route['prefix'] . $route['path'];
                $method = $route['method'];
                if (empty($route['datas'])) {
                    $routes[$path] = array($controllerName, $method);
                } else {
                    $routes[$path] = array($controllerName, $method, $route['datas']);
                }
            }
        }
    }

    /**
     * @param string $url
     *
     * @return array<mixed>
     */
    // public static function checkUrlVars(string $url): array|false
    // {
    //     $slug = '';
    //     $start = $start = strpos($url, '{');
    //     if ($start) {
    //         if (strpos($url, '}')) {
    //             $slug = substr($url, $start + 1, -1);
    //             $length = strlen($url) - $start;
    //             $path = substr($url, 0, $length - 1);
    //             return array('slug' => $slug, 'path' => $path);
    //         }
    //     }
    //     return false;
    // }
}
