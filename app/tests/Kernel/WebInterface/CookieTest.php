<?php

use Editiel98\Kernel\WebInterface\Cookie;
use Editiel98\Kernel\WebInterface\RequestHandler;
use PHPUnit\Framework\TestCase;

class CookieTest extends TestCase
{
    private RequestHandler $handler;

    public function setup(): void
    {
        $this->handler = RequestHandler::getInstance();
        $this->handler->init([], [], ['REQUEST_URI' => '/', 'HTTP_HOST' => 'localhost'], [], [],[]);
    }

    public function testWithNoValues(): void
    {
        $cookie = new Cookie('Hello');
        $this->assertStringStartsWith('Hello=deleted;', $cookie->toString());
        $this->assertStringNotContainsString('secure',$cookie->toString());
        $this->assertStringNotContainsString('httpOnly',$cookie->toString());
    }

    public function testWithValue(): void
    {
        $cookie = new Cookie('Hello', 'World');
        $this->assertStringStartsWith('Hello=World;', $cookie->toString());
    }

    public function testWithValueAndWrongExpires(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $cookie = new Cookie('Hello', 'World', 'bonjour');
    }

    public function testWithValueAndExpires(): void
    {
        $cookie = new Cookie('Hello', 'World');
        $now = gmdate('d M Y H:m:s');
        $cookie = new Cookie('Hello', 'World', 'now');
        $testString = 'Hello=World;expires=' . $now . ' GMT;Max-Age=0';
        $this->assertStringStartsWith($testString, $cookie->toString());
    }

    public function testMaxAge(): void
    {
        $cookie = new Cookie('Hello', 'World', '+1 day');
        $now = gmdate('d M Y H:m:s', strtotime('+1 day'));
        $diff = strtotime('+1 day') - strtotime('now');
        $testString = 'Hello=World;expires=' . $now . ' GMT;Max-Age=' . $diff;
        $this->assertStringStartsWith($testString, $cookie->toString());
    }

    public function testWithDomainOther(): void
    {
        $cookie = new Cookie('Hello', 'World', domain:'test');
        $this->assertStringContainsString('test',$cookie->toString());
    }

    public function testWithSecure(): void
    {
        $cookie = new Cookie('Hello', 'World', secure:true);
        $this->assertStringContainsString('secure',$cookie->toString());
    }

    public function testWithHTTPOnly(): void
    {
        $cookie = new Cookie('Hello', 'World', httpOnly:true);
        $this->assertStringContainsString('httpOnly',$cookie->toString());
    }

    public function testPath(): void
    {
        $cookie = new Cookie('Hello', 'World', path:'/CeSite');
        $this->assertStringContainsString('/CeSite',$cookie->toString());
    }
}
