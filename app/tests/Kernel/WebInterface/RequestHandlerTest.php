<?php

use Editiel98\Kernel\WebInterface\RequestHandler;
use PHPUnit\Framework\TestCase;

class RequestHandlerTest extends TestCase
{
    public function testIsRequestHandler(): void
    {
        $x = RequestHandler::getInstance();
        $this->assertInstanceOf(RequestHandler::class, $x);
    }

    public function testSingleton(): void
    {
        $x = RequestHandler::getInstance();
        $y = RequestHandler::getInstance();
        $this->assertSame($x, $y);
    }

    public function testGetValue(): void
    {
        $x = RequestHandler::getInstance();
        $x->init(['Hello' => 'world'], [], [], [], []);
        $y = RequestHandler::getInstance();
        $query = $y->query->hasKey('Hello');
        $this->assertTrue($query);
    }

    public function testGetURI(): void
    {
        $rh = RequestHandler::getInstance();
        $rh->init([], [], ['REQUEST_URI' => '/test'], [], []);
        $uri = $rh->getURI();
        $this->assertEquals('/test', $uri);
    }

    public function testGetMethod(): void
    {
        $rh = RequestHandler::getInstance();
        $rh->init([], [], ['REQUEST_METHOD' => 'GET'], [], []);
        $method = $rh->getMethod();
        $this->assertEquals('GET', $method);
    }

    public function testGetOverideMethod(): void
    {
        $rh = RequestHandler::getInstance();
        $rh->init([], ['override_method' => 'PUT'], ['REQUEST_METHOD' => 'POST'], [], []);
        $method = $rh->getMethod();
        $this->assertEquals('PUT', $method);
    }
}
