<?php

use Editiel98\Kernel\Routing\Routing;
use PHPUnit\Framework\TestCase;

class RoutingTest extends TestCase
{
    public function testRoute(): void
    {
        $this->assertIsArray(Routing::decodeURI('/test'));
    }
}
