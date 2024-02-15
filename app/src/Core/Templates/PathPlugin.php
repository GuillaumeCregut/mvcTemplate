<?php

namespace Editiel98\Templates;

use Editiel98\Kernel\Routing\RegisterController;

class PathPlugin extends AbstractPlugin
{
    protected string $name = 'path';

    public static function display($params, $smarty)
    {
        if (empty($params['route'])) {
            return '';
        }
        $route = $params['route'];
        $routes = RegisterController::getRoutes();
        if (empty($routes[$route])) {
            return '';
        }
        $routeToShow = $routes[$route];
        if (!empty($params['slug'])) {
            $routeToShow .= '/' . $params['slug'];
        }
        if (!empty($params['datas'])) {
            $datas = $params['datas'];
            $first = true;
            $string = '';
            foreach ($datas as $key => $value) {
                if (!$first) {
                    $string .= '&';
                }
                $string .= $key . '=' . $value;
                $first = false;
            }
            $routeToShow .= '?' . $string;
        }
        return $routeToShow;
    }

    /**
     * Get the value of name
     */
    public function getName(): string
    {
        return $this->name;
    }
}
