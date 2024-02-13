<?php

namespace Editiel98\Kernel\Routing;

use Editiel98\Kernel\ClassFinder;
use ReflectionClass;

class RegisterController
{
    private static array $displayRoutes;
    /**
     * @param string $controller
     * 
     * @return array
     */
    private static function registerContoller(string $controller): array |false
    {
        $routes = [];
        $class = new ReflectionClass($controller);
        $className = $class->getName();
        foreach ($class->getMethods() as $method) {
            if (empty($method->getAttributes(\Editiel98\Kernel\Attribute\RouteAttribute::class)))
                continue;
            $methodName = $method->getName();
            $routeAttributes[$methodName] = $method->getAttributes(\Editiel98\Kernel\Attribute\RouteAttribute::class)[0];
        }
        if (empty($routeAttributes)) {
            return false;
        }
        foreach ($routeAttributes as $key => $routeAttribute) {
            $route = $routeAttribute->newInstance();
            $routeName = $route->getName();
            $routes[$className][] = array('path' => $route->getPath(), 'method' => $key, 'datas' => $route->getDatas(),'name'=>$routeName);
            self::$displayRoutes[]=array('path' => $route->getPath(),'name'=>$routeName);
        }
        return $routes;
    }

   
    /**
     * @return array named array with controller name, methods and params
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


    public static function getRoutes(): array
    {
        $routes=[];
        foreach(self::$displayRoutes as $key=>$route) {
            $slugs=self::checkUrlVars($route['path']);
            $name=$route['name'];
            $path=$route['path'];
            if($slugs) {
                $path=$slugs['path'];
            }
            $routes[$name]=$path;
        }
        return $routes;
    }

    /**
     * @param array $routes
     * @param array $controller
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
                if($slugPresent) {
                    $path=$slugPresent['path'];
                    $slug=$slugPresent['slug'];
                } else {
                    $slug='';
                }

                $method = $route['method'];
                if (empty($route['datas']))
                    $routes[$path] = array($controllerName, $method, $slug);
                else
                    $routes[$path] = array($controllerName, $method, $slug, $route['datas']);
            }
        }
    }

    /**
     * @param string $url
     * 
     * @return array
     */
    public static function checkUrlVars(string $url): array|false
    {
        $slug = '';
        if ($start = strpos($url, '{')) {
            if (strpos($url, '}')) {
                $slug = substr($url, $start + 1, -1);
                $length=strlen($url)-$start;
                $path= substr($url,0,$length-1);
                return array('slug'=>$slug,'path'=>$path);
            }
        }
        return false;
    }
}
