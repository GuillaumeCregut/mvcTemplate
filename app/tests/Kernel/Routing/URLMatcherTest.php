<?php

use Editiel98\Kernel\Routing\URLMatcher;
use PHPUnit\Framework\TestCase;

class URLMatcherTest extends TestCase
{
    private array $routes=[
        array(
            'prefix'=>'',
            'path'=>'/',
            'method'=>'index',
            'datas'=>[],
            'name'=>'home',
            'controller'=>'controllerName'
        ),
        array(
            'prefix'=>'/api',
            'path'=>'/',
            'method'=>'index',
            'datas'=>[],
            'name'=>'test_index',
            'controller'=>'TestController'
        ),
        array(
            'prefix'=>'/api',
            'path'=>'/toto/{slug}',
            'method'=>'toto',
            'datas'=>[],
            'name'=>'with_slug',
            'controller'=>'TestController'
        ),
        array(
            'prefix'=>'/api',
            'path'=>'/toto/admin',
            'method'=>'admin',
            'datas'=>[],
            'name'=>'test_admin',
            'controller'=>'TestController'
        ),
        array(
            'prefix'=>'/api',
            'path'=>'/toto/{slug}/edit',
            'method'=>'test',
            'datas'=>[],
            'name'=>'edit_slug',
            'controller'=>'TestController'
        ),
    ];
    
    public function testWithNothing(): void
    {
        $urlMatcher=new URLMatcher();
        $route=$urlMatcher->findRoute('',$this->routes);
        $this->assertEmpty($route);

    }

    public function testWithHomeRoute(): void
    {
        $urlMatcher=new URLMatcher();
        $route=$urlMatcher->findRoute('/',$this->routes);
        $this->assertArrayHasKey('controller',$route);
        $name=$route['name'];
        $this->assertEquals('home',$name);
    }

    public function testWithoutSlugOnSlugRoute(): void
    {
        $urlMatcher=new URLMatcher();
        $route=$urlMatcher->findRoute('/api/toto/',$this->routes);
        $this->assertEmpty($route);
        
    }

    public function testWithSlugRoute(): void
    {
        $urlMatcher=new URLMatcher();
        $route=$urlMatcher->findRoute('/api/toto/tti',$this->routes);
        $this->assertArrayHasKey('controller',$route);
        $name=$route['name'];
        $this->assertEquals('with_slug',$name);
    }

    public function testRouteSameAsSlug(): void
    {
        $urlMatcher=new URLMatcher();
        $route=$urlMatcher->findRoute('/api/toto/admin',$this->routes);
        $this->assertArrayHasKey('controller',$route);
        $name=$route['name'];
        $this->assertEquals('test_admin',$name);
    }

    public function testRouteWithSlugBetween(): void
    {
        $urlMatcher=new URLMatcher();
        $route=$urlMatcher->findRoute('/api/toto/titi/edit',$this->routes);
        $this->assertArrayHasKey('controller',$route);
        $name=$route['name'];
        $this->assertEquals('edit_slug',$name);
    }

    public function testRouteWithWrongURL(): void
    {
        $urlMatcher=new URLMatcher();
        $route=$urlMatcher->findRoute('/api/titi/',$this->routes);
        $this->assertEmpty($route);
    }

    public function testRouteWithTooManyParams(): void
    {
        $urlMatcher=new URLMatcher();
        $route=$urlMatcher->findRoute('/api/toto/titi/bonjour',$this->routes);
        $this->assertEmpty($route);
    }
}