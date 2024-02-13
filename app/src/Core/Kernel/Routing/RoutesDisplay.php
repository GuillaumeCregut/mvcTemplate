<?php

namespace Editiel98\Kernel\Routing;

use Editiel98\Kernel\ClassFinder;
use ReflectionClass;

class RoutesDisplay
{
    private array $routes = [];

    public function __construct()
    {
        $controllers = ClassFinder::getClassesInNamespace('App\\Controller');
        var_dump($controllers);
        foreach ($controllers as $controller) {
            if (get_parent_class($controller) === 'Editiel98\AbstractController') {
                $controllerRoutes = $this->registerContoller($controller);
                if (!$controllerRoutes) {
                    continue;
                }
                var_dump($controllerRoutes);
                foreach($controllerRoutes as $controllerRoute) {
                    $this->routes[$controllerRoute['name']]=$controllerRoute['path'];
                }
            }
        }
        var_dump($this->routes);
    }

    private function registerContoller(string $controller): array |false
    {
        var_dump($controller);
        $routes = [];
        $class = new ReflectionClass($controller);
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
            $path=$route->getPath();
            $slugs= $this->checkUrlVars($path);
            if($slugs) {
                $path=$slugs['path'];
            }
            $routes[] = array('name' => $routeName, 'path' => $path);
        }
        return $routes;
    }

    /**
     * @param string $url
     * 
     * @return array
     */
    private function checkUrlVars(string $url): array|false
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

    /**
     * Get the value of routes
     */
    public function getRoutes(): array
    {
        return $this->routes;
    }
}
