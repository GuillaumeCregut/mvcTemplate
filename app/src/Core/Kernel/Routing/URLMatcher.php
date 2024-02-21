<?php

namespace Editiel98\Kernel\Routing;

use Editiel98\Kernel\Exception\WebInterfaceException;
use Error;
use Exception;

class URLMatcher
{
    /**
     * @var mixed[];
     */
    private array $routeToReturn = [];
    private string $slugReg = '/\{[a-zA-Z0-9]+\}/';

    /**
     * @param string $url
     * @param mixed[] $routes
     * 
     * @return mixed[] 
     */
    public function findRoute(string $url, array $routes): array
    {
        foreach ($routes as $route) {
            $fullRoute = $route['prefix'] . $route['path'];
            $slugs = $this->hasSlug($route['path']);
            if (!$slugs) {
                //pas de slug dans la route
                if ($url === $fullRoute) {
                    return $route;
                }
            } else {
                $routeSlug = $this->matchPattern($url, $route);
                if (!$routeSlug) {
                    continue;
                }
                $this->routeToReturn = $routeSlug;
            }
        }
        return $this->routeToReturn;
    }

    /**
     * @param string $route
     * 
     * @return bool
     */
    private function hasSlug(string $route): bool
    {
        $testReg = preg_match_all($this->slugReg, $route, $slugs);
        if (false === $testReg) {
            throw new  WebInterfaceException('Error in checking URL');
        }
        if ($testReg === 0) {
            return false;
        }
        return true;
    }

    /**
     * @param string $url
     * @param mixed[] $route
     * 
     * @return mixed[]
     */
    private function matchPattern(string $url, array $route): array | false
    {
        $fullRoute = $route['prefix'] . $route['path'];
        $regURL = preg_replace($this->slugReg, '[a-zA-Z0-9]+', $fullRoute);
        $regURL = addcslashes($regURL, '/');
        $regURL = '/' . $regURL . '(?!\/\w+)$/';
        $testURL = preg_match_all($regURL, $url);
        if (false === $testURL) {
            throw new  WebInterfaceException('Error in checking slug in URL');
        }
        if ($testURL === 0) {
            return false;
        }
        //add slugs
        $slugs = $this->getSlugs($route, $url);
        if (!empty($slugs)) {
            $route['params'] = $slugs;
        }
        return $route; //to be changed
    }

    /**
     * @param mixed[] $route
     * @param string $url
     * 
     * @return mixed[]
     */
    private function getSlugs(array $route, string $url): array
    {
        try {
            $fullRoute = $route['prefix'] . $route['path'];
            $urltoTest = ltrim($url, '/');
            $urlTemplate = ltrim($fullRoute, '/');
            $testArray = explode('/', $urltoTest);
            $urlArray = explode('/', $urlTemplate);
            $slugsValues = [];
            for ($i = 0; $i < count($urlArray); $i++) {
                if ($testArray[$i] === $urlArray[$i]) {
                    continue;
                }
                $slug = ltrim(rtrim($urlArray[$i], '}'), '{');
                $value = $testArray[$i];
                $slugsValues[$slug] = $value;
            }
            return $slugsValues;
        } catch (Error $e) {
            throw new WebInterfaceException('Error in decoding slugs');
        } catch (Exception $e) {
            throw new WebInterfaceException('Error in decoding slugs');
        }
    }
}
