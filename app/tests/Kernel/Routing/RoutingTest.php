<?php

use Editiel98\Kernel\Routing\Routing;
use Editiel98\Kernel\WebInterface\RequestHandler;
use PHPUnit\Framework\TestCase;

class RoutingTest extends TestCase
{
    public function testRoute(): void
    {
        $handler=RequestHandler::getInstance();
        $handler->init([], [], ['REQUEST_URI' => '/test'], [], []);
        $this->assertIsArray(Routing::decodeURI('/test'));
    }
}
